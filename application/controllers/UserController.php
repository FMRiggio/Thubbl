<?php

class UserController extends Thubbl_Controllers_CustomController
{

	public function preDispatch()
	{
		parent::preDispatch();

		$this->view->domain = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';
		$this->view->currentPage = $this->getRequest()->getControllerName() . '_' . $this->getRequest()->getActionName();
	}

    public function indexAction()
    {
        $this->_redirect('/user/mypage');
        return;
	}

	public function editSocialNetworksAction()
	{
		$request = $this->getRequest();
		$params = $request->getParams();

		$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();

		$socialNetworks = new Application_Model_SocialNetworks();
		$form = new Application_Form_SocialNetworks();

		if ($request->getPost()) {

			$data = $request->getPost();
			if ($form->isValid($data)) {
				unset($data['Invia']);

				if (isset($data['instagram'])) {
					$url = $this->bootstrap->getOption('InstagramApiUrl')
						 . 'users/search?q=' . $data['instagram']
						 . '&access_token=' . $this->bootstrap->getOption('InstagramAccessToken')
						 ;
					$response    = get_object_vars(json_decode(file_get_contents($url)));

					if (count($response['data']) > 0) {
						$response    = get_object_vars($response['data'][0]);
						$instagramID = $response['id'];

						$userProps = new Application_Model_UserProperties();
						$userProps->setProperty($userAuth['id'], 'instagram_id', $instagramID);

					}

				}
			
				$socialNetworks->saveSocialNetworksForUser($data, $userAuth['id']);
				
				$this->flashMessenger->addMessage('Social Networks salvati!');
				$this->_redirect('/user/edit-social-networks');
				return;
			}
		} else {
			$sn = $socialNetworks->getSocialNetworksByUser($userAuth['id']);
			$snPartial = array();
			if (count($sn) > 0) {
				foreach ($sn as $partial) {
					$snPartial[$partial['kind']] = $partial['url'];
				}
			}
			$form->populate($snPartial);
		}

		$this->view->form = $form;
	}

