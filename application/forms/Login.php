<?php

class Application_Form_Login extends Application_Form_Form
{

	public function init()
	{
		parent::init();


		$this->setAction('/user/login');
		$this->setEnctype(Zend_Form::ENCTYPE_URLENCODED);
		$this->setMethod(Zend_Form::METHOD_POST);

		//------------------------------------------//
		// RIGA 1
		//------------------------------------------//

		$this->addElement('text', 'email', array(
			    'label'         => 'Email *'
			  , 'required'      => TRUE
			  , 'tabindex'      => 1
			  , 'maxlength'     => 128
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
              , 'validators'    => array(
                          array('EmailAddress', FALSE, array('messages' => $this->_emailErrors))
                        , array('NotEmpty', FALSE, array('messages' => 'L\'email è un campo obbligatorio.'))
                )
		));

		$this->addElement('password', 'password', array(
			    'label'         => 'Password *'
			  , 'required'      => TRUE
			  , 'tabindex'      => 2
			  , 'maxlength'     => 8
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(
					array('NotEmpty', FALSE, array('messages' => 'La password è un campo obbligatorio.'))
			  )
		));


		$this->addElement('submit', 'Invia', array(
			    'tabindex'      => 2
			  , 'class'         => 'button'
			  , 'label'         => 'Login'
			  , 'decorators'    => $this->_buttonDecorators
		));



		//------------------------------------------//
		// DISPLAY GROUPS
		//------------------------------------------//

		// Colonna 1
		$this->addDisplayGroup(array(
			    'email'
			  , 'password'
		), 'column1', array('decorators'    => $this->_formDisplayGroupsDecorators));


		// Riga 5 - Consensi
		$this->addDisplayGroup(array(
				'Invia'
		), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators));
	}

}