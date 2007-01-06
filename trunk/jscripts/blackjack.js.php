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
/*
Script: postEditor.js
	Using postEditor you can tabulate without losing your focus and maintain the tabsize in line brakes. 
	You can also use snippets like in TextMate.
Author:
	Daniel Mota aka IceBeat, <http://icebeat.bitacoras.com>
Contributors:
	Sergio Álvarez aka Xergio, <http://xergio.net>
	Jordi Rivero aka Godsea, <http://godsea.dsland.org>
License:
	MIT-style license.
*/
//Language files

//Actual editor. Compressed to preserve filesize.
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[(function(e){return d[e]})];e=(function(){return'\\w+'});c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('j 1Y={};1Y.2d=2e 2f({q:"	",1V:r(F){b.F=1X.16({17:{},W:{},19:{}},F||{})},2g:r(1M,N,F){d(2h.2i)u;b.v=$(1M);b.N=$(N);b.1V(F);b.Z={1f:b.v.1u(\'1s-U\').1v()||14,2j:b.v.1u(\'2k-2l\').1v()||11,U:b.v.1u(\'U\').1v()};b.h=B;b.I=0;b.O=0;b.t=B;b.L=b.q.f;b.v.2m=b.1A.2n(b)},2o:r(17){b.F.17=17||{}},2p:r(W){b.F.W=W||{}},2q:r(19){b.F.19=19||{}},c:r(){u b.v.21},k:r(){u b.v.22},y:r(l,E){u b.v.o.y(l,E)},o:r(o){b.v.o=o.V("")},H:r(M){j M=M?M.f:0;u b.y(0,b.c()-M)},J:r(M){j M=M?M.f:0;u b.v.o.y(b.k()-M)},x:r(l,E){b.v.21=l;b.v.22=l+E},R:r(R,Q){d(Q){b.12=b.v.12;b.1m=b.v.1m}z{b.v.12=b.12;b.v.1m=b.1m}d(R)b.v.R()},1Q:r(){j 1y=b.H().1l("\\n").f,U=(1y-23.24(b.v.12/b.Z.1f))*b.Z.1f;U+=b.Z.1f;d(U>=b.Z.U)b.v.12+=b.Z.1f;b.R(G,1)},1A:r(e){d(b.1D(e))u;d(b.1E(e))u;b.1F(e);d(b.1L(e))u;d([13,9,8,1n].1b(e.1c))b.R(S,G);25(e.1c){1d 27:b.t=B;b.h=B;10;1d 13:b.1P(e);10;1d 9:b.1o(e);10;1d 8:b.1R(e);10;1d 1n:b.1T(e);10}d([13,9,8,1n].1b(e.1c))b.R(G,S)},1D:r(e){d(e.1I&&e.1c==13){d(b.N){e.Y();b.N.R();u G}}u S},1E:r(e){j K=1G.1H(e.K),C=b.F.W[K];d(C){d($Q(C)==\'1S\')C={X:C};d(!C.T||b.T(C.T)){j c=b.c(),k=b.k(),l=b.H();d(c==k){b.o([l,C.X,b.J()]);b.x(l.f,0)}z{e.Y();b.I=c;b.O=k;b.o([l,K,b.y(c,k),C.X,b.J()]);b.x(c+1,k-c)}}C=B;u G}u S},1F:r(e){j K=1G.1H(e.K);d(e.26&&e.1I){d([0,1,2,3,4,5,6,7,8,9].1b(K)){j 1p=b.F.19[K];d(1p){j c=b.c(),k=b.k(),w=1p.1Z(b,[b.y(c,k)]);d(w){j l=b.H();d($Q(w)==\'1x\'){b.o([l,w.V(""),b.J()]);b.x(l.f+w[0].f,w[1].f)}z{d(w.1a){d(w.p){l=b.y(0,w.1a[0]);j E=b.y(w.1a[1],b.v.o.f);b.o([l,w.p.V(""),E]);b.x(l.f+w.p[0].f,w.p[1].f)}z{b.x(w.1a[0],w.1a[1])}}z{b.o([l,w.p.V(""),b.J()]);b.x(l.f+w.p[0].f,w.p[1].f)}}}}}}},1L:r(e){d(b.h){j c=b.c(),k=b.k(),D=b.I,E=b.O;d(![D+1,D,D-1,E].1b(c)){b.t=B;b.h=B}d(b.h&&[2a,2b].1b(e.1c)&&c==k){b.t=B;b.h=B}b.I=c;b.O=k}z{b.I=0;b.O=0}u S},T:r(1r){j c=b.c(),P=b.H();1W(j D 20 1r){d(!D)u G;j 1q=P.1w(D);d(1q>-1){j 1j=b.y(1q+D.f,c).1w(1r[D]);d(1j==-1)u G}}u S},1P:r(e){b.1Q();j c=b.c(),k=b.k(),l=b.H();d(c==k){j 1s=l.1l("\\n").1z(),q=1s.1h(/^\\s+/1C);d(q){e.Y();q=q.V("");b.o([l,"\\n",q,b.J()]);b.x(c+1+q.f,0)}}},1R:r(e){j c=b.c(),k=b.k();d(c==k&&b.y(c-b.L,c)==b.q){e.Y();j l=b.H(b.q),E=b.y(c,b.v.o.f);d(l.1h(/\\n$/g)&&E.1h(/^\\n/g)){b.o([l,b.y(c-1,b.v.o.f)])}z{b.o([l,E])}b.x(c-b.L,0)}z d(c==k){j K=b.y(c-1,c),1j=b.y(c,c+1),C=b.F.W[K];d($Q(C)==\'1S\')C={X:C};d(C&&C.X==1j){b.o([b.H(C.X),b.y(c,b.v.o.f)]);b.x(c,0)}}},1T:r(e){j c=b.c(),k=b.k();d(c==k&&b.y(c,c+b.L)==b.q){e.Y();b.o([b.H(),b.y(c+b.L,b.v.o.f)]);b.x(c,0)}},1o:r(e){e.Y();j c=b.c(),k=b.k(),w=b.y(c,k),P=b.H();d(b.1J(e,c,k))u;d(b.1N(e,c,k))u;d(c!=k&&w.1k("\\n")!=-1){j 1U=w.1i(/\\n/g,"\\n"+b.q);b.o([P,b.q,1U,b.J()]);b.x(c+b.L,k+(b.L*w.1l("\\n").f)-c-b.L)}z{j A=B;1W(j D 20 b.F.17){j o=b.F.17[D];d($Q(o)==\'r\')1K;d(P.f-D.f==-1)1K;d(P.f-D.f==P.1w(D)){d($Q(o)==\'1x\')o={p:o};A=1X.16({},o);10}}d(A&&(!A.T||b.T(A.T))){d(A.15){j 15=A.15.1Z(b,[D]);d($Q(15)==\'1x\')A.p=15;z A=15}j p=A.p.1g(),q=P.1l("\\n").1z().1h(/^\\s+/1C),l=b.H(A.D||D);d(q){q=q.V("");p[0]=p[0].1i(/\\n/g,"\\n"+q);p[1]=p[1].1i(/\\n/g,"\\n"+q);p[2]=p[2].1i(/\\n/g,"\\n"+q)}b.o([l,p[0],p[1],p[2],b.J()]);d(A.q){b.h={q:A.q.1g(),p:p.1g(),l:A.l};j m=b.h.q.1O();b.h.c=p[1].1k(m);d(b.h.c>-1){b.h.18=l.f+p[0].f+b.h.c;b.I=b.h.18;b.O=b.I+m.f;b.t=B;d(A.t){b.h.t=A.t;b.h.m=m;b.h.1e=G;d(28 A.1e==\'29\')b.h.1e=A.1e;j t=b.h.t[m];d(t){j i=[m].16(t);j a=t.1g().16([\'\']);b.h.1t=m;b.t=a.1B(i)}}b.x(l.f+p[0].f+b.h.c,m.f)}z{b.h=B;b.x(l.f+p[0].f,p[1].f)}}z{b.x(l.f+p[0].f,p[1].f)}p=B}z{b.o([P,b.q,b.y(c,b.v.o.f)]);d(c==k)b.x(c+b.L,0);z b.x(c+b.L,k-c)}}},1N:r(e,c,k){d(b.h){j f=b.h.q.f;d(f){d(b.h.18<=c){j m=b.h.q.1O(),N=b.y(c,c+b.h.p[1].f-b.h.c).1k(m);d(f==1&&!m){j E=b.h.p[2].f;d($Q(b.h.l)==\'2c\')E=b.h.l;z d(b.h.l)E=0;b.x(k+b.J().1k(b.h.p[2])+E,0);b.t=B;u G}z d(N>-1){b.h.c=N;b.h.18=N+c;b.I=b.h.18;b.O=b.I+m.f;b.h.m=m;d(b.t){j t=b.h.t[m];d(t){j i=[m].16(t);j a=t.1g().16([\'\']);b.h.1t=m;b.t=a.1B(i)}z{b.t=B}}b.x(c+N,m.f);u G}z{b.1o(e);u G}}}b.h=B}u S},1J:r(e,c,k){d(b.t&&c==b.I&&k==b.O&&b.h.m.f==k-c){j m=b.t[b.h.m];d(m){b.O=b.I+m.f;b.h.m=m;b.o([b.H(),m,b.J()]);b.x(c,m.f);u G}z d(b.h.1e){m=b.h.1t;b.h.m=m;b.O=b.I+m.f;b.o([b.H(),m,b.J()]);b.x(c,m.f);u G}}b.t=B;u S}});',62,151,'|||||||||||this|ss|if||length||autoTab||var|se|start|item||value|snippet|tab|function||completion|return|element|sel|selectRange|slice|else|snippetObj|null|stpair|key|end|options|true|getStart|ssKey|getEnd|charCode|tabl|rest|next|seKey|text|type|focus|false|scope|height|join|smartTypingPairs|pair|preventDefault|styles|break||scrollTop|||command|extend|snippets|ssLast|selections|selection|test|keyCode|case|loop|line_height|copy|match|replace|close|indexOf|split|scrollLeft|46|onTab|fn|open|scopes|line|index|getStyle|toInt|lastIndexOf|array|lines|pop|onKeyPress|associate|gi|filterByNext|filterByPairs|filterBySelect|String|fromCharCode|shiftKey|filterCompletion|continue|filterByTab|el|filterAutoTab|shift|onEnter|updateScroll|onBackspace|string|onDelete|newsel|setOptions|for|Object|postEditor|apply|in|selectionStart|selectionEnd|Math|round|switch|ctrlKey||typeof|boolean|38|39|number|create|new|Class|initialize|window|ActiveXObject|font_size|font|size|onkeypress|bind|changeSnippets|changeSmartTypingPairs|changeSelections'.split('|'),0,{}));

