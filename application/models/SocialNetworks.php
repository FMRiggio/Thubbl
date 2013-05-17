<?php

class Application_Model_SocialNetworks extends Application_Model_DbTable_SocialNetworks
{
	const FACEBOOK   = 'facebook';
	const TWITTER    = 'twitter';
	const INSTAGRAM  = 'instagram';
	const YOUTUBE    = 'youtube';
	const GPLUS      = 'gplus';
	const FOURSQUARE = 'foursquare';
	const PINTEREST  = 'pinterest';
	const FLICKR     = 'flickr';
	const FEED       = 'feed';


	private $_orderByKind = array(
		  'facebook'   => 1
		, 'twitter'    => 2
		, 'instagram'  => 3
		, 'youtube'    => 4
		, 'gplus'      => 5
		, 'foursquare' => 6
		, 'pinterest'  => 7
		, 'flickr'     => 8
		, 'feed'       => 9
	);

	public function saveSocialNetworksForUser($data, $idUser) {
		if (count($data) > 0) {
			foreach ($data as $kind => $urlSN) {
				if (!$this->getURLByKindAndUser($kind, $idUser)) {
					$insert = array(
						  'kind'        => $kind
						, 'url'         => $urlSN
						, 'id_user'     => $idUser
						, 'ordinamento' => $this->getOrderByKind($kind)
					);
					$this->insert($insert);
				} else {
					$update = array(
						'url' => $urlSN
					);
					$this->update($update, "kind = '" . $kind . "' AND id_user = " . $idUser);
				}
			}
		}
	}

	public function getOrderByKind($kind) {
		return $this->_orderByKind[$kind];
	}
	public function getURLByKindAndUser($kind, $idUser)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id_user = ?', $idUser)
					   ->where('kind = ?', $kind);
		$result = $this->fetchAll($select);
		if ($result) {
			return $result->toArray();
		}
		return FALSE;
	}

	public function getSocialNetworksByUser($idUser)
	{
		$select = $this->select()
					   ->from($this)
					   ->where('id_user = ?', $idUser)
					   ->order('ordinamento');
		
		$result = $this->fetchAll($select);
		if ($result) {
			return $result->toArray();
		}
		return array();
	}

}