<?php

class bj_entries {
	var $limit;
	var $offset;
	var $normal = true;
	var $fromcache;
	var $cache_key = '';
	var $query_key = '';
	var $saved = array();
	
	#Query information in the WHERE clause.
	var $args = array();
	var $tags = '';
	var $search = '';
	var $sortby = 'posted';
	var $sort = 'DESC';

	function bj_entries($offset,$limit,$cache=true) {
		global $i;
		$i = 0;
		$this->offset = $offset;
		$this->limit = $limit;
		$this->fromcache = $cache;
	}
	
	function setID($v) {
		$this->normal = false;
		$this->args['ID'] = $v;
	}
	
	function setSecond($v) {
		$this->normal = false;
		$this->args['SECOND(posted)'] = $v;
	}
	
	function setMinute($v) {
		$this->normal = false;
		$this->args['MINUTE(posted)'] = $v;
	}
	
	function setHour($v) {
		$this->normal = false;
		$this->args['HOUR(posted)'] = $v;
	}
	
	function setDay($v) {
		$this->normal = false;
		$this->args['DAYOFMONTH(posted)'] = $v;
	}
	
	function setMonth($v) {
		$this->normal = false;
		$this->args['MONTH(posted)'] = $v;
	}
	
	function setYear($v) {
		$this->normal = false;
		$this->args['YEAR(posted)'] = $v;
	}
	
	function setTitle($v) {
		$this->normal = false;
		$this->args['title'] = $v;
	}
	
	function setShortname($v) {
		$this->normal = false;
		$this->args['shortname'] = $v;
	}
	
	function setSection($v) {
		$this->args['section'] = $v;
	}
	
	function setAuthor($v) {
		$this->args['author'] = $v;
	}
	
	function setPtype($v) {
		$this->normal = false;
		$this->args['ptype'] = $v;
	}
	
	function fromCache($v) {
		$this->fromcache = $v;
	}
	
	#Extremely abormal stuff.
	function setTags($v) {
		if(strpos($v,',') !== false or strpos($v,'s') !== false) {
			$this->normal = false;
			$this->args['tags'] = str_replace(',','-',$v);
			$this->tags .= " (";
			$t = explode(",",$v);
			foreach($t as $i=>$tag) {
				if($i > 0)
					$this->tags .= " OR ";
				if(substr($tag,0,1) == "s")
					$this->tags .= "tags NOT REGEXP '\"".substr($tag,1)."\"'";
				else
					$this->tags .= "tags REGEXP '\"".$tag."\"'";
			}
			$this->tags .= ")";
		}
		else {
			$this->args['tags'] = $v;
			$this->tags = " (tags REGEXP '\"".$v."\"')";
		}
	}
	
	function setSearch($v) {
		$this->normal = false;
		$s = str_replace(' ','+',$v);
		$s = explode('+',$s);
		$this->args['search'] = implode('+',$s);
		$this->search .= " (((title LIKE '%".$s[0]."%') OR (content LIKE '%".$s[0]."%'))";
		for ( $i = 1; $i < count($s); $i++)
			$this->search .= " OR ((title LIKE '%".$s[$i]."%') OR (content LIKE '%".$s[$i]."%'))";
		$this->search .= ')';
	}
	
	function sortBy($v) {
		if($v != 'posted')
			$this->normal = false;
		$this->sortby = $v;
	}
	
	function sortOrder($v) {
		if($v != 'DESC')
			$this->normal = false;
		$this->sort = $v;
	}
	
	function setLimit($o,$v) {
		$this->offset = $o;
		$this->limit = $v;
	}
	
	function fetch() {
		global $bj;
		
		run_actions('prefetch'); #A little less talk, a little more action.
		
		#Doing a normal cache fetch can only involve parsing one of these.
		if($this->args['section'] and ($this->args['author'] or $this->args['tags']))
			$this->normal = false;
		elseif($this->args['author'] and ($this->args['section'] or $this->args['tags']))
			$this->normal = false;
		elseif($this->args['tags'] and ($this->args['author'] or $this->args['section']))
			$this->normal = false;
		
		$this->build_cache_key();
		
		#Do we normally do this?
		if($this->normal)
			foreach($this->args as $arg=>$value)
				$arr = $bj->cache->get_entries($arg,$value,$this->fromcache);
		else {
			add_action('query_extra_'.$this->cache_key,array($this,'query_key'));
			$arr = $bj->cache->get_entries($this->cache_key,0,$this->fromcache);
		}
		
		#Limit and offset
		if(is_array($arr) and $this->limit)
			$arr = array_slice($arr,$this->offset,$this->limit);
		
		#Later, d00d!
		return $arr;
	}
	
	function build_cache_key() {
		$this->cache_key = '';
		foreach($this->args as $arg=>$value) {
			if($value == false)
				unset($this->args[$arg]);
			else
				$this->cache_key .= '-'.$arg.'-'.$value;
		}
		if(!$this->normal)
			$this->cache_key .= '-sort-'.$this->sortby.'-'.$this->sort;
		$this->cache_key = '-'.bj_shortname($this->cache_key);
	}
	
