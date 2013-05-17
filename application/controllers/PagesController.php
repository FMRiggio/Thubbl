<?php

class PagesController extends Thubbl_Controllers_CustomController
{

	public function preDispatch()
	{
		parent::preDispatch();

		$this->view->baseUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';

		$this->view->currentPage = $this->getRequest()->getControllerName() . '_' . $this->getRequest()->getActionName();
	}

    public function indexAction()
    {
        $this->_redirect('/');
        return;
	}


	/*
	 *
	 * Form di contatti
	 * 
	 */
	public function contactsAction()
	{
		$request = $this->getRequest();
		$form = new Application_Form_Contacts();

		// process login if request method is post
		if ($request->getPost()) {

			$data = $request->getPost();
			
			if ($form->isValid($data)) {

				$this->view->title      = 'Richiesta di contatto';
				$this->view->first_name = $data['first_name'];
				$this->view->last_name  = $data['last_name'];
				$this->view->email      = $data['email'];
				$this->view->request    = $data['request'];

				// Invio email di contatti
				$email = new Zend_Mail();
				$email->setFrom('info@thubbl.com', 'Thubbl');
				$email->setBodyHtml($this->view->render('email_template/contatti.phtml'));
				$email->setSubject('Thubbl - Richiesta di contatto');
				$email->addTo($this->bootstrap->getOption('contact_email'), $this->bootstrap->getOption('contact_name'));

				$urlRedirect = '/pages/thankyou';

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
		$this->view->contactsForm = $form;
	}

	/*
	 * Thankyou page della form di contatti
	 */
    public function thankyouAction()
    {
    }

	/*
	 * Pagina con gli esempi
	 */
    public function examplesAction()
    {
    }

	/*
	 * Pagina della privacy
	 */
    public function privacyAction()
    {
    }
}

















