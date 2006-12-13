<?php

if(!defined('BJPATH')) {
	echo"Naughty naughty.";
	die();
}

$offset = intval($_GET['offset']);
$getname = bj_clean_string($_GET['name']);
switch($_GET['req']) {
	case 'section' :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".$getname."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$query_string = 'type=public&section='.$section['ID'].'&offset='.$offset;
		$entries = get_entries($query_string);
		
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '') {
			include(BJTEMPLATE .'/'.$section['handler']);
		}
		elseif(file_exists(BJTEMPLATE .'/section.php')) {
			include(BJTEMPLATE . '/section.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'entry' :
		$query_string = 'limit=1&shortname='.$getname;
		$entries = get_entries($query_string);
		if(!$entries) {
			load_404_instead();
		}
		if(file_exists(BJTEMPLATE . '/entry.php')) {
			include(BJTEMPLATE . '/entry.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'tag' :
		$tag = $bj_db->get_item("SELECT * FROM `".$bj_db->tags."` WHERE `shortname` = '".$getname."' LIMIT 1");
		if(!$tag) {
			load_404_instead();
		}
		$query_string = 'type=public&offset='.$offset.'&tag='.$tag['ID'];
		$entries = get_entries($query_string);
		if(file_exists(BJTEMPLATE . '/tag-'.$tag['ID'].'.php')) {
			include(BJTEMPLATE . '/tag'.$tag['ID'].'.php');
		}
		elseif(file_exists(BJTEMPLATE . '/tag.php')) {
			include(BJTEMPLATE . '/tag.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
		break;
	case 'author' :
		$authors = get_users('f_name='.$getname);
		if(!$authors) {
			load_404_instead();
		}
		if(file_exists(BJTEMPLATE .'/author.php')) {
			include(BJTEMPLATE . '/author.php');
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
	case 'urmom' :
		header("Content-Type: image/png\n" .
			   "Content-Disposition: inline; filename=\"erm.png\"");
		echo base64_decode(
			'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAH/UlEQVR4nD2X2a4bWXJF146InEjeoXRVGlruGrob'.
			'XfBDf4K/wT9vwH5puP1Qdg0oqSTdicw8EX442SJBJEESPJFxduy9jv793/5SrSUIzIQh3AQSkhACgXsg9ocMVFAi'.
			'q8jWqEq2bSVb458/TIrHh5WHx5UqoACqXwBJRFVhBibDJFBhMrQ/v6xZIDPMRw53/0JMC5gR4wQKattYnx94/PB/'.
			'nB8+sK3PbG0lQgzubFlkSyp7YVlAJQGQVV+WMhkUyPonZobMkDnT9Suu3/0r09UNuT7TtgttS3w6gUQULC+/o10e'.
			'uDx84P79/5K//Mjz0z1WCWaUisoEikx6AWGOyXDrXZDZvriQOfJgufuG6e47SuL8+AlKYIGPxjBuZCXrJdmyiOnE'.
			'ablmuX3LdPWa7e//wdPH97SWZPWbVBVJEpIwCZchOZJ92UPUFxlvvmH5+q9YTAyDmA5BhDBV75SKzGRbLzx8fuZy'.
			'PlNMyIKrl2/50/HEz//9X3z86R+01siEgSKb9Q6A7SsW2C4+Cfdgefkdy8tvOR4HDrcv8GhIbZeSAFHZMCVmgfuB'.
			'h88PbGvjsoEQ03Lg7Q9/4/J0z+PvPyMVYSK9CCHcHZf1xU2YORbG8eWfOLz5ntNhY7me8TgjgRRU6UvRZU5l0rIw'.
			'Xbi+GXh6FHqGtoE5TBW8/PbP/Hz+SLbWdZdt70AmKaCElYBkWO44vv6e43HjeB348M+xnECiSIRDJcVKsoJE4qCJ'.
			'+TCQ+YkVJ5thKq7v3vD78Zrnh9+pLSlEIJA7Ju/XfbSu/vBXDlficAVYYR6YzaisF1BJ1blrhgFpQLnx/PyAh4gw'.
			'loNT2TivDUJEOMe7dzy8/0BrSWuFgVA5krBd+YcXb7n6+gWnK4hw7u9/RbJepDVMKx4DMZwwHzAvzLb+nTmmCR8O'.
			'DOPCMIpqje28Qia3r14xzAvhRgyGUUVrF7JtVDZivuWrb37gdO2M04S7c3N1g5swT3w44MMRt8Bsxb0wH3AfCJ84'.
			'na5ZphNuIxETh+OEObT1AgqWq5fcvnqLDY5JhKm3BgyPibvv/8bp5sg0JfIJ58g4vsH8ADYiVmCjcqPy3D8vJ3FM'.
			'0NRIFV6F5ABMc/F0X5S65G7efMvn336k2YWICDxG3J2rP/zA8e41w9zwCMwWzA6YBdIGPAH9j5tEtiJ87ltoG9k2'.
			'HEe07v0E5TPLktwHRIxgjasXr1kO1zx++o2QDA8n5humu++QVoZhJmJBdtPNhhWUfHosEuMwT6AJ4sRWgWhd/Wag'.
			'RpGQDXNQLbhGPBKTUYJhNk4v3/Hw4T2BGRbO9bu/UDhS4T4iO/R01AZyPj7C/RoclpFUUEDmCtkQxrZtUCvFGTJx'.
			'YBiDqg2yCHfYA8/CuXn9Rz799HfC3VEs2OEV2/bENF0RMeAWoIaUtDI+X5JpnpBEfnFNB5yqjRg31suGaSYxKgv3'.
			'AC5US8IdNycBk3O8fcFydUtM88LxxVvWLZmnYFpmzEfMDalbxf3ZmJaFaQgGSyjISrALmee+kG4orfhgtLV1u/We'.
			'fuM8MSfEMPF8WRFinBYON3eExYhN11RuLPOBYZiRj5gNSEnRKAVDDIQXYxjQMIpk2uEiyHTCpy5SnhldhG80jHE+'.
			'sp4fiXHged1IBYMnV3evCPORGK+oIRinkXFaCB+/RHGV0zBQEgZDOJndDVWFa3fRKkRRGBqDtq24OZQzLie2p5WK'.
			'kbALZgPuK7ev3xHymel4y/lyZpyPmPcYNSvMRJYYB9FouA+YGWZFFYw2dHEVZBmVO8RU664K4DAfT5w/39MkhnCq'.
			'OpRMy4HIZgzTzNO5Mc4zvudBD8eVkhMu0IJH4TYQPlIYbsVlvTD4gCEwQUHbkohA1Xa0mFlOI5/vN8Zp6kGWDW2J'.
			'WQizYhgGIqYOJYieDBthC2FT74xmzCfco4NrwfP9iito2fFAKmR0ujLtHDkxHa+JcMYxCAe35OHje6ydH2jP99gw'.
			'4RE7CBXS1otxZx5HoiBb72lhFKKlUDhtD7HMhqkYB8d3X3Jzus6+YhiNyk9UXTAVl4dHImyjrU/UcEL6AswdPGzA'.
			'BMvoVAm52FqyJThimiZuhonMjTDDoqMdtZLlJOpx3ZyiGGNjPReSMex8EVjx+PEX5q9fgLzftQ1IsXehMHOuD0EW'.
			'rC07B2YjtIGSqsY873RXPQcqg6aB1hpV0S1dK+EHUobFyni4JjLF+vyRr5aR8OjEqgH0jMidWbTbMkxjQ7L9VbtW'.
			'nB0zqUwEpAqzEeoBSjQXFiNhA4xBUPiwEhRcHu759Mv/8OabN2COWLt6FX2WdMG0Ytb6hOwHGJH9PYXo21RqlNm+'.
			'na1rJjtRTcs14zCS7cLlOfjpH/9JVBnmTj7/thNRIDVkI+beSdcSszOmYZf6Pil7J4pGVev5oOjFWcN2JmiXDTAi'.
			'RspGqOLXH3/l/PiZUD+XsVy9YJxPyKJTTox91HwGQXZ8xNXxzWPYIdXIbGRbKQmpx27L1sOMRowDmLOtZ9q5nwmf'.
			'7j9CQaxrY/TgePsS89jzvCBXisLdujYscPXpMOsHFugeIoGraCWqbUAQbjSJygGU4IGGKyLuyexClkQYhhKmceh7'.
			'X0mlUcYeSCNUJ9/SPgFmoLkbDRcw64eVEqWZTGht14cSKXE1yoMM0BZITlUSZsKHka//+OeehOpZbzbuADrh+wGV'.
			'XHcjCsTQ0VxBIcTWvXMXbuWGfKZao7iw1RmqYQrKradpS/4fX0GB1sn7b74AAAAASUVORK5CYII='
		);
		break;
	default :
		$section = $bj_db->get_item("SELECT * FROM `".$bj_db->sections."` WHERE `shortname` = '".load_option('default_section')."' LIMIT 1");
		if(!$section) {
			load_404_instead();
		}
		$query_string = 'type=public&section='.$section['ID'].'&offset='.$offset;
		$entries = get_entries($query_string);
		if(file_exists(BJTEMPLATE .'/'.$section['handler']) and $section['handler'] != '') {
			include(BJTEMPLATE .'/'.$section['handler']);
		}
		else {
			include(BJTEMPLATE . '/index.php');
		}
}

?>