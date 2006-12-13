<?php
require('../bj_config.php');
$offset = 60*60*24*60;
$ExpStr = "Expires: ".gmdate("D, d M Y H:i:s",time() + $offset)." GMT";
$LmStr = "Last-Modified: ".gmdate("D, d M Y H:i:s",filemtime(__FILE__))." GMT";
header("Cache-Control: public");
header("Pragma: cache");
header($ExpStr);
header($LmStr);
header('Content-Type: text/javascript; charset: UTF-8');
?>
//Tooltips
window.onload = function() {
	new Tips($S('var'), {transitionStart:Fx.Transitions.sineIn,transitionEnd:Fx.Transitions.sineOut});
	
	//RTE stuff- More below all of this too.
	if(Cookie.get('<?php echo $bj_db->prefix; ?>te_height') != '') {
		height = Cookie.get('<?php echo $bj_db->prefix; ?>te_height');
		var myFx = new Fx.Style('textarea', 'height').set(height);
	}
	$('editbar').setStyle('display','block');
};

//Ajax deleter.
ajaxDelete = function(url,id,text) {
	var j00sure = confirm(text);
	if(j00sure) {
		var delCall = new Ajax(url,{onComplete:deleteEffect});
		delCall.request();
		var hideThis = new Fx.Opacity(id,{duration:750});
		hideThis.custom(1, 0.2);
	}
};

deleteEffect = function(text) {
	$('ajaxmessage').setHTML(text);
};

//And its opposite, the Ajax adder.
ajaxAdd = function(url,postdata) {
	new Ajax(url,{postBody:postdata,onComplete:addEffect}).request();
};

addEffect = function(text) {
	/*
	 * Now, just a note:
	 * This requires, somewhere in the returned row,
	 * an element like <span id="latest_id" class="id_here"></span>,
	 * where id_here is the row ID you want to use.
	 */
	new Element('tr').injectAfter('headings').setHTML(text).setProperties({id:$('latest_id').getProperty('class')});
	//Fancy color effect.
	var myColorFx = new Fx.Color($('latest_id').getProperty('class'), 'background-color', {duration: 2700});
	myColorFx.custom('<?php echo run_filters('addEffect_hlight','EAE559'); ?>', '<?php echo run_filters('addEffect_faded','EBEBEB'); ?>');
	//Delete our fix from the DOM.
	$('latest_id').remove();
	$('latest_el').remove();
};


//Editor stuff
moreheight = function(id,pixels) {
	var effect = $(id).effects();
	var height = $(id).getStyle('height').toInt();
	var newheight = height + pixels;
	effect.custom({
      'height': [height, newheight],
      'duration': 250,
    });
    Cookie.set('<?php echo $bj_db->prefix; ?>te_height',newheight);
};

lessheight = function(id,pixels) {
	var effect = $(id).effects();
	var height = $(id).getStyle('height').toInt();
	var newheight = height - pixels;
	effect.custom({
      'height': [height, newheight],
      'duration': 250,
    });
    Cookie.set('<?php echo $bj_db->prefix; ?>te_height',newheight);
};

//Simple tags- like strong, emphasis, images. Stuff like that.
simpleTag = function(id,tag,text,insertnl) {
	if(insertnl == true) {
		$(id).appendText('\n<' + tag + '>' + text + '</' + tag + '>');
	}
	else {
		$(id).appendText('<' + tag + '>' + text + '</' + tag + '>');
	}
};

imgTag = function(id,alttext,srctext) {
	$(id).appendText('<img src="' + srctext + '" alt="' + alttext + '" />');
};

linkTag = function(id,text,hreftext) {
	$(id).appendText('<a href="' + hreftext + '">' + text + '</a>');
};

listTag = function(id,tag,li) {
	$(id).appendText('\n<' + tag + '>\n<li>' + li + '</li>\n<li>' + li + '</li>\n<li>' + li + '</li>\n</' + tag + '>');
};