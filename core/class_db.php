<?php

class bj_db {
	
	var $querycount = 0;
	//var $queries = array(); //For debugging.
	var $show_errors = true;

	function bj_db($prefix,$user,$password,$db,$host) {
		$this->prefix = $prefix;
		$this->tags = $this->prefix.'tags';
		$this->comments = $this->prefix.'comments';
		$this->options = $this->prefix.'options';
		$this->entries = $this->prefix.'entries';
		$this->sections = $this->prefix.'sections';
		$this->usermeta = $this->prefix.'usermeta';
		$this->users = $this->prefix.'users';

		$this->connect = @mysql_connect($host,$user,$password);
		
		if(!$this->connect)
			$this->uhoherror("Can't connect to database.");
		
		$this->select($db);
	}
	
	function select($dbname) {
		if(!@mysql_select_db($dbname,$this->connect))
			$this->uhoherror("Can't select database.");
	}
	
	function hide_errors() {
		$this->show_errors = false;
	}
	
	function show_errors() {
		$this->show_errors = true;
	}
	
	function print_errors($query,$link) {
		echo"<p class='dberror'><strong>Database Error:</strong> ".mysql_error($link)."<br /><code>".$query."</code></p>";
	}
	
	function query($query,$link=false) {
		$this->do_query_ops($query);
		
		if(!$link)
			$link = $this->connect;
		$return = @mysql_query($query,$link);
		if(mysql_error($link))
			$this->print_errors($query,$link);
		return $return;
	}
	
	function get_item($query,$link=false) {
		if(!$link)
			$link = $this->connect;
		return mysql_fetch_assoc($this->query($query,$link));
	}
	
	function get_rows($query,$link = false) {
		if(!$link)
			$link = $this->connect;
		$loopquery = $this->query($query,$link);
		while($row = mysql_fetch_assoc($loopquery))
			$newitem[] = $row;
		return $newitem;
	}
	
	function do_query_ops($query='') {
		if(is_array($this->queries))
			$this->queries[] = $query;
		$this->querycount++;
		run_actions('query_ops',$query);
	}
	
	function querycount() {
		return $this->querycount;
	}
	
	function uhoherror($error) {
		die($error);
	}
	
	function escape($string,$link=false) {
		if(!$link)
			$link = $this->connect;
		$string = stripslashes($string);
		$string = mysql_real_escape_string($string,$link);
		return $string;
	}
}

$bj->db = new bj_db($table_prefix,DB_USER, DB_PASS, DB_NAME, DB_HOST);

?>