    public function editImagesAction()
    {
    	$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();
		$request = $this->getRequest();

		$userImagesFolder       = BASE_PATH . '/var/users/' . $userAuth['id'] . '/';
		$publicUserImagesFolder = '/users/' . $userAuth['id'] . '/';

		$images         = new Application_Model_Images();
		$userProperties = new Application_Model_UserProperties();
		$form    = new Application_Form_Images();
		$form->setImageDestination($userImagesFolder);

		$userImages = $images->getImagesByUser($userAuth['id']);
		$userLogo   = $images->getImageByKindAndUser(Application_Model_Images::LOGO_KIND, $userAuth['id']);
		$userProps  = $userProperties->getPropertyByUser($userAuth['id'], 'background_color');

		if ($userLogo) {
			$form->getElement('logo')->setRequired(FALSE);
		}

		if (isset($userProps['property_name']) && $userProps['property_name'] == 'background_color') {
			$form->getElement('background_color')->setValue($userProps['property_value']);
		}

		if ($userImages) {
			$partial = array();
			foreach ($userImages as $image) {
				if ($image['kind'] == Application_Model_Images::LOGO_KIND) {
					$form->getElement('logo_placeholder')
						 ->setDescription($this->view->getHelper('Img')->img($publicUserImagesFolder . $image['filename']));
				} else {
					$form->getElement($image['kind'] . '_placeholder')
						 ->setDescription(
						 	$this->view->getHelper('Img')->img($publicUserImagesFolder . 'thumbs/' . $image['filename'])
							. $this->view->getHelper('HRef')->href('delete-image?id=' . $image['id']
																 , '<span class="entypo-cancel"></span>'
																 , array('title' => 'Delete image', 'class' => 'delete_image_link')
																 )
							);
				}
			}
		}

		if ($request->getPost()) {

			$data = $request->getPost();
			if ($form->isValid($data)) {

				// Salvataggio colore di sfondo
				if (isset($data['background_color']) && $data['background_color'] != '') {
					$userProperties->setProperty($userAuth['id'], 'background_color', $data['background_color']);
				}

				$filter = new Thubbl_Filter_ImageSize();

				// Salvataggio logo
				$logoFilename = $form->getElement('logo')->getFileName();
				if (count($logoFilename) > 0) {
					$originalFilename = pathinfo($form->getElement('logo')->getFileName());
	
					if (isset($originalFilename['extension'])) {
						$newFilename = $originalFilename['dirname'] . '/logo.' . $originalFilename['extension'];
						$form->getElement('logo')->addFilter('Rename', array('target' => $newFilename, 'overwrite' => TRUE));
						$form->getElement('logo')->receive();
	
						$output = $filter->setHeight(100)
										 ->setWidth(100)
										 ->setThumnailDirectory($userImagesFolder)
										 ->setOverwriteMode(Thubbl_Filter_ImageSize::OVERWRITE_ALL)
										 ->setQuality(94)
										 ->setStrategy(new Thubbl_Filter_ImageSize_Strategy_Crop())
										 ->filter($newFilename);
	
						$insert = array(
							  'filename' => 'logo.' . $originalFilename['extension']
							, 'kind'     => Application_Model_Images::LOGO_KIND
							, 'id_user'  => $userAuth['id']
						);
		
						$images->insert($insert);
					}
				}

				// Salvataggio sfondo
				$backgroundFilename = $form->getElement('background_image')->getFileName();
				if (count($backgroundFilename) > 0) {
					$originalFilename = pathinfo($form->getElement('background_image')->getFileName());
					if (isset($originalFilename['extension'])) {
						$newFilename = $originalFilename['dirname'] . '/background.' . $originalFilename['extension'];
						$form->getElement('background_image')->addFilter('Rename', array('target' => $newFilename, 'overwrite' => TRUE));
						$form->getElement('background_image')->receive();
	
						// Save thumbnail
						$output = $filter->setWidth(200)
										 ->setThumnailDirectory($userImagesFolder . '/thumbs/')
										 ->setOverwriteMode(Thubbl_Filter_ImageSize::OVERWRITE_ALL)
										 ->setQuality(94)
										 ->setStrategy(new Thubbl_Filter_Imagesize_Strategy_Resize())
										 ->filter($newFilename);
	
						$insert = array(
							  'filename' => 'background.' . $originalFilename['extension']
							, 'kind'     => Application_Model_Images::BACKGROUND_KIND
							, 'id_user'  => $userAuth['id']
						);
		
						$images->insert($insert);
					}
				}

				// Salvataggio gallery
				for ($i = 1; $i < 5; $i++) {
					$galleryFilename = $form->getElement('gallery' . $i)->getFilename();
					if (count($galleryFilename) > 0) {
					$originalFilename = pathinfo($form->getElement('gallery' . $i)->getFileName());
						if (isset($originalFilename['extension'])) {
							$newFilename = $originalFilename['dirname'] . '/gallery' . $i . '.' . $originalFilename['extension'];
							$form->getElement('gallery' . $i)->addFilter('Rename', array('target' => $newFilename, 'overwrite' => TRUE));
							$form->getElement('gallery' . $i)->receive();

							// Resize dell'immagine
							$output = $filter->setWidth(1020)
											 ->setHeight(300)
										 	 ->setThumnailDirectory($userImagesFolder)
											 ->setOverwriteMode(Thubbl_Filter_ImageSize::OVERWRITE_ALL)
											 ->setQuality(94)
											 ->filter($newFilename);

							// Salvataggio thumbnail
							$output = $filter->setWidth(200)
											 ->setHeight(59)
										 	 ->setThumnailDirectory($userImagesFolder . '/thumbs/')
											 ->setOverwriteMode(Thubbl_Filter_ImageSize::OVERWRITE_ALL)
											 ->setQuality(94)
											 ->setStrategy(new Thubbl_Filter_Imagesize_Strategy_Resize())
											 ->filter($newFilename);

							$insert = array(
								  'filename' => 'gallery' . $i . '.' . $originalFilename['extension']
								, 'kind'     => Application_Model_Images::getGalleryKind($i)
								, 'id_user'  => $userAuth['id']
							);
		
							$images->insert($insert);
						}
					}
				}

				if (isset($data['Invia_Prosegui']) && $data['Invia_Prosegui'] != '') {
					$this->_redirect('/user/edit-social-networks');
				}
				$this->_redirect('/user/edit-images');
				return;
			}
		}
		$this->view->form = $form;

    }

