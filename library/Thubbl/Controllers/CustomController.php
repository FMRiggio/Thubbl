<?php

class Thubbl_Controllers_CustomController extends Zend_Controller_Action
{
	public $bootstrap;
	public $session;
	public $flashMessenger;

	private $_adminAreaPages = array(
		  'user edit-data'
		, 'user edit-images'
		, 'user edit-social-networks'
		, 'user mypage'
	);

	public function preDispatch()
	{
		parent::preDispatch();

        $this->bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $this->view->baseUrl = 'http'. (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] 
                             . '/' . $this->bootstrap
                             ->getOption('baseUrl')
                             ;

		$this->view->doctype('HTML5');

   		$this->view->headMeta()->setCharset('UTF-8')
			   ->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8')
		 	   ->appendHttpEquiv('Content-Language', 'it-IT')
			   ;

		$this->view->headLink()->appendStylesheet($this->view->baseUrl . '/css/normalize.css', 'screen');
		$this->view->headLink()->appendStylesheet('http://fonts.googleapis.com/css?family=Ubuntu:400,500,700');

		$this->session = new Zend_Session_Namespace('Default');

		$this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->flashMessenger->setNamespace('Default');

		// istanzio la Zend_Auth
		$auth = Zend_Auth::getInstance();
		$auth->setStorage(new Zend_Auth_Storage_Session());

		// istanzio il Zend_Controller_Front
		$controller = Zend_Controller_Front::getInstance();
		$controller->registerPlugin(new Thubbl_Controllers_CustomControllerAclManager($auth));

		if ($auth->hasIdentity()) {
			$this->view->authenticated = true;
			$this->view->identity = $auth->getIdentity();
		} else {
			$this->view->authenticated = false;
		}

		$this->view->bodyClass = $this->getRequest()->getControllerName() 
							   . ' ' . $this->getRequest()->getActionName()
							   ;

		$this->view->headScript()->appendFile($this->view->baseUrl . '/js/vendor/modernizr-2.6.2.min.js')
								 ->appendFile($this->view->baseUrl . '/js/vendor/jquery-1.8.3.min.js')
								 ->appendFile($this->view->baseUrl . '/js/vendor/jquery.cycle.lite.js')
								 ->appendFile($this->view->baseUrl . '/js/vendor/jquery.placeholder.min.js')
								 ;

		if ($this->view->bodyClass == 'user mypage') {
			$this->view->headScript()->appendFile($this->view->baseUrl . '/js/vendor/jquery.fancybox.pack.js?v=2.1.4');
			$this->view->headLink()->appendStylesheet($this->view->baseUrl . '/css/jquery.fancybox.css?v=2.1.4');
		}

		if (in_array($this->view->bodyClass, $this->_adminAreaPages)) {
			$this->view->headLink()->appendStylesheet($this->view->baseUrl . '/css/mypage.css', 'screen');
			$this->_helper->layout->setLayout('mypage');
		} else {
			$this->view->headLink()->appendStylesheet($this->view->baseUrl . '/css/main.css', 'screen');
		}

		$this->view->headLink(array(  'rel' => 'icon'
									, 'href' => '/favicon.ico'));
	
	}

	public function postDispatch() {
		parent::postDispatch();
		
		$this->view->messages = $this->flashMessenger->getMessages();
	}
}