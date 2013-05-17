<?php

class Application_Form_Images extends Application_Form_Form
{

	public $userImages;

	public static $fileElementDecorators = array(
    	    'File'
    	  , array('Label', array('escape' => false))
    	  , 'Errors'
    	  , array('HtmlTag', array('tag' => 'div', 'class' => 'field_content image clearfix')));

    public function init()
    {
		parent::init();

		$this->setAction('');
		$this->setEnctype(Zend_Form::ENCTYPE_MULTIPART);
		$this->setMethod(Zend_Form::METHOD_POST);

		//------------------------------------------//
		// RIGA 1
		//------------------------------------------//

		// Placeholder per il logo
		$this->addElement('hidden', 'logo_placeholder', array(
			    'description'   => '<img src="/widgets/trans.gif" alt="" />'
			  , 'decorators'    => $this->_placeholderDecorators
		));
		// Logo
		$this->addElement('file', 'logo', array(
			    'label'       => '<span class="ico entypo-info-circled"></span><span class="help">Max 1 MB<br>Formato .JPG/.GIF/.PNG<br>dim. min. consigliate 100 x 100</span>Logo *'
			  , 'required'    => TRUE
			  , 'decorators'  => self::$fileElementDecorators
			  , 'validators'  => array(
			  		  array('Extension', FALSE, 'jpg, png, gif')
			  		, array('Size', FALSE, 1048576)
			  	)
		));


		//------------------------------------------//
		// RIGA 2
		//------------------------------------------//
		$this->addElement('file', 'background_image', array(
			    'label'       => '<span class="ico entypo-info-circled"></span><span class="help">Max 2 MB<br>Formato .JPG/.GIF/.PNG<br>dim. min. consigliate 1280 x 1024</span>Immagine di sfondo'
			  , 'required'    => FALSE
			  , 'decorators'  => self::$fileElementDecorators
			  , 'validators'  => array(
			  		  array('Extension', FALSE, 'jpg, png, gif')
			  		, array('Size', FALSE, 2097152)
			  	)
		));
		// Placeholder per il background
		$this->addElement('hidden', 'background_placeholder', array(
			    'description'   => '<img src="/widgets/trans.gif" alt="" />'
			  , 'decorators'    => $this->_placeholderDecorators
		));

		$this->addElement('text', 'background_color', array(
			    'label'         => 'Colore di sfondo'
			  , 'required'      => FALSE
			  , 'maxlength'     => 7
			  , 'decorators'    => $this->_formElementDecorators
			  , 'filters'       => array('StringTrim')
			  , 'placeholder'   => '#FF0000'
		));



		//------------------------------------------//
		// RIGA 3
		//------------------------------------------//
		$this->addElement('file', 'gallery1', array(
			    'label'       => '<span class="ico entypo-info-circled"></span><span class="help">Max 1 MB<br>Formato .JPG/.GIF/.PNG<br>dim. min. consigliate 1020 x 300</span>Immagine galleria 1'
			  , 'required'    => FALSE
			  , 'decorators'  => self::$fileElementDecorators
			  , 'validators'  => array(
			  		  array('Extension', FALSE, 'jpg, png, gif')
			  		, array('Size', FALSE, 1048576)
			  	)
		));
		// Placeholder per gallery_1
		$this->addElement('hidden', 'gallery1_placeholder', array(
			    'description'   => '<img src="/widgets/trans.gif" alt="" />'
			  , 'decorators'    => $this->_placeholderDecorators
		));

		$this->addElement('file', 'gallery2', array(
			    'label'       => '<span class="ico entypo-info-circled"></span><span class="help">Max 1 MB<br>Formato .JPG/.GIF/.PNG<br>dim. min. consigliate 1020 x 300)</span>Immagine galleria 2'
			  , 'required'    => FALSE
			  , 'decorators'  => self::$fileElementDecorators
			  , 'validators'  => array(
			  		  array('Extension', FALSE, 'jpg, png, gif')
			  		, array('Size', FALSE, 1048576)
			  	)
		));
		// Placeholder per gallery_2
		$this->addElement('hidden', 'gallery2_placeholder', array(
			    'description'   => '<img src="/widgets/trans.gif" alt="" />'
			  , 'decorators'    => $this->_placeholderDecorators
		));

		$this->addElement('file', 'gallery3', array(
			    'label'       => '<span class="ico entypo-info-circled"></span><span class="help">Max 1 MB<br>Formato .JPG/.GIF/.PNG<br>dim. min. consigliate 1020 x 300</span>Immagine galleria 3'
			  , 'required'    => FALSE
			  , 'decorators'  => self::$fileElementDecorators
			  , 'validators'  => array(
			  		  array('Extension', FALSE, 'jpg, png, gif')
			  		, array('Size', FALSE, 1048576)
			  	)
		));
		// Placeholder per gallery_3
		$this->addElement('hidden', 'gallery3_placeholder', array(
			    'description'   => '<img src="/widgets/trans.gif" alt="" />'
			  , 'decorators'    => $this->_placeholderDecorators
		));

		$this->addElement('file', 'gallery4', array(
			    'label'       => '<span class="ico entypo-info-circled"></span><span class="help">Max 1 MB<br>Formato .JPG/.GIF/.PNG<br>dim. min. consigliate 1020 x 300</span>Immagine galleria 4'
			  , 'required'    => FALSE
			  , 'decorators'  => self::$fileElementDecorators
			  , 'validators'  => array(
			  		  array('Extension', FALSE, 'jpg, png, gif')
			  		, array('Size', FALSE, 1048576)
			  	)
		));
		// Placeholder per gallery_4
		$this->addElement('hidden', 'gallery4_placeholder', array(
			    'description'   => '<img src="/widgets/trans.gif" alt="" />'
			  , 'decorators'    => $this->_placeholderDecorators
		));

		$this->addElement('submit', 'Invia', array(
			    'class'         => 'button'
			  , 'label'         => 'Salva'
			  , 'decorators'    => $this->_buttonDecorators
		));

		$this->addElement('submit', 'Invia_Prosegui', array(
			    'class'         => 'button'
			  , 'label'         => 'Salva e prosegui'
			  , 'decorators'    => $this->_buttonDecorators
		));


		//------------------------------------------//
		// DISPLAY GROUPS
		//------------------------------------------//

		// Colonna 1 - Image 1
		$this->addDisplayGroup(array(
			      'logo_placeholder'
			    , 'logo'
		), 'column1-img1', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column image1'));

		// Colonna 2 - Image 2
		$this->addDisplayGroup(array(
			      'background_placeholder'
			    , 'background_image'
		), 'column2-img2', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column image1'));

		// Colonna 1 - Image 3
		$this->addDisplayGroup(array(
			      'gallery1_placeholder'
			    , 'gallery1'
		), 'column1-img3', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column image2'));

		// Colonna 2 - Image 4
		$this->addDisplayGroup(array(
			      'gallery2_placeholder'
			    , 'gallery2'
		), 'column2-img4', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column image2'));

		// Colonna 1 - Image 5
		$this->addDisplayGroup(array(
			      'gallery3_placeholder'
			    , 'gallery3'
		), 'column1-img5', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column image2'));

		// Colonna 2 - Image 6
		$this->addDisplayGroup(array(
			      'gallery4_placeholder'
			    , 'gallery4'
		), 'column3-img6', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'column image2'));

		// Riga 2
		$this->addDisplayGroup(array(
			      'background_color'
		), 'row2', array('decorators'    => $this->_formDisplayGroupsDecorators, 'class' => 'row'));

		$this->addDisplayGroup(array(
			    'Invia'
			  , 'Invia_Prosegui'
		), 'consensi', array('decorators'    => $this->_formDisplayGroupsDecorators));
		
    }

	public function isValid($data)
	{
		$valid = parent::isValid($data);
		$this->_errorsExist = !$valid;
		return $valid;
	}

	public function setImageDestination($userFolder)
	{
		$elements = $this->getElements();
		foreach ($elements as $element) {
			if ($element instanceof Zend_Form_Element_File) {
				$element->setDestination($userFolder);
			}
		}
	}
}

