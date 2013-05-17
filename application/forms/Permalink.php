<?php

class Application_Form_Permalink extends Application_Form_Form
{

    public function init()
    {
		parent::init();

		$this->setAction('');
		$this->setEnctype(Zend_Form::ENCTYPE_URLENCODED);
		$this->setMethod(Zend_Form::METHOD_POST);

		//------------------------------------------//
		// RIGA 1
		//------------------------------------------//

		// Permalink
		$this->addElement('text', 'permalink', array(
			    'label'         => 'Identificativo *'
			  , 'required'      => TRUE
			  , 'maxlength'     => 64
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim', 'StringToLower')
			  , 'validators'    => array(
					  array('NotEmpty', FALSE, array('messages' => 'L\'identificativo è un campo obbligatorio.'))
					, array(new Thubbl_Validate_Permalink())
			    )
		));

		$this->addElement('submit', 'Invia', array(
			    'class'         => 'button'
			  , 'label'         => 'Invia'
			  , 'decorators'    => $this->_buttonDecorators
		));

		//------------------------------------------//
		// DISPLAY GROUPS
		//------------------------------------------//

		$this->addDisplayGroup(array(
			    'permalink'
			  , 'Invia'
		), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators));

    }

}

