<?php
 
class Zend_View_Helper_Img extends Zend_View_Helper_Abstract
{
	protected $_baseurl = null;
	protected $_exists = array();
 
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_baseurl = '/';
	}
 
	/**
	 * Output the <img /> tag
	 *
	 * @param string $path
	 * @param array $params
	 * @return string
	 */
	public function img($path, $params = array())
	{
		$plist = array();
		$paramstr = null;

		if (!isset($params['alt'])) {
			$params['alt'] = '';
		}
		foreach ($params as $param => $value) {
			$plist[] = $param . '="' . $this->view->escape($value) . '"';
		}
		$paramstr = ' ' . join(' ', $plist);
		return '<img src="' . $path . '"' . $paramstr . ' />';
	}
}