//TinyMCE
tinyMCE.init({
	theme : "advanced",
	mode : "exact",
	elements : "textarea",
	extended_valid_elements : "a[href|target|name]",
	debug : false,
	remove_linebreaks : false,
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resize_horizontal : false,
	theme_advanced_resizing : true
});

//Tooltips
window.onload = function() {
	new Tips($S('var'), {transitionStart:Fx.Transitions.sineIn,transitionEnd:Fx.Transitions.sineOut});
};

ajaxDelete = function(url,id,text) {
	var j00sure = confirm(text);
	if(j00sure) {
		var delCall = new Ajax(url,{onComplete:confirmus});
		delCall.request();
		var hideThis = new Fx.Opacity(id,{duration:750});
		hideThis.custom(1, 0.2);
	}	
};	

//Ajax deleter.
confirmus = function(text,xml,thing){
	document.getElementById("ajaxmessage").innerHTML="<strong class=\"error\">" + text +"</strong>";
};