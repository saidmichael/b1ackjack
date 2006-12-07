//Tooltips
window.onload = function() {
	new Tips($S('var'), {transitionStart:Fx.Transitions.sineIn,transitionEnd:Fx.Transitions.sineOut});
	
	//RTE stuff- More below all of this too.
	if(Cookie.get('bj_te_height') != '') {
		height = Cookie.get('bj_te_height');
		var myFx = new Fx.Style('textarea', 'height').set(height);
	}
	$('editbar').setStyle('display','block');
};

//Ajax deleter.
ajaxDelete = function(url,id,text) {
	var j00sure = confirm(text);
	if(j00sure) {
		var delCall = new Ajax(url,{onComplete:confirmus});
		delCall.request();
		var hideThis = new Fx.Opacity(id,{duration:750});
		hideThis.custom(1, 0.2);
	}
};

confirmus = function(text,xml,thing){
	$("ajaxmessage").setHTML="<strong class=\"error\">" + text +"</strong>";
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
    Cookie.set('bj_te_height',newheight);
};

lessheight = function(id,pixels) {
	var effect = $(id).effects();
	var height = $(id).getStyle('height').toInt();
	var newheight = height - pixels;
	effect.custom({
      'height': [height, newheight],
      'duration': 250,
    });
    Cookie.set('bj_te_height',newheight);
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