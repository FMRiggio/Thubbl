<?php

class Application_Form_Contacts extends Application_Form_Form
{

    public function init()
    {
        parent::init();

        $this->setAction('/pages/contacts');
        $this->setEnctype(Zend_Form::ENCTYPE_URLENCODED);
        $this->setMethod(Zend_Form::METHOD_POST);

        //------------------------------------------//
        // RIGA 1
        //------------------------------------------//

        // Nome
        $this->addElement('text', 'first_name', array(
                'label'         => 'Nome *'
              , 'required'      => TRUE
              , 'maxlength'     => 64
              , 'decorators'    => $this->_formElementDecorators
              , 'filters'       => array('StringTrim')
              , 'validators'    => array(array('NotEmpty', FALSE, array('messages' => 'Il nome è un campo obbligatorio.')))
        ));

        // Cognome
        $this->addElement('text', 'last_name', array(
                'label'         => 'Cognome *'
              , 'required'      => TRUE
              , 'maxlength'     => 64
              , 'decorators'    => $this->_formElementDecorators
              , 'filters'       => array('StringTrim')
              , 'validators'    => array(array('NotEmpty', FALSE, array('messages' => 'Il cognome è un campo obbligatorio.')))
        ));


        //------------------------------------------//
        // RIGA 2
        //------------------------------------------//

        // Email
        $this->addElement('text', 'email', array(
                'label'         => 'Email *'
              , 'required'      => TRUE
              , 'maxlength'     => 128
              , 'decorators'    => $this->_formElementDecorators
              , 'filters'       => array('StringTrim', 'StringToLower')
              , 'validators'    => array(
                          array('EmailAddress', FALSE, array('messages' => $this->_emailErrors))
                        , array('NotEmpty', FALSE, array('messages' => 'L\'email è un campo obbligatorio.'))
                )
        ));

        //------------------------------------------//
        // RIGA 3
        //------------------------------------------//

        // Request
        $this->addElement('textarea', 'request', array(
                'label'         => 'Richiesta *'
              , 'required'      => TRUE
              , 'decorators'    => $this->_formElementDecorators
              , 'filters'       => array('StringTrim')
              , 'rows'          => 5
              , 'validators'    => array(array('NotEmpty', FALSE, array('messages' => 'La richiesta è un campo obbligatorio.')))
        ));


        //------------------------------------------//
        // Consensi
        //------------------------------------------//

        $consensoLabel = 'Acconsento al trattamento dei dati sensibili';

        $this->addElement('hidden', 'consensi_title', array(
                'description'   => 'Privacy'
              , 'decorators'    => $this->_textDecorators
        ));

$privacyText = <<<TEXT
<div class="box_privacy">
Letta l'informativa sulla <a href="/pages/privacy">Privacy</a> ai sensi del Dlg 196/03, 
do il consenso al trattamento dei dati personali per le finalità e con le modalità 
specificatamente indicate nell'informativa stessa.
</div>
TEXT;
        $this->addElement('hidden', 'consensi_text', array(
                'description'   => $privacyText
              , 'decorators'    => $this->_privacyDecorators
        ));

        $this->addElement('radio', 'privacy', array(
                'label'         => ''
              , 'required'      => TRUE
              , 'decorators'    => $this->_formElementDecorators
              , 'separator'     => ' '
              , 'filters'       => array('StringTrim')
              , 'validators'    => array(
                      array('NotEmpty', FALSE, array('messages' => 'La privacy è un campo obbligatorio.'))
                )
        ));
        $this->getElement('privacy')->addMultiOption('T', 'Sì, dò il consenso');
        $this->getElement('privacy')->addMultiOption('F', 'No, non dò il consenso');
        $validator = new Zend_Validate_InArray(
            array('haystack' => array('T', 'F'))
        );
        $validator->setMessages(array(
                  Zend_Validate_InArray::NOT_IN_ARRAY => 'Scegli tra "Sì, dò il consenso" e "No, non dò il consenso".'
        ));

        $this->getElement('privacy')->addValidator($validator);

        $this->addElement('submit', 'Invia', array(
                'class'         => 'button'
              , 'label'         => 'Invia'
              , 'decorators'    => $this->_buttonDecorators
        ));



        //------------------------------------------//
        // DISPLAY GROUPS
        //------------------------------------------//

        // Colonna 1
        $this->addDisplayGroup(array(
                'first_name'
              , 'email'
        ), 'column1', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column first'));


        // Colonna 2
        $this->addDisplayGroup(array(
                'last_name'
        ), 'column2', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column second'));

        // Consensi
        $this->addDisplayGroup(array(
                'request'
        ), 'row1', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'row'));

        // Consensi
        $this->addDisplayGroup(array(
                'consensi_title'
              , 'consensi_text'
              , 'privacy'
              , 'Invia'
        ), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'row'));


    }

}