	public function deleteImageAction()
	{
		$request = $this->getRequest();
		$params = $this->getAllParams();

		$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();
		$images = new Application_Model_Images();
		if (isset($userAuth) && $userAuth['id'] != '') {
				
			$userImagesFolder       = BASE_PATH . '/var/users/' . $userAuth['id'] . '/';
			$image = $images->getImageByIdAndUser($params['id'], $userAuth['id']);
			$result = $images->deleteImageByIdAndUser($params['id'], $userAuth['id']);
			if ($image && $result) {
				unlink($userImagesFolder . $image['filename']);
				unlink($userImagesFolder . 'thumbs/' . $image['filename']);
			}
			
			$this->flashMessenger->addMessage('Immagine cancellata!');
		}
		$this->_redirect('/user/edit-images');
		return;
	}

	public function editDataAction()
	{
		$request = $this->getRequest();
		$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();

		$data = $this->getUserDataForPage($userAuth['id']);
		$user    = new Application_Model_User();
		$form    = new Application_Form_User();

		if ($request->getPost()) {

			$data = $request->getPost();
			if ($form->isValid($data)) {
				$pwd  = Thubbl_Utility::random_string(8);
				$salt = md5(Thubbl_Utility::random_string(8));

				$update = array(
					  'email'          => trim($data['email'])
					, 'displayed_name' => trim($data['displayed_name'])
					, 'claim'          => trim($data['claim'])
					, 'permalink'      => trim($data['permalink'])
				);

				$user->update($update, 'id = ' . $userAuth['id']);
				$this->_redirect('/user/edit-data');
				return;
			}
		} else {
			$form->populate($data['user']);
		}
		$this->view->userDataform = $form;
	}


	/*
	 *
	 * Login di un utente
	 * 
	 */
	public function loginAction()
	{
		$request = $this->getRequest();
		$user    = new Application_Model_User();
		$form    = new Application_Form_Login();

		// if a user's already logged in, send them to their account home page
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity()){
			$this->_redirect('/user/mypage');
		}

