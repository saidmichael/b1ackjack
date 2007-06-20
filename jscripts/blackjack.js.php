<?php
require('../bj_init.php');
$offset = 60*60*24*60;
$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s",time() + $offset)." GMT";
$LmStr = "Last-Modified: ".gmdate("D, d M Y H:i:s",filemtime(__FILE__))." GMT";
header("Cache-Control: public");
header("Pragma: cache");
header($ExpStr);
header($LmStr);
header('Content-Type: text/javascript; charset: UTF-8');
?>
var blackJack = {
	init: function() {
		//Tips
		new Tips($$('.cowtip'), {});
		
		$('content').getElements('.deleteme').each(function(link) {
			link.onclick = function() {
				blackJack.ajaxDelete(this.rel);
				return false;
			}
		});
		
		$('content').getElements('.button_deleteme').each(function(button) {
			button.onclick = function() {
				if(!confirm('<?php _e('Are you sure you want to delete this?'); ?>'))
					return false;
			}
		});
		
		if($('content').getElement('.editortaginsert')) {
			$('content').getElement('.editortaginsert').setHTML('<input type="text" name="ajaxtag" id="ajaxtag" value="" /> <input type="button" name="ajaxtagsubmit" id="ajaxtagsubmit" class="inlinesubmit" value="<?php _e('Add'); ?>" />');
			$('ajaxtagsubmit').onclick = function() {
				blackJack.ajaxAdd('tags.php?req=ajaxadd&li=true',{name:$('ajaxtag').getValue()},'headings');
			};
		}
		
		//Editbar
		blackJack.editbar = $('editbar');
		if(!blackJack.editbar) return false;
		else blackJack.booyah($('textarea'));
	},
	
	//postLang: for the editor.
	postLang : {
		snippets: {
			"strong" : ["<strong>","<?php _e('something here'); ?>","</strong>"],
			"em" : ["<em>","<?php _e('something here'); ?>","</em>"],
			"blockquote" : ["\n<blockquote>","<?php _e('something here'); ?>","</blockquote>"],
			"code" : ["<code>","<?php _e('something here'); ?>","</code>"],
			"bq" : ["\n<blockquote>","<?php _e('something here'); ?>","</blockquote>"],
			"ul" : {
				snippet:["\n<ul>\n	<li>","<?php _e('something here'); ?>","</li>\n</ul>"],
				tab:['<?php _e('something here'); ?>',''],
				start: 5
			},
			"ol" : {
				snippet:["\n<ol>\n	<li>","<?php _e('something here'); ?>","</li>\n</ol>"],
				tab:['something',''],
				completion: {
					'something':['text','snippet']
				},
				loop: true, //optional, default true
				start: 5 //position snippet[2] default false == snippet[2].length, true == 0
			},
			"li" : {
				snippet:["\n<li>","<?php _e('something here'); ?>","</li>"],
				tab:['<?php _e('something here'); ?>','']
			},
			"</li>" : {
				snippet:["</li>\n<li>","<?php _e('something here'); ?>","</li>"],
				tab:['<?php _e('something here'); ?>','']
			},
			"date" : {
				command: function(k) {
					var dayNames = ["<?php _e('Sunday'); ?>","<?php _e('Monday'); ?>","<?php _e('Tuesday'); ?>","<?php _e('Wednesday'); ?>","<?php _e('Thursday'); ?>","<?php _e('Friday'); ?>","<?php _e('Saturday'); ?>"],
					monthNames = ["<?php _e('January'); ?>","<?php _e('February'); ?>","<?php _e('March'); ?>","<?php _e('April'); ?>","<?php _e('May'); ?>","<?php _e('June'); ?>","<?php _e('July'); ?>","<?php _e('August'); ?>","<?php _e('September'); ?>","<?php _e('October'); ?>","<?php _e('November'); ?>","<?php _e('December'); ?>"],
					dt = new Date(),
					y  = dt.getYear();
					if (y < 1000) y +=1900;
					return {
						//key:"date", optional
						snippet:['',dayNames[dt.getDay()] + ", " + monthNames[dt.getMonth()] + " " + dt.getDate() + ", " + y,' '],
						tab:[dayNames[dt.getDay()] + ", " + monthNames[dt.getMonth()] + " " + dt.getDate() + ", " + y,'']
					};
				}
			},
			"a" : {
				snippet:['<a href="','http://" title="<?php _e('description'); ?>"><?php _e('something here'); ?>','</a>'],
				tab:['http://','<?php _e('description'); ?>','<?php _e('something here'); ?>','']
			},
			"img" : {
				snippet:['<img src="','http://" alt="<?php _e('alternate text'); ?>"',' />'],
				tab:['http://','<?php _e('alternate text'); ?>','']
			}
		},
		smartTypingPairs: {
			'"' : '"',
			'(' : ')',
			'{' : '}',
			'[' : ']',
			"`" : "`",
		},
		selections: { }
	},
	
	booyah: function(el) {
		new postEditor.create('textarea',false,blackJack.postLang);
		
		//Height
		if(Cookie.get('<?php echo $bj->db->prefix; ?>te_height') != '') {
			height = Cookie.get('<?php echo $bj->db->prefix; ?>te_height');
			var myFx = new Fx.Style('textarea', 'height').set(height);
		}
		$('editbar').setStyle('display','block');
		$('nonJSedit').setStyle('display','none');
		
		blackJack.editbar.getElements('a.button').each(function(link) {
			link.onclick = function()	{
				if (this.hasClass('newline')) blackJack.insertItem(el, this.rel, "\n");
				else blackJack.insertItem(el, this.rel);
				return false;
			}
		});
		
		blackJack.editbar.getElement('a.less').onclick = function(){
			var effect = $('textarea').effect('height',{duration: 375});
			var height = $('textarea').getStyle('height').toInt();
			var newheight = height - 60;
			effect.custom(height, newheight);
			Cookie.set('<?php echo $bj->db->prefix; ?>te_height',newheight);
			return false;
		};
		
		blackJack.editbar.getElement('a.more').onclick = function(){
			var effect = $('textarea').effect('height',{duration: 375});
			var height = $('textarea').getStyle('height').toInt();
			var newheight = height + 60;
			effect.custom(height, newheight);
			Cookie.set('<?php echo $bj->db->prefix; ?>te_height',newheight);
			return false;
		};
		
		blackJack.editbar.getElement('a.moresep').onclick = function(){
			blackJack.insertItem(el, this.rel, "\n\n",'<?php echo run_actions('snippet_separator','__More__'); ?>');
			return false;
		};
	},
	
	insertItem: function(el,rel,newline,selitem) {
		newline = newline || '';
		var start = el.selectionStart;
		var end = el.selectionEnd
		var bits = rel.split('$');
		var sel = el.value.substring(start, end);
		if (!sel.length) sel = selitem || "<?php _e('something here'); ?>";
		var content = newline+bits[0]+sel+bits[1];
		el.value = el.value.substring(0, start) + content + el.value.substring(end, el.value.length);
	},
	
	//Ajax deleter.
	ajaxDelete: function(rel) {
		var bits = rel.split('$');
		if(confirm('<?php _e('Are you sure you want to delete this?'); ?>')) {
			var delCall = new Ajax(bits[0],{onComplete:blackJack.deleteEffect});
			delCall.request();
			$('messages').setHTML('<p id="loading"><?php _e('Loading'); ?></p>');
			var hideThis = new Fx.Style(bits[1],'opacity',{duration:750});
			hideThis.start(1, 0.2);
		}
	},

	deleteEffect: function(text) {
		var hideThis = new Fx.Style('messages','opacity',{duration:100});
		hideThis.start(1, 0);
		$('messages').setHTML('<p>'+text+'</p>');
		var showThis = new Fx.Style('messages','opacity',{duration:100});
		showThis.start(0, 1);
	},
	
	//And its opposite, the Ajax adder.
	ajaxAdd: function(url,postdata,injectafter) {
		blackJack.addInject = injectafter;
		new Ajax(url,{postBody:postdata,onComplete:blackJack.addEffect,evalScripts:true}).request();
	},

	addEffect: function(text) {
		/*
		 * Now, just a note:
		 * This requires, somewhere in the returned row,
		 * an element like <span id="latest_id" class="id_here"></span>,
		 * where id_here is the row ID you want to use.
		 */
		new Element('tr').injectAfter(blackJack.addInject).setHTML(text).setProperty('id',$('latest_id').getProperty('class'));
		//Fancy color effect.
		var myColorFx = new Fx.Style($('latest_id').getProperty('class'), 'background-color', {duration: 750});
		myColorFx.start('<?php echo run_actions('addEffect_hlight','EAE559'); ?>', '<?php echo run_actions('addEffect_faded','EBEBEB'); ?>');
		//Delete our fix from the DOM.
		$('latest_id').remove();
	},
	
	//And finally, the ajax updater.
	ajaxUpdate: function(url,postdata,update) {
		blackJack.updateID = update;
		new Ajax(url,{postBody:postdata,onComplete:blackJack.updateEffect,evalScripts:true}).request();
	},

	updateEffect: function() {
		//Fancy color effect.
		var myColorFx = new Fx.Style(blackJack.updateID, 'background-color', {duration: 750});
		myColorFx.start('<?php echo run_actions('addEffect_hlight','EAE559'); ?>', '<?php echo run_actions('addEffect_faded','EBEBEB'); ?>');
	}
};

Window.onDomReady(blackJack.init);
