<?php
 
class Zend_View_Helper_HRef extends Zend_View_Helper_Abstract
{

	/**
	 * Output the <a href=""></a> tag
	 *
	 * @param string $url
	 * @param string $label
	 * @param array $params
	 * @return string
	 */
	public function href($url, $label, $params = array())
	{
		$plist = array();
		$paramstr = null;

		if (!isset($params['title'])) {
			$params['title'] = '';
		}
		foreach ($params as $param => $value) {
			$plist[] = $param . '="' . $this->view->escape($value) . '"';
		}
		$paramstr = ' ' . join(' ', $plist);
		return '<a href="' . $url . '"' . $paramstr . '>' . $label . '</a>';
	}
}