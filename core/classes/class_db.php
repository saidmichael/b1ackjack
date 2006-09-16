<?php

class bj_db {
	
	var $categories;
	var $comments;
	var $options;
	var $posts;
	var $sections;
	var $users;
	var $prefix;
	
	function bj_db($user,$password,$db,$host) {
		$this->connect = @mysql_connect($host,$user,$password);
		
		if(!$this->connect) {
			$this->uhoherror("Can't connect to database.");
		}
		
		$this->select($db);
	}
	
	function select($dbname) {
		if(!@mysql_select_db($dbname,$this->connect)) {
			$this->uhoherror("Can't select database.");
		}
	}
	
	function query($query=false,$link=false) {
		if(!$link) {
			$link = $this->connect;
		}
		if($query) {
			return mysql_query($query,$link);
			$this->querycount(0);
		}
	}
	
	function get_item($query='') {
		if(!$link) {
			$link = $this->connect;
		}
		return mysql_fetch_assoc($this->query($query,$link));
	}
	
	function get_rows($query='',$type = "OBJECT",$link = false) {
		if(!$link) {
			$link = $this->connect;
		}
		if($type == "OBJECT") {
			return mysql_fetch_object($this->query($query,$link));
			
		}
		else {
			//return mysql_fetch_assoc($this->query($query)); So it's returned in an array.
			$loopquery = $this->query($query,$link);
			$i = 0;
			while($row = mysql_fetch_assoc($loopquery)) {
				if(is_array($row)) {
					foreach($row as $item=>$value) {
						$newitem[$i][$item] = $value;
					}
					$i++;
				}
			}
			return $newitem;
		}
	}
	
	function querycount($num = 0) {
		static $querycount = 0;
		if($num == 0) {
			$querycount++;
		}
		else {
			echo $querycount;
		}
	}
	
	function uhoherror($error) {
		echo $error;
		die();
	}
}

$bj_db = new bj_db(DB_USER, DB_PASS, DB_NAME, DB_HOST);

?>