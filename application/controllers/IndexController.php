<?php

class IndexController extends Thubbl_Controllers_CustomController
{

    public function indexAction()
    {
    	$request = $this->getRequest();
		$params  = $this->getRequest()->getParams();

		if (isset($params['permalink']) && $params['permalink'] != '') {
			$user = new Application_Model_User();
			if ($user->permalinkExist($params['permalink'])) {
				$this->_forward('mypage', 'user', 'default', array('permalink' => $params['permalink']));
			}
		}
		$this->view->headTitle('Thubbl - La tua Social Brand Page');

        $this->view->registrationForm = new Application_Form_Registration();
		$this->view->loginForm        = new Application_Form_Login();
		$this->view->contactsForm     = new Application_Form_Contacts();
    }

    public function releaseAction() {
        include APPLICATION_PATH . '/../var/scripts/ftp_distribution/dist.php';
        $d = new Dist();
        die;
    }

}