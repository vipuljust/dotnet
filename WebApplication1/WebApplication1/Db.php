<?php

class Db {
	private $database;
	private $hostname;
	private $username;
	private $password;
	private $link;
	protected $last_execution_time;
  
	public static function getInstance(){
		static $db = null;
		if ( $db == null ) $db = new Db();
		return $db;
	}

	public function __construct() { 
		if ($this->isLocalHost()) {
			/* ... */
		} else {
			//your credentials here
			$this->database = '';
			$this->hostname = '';
			$this->username = '';
			$this->password = '';
		}

		try {
			$this->link=mysql_connect($this->hostname,
						  $this->username,
						  $this->password);
			if (!$this->link) {
				die('Databaseforbindelse mislykkedes : '.mysql_error());
			} else {
				mysql_select_db ($this->database);
			}

		} catch (Exception $e){
			throw new Exception('Databaseforbindelse mislykkedes ..');
			exit;
 		}
	}

	public function exec($SQL) {
		mysql_query($SQL, $this->link);
	}

	public function query($SQL) {
		$ms=microtime(true);
		$result=mysql_query($SQL, $this->link);
		$ms=microtime(true)-$ms;
		$this->last_execution_time=($ms*1000).' ms.';
		return $result;
	}

	public function getRow($SQL) {
		$result=mysql_query($SQL, $this->link);
		$result=mysql_fetch_array($result);
		return $result;
	}

	public function hasData($SQL) {
		$result=mysql_query($SQL, $this->link);
		return is_array(@mysql_fetch_array($result));
	}

	public function getRecCount($table) {
		$SQL='select count(*) from '.$table;
		$count=$this->getRow($SQL);
		return $count[0];
	}		

	public function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	public function lastIndex() {
		return mysql_insert_id($this->link);
	}

	public function q($string, $comma = true) {
		$string=mysql_real_escape_string($string, $this->link);
		return $comma ? '"'.$string.'",' : '"'.$string.'"';
	}

	public function isLocalHost() {
		$host = $_SERVER["SERVER_ADDR"]; 
		return (($host=='127.0.0.1') || ($host=='::1'));
	}

	public function setCharset() {
		if ($this->isLocalHost()) {
			mysql_set_charset('utf8');
		} else {
			mysql_set_charset('utf8');
		}
	}

	//extra
	public function debug($a) {
		echo '<pre>';
		print_r($a);
		echo '</pre>';
	}

	public function removeLastChar($s) {
		return substr_replace($s ,"", -1);
	}

	public function fileDebug($text) {
		$fh = fopen($this->debugFile, 'a') or die();
		fwrite($fh, $text."\n");
		fclose($fh);
	}

	public function resetDebugFile() {
		$fh = fopen($this->debugFile, 'a') or die();
		ftruncate($fh, 0);
		fclose($fh);
	}

}

?>