var blackJack = {
	init: function() {
		//Tips
		new Tips($S('var'), {transitionStart:Fx.Transitions.sineIn,transitionEnd:Fx.Transitions.sineOut});
		
		$('wrapper').getElements('.deleteme').each(function(link) {
			link.onclick = function()	{
				blackJack.ajaxDelete(this.rel);
				return false;
			}
		});
		
		if($('wrapper').getElement('.editortaginsert')) {
			$('wrapper').getElement('.editortaginsert').setHTML('<input type="text" name="ajaxtag" id="ajaxtag" value="" /> <input type="button" name="ajaxtagsubmit" id="ajaxtagsubmit" class="inlinesubmit" value="<?php _e('Add'); ?>" />');
			$('ajaxtagsubmit').onclick = function() {
				blackJack.ajaxAdd('tags.php?req=ajaxadd&li=true',{name:$('ajaxtag').getValue()});
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
				snippet:['<a href="','http://" title="desc">text','</a>'],
				tab:['http://','desc','text','']
			},
			"img" : {
				snippet:['<img src="','http://" alt="alternate text"',' />'],
				tab:['http://','alternate text','']
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
		if(Cookie.get('<?php echo $bj_db->prefix; ?>te_height') != '') {
			height = Cookie.get('<?php echo $bj_db->prefix; ?>te_height');
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
			Cookie.set('<?php echo $bj_db->prefix; ?>te_height',newheight);
			return false;
		};
		
		blackJack.editbar.getElement('a.more').onclick = function(){
			var effect = $('textarea').effect('height',{duration: 375});
			var height = $('textarea').getStyle('height').toInt();
			var newheight = height + 60;
			effect.custom(height, newheight);
			Cookie.set('<?php echo $bj_db->prefix; ?>te_height',newheight);
			return false;
		};
		
		blackJack.editbar.getElement('a.moresep').onclick = function(){
			blackJack.insertItem(el, this.rel, "\n\n",'<?php echo run_filters('snippet_separator','__More__'); ?>');
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
		var j00sure = confirm(bits[2]);
		if(j00sure) {
			var delCall = new Ajax(bits[0],{onComplete:blackJack.deleteEffect});
			delCall.request();
			$('ajaxmessage').setHTML('<div id="loading"><?php _e('Loading'); ?></div>');
			var hideThis = new Fx.Opacity(bits[1],{duration:750});
			hideThis.custom(1, 0.2);
		}
	},

	deleteEffect: function(text) {
		var hideThis = new Fx.Opacity('ajaxmessage',{duration:100});
		hideThis.custom(1, 0);
		$('ajaxmessage').setHTML(text);
		var showThis = new Fx.Opacity('ajaxmessage',{duration:100});
		showThis.custom(0, 1);
	},
	
	//And its opposite, the Ajax adder.
	ajaxAdd: function(url,postdata) {
		new Ajax(url,{postBody:postdata,onComplete:blackJack.addEffect}).request();
	},

	addEffect: function(text) {
		/*
		 * Now, just a note:
		 * This requires, somewhere in the returned row,
		 * an element like <span id="latest_id" class="id_here"></span>,
		 * where id_here is the row ID you want to use.
		 */
		new Element('tr').injectAfter('headings').setHTML(text).setProperties({id:$('latest_id').getProperty('class')});
		//Fancy color effect.
		var myColorFx = new Fx.Color($('latest_id').getProperty('class'), 'background-color', {duration: 750});
		myColorFx.custom('<?php echo run_filters('addEffect_hlight','EAE559'); ?>', '<?php echo run_filters('addEffect_faded','EBEBEB'); ?>');
		//Delete our fix from the DOM.
		$('latest_id').remove();
	}
};

Window.onDomReady(blackJack.init);
