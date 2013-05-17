<?php
class Thubbl_Validate_Email extends Zend_Validate_Abstract
{
	const INVALID_EMAIL = 'invalidEmail';

	protected $_messageTemplates = array(
		self::INVALID_EMAIL => "Email già in uso.",
	);

	public function isValid($value)
	{
		$valueString = (string) $value;
		$this->_setValue($valueString);
		$user = new Application_Model_User();

		$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();

		if ($user->getUserByEmail($value)) {
			$this->_error(self::INVALID_EMAIL);
			return false;
		}

		return true;
	}
}