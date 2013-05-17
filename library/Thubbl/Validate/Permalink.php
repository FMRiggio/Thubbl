<?php
class Thubbl_Validate_Permalink extends Zend_Validate_Abstract
{
	const INVALID_PERMALINK = 'invalidPermalink';

	protected $_messageTemplates = array(
		self::INVALID_PERMALINK => "Identificativo già in uso.",
	);

	public function isValid($value)
	{
		$valueString = (string) $value;
		$this->_setValue($valueString);
		$user = new Application_Model_User();

		$auth = Zend_Auth::getInstance();
		$userAuth = $auth->getIdentity();

		if (isset($userAuth['id']) && $userAuth['id'] != '') {
			if ($user->permalinkAlreadyExist($value, $userAuth['id'])) {
				$this->_error(self::INVALID_PERMALINK);
				return false;
			}
		} else {
			if ($user->permalinkAlreadyExist($value)) {
				$this->_error(self::INVALID_PERMALINK);
				return false;
			}
		}
		
		return true;
	}
}