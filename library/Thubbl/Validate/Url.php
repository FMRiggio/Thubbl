<?php
class Thubbl_Validate_Url extends Zend_Validate_Abstract
{
	const INVALID_URL = 'invalidUrl';

	protected $_messageTemplates = array(
		self::INVALID_URL => "'%value%' non è un URL valida.",
	);

	public function isValid($value)
	{
		$valueString = (string) $value;
		$this->_setValue($valueString);

		if (!Zend_Uri::check($value)) {
			$this->_error(self::INVALID_URL);
			return false;
		}
		return true;
	}
}