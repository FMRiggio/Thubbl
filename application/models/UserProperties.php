<?php

class Application_Model_UserProperties extends Application_Model_DbTable_UserProperties
{

	public function getPropertyByUser($idUser, $propertyName)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id_user = ?', $idUser)
					   ->where('property_name = ?', $propertyName);
		
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function setProperty($idUser, $propertyName, $propertyValue)
	{
		$exist = $this->getPropertyByUser($idUser, $propertyName);
		if ($exist) {
			$data = array(
				'property_value' => $propertyValue
			);
			$result = $this->update($data, "id_user = " . $idUser . " AND property_name = '" . $propertyName . "'");
		} else {
			$data = array(
				  'id_user'        => $idUser
				, 'property_name'  => $propertyName
				, 'property_value' => $propertyValue
			);
			$result = $this->insert($data);
		}
		if ($result) {
			return TRUE;
		}
		return FALSE;
	}

}