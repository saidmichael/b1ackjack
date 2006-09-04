<?php

class bj_db {
	var $querycount = 0;
	
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
	
	function query($query=false) {
		if($query) {
			return mysql_query($query);
			$this->querycount(0);
		}
	}
	
	function get_item($query='') {
		return mysql_fetch_assoc($this->query($query));
	}
	
	function get_rows($query='',$type = "OBJECT") {
		if($type == "OBJECT") {
			return mysql_fetch_object($this->query($query));
			
		}
		else {
			//return mysql_fetch_assoc($this->query($query)); So it's returned in an array.
			$loopquery = $this->query($query);
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
		if($num == 0) {
			$this->querycount++;
		}
		else {
			echo $this->querycount;
		}
	}
	
	function uhoherror($error) {
		echo $error;
		die();
	}
}

$bj_db = new bj_db(DB_USER, DB_PASS, DB_NAME, DB_HOST);

?>