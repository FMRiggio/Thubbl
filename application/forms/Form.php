<?php

class Application_Form_Form extends Zend_Form
{
    //
    // Form Decorator
    //
    // Prima di FormElements i tag renderizzati sono tutti sullo stesso livello.
    // Dopo sono incapsulati, in questo caso specifico il tag 'HtmlTag' contiene 'Form' che contiene 'FormElements'
    //
    protected $_formDecorators = array(
          array( array( 'listaErrori' => 'Errors' ), array('class' => 'message error no-margin' ) )
        , 'FormElements'  
        , 'Form'
    );


    //
    // Element decorators
    //
    protected $_formElementDecorators = array(
          'ViewHelper'
        , array( 'Errors', array('escape' => false, 'placement' => 'append' ) )
        , array( array( 'containerP' => 'HtmlTag' ), array('tag' => 'p', 'class' => 'field') )
        , array( 'Label', array('tag' => 'p', 'escape' => false, 'class' => 'label' ) )
        , array( array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'field_content' ) ),
    ); 
   
    /** 
     * Button decorators 
     */ 
    protected $_buttonDecorators = array(  
        'ViewHelper',  
        array( array( 'containerP' => 'HtmlTag' ), array('tag' => 'p') ),  
        array( array( 'containerDiv' => 'HtmlTag' ), array('tag' => 'div', 'class' => 'button') )  
    );  

    // Captcha decorator
    protected $_captchaDecorators = array(
          'Label'
        , 'Errors'
        , array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'field_content'))
    );

    // Text decorator
    protected $_textDecorators = array(
        array('Description', array('escape' => FALSE))
    );

    // Privacy decorator
    protected $_privacyDecorators = array(
        array('Description', array('tag' => 'div', 'class' => 'field', 'escape' => FALSE))
    );

    // Placeholder decorator
    protected $_placeholderDecorators = array(
        array('Description', array('escape' => FALSE, 'class' => 'placeholder'))
    );

    protected $_formDisplayGroupsDecorators = array(
          'FormElements'
        , array( array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => 'columns clearfix' ) )
        , 'Fieldset' 
    );

    /**
     * @const tipi di input
     */
    const TYPE_MULTISELECT = 'multiselect';
    const TYPE_SELECT = 'select';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_TEXT = 'text';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_PASSWORD = 'password';
    const TYPE_SUBMIT = 'submit';
    const TYPE_BUTTON = 'button';
    const TYPE_CAPTCHA = 'captcha';
    const TYPE_FILE = 'file';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_IMAGE = 'image';
     
    const ERROR_MESSAGE = 'Sì è verificato un errore. Riprova.';
     
    const PASSWORD_MIN_LENGTH = '6';
    const PASSWORD_MAX_LENGTH = '10';

	public $_emailErrors = array(
		  'emailAddressInvalidHostname'  => 'Controlla il formato della mail.'
		, 'emailAddressInvalidFormat'    => 'Controlla il formato della mail.'
		, 'emailAddressInvalidLocalPart' => "'%localPart%' non è un indirizzo email valido '%value%'"
		, 'emailAddressLengthExceeded'   => "'%value%' eccede la lunghezza consentita"
	);
							  
    public function init() 
    {
        $this->setDecorators($this->_formDecorators);
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttrib('accept-charset', 'utf-8'); 
    }


   public function isValid( $formData )  
    {
        $isValid = parent::isValid( $formData );
		$this->_errorsExist = !$isValid;
        if( !$isValid ) {
            $this->addErrorMessage( self::ERROR_MESSAGE );
        }
        return $isValid;
    }

}