<?php
require 'config.php';
require 'iterator.php';

class Dist {

    private $ftpConnection;
    private $ignoringFolder;
    private $csv;
    private $index;

    public function __construct()
    {
    	$config = new Conf();
		
        //$ftpData = Conf::$ftpData;
        //$this->ignoringFolder = Conf::$ignoringFolder;

        $this->ftpConnection = ftp_connect($config->ftpData['host'], 0) or die('Couldn\'t connect to the server.');

                
        if (ftp_login($this->ftpConnection, $config->ftpData['username'], $config->ftpData['password'])) {
            if ($config->debug) {
                echo 'Connected to the server<br>';
            }
        } else {
            die('Couldn\'t connect to the server. Wrong data.');
        }

        // Setting the index
        if (file_exists(SCRIPT_PATH . '/index.csv')) {
            $this->csv = fopen(SCRIPT_PATH . '/index.csv', 'r+');
        } else {
            $this->csv = NULL;
        }

        $this->newCsv = fopen(SCRIPT_PATH . '/reindex.csv', 'w');
        $this->_setIndex();

		
        // Start reading folder
        $ftpIterator = new FtpDirectoryIterator($config->localBaseDir, $this->ftpConnection, $config->ftpBaseDir, $config->ignoringFiles, $config->debug);
        ftp_close($this->ftpConnection);  
    }

    private function _setIndex()
    {
        if ($this->csv) {
            while (($data = fgetcsv($this->csv, 1000, ";")) !== FALSE) {
                $this->index[$data[0]] = $data[1];
            }            
        }
    }

    private function _navigateFolder($filesList)
    {

        $folders = $this->_filterFolders($filesList);

        if (count($folders) > 0) {
            foreach ($folders as $f) {
                if (isset($this->index[$f])) {
                    
                }
            }
        }
        
        //ftp_mdtm ( resource $ftp_stream , string $remote_file ) last modification time
        //ftp_chdir ( resource $ftp_stream , string $directory ) change dir
        
        var_dump($folders);die;
    }


}

?>