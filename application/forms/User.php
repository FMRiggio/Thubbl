<?php

class Application_Form_User extends Application_Form_Form
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

		// Email
		$this->addElement('text', 'email', array(
			    'label'         => 'Email *'
			  , 'required'      => TRUE
			  , 'maxlength'     => 128
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim', 'StringToLower')
			  , 'validators'    => array(
			    	      array('EmailAddress', FALSE, array('messages' => 'Controlla il formato della mail.'))
						, array('NotEmpty', FALSE, array('messages' => 'L\'email è un campo obbligatorio.'))
			    )
		));

		// Nome visualizzato
		$this->addElement('text', 'displayed_name', array(
			    'label'         => 'Nome Visualizzato *'
			  , 'required'      => TRUE
			  , 'maxlength'     => 64
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'validators'    => array(array('NotEmpty', FALSE, array('messages' => 'Il nome visualizzato è un campo obbligatorio.')))
		));


		//------------------------------------------//
		// RIGA 2
		//------------------------------------------//

		$this->addElement('text', 'claim', array(
			    'label'         => 'Claim'
			  , 'required'      => FALSE
			  , 'maxlength'     => 128
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
		));

		// Permalink
		$this->addElement('text', 'permalink', array(
			    'label'         => 'Permalink *'
			  , 'required'      => TRUE
			  , 'maxlength'     => 64
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim', 'StringToLower')
			  , 'validators'    => array(
					  array('NotEmpty', FALSE, array('messages' => 'Il permalink è un campo obbligatorio.'))
					, array(new Thubbl_Validate_Permalink())
			    )
		));

		//------------------------------------------//
		// Invia
		//------------------------------------------//

		$this->addElement('submit', 'Invia', array(
			    'class'         => 'button'
			  , 'label'         => 'Salva'
			  , 'decorators'    => $this->_buttonDecorators
		));



		//------------------------------------------//
		// DISPLAY GROUPS
		//------------------------------------------//

		// Riga 1 - Colonna 1
		$this->addDisplayGroup(array(
			    'email'
			  , 'displayed_name'
		), 'column1', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column'));

		// Riga 2
		$this->addDisplayGroup(array(
			    'claim'
			  , 'permalink'
		), 'column2', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column'));

		// Invia
		$this->addDisplayGroup(array(
			  'Invia'
		), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators));
    }

	public function render(Zend_View_Interface $view = null)
	{
		foreach ($this->_elements as $element) {
			if ($element->hasErrors()) {
				$decorator = $element->getDecorator('row');
				$decorator->setOption('class', trim($decorator->getOption('class') . ' error'));
			}
		}
		return parent::render($view);
	}

	public function isValid($data)
	{
		$valid = parent::isValid($data);
		$this->_errorsExist = !$valid;
		return $valid;
	}
}

