<?php

class Application_Form_RememberPassword extends Application_Form_Form
{

	public function init()
	{
		parent::init();


		$this->setAction('/user/remember-password');
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
						, array(new Thubbl_Validate_EmailNotExists())
                )
		));

		$this->addElement('submit', 'Invia', array(
			    'tabindex'      => 2
			  , 'class'         => 'button'
			  , 'label'         => 'Invia'
			  , 'decorators'    => $this->_buttonDecorators
		));



		//------------------------------------------//
		// DISPLAY GROUPS
		//------------------------------------------//

		// Colonna 1
		$this->addDisplayGroup(array(
			    'email'
		), 'column1', array('decorators'    => $this->_formDisplayGroupsDecorators));


		// Riga 5 - Consensi
		$this->addDisplayGroup(array(
				'Invia'
		), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators));
	}

}