<?php

class Application_Form_Registration extends Application_Form_Form
{

    public function init()
    {
        parent::init();

        $this->setAction('/user/registration');
        $this->setEnctype(Zend_Form::ENCTYPE_URLENCODED);
        $this->setMethod(Zend_Form::METHOD_POST);

        //------------------------------------------//
        // Colonna 1
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
						, array(new Thubbl_Validate_Email())
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

        $this->addElement('text', 'claim', array(
                'label'         => 'Il tuo claim'
              , 'required'      => FALSE
              , 'maxlength'     => 128
              , 'decorators'    => $this->_formElementDecorators
              , 'filters'       => array('StringTrim')
        ));

        //------------------------------------------//
        // Colonna 2
        //------------------------------------------//

        // Captcha
        $this->addElement('captcha', 'captcha', array(
                'label'         => 'Inserisci il captcha *'
              , 'required'      => TRUE
              , 'decorators'    => $this->_captchaDecorators
              , 'captcha' => array(
                      'captcha'        => 'Image'
                    , 'font'           => BASE_PATH . '/public/fonts/arial.ttf'
                    , 'fontSize'       => '35'
                    , 'wordLen'        => 5
                    , 'height'         => '100'
                    , 'width'          => '300'
                    , 'imgDir'         => BASE_PATH.'/public/captcha'
                    , 'imgUrl'         => Zend_Controller_Front::getInstance()->getBaseUrl() . '/captcha'
                    , 'dotNoiseLevel'  => 50
                    , 'lineNoiseLevel' => 5
                )
        ));
        $validator = new Zend_Captcha_Image();
        $validator->setMessages(array(
                  Zend_Captcha_Word::MISSING_VALUE => 'Il Captcha è un campo obbligatorio.'
                , Zend_Captcha_Word::MISSING_ID    => 'Attenzione! Manca il RECAPTCHA!'
                , Zend_Captcha_Word::BAD_CAPTCHA   => 'Errore! Il CAPTCHA non corrisponde. Riprova!'
        ));
        $this->getElement('captcha')->addValidator($validator);


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
                  Zend_Validate_InArray::NOT_IN_ARRAY => 'Scegli tra Sì e No.'
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

        // Riga 1 - Colonna 1
        $this->addDisplayGroup(array(
                'email'
              , 'displayed_name'
              , 'claim'
        ), 'row1', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column first'));

        // Riga 2
        $this->addDisplayGroup(array(
                'captcha'
        ), 'row-captcha', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column second'));

        // Consensi
        $this->addDisplayGroup(array(
                'consensi_title'
              , 'consensi_text'
              , 'privacy'
              , 'Invia'
        ), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'row'));


    }

}