		// process login if request method is post
		if ($request->getPost()) {

			$data = $request->getPost();
			
			if ($form->isValid($data)) {
				$idUser = $user->checkIdentity($data['email'], $data['password']);

				if ($idUser) {
					$auth->getStorage()->write($idUser);
					$this->_redirect('/user/mypage/');
					return;
				}
			}

		}
		$this->view->loginForm = $form;
	}


	public function logoutAction()
	{
		$this->_helper->layout()->disableLayout(); 
		$this->_helper->viewRenderer->setNoRender(true);
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect($this->view->domain);
		return;
	}

	/*
	 * 
	 * Registrazione di un utente, al termine del quale parte una mail con il link per l'attivazione
	 * 
	 */
    public function registrationAction()
    {
		$request = $this->getRequest();
		$user    = new Application_Model_User();
		$form    = new Application_Form_Registration();

		if ($request->getPost()) {

			$data = $request->getPost();
			if ($form->isValid($data)) {
				$pwd  = Thubbl_Utility::random_string(8);
				$salt = md5(Thubbl_Utility::random_string(8));

				$insert = array(
					  'email'          => trim($data['email'])
					, 'salt'           => md5($salt . $this->bootstrap->getOption('pepper'))
					, 'password'       => md5($pwd)
					, 'displayed_name' => trim($data['displayed_name'])
					, 'claim'          => trim($data['claim'])
					, 'privacy'        => ($data['privacy'] == 'T' ? 1 : 0)
					, 'date_created'   => date('Y-m-d H:i:s')
					, 'active'         => FALSE
				);

				$user->insert($insert);

				// Invio email con link di conferma
				$this->view->title            = 'Conferma registrazione';
				$this->view->email            = $insert['email'];
				$this->view->password         = $pwd;
				$this->view->confirmationLink = $this->view->domain . 'user/activate/' . $salt;

				$email = new Zend_Mail();
				$email->setFrom('info@thubbl.com', 'Thubbl');
				$email->setBodyHtml($this->view->render('email_template/registrazione.phtml'));
				$email->setSubject('Thubbl - Conferma registrazione');
				$email->addTo($insert['email'], $insert['displayed_name']);

				$urlRedirect = '/user/registration-complete';

				// Condizione per debug in sviluppo
				if (APPLICATION_ENV == 'development') {
					$config = $this->bootstrap->getOption('mail');
					$transport = new Zend_Mail_Transport_Smtp($config['host'], $config);
				} elseif (APPLICATION_ENV == 'production') {
					$transport = new Zend_Mail_Transport_Smtp();
				}

				$email->send($transport);
				$this->_redirect($urlRedirect);
				return;
			}
		}
		$this->view->registrationForm = $form;
    }


	/*
	 * Thankyou page della form di registrazione
	 */
    public function registrationCompleteAction()
    {
    }

	/*
	 * Attivo l'utente in base al parametro checksum, se corretto, attivo l'utente e creo la cartella per le sue immagini
	 */
    public function activateAction()
    {
		$request = $this->getRequest();

		// Controllo se l'utente esista
		$user   = new Application_Model_User();
		$salt   = $this->getRequest()->getParam('salt');
		$pepper = $this->bootstrap->getOption('pepper');

		$auth = Zend_Auth::getInstance();
		$utente = $user->getUserBySalt(md5($salt . $pepper));

		// Attivazione dell'utente
		if ($utente) {
			$auth->getStorage()->write(array('id' => $utente['id']));

			$update = array('active' => TRUE);
			$user->update($update, 'id = ' . $utente['id']);

			$this->view->activate = TRUE;
			$this->view->message = 'Utente attivato.';
			$permalink = Thubbl_Utility::permalink($utente['displayed_name']);

			// Controllo se il permalink è già in uso
			if ($user->permalinkAlreadyExist($permalink, $utente['id']) == TRUE) {
				$i = 1;
				$permalinkIsUnique = FALSE;
				while(!$permalinkIsUnique) {
					$permalink = $permalink . $i;
					if ($user->permalinkAlreadyExist($permalink, $utente['id']) == FALSE) {
						$permalinkIsUnique = TRUE;
					} else {
						$permalinkIsUnique = FALSE;
					}
					$i++;
				}
			}

			$form = new Application_Form_Permalink();
			$form->getElement('permalink')->setValue($permalink);

			if ($request->getPost()) {

				$data = $request->getPost();
				$data['id_utente'] = $utente['id'];

				if ($form->isValid($data)) {

					// Importo il permalink e creo la cartella delle immagini dell'utente
					$update = array('permalink' => $permalink);
					$user->update($update, 'id = ' . $utente['id']);

					if (!file_exists(BASE_PATH . '/var/users/' . $utente['id'])) {
						mkdir(BASE_PATH . '/var/users/' . $utente['id']);
					}

					if (!file_exists(BASE_PATH . '/var/users/' . $utente['id'] . '/thumbs/')) {
						mkdir(BASE_PATH . '/var/users/' . $utente['id'] . '/thumbs/');
					}

					$this->_redirect('/user/edit-images/ua');
				}
			}

			$this->view->form = $form;

		} else {
			$this->view->activate = FALSE;
			$this->view->message = 'Impossibile attivare l\'utente, controlla il link.';
		}
    }


	public function mypageAction()
	{
		$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();

		$params  = $this->getRequest()->getParams();

		if ($userAuth) {
			$userId = $userAuth['id'];
		} elseif ($params['permalink']) {
			$user = new Application_Model_User();
			$userRow = $user->getUserByPermalink($params['permalink']);
			$userId = $userRow['id'];
		} else {
			$this->_redirect('/');
			return;
		}
		$data = $this->getUserDataForPage($userId);

		$this->view->publicUserImagesFolder = $data['publicUserImagesFolder'];
		$this->view->background             = $data['background']['filename'];
		$this->view->background_color       = $data['background_color']['property_value'];
		$this->view->logo                   = $data['logo'];
		$this->view->gallery                = $data['gallery'];
		$this->view->socialNetworks         = $data['socialNetworks'];
		$this->view->user                   = $data['user'];

		if (isset($data['socialNetworks']['youtube']) && $data['socialNetworks']['youtube']['url'] != '') {
			$userName = str_replace('http://www.youtube.com/user/', '', trim($data['socialNetworks']['youtube']['url'], '/'));
			$yt = new Zend_Gdata_YouTube();
			$yt->setMajorProtocolVersion(2);
			$videoFeed = $yt->getUserUploads($userName);
			if (count($videoFeed) > 0) {
				foreach ($videoFeed as $videoEntry) {
					$this->view->lastVideo = $videoEntry->getVideoId();
					break;
				}
			}
		}

		if (isset($data['socialNetworks']['instagram']) && $data['socialNetworks']['instagram']['url'] != '') {

			if (isset($data['instagram_id']) && $data['instagram_id']['property_value'] != '') {
				$this->view->instagramUserId      = $data['instagram_id']['property_value'];
				$this->view->instagramAccessToken = $this->bootstrap->getOption('InstagramAccessToken');
				$this->view->instagramMaxPhotos   = $this->bootstrap->getOption('InstagramMaxPhotos');
			}
		}

		if (isset($data['socialNetworks']['twitter']) && $data['socialNetworks']['twitter']['url'] != '') {
			$this->view->twitter_username = $data['socialNetworks']['twitter']['url'];
		}
	}

	private function getUserDataForPage($idUser)
	{
		$user = new Application_Model_User();
		$userProps = new Application_Model_UserProperties();
		$images  = new Application_Model_Images();
		$socialNetworks = new Application_Model_SocialNetworks();

		$data = array();
		$data['publicUserImagesFolder'] = '/users/' . $idUser . '/';
		$data['background'] = $images->getImageByKindAndUser(Application_Model_Images::BACKGROUND_KIND, $idUser);
		$data['background_color'] = $userProps->getPropertyByUser($idUser, 'background_color');
		$data['instagram_id'] = $userProps->getPropertyByUser($idUser, 'instagram_id');
		$data['logo'] = $images->getImageByKindAndUser(Application_Model_Images::LOGO_KIND, $idUser);

		$gallery = array();
		for ($i = 1; $i < 5; $i++) {
			$galleryKind = $images->getGalleryKind($i);
			$img = $images->getImageByKindAndUser($galleryKind, $idUser);

			if (count($img) > 0 && $img['filename'] != '') {
				$gallery[] = $img;
			}
		}
		$data['gallery'] = $gallery;
		$socialNetworks = $socialNetworks->getSocialNetworksByUser($idUser);
		$partial = array();
		if (count($socialNetworks) > 0) {
			foreach ($socialNetworks as $sn) {
				switch ($sn['kind']) {
					case Application_Model_SocialNetworks::TWITTER:
						$sn['url'] = 'https://twitter.com/' . $sn['url'];
						break;
					case Application_Model_SocialNetworks::INSTAGRAM:
						$sn['url'] = 'http://instagram.com/' . $sn['url'];
						break;
				}
				$partial[$sn['kind']] = $sn;
			}
		}
		$data['socialNetworks'] = $partial;
		$data['user'] = current($user->find($idUser)->toArray());

		return $data;
	}

	public function rememberPasswordAction()
	{
		$request = $this->getRequest();

		// Controllo se l'utente esista
		$user   = new Application_Model_User();

		$auth = Zend_Auth::getInstance();
		$form = new Application_Form_RememberPassword();

		if ($request->getPost()) {

			$data = $request->getPost();

			if ($form->isValid($data)) {
				
				// Retrieve dell'utente
				$idUtente = $user->getUserByEmail($data['email']);
				$utente = $user->getUserById($idUtente);

				// Reset della password
				$pwd  = Thubbl_Utility::random_string(8);
				$update = array('password' => md5($pwd));
				$user->update($update, 'id = ' . $utente['id']);

				// Invio email con nuova password
				$this->view->title            = 'Reset della password';
				$this->view->email            = $utente['email'];
				$this->view->password         = $pwd;

				$email = new Zend_Mail();
				$email->setFrom('info@thubbl.com', 'Thubbl');
				$email->setBodyHtml($this->view->render('email_template/remember-password.phtml'));
				$email->setSubject('Thubbl - Reset della password');
				$email->addTo($utente['email'], $utente['displayed_name']);

				$urlRedirect = '/user/login';

				// Condizione per debug in sviluppo
				if (APPLICATION_ENV == 'development') {
					$config = $this->bootstrap->getOption('mail');
					$transport = new Zend_Mail_Transport_Smtp($config['host'], $config);
				} elseif (APPLICATION_ENV == 'production') {
					$transport = new Zend_Mail_Transport_Smtp();
				}

				$email->send($transport);
				$this->_redirect($urlRedirect);
				return;
				
			}
		}
		
		$this->view->form = $form;
	}
}