<?php

/*
bj_locale()
Controls language handling and other sorts of fun stuff. Makes it easier for dates and months.
*/

class bj_locale {
	var $langs = array();
	var $weekday;
	var $weekday_initial;
	var $weekday_abbrev;
	var $month;
	var $month_abbrev;
	var $meridiem;
	
	function bj_locale() {
		global $bj;
		#Main file.
		if(file_exists(BJPATH."content/langs/".BJ_LANG.".php"))
			require_once(BJPATH."content/langs/".BJ_LANG.".php");
		#Plugins
		foreach($bj->vars->plugins as $plugin=>$null)
			if(file_exists(BJPATH."content/langs/plugin_".$plugin."_".BJ_LANG.".php"))
				require_once(BJPATH."content/langs/plugin_".$plugin."_".BJ_LANG.".php");
		#Themes
		if(file_exists(BJPATH."content/langs/skin_".current_skinname()."_".BJ_LANG.".php"))
			require_once(BJPATH."content/langs/skin_".current_skinname()."_".BJ_LANG.".php");

		// The Weekdays
		$this->weekday[0] = $this->_r('Sunday');
		$this->weekday[1] = $this->_r('Monday');
		$this->weekday[2] = $this->_r('Tuesday');
		$this->weekday[3] = $this->_r('Wednesday');
		$this->weekday[4] = $this->_r('Thursday');
		$this->weekday[5] = $this->_r('Friday');
		$this->weekday[6] = $this->_r('Saturday');

		// The first letter of each day.  The _%day%_initial suffix is a hack to make
		// sure the day initials are unique.
		$this->weekday_initial[$this->_r('Sunday')]    = $this->_r('S_Sunday_initial');
		$this->weekday_initial[$this->_r('Monday')]    = $this->_r('M_Monday_initial');
		$this->weekday_initial[$this->_r('Tuesday')]   = $this->_r('T_Tuesday_initial');
		$this->weekday_initial[$this->_r('Wednesday')] = $this->_r('W_Wednesday_initial');
		$this->weekday_initial[$this->_r('Thursday')]  = $this->_r('T_Thursday_initial');
		$this->weekday_initial[$this->_r('Friday')]    = $this->_r('F_Friday_initial');
		$this->weekday_initial[$this->_r('Saturday')]  = $this->_r('S_Saturday_initial');

		foreach ($this->weekday_initial as $weekday_ => $weekday_initial_)
			$this->weekday_initial[$weekday_] = preg_replace('/_.+_initial$/', '', $weekday_initial_);

		// Abbreviations for each day.
		$this->weekday_abbr[$this->_r('Sunday')]    = $this->_r('Sun');
		$this->weekday_abbr[$this->_r('Monday')]    = $this->_r('Mon');
		$this->weekday_abbr[$this->_r('Tuesday')]   = $this->_r('Tue');
		$this->weekday_abbr[$this->_r('Wednesday')] = $this->_r('Wed');
		$this->weekday_abbr[$this->_r('Thursday')]  = $this->_r('Thu');
		$this->weekday_abbr[$this->_r('Friday')]    = $this->_r('Fri');
		$this->weekday_abbr[$this->_r('Saturday')]  = $this->_r('Sat');

		// The Months
		$this->month['01'] = $this->_r('January');
		$this->month['02'] = $this->_r('February');
		$this->month['03'] = $this->_r('March');
		$this->month['04'] = $this->_r('April');
		$this->month['05'] = $this->_r('May');
		$this->month['06'] = $this->_r('June');
		$this->month['07'] = $this->_r('July');
		$this->month['08'] = $this->_r('August');
		$this->month['09'] = $this->_r('September');
		$this->month['10'] = $this->_r('October');
		$this->month['11'] = $this->_r('November');
		$this->month['12'] = $this->_r('December');

		// Abbreviations for each month. Uses the same hack as above to get around the
		// 'May' duplication.
		$this->month_abbr[$this->_r('January')] = $this->_r('Jan_January_abbreviation');
		$this->month_abbr[$this->_r('February')] = $this->_r('Feb_February_abbreviation');
		$this->month_abbr[$this->_r('March')] = $this->_r('Mar_March_abbreviation');
		$this->month_abbr[$this->_r('April')] = $this->_r('Apr_April_abbreviation');
		$this->month_abbr[$this->_r('May')] = $this->_r('May_May_abbreviation');
		$this->month_abbr[$this->_r('June')] = $this->_r('Jun_June_abbreviation');
		$this->month_abbr[$this->_r('July')] = $this->_r('Jul_July_abbreviation');
		$this->month_abbr[$this->_r('August')] = $this->_r('Aug_August_abbreviation');
		$this->month_abbr[$this->_r('September')] = $this->_r('Sep_September_abbreviation');
		$this->month_abbr[$this->_r('October')] = $this->_r('Oct_October_abbreviation');
		$this->month_abbr[$this->_r('November')] = $this->_r('Nov_November_abbreviation');
		$this->month_abbr[$this->_r('December')] = $this->_r('Dec_December_abbreviation');

		foreach ($this->month_abbr as $month_ => $month_abbrev_)
			$this->month_abbr[$month_] = preg_replace('/_.+_abbreviation$/', '', $month_abbrev_);

		// The Meridiems
		$this->meridiem['am'] = $this->_r('am');
		$this->meridiem['pm'] = $this->_r('pm');
		$this->meridiem['AM'] = $this->_r('AM');
		$this->meridiem['PM'] = $this->_r('PM');
	}
	
	#Function: _e(Text)
	#Description: Prints from the language file (if the phrase is stored).
	function _e($text) {
		echo $this->_r($text);
	}
	
	#Function: _r(Text)
	#Description: Returns based upon a language.
	function _r($text) {
		if(isset($this->langs[$text]))
			return $this->langs[$text];
		else
			return $text;
	}
}

$bj->locale = new bj_locale();

#Shorthand.
function _e($text) {
	global $bj;
	echo $bj->locale->_r($text);
}

function _r($text) {
	global $bj;
	return $bj->locale->_r($text);
}

?>
