<?php
// Define path to base directory
defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__) . '/../../'));
defined('SCRIPT_PATH') || define('SCRIPT_PATH', realpath(dirname(__FILE__) . '/'));

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', BASE_PATH . 'application/');

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../../library'),
    get_include_path(),
)));


class Conf {
							  
    public $ftpData = array(),
		   $ignoringFiles = array(),
		   $indexOn = 'file',
		   $ftpBaseDir = '/',
		   $localBaseDir,
           $debug = TRUE;

	public function __construct()
	{
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);

		$this->ftpData['host']     = $config->ftp->host;
		$this->ftpData['username'] = $config->ftp->username;
		$this->ftpData['password'] = $config->ftp->password;
		$this->ftpData['port']     = $config->ftp->port;

		if ($config->ftp->baseDir != '') {
			$this->ftpBaseDir = $config->ftp->baseDir;
		} else {
			$this->ftpBaseDir = BASE_PATH;
		}

		if ($config->ftp->localBaseDir != '') {
			$this->localBaseDir = $config->ftp->localBaseDir;
		} else {
			$this->localBaseDir = BASE_PATH;
		}
		
		if (count($config->ftp->ignoringFiles->toArray()) > 0) {
			foreach ($config->ftp->ignoringFiles->toArray() as $d) {
				$this->ignoringFiles[] = $d;
			}
		}

	}
}
?>