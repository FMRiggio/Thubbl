<?php

class Application_Form_SocialNetworks extends Application_Form_Form
{

	public $userImages;

    public function init()
    {
		parent::init();

		$this->setAction('');
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
		$this->setMethod(Zend_Form::METHOD_POST);

		//------------------------------------------//
		// RIGA 1
		//------------------------------------------//
		$this->addElement('text', Application_Model_SocialNetworks::FACEBOOK, array(
			    'label'         => 'Url alla tua pagina Facebook'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'http://www.facebook.com/mypage'
		));

		$this->addElement('text', Application_Model_SocialNetworks::TWITTER, array(
			    'label'         => 'Il nome del tuo account Twitter'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array()
			  , 'placeholder'   => 'myNameOnTwitter'
		));

		$this->addElement('text', Application_Model_SocialNetworks::INSTAGRAM, array(
			    'label'         => 'Il tuo hashtag Instagram'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array()
			  , 'placeholder'   => 'myInstagramHashtag'
		));

		$this->addElement('text', Application_Model_SocialNetworks::PINTEREST, array(
			    'label'         => 'Pinterest'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'http://pinterest.com/myPinterest/'
		));

		$this->addElement('text', Application_Model_SocialNetworks::YOUTUBE, array(
			    'label'         => 'Youtube'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'http://www.youtube.com/user/myYoutubeUsername/'
		));

		$this->addElement('text', Application_Model_SocialNetworks::GPLUS, array(
			    'label'         => 'Google+'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'https://plus.google.com/u/0/myUserID'
		));

		$this->addElement('text', Application_Model_SocialNetworks::FOURSQUARE, array(
			    'label'         => 'Foursquare'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'https://it.foursquare.com/user/myUserID'
		));

		$this->addElement('text', Application_Model_SocialNetworks::FLICKR, array(
			    'label'         => 'Flickr'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'http://www.flickr.com/photos/myUsername/'
		));

		$this->addElement('text', Application_Model_SocialNetworks::FEED, array(
			    'label'         => 'Feed Reader / Website'
			  , 'required'      => FALSE
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(new Thubbl_Validate_Url())
			  , 'placeholder'   => 'http://www.mywebsite.com/feed'
		));


		$this->addElement('submit', 'Invia', array(
			    'class'         => 'button'
			  , 'label'         => 'Salva'
			  , 'decorators'    => $this->_buttonDecorators
		));

		//------------------------------------------//
		// DISPLAY GROUPS
		//------------------------------------------//

		// Colonna 1
		$this->addDisplayGroup(array(
			      Application_Model_SocialNetworks::FACEBOOK
			    , Application_Model_SocialNetworks::TWITTER
			    , Application_Model_SocialNetworks::INSTAGRAM
			    , Application_Model_SocialNetworks::PINTEREST
			    , Application_Model_SocialNetworks::YOUTUBE
		), 'column1', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column'));

		// Colonna 1
		$this->addDisplayGroup(array(
			      Application_Model_SocialNetworks::GPLUS
			    , Application_Model_SocialNetworks::FOURSQUARE
			    , Application_Model_SocialNetworks::FLICKR
			    , Application_Model_SocialNetworks::FEED
		), 'column2', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column'));

		$this->addDisplayGroup(array(
			    'Invia'
		), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators));
    }

	public function isValid($data)
	{
		$valid = parent::isValid($data);
		$this->_errorsExist = !$valid;
		return $valid;
	}

}

