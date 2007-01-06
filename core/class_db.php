<?php

class bj_db {
	
	var $querycount = 0;
	//var $queries = array(); //For debugging.

	function bj_db($prefix,$user,$password,$db,$host) {
		$this->prefix = $prefix;
		$this->tags = $this->prefix.'tags';
		$this->comments = $this->prefix.'comments';
		$this->options = $this->prefix.'options';
		$this->entries = $this->prefix.'entries';
		$this->sections = $this->prefix.'sections';
		$this->users = $this->prefix.'users';

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
		$this->do_query_ops($query);
		
		if(!$link) {
			$link = $this->connect;
		}
		if($query) {
			return mysql_query($query,$link);
		}
	}
	
	function get_item($query='') {
		if(!$link) {
			$link = $this->connect;
		}
		return mysql_fetch_assoc($this->query($query,$link));
	}
	
	function get_rows($query='',$link = false) {
		if(!$link) {
			$link = $this->connect;
		}
		$loopquery = $this->query($query,$link);
		while($row = mysql_fetch_assoc($loopquery)) {
			$newitem[] = $row;
		}
		return $newitem;
	}
	
	function do_query_ops($query='') {
		if(is_array($this->queries)) {
			$this->queries[] = $query;
		}
		$this->querycount++;
	}
	
	function querycount() {
		return $this->querycount;
	}
	
	function uhoherror($error) { ?>
<html>
	<head>
		<title>Error'd!</title>
	</head>
	<body>
		<h1>Error</h1>
		<hr />
		<p><?php echo $error; ?></p>
		<hr />
		<p>Powered by Blackjack</p>
	</body>
</html>
<?php
		die();
	}
	
	function escape($string,$link=false) {
		if(!$link) {
			$link = $this->connect;
		}
		$string = stripslashes($string);
		$string = mysql_real_escape_string($string,$link);
		return $string;
	}
}

$bj_db = new bj_db($table_prefix,DB_USER, DB_PASS, DB_NAME, DB_HOST);

?>
