<?php
class Application_Model_User extends Application_Model_DbTable_User
{
	public $ignoringPages = array(
		'pages', 'user', 'index', 'error'
	);

	public function getUserById($idUtente)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id = ?', $idUtente);
		
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function getUserByEmail($email)
	{
		$select = $this->select()
					   ->from($this, array('id'))
					   ->where('email = ?', $email);
		
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function getUserByPermalink($permalink)
	{
		$select = $this->select()
					   ->from($this, array('id'))
					   ->where('permalink = ?', $permalink);
		
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function getUserBySalt($salt)
	{
		$select = $this->select()
					   ->from($this, array('id', 'displayed_name'))
					   ->where('salt = ?', $salt);
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function permalinkAlreadyExist($permalink, $idUtente)
	{
		$select = $this->select()
					   ->from($this, array('id'))
					   ->where('permalink = ?', $permalink);

		if (isset($idUtente)) {
			$select->where('id != ?', $idUtente);
		}
		$result = $this->fetchRow($select);

		if ($result) {
			return TRUE;
		}

		foreach ($this->ignoringPages as $page) {
			if (substr(strtolower($permalink), 0, strlen($page)) == strtolower($page)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function permalinkExist($permalink)
	{
		$select = $this->select()
					   ->from($this, array('id'))
					   ->where('permalink = ?', $permalink);
		$result = $this->fetchRow($select);

		if ($result) {
			return TRUE;
		}
		return FALSE;
	}

	public function checkIdentity($email, $password)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('active = ?', TRUE)
					   ->where('email = ?', $email)
					   ->where('password = ?', md5($password))
					   ;
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

}

