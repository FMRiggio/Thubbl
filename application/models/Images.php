<?php

class Application_Model_Images extends Application_Model_DbTable_Images
{
	const LOGO_KIND = 'logo';
	const BACKGROUND_KIND = 'background';

	private static $_galleryKind = array(
		  1 => 'gallery1'
		, 2 => 'gallery2'
		, 3 => 'gallery3'
		, 4 => 'gallery4'
	);

	public static function getGalleryKind($value)
	{
		$galleryKind = self::$_galleryKind;
		return $galleryKind[$value];
	}

	public function getImagesByUser($idUser)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id_user = ?', $idUser);
		
		$result = $this->fetchAll($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function getImageByKindAndUser($kind, $idUser)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id_user = ?', $idUser)
					   ->where('kind = ?', $kind);
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}


	public function getImageByIdAndUser($idImage, $idUser)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id = ?', $idImage)
					   ->where('id_user = ?', $idUser)
					   ;
		$result = $this->fetchRow($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function deleteImageByIdAndUser($idImage, $idUser)
	{
		$result = $this->delete(array(
			  'id = ?'      => $idImage
			, 'id_user = ?' => $idUser
		));

		if ($result != 0) {
			return TRUE;
		}
		return FALSE;
	}
}