	function query_key() {
		$this->query_key = '';
		#Args.
		if(count($this->args) > 0) {
			$this->query_key .= ' WHERE';
			$and = '';
			$i = 0;
			foreach($this->args as $arg=>$value) {
				if($arg != 'tags' and $arg != 'search') {
					if($arg == 'ptype' and $value == '*')
						$this->query_key .= $and.' '.$arg.' IS NOT NULL';
					else
						$this->query_key .= $and.' '.$arg.' = \''.$value.'\'';
					$i++;
					if($i > 0)
						$and = ' AND';
				}
			}
			if(!empty($this->tags)) {
				$this->query_key .= $and.$this->tags;
				$and = ' AND';
			}
			if(!empty($this->search))
				$this->query_key .= $and.$this->search;
		}
		#Order by...
		$this->query_key .= ' ORDER BY '.$this->sortby.' '.$this->sort;
		#Bon voyage, query!
		return $this->query_key;
	}
	
	function saveas($name) {
		$this->saved[$name] = array('args'=>$this->args,'tags'=>$this->tags,'search'=>$this->search,'sortby'=>$this->sortby,'sort'=>$this->sort,'offset'=>$this->offset,'limit'=>$this->limit,'normal'=>$this->normal,'fromcache'=>$this->fromcache);
	}
	
	function load($name) {
		if(isset($this->saved[$name])) {
			global $i;
			$i = 0;
			$this->args = $this->saved[$name]['args'];
			$this->tags = $this->saved[$name]['tags'];
			$this->search = $this->saved[$name]['search'];
			$this->sortby = $this->saved[$name]['sortby'];
			$this->sort = $this->saved[$name]['sort'];
			$this->offset = $this->saved[$name]['offset'];
			$this->limit = $this->saved[$name]['limit'];
			$this->normal = $this->saved[$name]['normal'];
			$this->fromcache = $this->saved[$name]['fromcache'];
			return true;
		}
		return false;
	}
	
	function restore() {
		global $i;
		$i = 0;
		$this->args = array();
		$this->tags = '';
		$this->search = '';
		$this->sortby = 'posted';
		$this->sort = 'DESC';
		$this->normal = true;
		$this->fromcache = true;
		return true;
	}
	
	function next_page($text,$before='',$after='') {
		global $bj;
		$older = $this->offset + $this->limit;
		$tmplimit = $this->limit;
		#We do this to grab all entries for the query processed. Pagination thing- you wouldn't understand.
		$this->setLimit($this->offset,false);
		if(count($this->fetch()) - $older > 0 and !is_entry()) {
			if(!is_admin()) {
				$load = (defined('BJ_REWRITE')) ? '' : 'index.php?load=';
				if(is_section() and !is_front())
					$extra_string = 'section/'.$bj->vars->load[1].'/';
				elseif(is_tag())
					$extra_string = 'tag/'.$bj->vars->load[1].'/';
				elseif(is_search())
					$extra_string = 'search/'.$bj->vars->load[1].'/';
				echo $before.'<a href="'.get_siteinfo('siteurl').$load.$extra_string.'page/'.$older.'">'.$text.'</a>'.$after;
			}
			else {
				if($_GET['req'] == 'filtertag' && $_GET['tag'] != '')
					$extra_string = 'req=filtertag&amp;tag='.bj_clean_string($_GET['tag']).'&amp;';
				elseif($_GET['req'] == 'filtersection' && $_GET['section'] != '')
					$extra_string = 'req=filtersection&amp;section='.bj_clean_string($_GET['section']).'&amp;';
				elseif(is_search())
					$extra_string = 'req=search&amp;s='.bj_clean_string($_GET['s']).'&amp;';
				echo $before.'<a href="'.get_siteinfo('siteurl').'admin/entries.php?'.$extra_string.'offset='.$older.'">'.$text.'</a>'.$after;
			}
		}
		$this->setLimit($this->offset,$tmplimit);
	}
	
	function prev_page($text,$before='',$after='') {
		global $bj;
		$newer = $this->offset - $this->limit;
		if($this->offset > 0 && !is_entry()) {
			if(!is_admin()) {
				if(is_section() and !is_front())
					$extra_string = 'section/'.$bj->vars->load[1].'/';
				elseif(is_tag())
					$extra_string = 'tag/'.$bj->vars->load[1].'/';
				$load = (defined('BJ_REWRITE')) ? '' : 'index.php?load=';
				echo $before.'<a href="'.get_siteinfo('siteurl').$load.$extra_string.'page/'.$newer.'">'.$text.'</a>'.$after;
			}
			else {
				if($_GET['req'] == 'filtertag' && $_GET['tag'] != '')
					$extra_string = 'req=filtertag&amp;tag='.$_GET['tag'].'&amp;';
				elseif($_GET['req'] == 'filtersection' && $_GET['section'] != '')
					$extra_string = 'req=filtersection&amp;section='.bj_clean_string($_GET['section']).'&amp;';
				elseif(is_search())
					$extra_string = 'req=search&amp;s='.bj_clean_string($_GET['s']).'&amp;';
				echo $before.'<a href="'.get_siteinfo('siteurl').'admin/entries.php?'.$extra_string.'offset='.$newer.'">'.$text.'</a>'.$after;
			}
		}
	}
}

$bj->query = new bj_entries(false,false);

?>
