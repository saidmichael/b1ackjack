/*
	Script: Moo.js
	mootools.js - moo javascript tools.
	My Object Oriented javascript; has no dependancies.

	by Valerio Proietti (http://mad4milk.net) MIT-style license.

  Credits:
	- Class is slightly based on Base.js  <http://dean.edwards.name/weblog/2006/03/base/>
	(c) 2006 Dean Edwards, License <http://creativecommons.org/licenses/LGPL/2.1/>

	- Some functions are based on those found in prototype.js <http://prototype.conio.net/>
	(c) 2005 Sam Stephenson sam [at] conio [dot] net, MIT-style license
		
	- Documentation by Aaron Newton (aaron.newton [at] cnet [dot] com).

	Class: Class
	The base class object of the <http://mootools.net> framework.
	
	Parameters:
	properties - the collection of properties that apply to the class.	
	
	Example:
	(start code)
	var Widget = new Class({
		initialize: function(options){
			//do some stuff when this class is instantiated
			this.someFunction(); //call a function within this class
		},
		someFunction: function() {
			//some other stuff
		}
	});
	
	var myWidget = new Widget(options);
	(end)
	*/
var Class = function(properties){
	var klass = function(){
		for (var p in this) this[p]._proto_ = this;
		if (arguments[0] != 'noinit' && this.initialize) return this.initialize.apply(this, arguments);
	};
	klass.extend = this.extend;
	klass.implement = this.implement;
	klass.prototype = properties;
	return klass;
};

/*	Function: empty
		Executes an empty function. Typically used as a default action (i.e. no action) in the options of a class.
		
		Example:
		(start code)
		var Widget = new Class({
			initialize: function(options){
				this.options = Object.extend({
					onComplete: Class.empty //set the default action for onComplete to do nothing
				}, options || {});
				//do some stuff when this class is instantiated
				this.someFunction(); //call a function within this class
				//that's all, execute onComplete
				this.options.onComplete(); //either nothing or what was passed in.
			},
			someFunction: function() {
				//some other stuff
			}
		});
		
		var myWidget = new Widget({onComplete: someFunction});
		(end)
		
		Returns:
		nothing
	*/
Class.empty = function(){};

/*	Function: create 
		Synonymous with new Class; returns a new <Class> with the passed in properties.
		
		Parameters:
		properties - the collection of properties that apply to the class.
		
		Returns:
		a new <Class> */
Class.create = function(properties){
	return new Class(properties);
};

Class.prototype = {
/*	Function: extend
		Returns a new instance of the base class extended with the passed in properties.
		
		Parameters:
		properties - the properties to add to the base class in this new Class. The base class
			is unaffected.
		
		Examples:
		(start code)
		var myClass = new Class(myProperties);
		var myExtendedClass = myClass.extend(someMoreProperties);
		(end)

		myExtendedClass now has myProperties + someMoreProperties.
		
		(start code)
		var Widget = new Class({
			initialize: function(options){
				//do some stuff when this class is instantiated
				this.someFunction(); //call a function within this class
			},
			someFunction: function() {
				//some other stuff
			}
		});
		
		var betterWidget = Widget.extend({
			someFunction: function(){
				this.parent() //execute the functionality defined in Widget.someFunction
				//some additional stuff that I want to happen in instances of betterWidget
			}
		});
		(end)
		
		<Function.parentize> adds the function *this.parent()* which will execute the
		functionality defined in the Class from which the new one inherits (using <extend>),
		allowing you to add additional behavior.
		
		Returns:
		The extended Object
	*/
	extend: function(properties){
		var pr0t0typ3 = new this('noinit');
		for (var property in properties){
			var previous = pr0t0typ3[property];
			var current = properties[property];
			if (previous && previous != current) current = previous.parentize(current) || current;
			pr0t0typ3[property] = current;
		}
		return new Class(pr0t0typ3);
	},
/*	Function: implement
		Applies the passed in properties to the base class (altering the base class, unlike <extend>).	
		implement allows you to overwrite the functionality of the base class you are affecting. 

		
		Parameters:
		properties - the properties to add to the base class.
		
		Examples:
		>var myClass = new Class(myProperties);
		>myClass.implement(someMoreProperties);
		
		myClass now has myProperties + someMoreProperties.
		
		>var myClass = new Class(myProperties);
		>var myOtherClass = new Class(someOtherProperties);
		>var myOtherClass.implement(myClass)
		
		myOtherClass now has myProperties + someOtherProperties.
		
		(start code)
		var Widget = new Class({
			initialize: function(options){
				//do some stuff when this class is instantiated
				this.someFunction(); //call a function within this class
			},
			someFunction: function() {
				//some other stuff
			}
		});
		
		Widget.implement({
			someFunction: function(){
				//something different than what was originally written
			}
		});
		(end)
		
		Now Widget.someFunction is your new code, and all new Widgets will have
		that property. In this way, you can change the functionality of any Class.
		
		Note:
		You can't refer to *this.parent()* when you implement a function (because you are
		overwriting it, rather than extending it), but you /can/ use *this.parent()* if
		the element you are implementing extends another one. In that case, this.parent()
		will execute the parent of the element you are implementing.
		
		(start code)
		var Widget = new Class({
			someFunction: function() {
				//basic stuff
			}
		});
		
		var betterWidget = Widget.extend({
			someFunction: function() {
				this.parent() //execute the basic stuff
				//some extra stuff
			}
		});
		
		betterWidget.implement({
			someFunction: function() {
				this.parent() //execute the basic stuff
				//some different extra stuff
			}
		});
		(end)
		Returns:
		nothing
	*/
	implement: function(properties){
		for (var property in properties) this.prototype[property] = properties[property];
	}
};
/*	Class Object
		This is an extention to the Object prototype in javascript.
		
		Function: extend
		Extends an object to include the new arguments passed in; returns that new combined object
		without altering the one you are extending. Similar to <Class.extend>.
		
		Example:
		>var options = {color: 'red', height: '100'};
		>var moreOptions = options.extend({width: '50'});
		
		*moreOptions* is now {color: 'red', height: '100', width: '50'} but *options* remains the same.

		Returns:
		The extended Object
	*/
Object.extend = function(){
	var args = arguments;
	if (args[1]) args = [args[0], args[1]];
	else args = [this, args[0]];
	for (var property in args[1]) args[0][property] = args[1][property];
	return args[0];
};

/*	Function: Native
		Converts an object so that its <extend> function implements the options passed in to the
		prototype of that object. In other words, it makes <extend> and <implement> both do the 
		same thing (see <implement>).
		
		Example:
		>var options = {color: 'red', height: '100'};
		>Object.Native(options);
		>options.extend({width: '50'});
		>OR
		>options.implement({width: '50'});
		
		options is now {color: 'red', height: '100', width: '50'}
		
		A better example is something like the <String> object, which Mootools actually does turn
		into an Object Native by default. Any String.extend is equivalent to String.implement, so that any
		code you apply to a string with either method will have the same effect; all subsequently
		created Strings will have that functionality.
		
		Mootools creates Object Natives for <Function> (see: <Function.js>), <Array>, <String>, and <Number>.
		
		Returns:
		nothing
	*/
Object.Native = function(){
	for (var i = 0; i < arguments.length; i++) arguments[i].extend = Class.prototype.implement;
};

new Object.Native(Function, Array, String, Number);
/*	Class: Function
		The mootools extentions to the Function object in javascript.
	*/
Function.extend({
/*	Function: parentize
		Function.parentize looks if the method of our extended Class is a
		duplicate of one found in the parent Class, and if found will attach
		it as this.parent() inside the duplicated method.
		
		Parameters:
		current - the current function/object
		
		See also:
		<Class.extend> for an example.
		 	*/
	parentize: function(current){
		var previous = this;
		return function(){
			this.parent = previous;
			return current.apply(this, arguments);
		};
	}
});
/*	Script: Function.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>

	*/
/*	Class: Function
		Extends the Function object prototype of javascript.
	*/
Function.extend({
/*	Function: pass
		Returns a closure of given function with applied arguments to it.
		See also <bind> method.
		
		Parameters:
		args - the arguments to pass to that function (array or single variable)
		bind - The variable to be binded to function. If is not supplied pass 
					 will make a try to bind automatically if function is a method of a class
		
		Example:
		>myFunction.pass({key: 'value'}, myElement});
		
		This will execute myFunction with the argument "key" set to "value" and bind
		the "this" of myFunction to myElement.
		
		Returns:
		an instance of the function with the args and bind elements applied.
	*/
	pass: function(args, bind){
		var fn = this;
		if ($type(args) != 'array') args = [args];
		return function(){
			return fn.apply(bind || fn._proto_ || fn, args);
		};
	},
/*	Function: bind
		Returns a closure of given function with changed "this" to specified variable. 
		
		Parameters:
		bind - the object to bind to the "this" of the function.
		
		Example:
		(start code)
		function myFunction(){
			this.setStyle('border', '1px solid black');
			//note that 'this' here refers to myFunction, not an element
			//we'll need to bind this function to the element we want to
			//alter
		};
		var myBoundFunction = myFunction.bind(myElement);
		OR
		myFunction.bind(myElement)(); //executes the function
		(end)
		
		Returns:
		an instance of the function with the element bound to it.
	*/
	bind: function(bind){
		var fn = this;
		return function(){
			return fn.apply(bind, arguments);
		};
	},
/*	Function: bindAsEventListener
		The Function.bindAsEventListener function returns an instance of 
		the function pre-bound to the function(=method) owner object. The returned 
		function will have the current event object as its argument.
		
		Parameters:
		bind - the element to bind to the function
		
		Example:
		(start code)
		function myFunction(event){
			this.setStyle('border', '1px solid black');
			//note that 'this' here refers to myFunction, not an element
			//we'll need to bind this function to the element we want to
			//alter
		};

		myElement.onclick = myFunction.bindAsEventListener(myElement);
		(end)
		
		See also:
		<Function.bind>, <addEvent>
	*/
	bindAsEventListener: function(bind){
		var fn = this;
		return function(event){
			fn.call(bind, event || window.event);
			return false;
		};
	},
/*	Function: delay
		Delays the execution of a function for the duration passed in and
		optionally binds the passed in element to it.
		
		Parameters:
		ms - the duration to wait in milliseconds
		bind - the element to bind to the function
		
		Example:
		(start code)
		myFunction.delay(50, myElement) //wait 50 milliseconds, then call myFunction and bind myElement to it
		
		(function(){alert('one second later...')}).delay(1000); //wait a second and alert
		(end)
		
		Returns:
		setTimeout object
	*/
	delay: function(ms, bind){
		return setTimeout(this.bind(bind || this._proto_ || this), ms);
	},
/*	Function: periodical
		Executes a function repeatidly, waiting the duration between executions.
		
		Parameters:
		ms - the duration to wait between executions.
		bind - The variable to be binded to function. If is not supplied pass will 
					 make a try to bind automatically if function is a method of a class
		
		Returns:
		setInterval object
	*/
	periodical: function(ms, bind){
		return setInterval(this.bind(bind || this._proto_ || this), ms);
	}
});

/*	Function: $clear
		clears a timeout or an Interval.
		
		Parameters:
		timer - the Timeout or Interval to clear.
		
		Example:
		var myTimer = myFunction.delay(5000); //wait 5 seconds and execute my function.
		$clear(myTimer); //nevermind
		
		See also:
		<Function.delay>, <Function.periodical>
	*/
function $clear(timer){
	clearTimeout(timer);
	clearInterval(timer);
	return null;
};

/*	Function: $type
		Returns the type of object that matches the element passed in.
		
		Parameters:
		obj - the object to inspect.

		Example:
		>$type(myString)
		> 'string'
		
		Returns:
		'function' - if the object is a function
		'textnode' - if the object is a node but not an element
		'element' - if the object is a DOM element (i.e. nodeType == 1)
		'array' - if the object is an array
		'object' - if the object is an object
		'string' - if the object is a string
		'number' - if the object is a number
		false - (boolean) if the object is not defined or none of the above, or if it's an empty string.
	*/
function $type(obj){
	if (!obj) return false;
	var type = false;
	if (obj instanceof Function) type = 'function';
	else if (obj.nodeName){
		if (obj.nodeType == 3 && !/\S/.test(obj.nodeValue)) type = 'textnode';
		else if (obj.nodeType == 1) type = 'element';
	}
	else if (obj instanceof Array) type = 'array';
	else if (typeof obj == 'object') type = 'object';
	else if (typeof obj == 'string') type = 'string';
	else if (typeof obj == 'number' && isFinite(obj)) type = 'number';
	return type;
};

/*	Class: Chain
		Chains together functions and then executes them sequentially.
	*/

var Chain = new Class({
/*	Function: chain
		append a function to the <Chain>.
		
		Parameters:
		fn - the function to append.
		
		Returns:
		the instance of the <Chain> class.
		
		Example:
		>var myChain = new Chain();
		>myChain.chain(myfunction);
		>myChain.chain(myfunction2);
		>myChain.callChain(); //executes myfunction, then myfunction2
	*/
	chain: function(fn){
		this.chains = this.chains || [];
		this.chains.push(fn);
		return this;
	},
/*	Function: callChain
		Executes all the functions in the <Chain>.

		Example:
		>var myChain = new Chain();
		>myChain.chain(myfunction);
		>myChain.chain(myfunction2);
		>myChain.callChain(); //executes myfunction, then myfunction2
		
		Returns:
		nothing
	*/
	
	callChain: function(){
		if (this.chains && this.chains.length) this.chains.splice(0, 1)[0].delay(10, this);
	},

/*	Function: clearChain
		Empties a chain of all its functions;
	*/	
	clearChain: function(){
		this.chains = [];
	}

});
/*	Script: Array.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>

	*/
/*	Class: Array
		Extends the Array object prototype of javascript.
	*/
if (!Array.prototype.forEach){
/*	Function: forEach
		Iterates through an array; note: <Array.each> is the
		preferred syntax for this funciton.
		
		Parameters:
		fn - the function to execute with each item in the array
		bind - the object to bind to the "this" of the object (optional).
		
		Example:
		(start code)
		["1","2","3"].forEach(function(num) { num.parseInt()});
		 > 1
		 > 2
		 > 3
		(end)
		
		Returns:
		nothing
		
		See also:
		<Function.bind>
		<Array.each>
	*/
	Array.prototype.forEach = function(fn, bind){
		for(var i = 0; i < this.length ; i++) fn.call(bind, this[i], i);
	};
}

Array.extend({
/*	Function: each
		Synonymouse with <Array.each>; preferred syntax.

		Parameters:
		fn - the function to execute with each item in the array
		bind - the object to bind to the "this" of the object (optional).
		
		Example:
		(start code)
		["1","2","3"].each(function(num) { num.parseInt()});
		 > 1
		 > 2
		 > 3
		(end)
		
		Returns:
		nothing
	*/	
	each: Array.prototype.forEach,
/*	Function: copy
		Returns a new array identical to the one on which copy was called.
		
		Example:
		>["1","2","3"].copy()
		> > ["1","2","3"]

		Returns:
		a copy of the array
	*/
	copy: function(){
		var nArray = [];
		for (var i = 0; i < this.length; i++) nArray.push(this[i]);
		return nArray;
	},
/*	Function: remove
		Removes an item from the array.
		
		Parameters:
		item - the item to remove
		
		Example:
		>["1","2","3"].remove("2")
		> > ["1","3"]

		Returns:
		the array without the item removed.
	*/	
	remove: function(item){
		for (var i = 0; i < this.length; i++){
			if (this[i] == item) this.splice(i, 1);
		}
		return this;
	},
/*	Function: test
		Tests an array for the presence of an item.
		
		Parameters:
		item - the item to search for in the array.
		
		Returns:
		true - the item was found
		false - it wasn't
		
		Example:
		(start code)
		["1","2","3"].test("3")
		 > true
		["1","2","3"].test(3)
		 > false
		(end)
	*/
	test: function(item){
		for (var i = 0; i < this.length; i++){
			if (this[i] == item) return true;
		};
		return false;
	},
/*	Function: extend
		Appends an array onto the end of the applied Array.
		
		Parameters:
		nArray - the array (or object; string, function, whatever) to append.
		
		Example:
		(start code)
		["1","2","3"].extend(["4","5"]);
		 > ["1","2","3","4","5"]
		
		["1","2","3"].extend("4")
		 > ["1","2","3","4"]
		
		["1","2","3"].extend(myObject)
		 > ["1","2","3",myObject]
		(end)
	*/
	extend: function(nArray){
		for (var i = 0; i < nArray.length; i++) this.push(nArray[i]);
		return this;
	}
});
/*	Function: $A()
		Applies the Array prototype to the array passed in. Use this
		to instanciate an array from, for example, a string.
		
		Examples:
		(start code)
		$A("123")
		 > ["1","2","3"]
		
		$A(["1","2","3"]) //let's say that my ["1","2","3"] array doesn't have all the moo extentions
		 > ["1","2","3"] //now it does.
		(end)
	*/
function $A(array){
	return Array.prototype.copy.call(array);
};
/*	Script: String.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>
	*/
/*	Class: String
		Extends the String object prototype of javascript.
	*/

String.extend({
/*	Function: test
		Tests a string to see if it contains a value.
		
		Parameters:
		value - the value you want to test for
		params - optional; any parameters you want to pass to the RegExp
		
		Returns:
		an array with the instances of the value searched for or returns empty.
		
		Example:
		>"I like cookies".test("cookie")
		> > ["cookie"]
		>
		>"I like cookies".test("COOKIE", "i") //ignore case
		> > ["cookie"]
		>
		>"I like cookies because cookies are good".test("COOKIE", "ig") //ignore case, find all instances
		> > ["cookie", "cookie"]
		>
		>"I like cookies".test("cake")
		> > <empty return>

		
		Returns:
		array - an array of the matched items
		nothing - no items found
			*/
	test: function(value, params){
		return this.match(new RegExp(value, params));
	},
/*	Function: toInt
		parses a string to an integer.
		
		Returns:
		either an int or "NaN" if the string is not a number.
		
		Examples:
		>"10".parse()
		> > 10
		>"10.8".parse()
		> > 10
		>"10asdf".parse()
		> > 10
		>"asdf10".parse()
		> > NaN
		>"asdf".parse()
		> > NaN
		
		Returns:
		integer - the parsed integer
		"NaN" - not a number
	*/
	toInt: function(){
		return parseInt(this);
	},
/*	Function: camelCase
		Converts a hiphenated string to a camelcase string.
		
		Examples:
		>"I like cookies".camelCase()
		> > "I like cookies"
		>"I-like-cookies".camelCase()
		> > "ILikeCookies"
		>"i-like-cookies".camelCase()
		> >"iLikeCookies"
		
		Returns:
		the camel case string
		*/
	camelCase: function(){
		return this.replace(/-\D/gi, function(match){
			return match.charAt(match.length - 1).toUpperCase();
		});
	},
/*	Function: capitalize
		Converts a sentence to mixed case; upper case letters for the first letter in each word.
		
		Examples:
		>"i-like-cookies".capitalize()
		> > "I-Like-Cookies"
		>"i like cookies".capitalize()
		> > "I Like Cookies"
		
		Returns:
		the capitalized string
	*/
	capitalize: function(){
		return this.toLowerCase().replace(/\b[a-z]/g, function(match){
			return match.toUpperCase();
		});
	},
/*	Function: trim
		Trims the whitespace off a string.
		
		Examples:
		>" i like cookies ".trim() //nix leading and trailing spaces
		> > "i like cookies"
		>" i like cookies \n\n".trim() //nix leading and trailing spaces and line breaks
		> > "i like cookies"
		
		Returns:
		the trimmed string
			*/
	trim: function(){
		return this.replace(/^\s*|\s*$/g, '');
	},
/*	Function: clean
		Removes all the spaces at the end and beginning of a function AND all 
		the double spaces if finds in a string.
		
		Example:
		>" i      like     cookies      \n\n".trim() //nix leading and trailing spaces and line breaks
		> > "i like cookies"
		
		Returns:
		the cleaned string
	*/
	clean: function(){
		return this.replace(/\s\s/g, ' ').trim();
	},
/*	Function: rgbToHex
		Converts an RGB value to hexidecimal.
		
		Parameters:
		string - must be in the format of "rgb(##, ##, ##)" where ## are numbers between 0 and 255, each
						 for red, green, and blue.
		
		Example:
		>"rgb(17,34,51)".rgbToHex()
		> > "#112233"
		
		Returns:
		string - the hex value (with the hash) of the RGB values
	*/
	rgbToHex: function(array){
		var rgb = this.test('([\\d]{1,3})', 'g');
		if (rgb[3] == 0) return 'transparent';
		var hex = [];
		for (var i = 0; i < 3; i++){
			var bit = (rgb[i]-0).toString(16);
			hex.push(bit.length == 1 ? '0'+bit : bit);
		}
		var hexText = '#'+hex.join('');
		if (array) return hex;
		else return hexText;
	},
/*	Function: hexToRgb
		Converts a hexidecimal color value to RGB.
		
		Parameters:
		string - the hex value (with or without the hash) of the RGB values
		
		Example:
		>"#112233".hexToRgb()
		> > "rgb(17,34,51)"
		>"112233".hexToRgb()
		> > "rgb(17,34,51)"
		>"#123".hexToRgb()
		> > "rgb(17,34,51)"
		>"123".hexToRgb()
		> > "rgb(17,34,51)"

		Returns:
		string - always in the format of "rgb(##, ##, ##)" where ## are numbers between 0 and 255, each
						 for red, green, and blue.
	*/
	hexToRgb: function(array){
		var hex = this.test('^[#]{0,1}([\\w]{1,2})([\\w]{1,2})([\\w]{1,2})$');
		var rgb = [];
		for (var i = 1; i < hex.length; i++){
			if (hex[i].length == 1) hex[i] += hex[i];
			rgb.push(parseInt(hex[i], 16));
		}
		var rgbText = 'rgb('+rgb.join(',')+')';
		if (array) return rgb;
		else return rgbText;
	}

});
/*	Class: Number
		Extends the <Moo.js> Number class.
	*/
Number.extend({
/*	Function: toInt
		Returns this number; this is here so that toInt works on both Strings and
		Numbers.	*/
	toInt: function(){
		return this;
	}

});
/*	Script: Element.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>
	*/

/*  Class: Element
			Extends the javascript prototype (DOM) Element class.
  */
var Element = new Class({

/*	Section: Element Creation	*/

/*  Function: initialize
			Creates a new element of the type passed in.
			
			Parameters:
			el - the tag name for the element you wish to create.
			
			Example:
			>var div = new Element('div');		
  */

	initialize: function(el){
		if ($type(el) == 'string') el = document.createElement(el);
		return $(el);
	},

/*	Section: Element injectors	*/
	
/*  Function: inject
			Injects a DOM element relative to another one where specified.
			
			Parameters:
			el - the element relative to where you want the applied element injected.
			where - where you want the applied element injected relative to the el element.
			
			Example:
			(start code)
			var div = new Element('div');
			div.inject('myElement', 'before');
			
			result:
			<div/>
			<div id="myElement"/>
			(end)
			
			Results in the new div being inserted before $('myElement');
			
			Where:
			before - before the specified DOM element. Synonymous with <injectBefore>.
			after - after the specified DOM element. Synonymous with <injectAfter>.
			inside - inside the specified DOM element. Synonymous with <adopt> and <injectInside>.
  */

	inject: function(el, where){
		el = $(el) || new Element(el);
		switch(where){
			case "before": $(el.parentNode).insertBefore(this, el); break;
			case "after": {
					if (!el.getNext()) $(el.parentNode).appendChild(this);
					else $(el.parentNode).insertBefore(this, el.getNext());
			} break;
			case "inside": el.appendChild(this); break;
		}
		return this;
	},
/*  Function: injectBefore
			Injects a DOM element before the specified element.
			
			Parameteres:
			el - the element you want the applied element injected before.
			
			Example:
			(start code)
			var div = new Element('div');
			div.injectBefore('myElement');
			
			result:
			<div/>

			<div id="myElement"/>
			(end)
			
			Results in new div being inserted before $('myElement');
			
			See also:
			<inject>, <injectAfter>, <injectInside>, <adopt>
			  */
	injectBefore: function(el){
		return this.inject(el, 'before');
	},

/*  Function: injectAfter
			Injects a DOM element after the specified element.
			
			Parameteres:
			el - the element you want the applied element injected after.
			
			Example:
			(start code)
			var div = new Element('div');
			div.injectAfter('myElement');
			
			result:
			<div id="myElement"/>
			<div/>
			(end)

			Results in new div being inserted after $('myElement');
			
			See also:
			<inject>, <injectBefore>, <injectInside>, <adopt>

			  */
	injectAfter: function(el){
		return this.inject(el, 'after');
	},

/*  Function: injectInside
			Injects a DOM element inside the specified element.
			
			Parameteres:
			el - the element you want the applied element injected inside.
			
			Example:
			(start code)
			var div = new Element('div');
			div.injectInside('myElement');
			
			result:
			<div id="myElement">
				<div/>
			</div>
			(end)
			
			Results in new div being inserted inside $('myElement');
			
			See also:
			<inject>, <injectBefore>, <injectAfter>, <adopt>
			  */
	injectInside: function(el){
		return this.inject(el, 'inside');
	},

/*  Function: adopt
			Appends the applied element to the passed in element; creates a new 
			element if the passed in element is a tag name.
			
			Parameteres:
			el - the element you want the applied element injected before, or the
					tag name of the element you wish to create.
			
			Example:
			(start code)
			var div = new Element('div');
			div.adopt('myElement');
			
			result:
			<div id="myElement">
				<div/>

			</div>
			(end)
			
			OR
			
			var div = new Element('div'); //<div></div>
			var anchor = div.adopt('a'); //<div><a></a></div>
			
			Returns:
			the element
			
			See also:
			<inject>, <injectBefore>, <injectAfter>, <injectInside>
			  */
	adopt: function(el){
		this.appendChild($(el) || new Element(el));
		return this;
	},

/*	Section: Element removal, clonage, replacement, appendage	*/
	
/*  Function: remove
			Removes the DOM node.
			
			Example:
			>$('myElement').remove() //bye bye  */
	remove: function(){
		this.parentNode.removeChild(this);
	},
/*  Function: clone
			Creates a new dom node with the attributes and contents of the applied node.
			
			Parameters:
			contents - the contents to use instead of the node being cloned (optional;
								 otherwise clone the contents of the cloned node);
		
			Example:
			(start code)
			var newEl = $('myElement').clone('hi there');
			 //returns newEl with the contents 'hi there' instead 
			 //of the contents of $('myElement');
			(end)
			
			Returns:
			the cloned element
			   */
	clone: function(contents){
		return $(this.cloneNode(contents || true));
	},

/*  Function: replaceWith
			Replaces the applied node with the one passed in.
			
			Parameters:
			el - the node with which to replace the applied node. 
			
			Example:
			>$('myOldNode').replaceWith($('myNewNode'));
			
			$('myOldNode') is gone, and $('myNewNode') is in its place.
			
			Returns:
			the passed in node (in the example above, $('myNewNode'))
			 */
	replaceWith: function(el){
		var el = $(el) || new Element(el);
		this.parentNode.replaceChild(el, this);
		return el;
	},
/*  Function: appendText
		Appends text to a DOM element.
		
		Parameters:
		text - the text to append.
		
		Example:
		><div id="myElement">hey</div>

		>$('myElement').appendText(' howdy');
		
		myElement innerHTML is now "hey howdy"
		
		Returns:
		the element
  */
	appendText: function(text){
		if (this.getTag() == 'style' && window.ActiveXObject) this.styleSheet.cssText = text;
		else this.appendChild(document.createTextNode(text));
		return this;
	},

/*	Section: Element css class manipulation	*/	
	
/* Function: hasClass
	 Tests an element to see if it has the passed in className.
	 
	 Parameters:
	 className - the class name to test.
	 
	 Example:
	 >$('myElement').hasClass('testClass');
	 
	 Returns:
	 true - the element has the class
	 false - it doesn't
	 
	 See Also:
	 <hasClassName>
	  */

	hasClass: function(className){
		return !!this.className.test("\\b"+className+"\\b");
	},

/*	Function: addClass
		Adds the passed in class to the element.
		
		Parameters:
		className - the class to add
		
		Example:
		>$('myElement').addClass('myClass');
		
		Returns:
		the element

	 See Also:
	 <addClassName>
	*/
	addClass: function(className){
		if (!this.hasClass(className)) this.className = (this.className+' '+className.trim()).clean();
		return this;
	},
/*	Function: removeClass
		Removes the passed in className from the element.
		
		Parameters:
		className - the class to remove.
		
		Example:
		>$('myElement').removeClass('myClass');
		
		Returns:
		the element

	 See Also:
	 <removeClassName>
	*/
	removeClass: function(className){
		if (this.hasClass(className)) this.className = this.className.replace(className.trim(), '').clean();
		return this;
	},
/*	Function: toggleClass
		Adds or removes the passed in class name to the element, depending on if it's present or not.
		
		Parameters:
		className - the class to add or remove
		
		Example:
		><div id="myElement" class="myClass"></div>
		>$('myElement').toggleClass('myClass');
		><div id="myElement" class=""></div>

		>$('myElement').toggleClass('myClass');
		><div id="myElement" class="myClass"></div>

		Returns:
		the element toggled
		
	 See Also:
	 <toggleClassName>
	*/
	toggleClass: function(className){
		if (this.hasClass(className)) return this.removeClass(className);
		else return this.addClass(className);
	},

/*	Section: Element styles	*/
	
/*	Function: setStyle	
		Sets a css property.
		
		Parameters:
		property - the property to set
		value - the value to which to set it
		
		Example:
		>$('myElement').setStyle('width', '300px'); //the width is now 300px
		
		Returns:
		the element
*/
	setStyle: function(property, value){
		if (property == 'opacity') this.setOpacity(value);
		else this.style[property.camelCase()] = value;
		return this;
	},
/*	Function: setStyles
		Applies a collection of styles to an element.
		
		Parameters:
		source - an object or string containing all the styles to apply
		
		Examples:
		(start code)
		$('myElement').setStyles({
			border: '1px solid #000',
			width: '300px',
			height: '400px'
		});

		OR
		$('myElement').setStyle('border: 1px solid #000; width: 300px; height: 400px;');
		
		Returns:
		the element
	*/
	setStyles: function(source){
		if ($type(source) == 'object') {
			for (var property in source) this.setStyle(property, source[property]);
		} else if ($type(source) == 'string') {
			if (window.ActiveXObject) this.cssText = source;
			else this.setAttribute('style', source);
		}
		return this;
	},
/*	Function: setOpacity
		Sets the opacity of an element.
		
		Parameters:
		opacity - the opacity of the element: 0 = hidden, 1 = visible, .5 = 50% transparent.
		
		Example:
		>$('myElement').setOpacity(.5) //make it 50% transparent
		
		Returns:
		the element
	*/
	setOpacity: function(opacity){
		if (opacity == 0 && this.style.visibility != "hidden") this.style.visibility = "hidden";
		else if (this.style.visibility != "visible") this.style.visibility = "visible";
		if (window.ActiveXObject) this.style.filter = "alpha(opacity=" + opacity*100 + ")";
		this.style.opacity = opacity;
		return this;
	},
/*	Function: getStyle
		Returns the style given the property passed in.
		
		Parameters:
		property - the css style property you want to retrieve
		num - boolean; whether or not the style is a number. true will return the style as an int.
		
		Example:
		>$('myElement').getStyle('width', true); // returns an int of the width of the element
		>$('myElement').getStyle('border'); //returns something like "1px solid #000"
		
		Returns:
		the style as a string, an int (if you supply *num* as *true*), or an empty string (if there is no style applied).
	*/
	getStyle: function(property, num){
		var proPerty = property.camelCase();
		var style = this.style[proPerty] || false;
		if (!style) {
			if (document.defaultView) style = document.defaultView.getComputedStyle(this,null).getPropertyValue(property);
			else if (this.currentStyle) style = this.currentStyle[proPerty];
		}
		if (style && ['color', 'backgroundColor', 'borderColor'].test(proPerty) && style.test('rgb')) style = style.rgbToHex();
		if (num) return style.toInt();
		else return style;
	},
/*	Function: removeStyles
		Strips a DOM element of all its style properties.
		
		Example:
		>$('myElement').removeStyles() //myElement now has not styles applied

		Returns:
		the element
	*/
	removeStyles: function(){
		$A(arguments).each(function(property){
			this.style[property.camelCase()] = '';
		}, this);
		return this;
	},

/*	Section: Events	*/

/*	Function: addEvent
		Attaches an event listener to a DOM element.
		
		Parameters:
		action - the event to monitor ('click', 'load', etc)
		fn - the function to execute
		
		Example:
		>$('myElement').addEvent('click', function(){alert('clicked!')});
	
		Returns:
		the element
	*/

	addEvent: function(action, fn){
		this[action+fn] = fn.bind(this);
		if (this.addEventListener) this.addEventListener(action, fn, false);
		else this.attachEvent('on'+action, this[action+fn]);
		var el = this;
		if (this != window) Unload.functions.push(function(){
			el.removeEvent(action, fn);
			el[action+fn] = null;
		});
		return this;
	},
/*	Function: removeEvent
		Removes an event listener from an element.
		
		Parameters:
		action - the event monitored ('click', 'load', etc)
		fn - the function to be executed
		
		(start code)
		var fn = new function(){alert('hi there')});
		$('myElement').addEvent('click', fn);
		...
		$('myElement').removeEvent('click', fn);
		(end)
		
		Returns:
		the element
	*/
	removeEvent: function(action, fn){
		if (this.removeEventListener) this.removeEventListener(action, fn, false);
		else this.detachEvent('on'+action, this[action+fn]);
		return this;
	},

/*	Section: get non text elements	*/

/*	Function: getBrother
		Returns the DOM element next in the DOM, excluding text nodes.
		
		Parameters:
		what - either 'next' or 'previous' to get traverse forwards or 
					 backwards through the dom siblings.
		
		Example:
		>$('myElement').getBrother('next'); //get the next DOM element from myElement

		Returns:
		the sibling element
		
		See Also:
		<getPrevious>, <getNext>, <getFirst>, <getLast>

	*/
	getBrother: function(what){
		var el = this[what+'Sibling'];
		while ($type(el) == 'textnode') el = el[what+'Sibling'];
		return $(el);
	},
/*	Function: getPrevious
		Returns the DOM element previous in the DOM, excluding text nodes.
		
		Parameters:
		what - either 'next' or 'previous' to get traverse forwards or 
					 backwards through the dom siblings.
		
		Example:
		>$('myElement').getPrevious(); //get the previous DOM element from myElement
		
		Returns:
		the sibling element
		
		See Also:
		<getBrother>, <getNext>, <getFirst>, <getLast>
	*/
	getPrevious: function(){
		return this.getBrother('previous');
	},
/*	Function: getNext
		Returns the DOM element next in the DOM, excluding text nodes.
		
		Parameters:
		what - either 'next' or 'previous' to get traverse forwards or 
					 backwards through the dom siblings.
		
		Example:
		>$('myElement').getNext(); //get the next DOM element from myElement

		Returns:
		the sibling element
		
		See Also:
		<getBrother>, <getPrevious>, <getFirst>, <getLast>
	*/
	getNext: function(){
		return this.getBrother('next');
	},
/*	Function: getFirst
		Returns the first sibling DOM element relative to the applied element, excluding text nodes.
		
		Parameters:
		what - either 'next' or 'previous' to get traverse forwards or 
					 backwards through the dom siblings.
		
		Example:
		>$('myElement').getFirst(); //get the first sibling DOM element from myElement
		
		Returns:
		the sibling element
		
		See Also:
		<getBrother>, <getPrevious>, <getNext>, <getLast>

	*/
	getFirst: function(){
		var el = this.firstChild;
		while ($type(el) == 'textnode') el = el.nextSibling;
		return $(el);
	},

/*	Function: getLast
		Returns the last sibling DOM element relative to the applied element, excluding text nodes.
		
		Parameters:
		what - either 'next' or 'previous' to get traverse forwards or 
					 backwards through the dom siblings.
		
		Example:
		>$('myElement').getLast(); //get the last sibling DOM element from myElement
		
		Returns:
		the sibling element
		
		See Also:
		<getBrother>, <getPrevious>, <getNext>, <getLast>
	*/	
	getLast: function(){
		var el = this.lastChild;
		while ($type(el) == 'textnode')
		el = el.previousSibling;
		return $(el);
	},


/*	Section: Properties	*/

/*	Function: setProperty
		Sets a property for a dom element.
		
		Parameters:
		property - the property to assign the value passed in
		value - the value to assign to the property passed in
		
		Example:
		>$('myImage').setProperty('src', 'whatever.gif'); //myImage now points to whatever.gif for its source
		
		Returns:
		the element
		
		See Also:
		<setProperties>
	*/

	setProperty: function(property, value){
		var el = false;
		switch(property){
			case 'class': this.className = value; break;
			case 'style': this.setStyles(value); break;
			case 'name': if (window.ActiveXObject && this.getTag() == 'input'){
				el = $(document.createElement('<input name="'+value+'" />'));
				$A(this.attributes).each(function(attribute){
					if (attribute.name != 'name') el.setProperty(attribute.name, attribute.value);
					
				});
				if (this.parentNode) this.replaceWith(el);
			};
			default: this.setAttribute(property, value);
		}
		return el || this;
	},
/*	Function: setProperties
		Sets numerous properties for an element.
		
		Parameters:
		source - an object with key/value pairs for css properties.
		
		Example:
		(start code)
		$('myElement').setProperties({
			src: 'whatever.gif',
			alt: 'whatever dude'
		});
		> > <img src="whatever.gif" alt="whatever dude">
		
		Returns:
		the element
		
		See Also:
		<setProperty>

	*/
	setProperties: function(source){
		for (var property in source) this.setProperty(property, source[property]);
		return this;
	},
/*	Function: setHTML
		Sets the innerHTML of the element to the passed in html.
		
		Parameters:
		html - the new innerHTML for the element.
		
		Example:
		>$('myElement').setHTML(newHTML) //the innerHTML of myElement is now = newHTML
		
		Returns:
		the element	*/
	setHTML: function(html){
		this.innerHTML = html;
		return this;
	},
/*	Function: getProperty
		Gets the property (attribute) of an element.
		
		Parameters:
		property - the attribute to retrieve
		
		Example:
		>$('myImage').getProperty('src')
		
		Returns:
		the value, or an empty string
	*/
	getProperty: function(property){
		return this.getAttribute(property);
	},
/*	Function: getTag
		Returns the tagName of the element in lower case.
		
		Example:
		>$('myImage').getTag()
		> > 'img'
		
		Returns:
		The tag name in lower case	*/
	getTag: function(){
		return this.tagName.toLowerCase();
	},

/*	Section: Position	*/

/*	Function: getOffset
		Returns the offset of the top or left borders of the element; element must be visible.
		Element.offsetTop or Element.offsetLeft are the values from the very top of the document,
		including the scroll distance (not just the offset from the visible window).
		
		Parameters:
		what - either "top" or "left"; the border to get the offset top or left.
		
		Example:
		>$('myElement').getOffset('top') //get the offset top
		
		Returns:
		an int
		
		See Also:
		<getTop>, <getLeft>	*/
	getOffset: function(what){
		what = what.capitalize();
		var el = this;
		var offset = 0;
		do {
			offset += el['offset'+what] || 0;
			el = el.offsetParent;
		} while (el);
		return offset;
	},

/*	Function: getTop
		Returns the offset top of an element. See <getOffset> for details.
	*/
	getTop: function(){
		return this.getOffset('top');
	},
/*	Function: getLeft
		Returns the offset left of an element. See <getOffset> for details.
	*/
	getLeft: function(){
		return this.getOffset('left');
	}

});
/*	Note: Element is an Object Native	*/
new Object.Native(Element);
Element.extend({
/*	Function: hasClassName
		Synonymous with <hasClass>; deprecated.	*/
	hasClassName: Element.prototype.hasClass,
/*	Function: addClassName
		Synonymous with <addClass>; deprecated.	*/
	addClassName: Element.prototype.addClass,
/*	Function: removeClassName
		Synonymous with <removeClass>; deprecated.	*/
	removeClassName: Element.prototype.removeClass,
/*	Function: toggleClassName
		Synonymous with <toggleClass>; deprecated.	*/
	toggleClassName: Element.prototype.toggleClass
});

/*	Function: $Element
		Applies a method with the passed in args to the passed in element.
		
		Parameters:
		el - the element
		method - the method to execute on that element
		args - the arguments to pass to that method
		
		Example:
		>$Element(el, 'hasClass', className) //true or false if the element has the class name
			*/
function $Element(el, method, args){
	if ($type(args) != 'array') args = [args];
	return Element.prototype[method].apply(el, args);
};
/*	Function: $()
		Returns an Element either selected by getElementById or, if the passed in parameter is
		already an element, it returns an Element with all the mootools extentions applied.
		
		Examples:
		>$('myElement') // gets a DOM element by id with all the mootools extentions applied.
		>var div = document.getElementById('myElement');
		>$(div) //returns an Element also with all the mootools extentions applied.
		
		You'll use this when you aren't sure if a variable is an actual element or an id, as
		well as just shorthand for document.getElementById().
		
		Returns:
		a DOM element or false (if one was not found)
	*/
function $(el){
	if ($type(el) == 'string') el = document.getElementById(el);
	if ($type(el) == 'element'){
		if (!el.extend){
			Unload.elements.push(el);
			el.extend = Object.extend;
			el.extend(Element.prototype);
		}
		return el;
	} else return false;
};

window.addEvent = document.addEvent = Element.prototype.addEvent;
window.removeEvent = document.removeEvent = Element.prototype.removeEvent;

/*	Class: Unload
		Garbage collector for mootools	*/
var Unload = {

	elements: [], functions: [], vars: [],
/*	Function: unload
		Collects all the garbage on the page; helps avoid memory leaks.
	*/
	unload: function(){
		Unload.functions.each(function(fn){
			fn();
		});
		
		window.removeEvent('unload', window.removeFunction);
		
		Unload.elements.each(function(el){
			for(var p in Element.prototype){
				window[p] = null;
				document[p] = null;
				el[p] = null;
			}
			el.extend = null;
		});
	}
	
};
window.removeFunction = Unload.unload;
window.addEvent('unload', window.removeFunction);
/*	Script: Fx.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>

		Class: Fx
		Fx class for <http://mootools.net> framework.
	*/
var Fx = fx = {};

/*	Class: Fx.Base
		Base class for the Mootools fx library.	*/
Fx.Base = new Class({
/*	Function: setOptions
		Applies the options for the fx object. Internal function to the Fx class. 
		
		Parameters:
		onStart - the function to execute as the fx begins; nothing (<Class.empty>) by default.
		onComplete - the function to execute after the fx has processed; nothing (<Class.empty>) by default.
		transition - the equation to use for the effect see <Fx Transitions>; default is <Fx.sinoidal>
		duration - the duration of the effect in ms; 500 is the default.
		unit - the unit to apply to the effect value (the css suffix); 'px' by default (other values include
					 things like 'em' for fonts or '%').
		wait - boolean; I'm not sure what this is for !!Fix!!
		fps - the frames per second for the transition (how smooth it is); default is 30
		
		Returns:
		nothing
	*/
	setOptions: function(options){
		this.options = Object.extend({
			onStart: Class.empty,
			onComplete: Class.empty,
			transition: Fx.Transitions.sineInOut,
			duration: 500,
			unit: 'px',
			wait: true,
			fps: 50
		}, options || {});
	},
/*	Function: step
		Iterates to the next position for an effect. Internal function to the Fx class.
		
		Returns:
		nothing
	*/
	step: function(){
		var time = new Date().getTime();
		if (time < this.time + this.options.duration){
			this.cTime = time - this.time;
			this.setNow();
		} else {
			this.options.onComplete.pass(this.element, this).delay(10);
			this.clearTimer();
			this.callChain();
			this.now = this.to;
		}
		this.increase();
	},
/*	Function: set
		Immediately sets the effect value with no transition.
		
		Parameters:
		to - the point to jump to
		
		Example:
		- an opacity effect executed as .set(0) will make it immediately transparent
		- an opacity effect executed as .set(.5) will make it immediately 50% opaque
		- a height effect executed as .set(100) will make it immediately 100px 
		
		Returns:
		the fx object
	*/
	set: function(to){
		this.now = to;
		this.increase();
		return this;
	},
/*	Function: setNow
		Assigns the "now" value of the effect to a specific value based on the point in time between
		the beginning of the effect and the assigned end of it (beginning + duration) applied to the
		transition equation. Internal function to the Fx class.
		
		See also:
		<compute>
		
		Returns:
		nothing
	*/
	setNow: function(){
		this.now = this.compute(this.from, this.to);
	},
/*	Function: compute
		Returns the numerical value of the effect's transition state based on the point in time from
		the beginning and the assigned end of the effect (beginning + duration) applied to the transition
		equation. Internal function to the Fx class.
		
		Parameters:
		from - the beginning state of the effect
		to - the end state

		Example:
		If the effect is fading from 0 to 1 (tranparent to opaque) and the duration is 2 seconds, setNow
		will compute the midpoint of the opacity applied to the transition, say, linear, as .5.

		Returns:
		the numerical value (usu. a float?)
	*/
	compute: function(from, to){
		return this.options.transition(this.cTime, from, (to - from), this.options.duration);
	},
/*	Function: custom
		Executes an effect from one position to the other.
		
		Parameters:
		from - the position to start the effect
		to - the end position
		
		Examples:
		- an opacity effect executed as .custom(0,1) will fade it from transparent to opaque
		- an opacity effect executed as .custom(0,.5) will fade it from transparent to 50% opaque
		- a height effect executed as .custom(0, 100) will scale the element from 0px high to 100px
		
		Returns:
		the fx object
	*/
	custom: function(from, to){
		if (!this.options.wait) this.clearTimer();
		if (this.timer) return;
		this.options.onStart.pass(this.element, this).delay(10);
		this.from = from;
		this.to = to;
		this.time = new Date().getTime();
		this.timer = this.step.periodical(Math.round(1000/this.options.fps), this);
		return this;
	},
/*	Function: clearTimer
		Clears the timer of the effect (so it stops processing the transition).
		Internal function to the Fx class.
		
		Returns:
		the timer (cleared).
	*/
	clearTimer: function(){
		this.timer = $clear(this.timer);
		return this;
	},
/*	Function: setStyle
		Sets a style to an element. Internal function to the Fx class.
		
		Parameters:
		el - the element to alter
		property - the css property to change
		value - the value to assign
		
		Returns:
		nothing
		
		See also:
		<Element.setStyle>
	*/
	setStyle: function(element, property, value){
		element.setStyle(property, value + this.options.unit);
	}

});
/*  Note: Fx.Base implements Chain  */
Fx.Base.implement(new Chain);
/*	Class: Fx.Style
		The style effect; extends <Fx.Base>. Used to transition a css property from one (numerical) value to another.
		
		Example:
		>var marginChange = new fx.Style('myElement', 'margin-top', {duration:500});
		>marginChange.custom(10, 100);

		See also:
		<Fx.Base>, <Fx.Style.initialize>

	*/
Fx.Style = Fx.Base.extend({
/*	Function: initialize
		creates a new <Fx.Style> object with the values passed in.
		
		Parameters:
		el - the element to apply the style transition to
		property - the property to transition
		options - the fx options (see: <Fx.Base.setOptions>)

		Returns:
		nothing	*/
	initialize: function(el, property, options){
		this.element = $(el);
		this.setOptions(options);
		this.property = property.camelCase();
	},
/*	Function: hide
		Sets the css property for this style to zero.
		
		Returns:
		the fx object
	*/
	hide: function(){
		return this.set(0);
	},
/*	Function: goTo
		Sets the css property for this style to the passed in value.
		
		Parameters:
		val - the value to assign to the css property
		
		Returns:
		the fx object
	*/
	goTo: function(val){
		return this.custom(this.now || 0, val);
	},
/*	Function: increase	
		Increments the effect. Internal function to the Fx class.

		Returns:
		nothing
*/
	increase: function(){
		this.setStyle(this.element, this.property, this.now);
	}
});

/*	Class: Fx.Styles
		Allows you to animate multiple properties at once. You don’t specify the properties you 
		intend to modify at first – when you call the custom method you just pass it the properties 
		as an object; extends <Fx.Base>.
		
		Example:
		
		>var myEffects = new fx.Styles('myElement', {duration: 1000, transition: fx.linear});
		>myEffects.custom({'height': [10, 100], 'width': [900, 300]});
		
		See also:
		<Fx.Base>, <Fx.Styles.initialize>
	*/

Fx.Styles = Fx.Base.extend({
/*	Function: initialize
		Creates a new Fx.Styles object with the values passed in.
		
		Parameters:
		el - the element for the effect(s)
		options - the fx options (see: <Fx.Base.setOptions>)
		
		Returns:
		nothing
	*/
	initialize: function(el, options){
		this.element = $(el);
		this.setOptions(options);
		this.now = {};
	},
/*	Function: setNow
		Assigns the "now" value of the effect to a specific value based on the point in time between
		the beginning of the effect and the assigned end of it (beginning + duration) applied to the
		transition equation. Internal function to the Fx.Styles class.
		
		See also:
		<Fx.Base.compute>
		
		Returns:
		nothing
	*/
	setNow: function(){
		for (var p in this.from) this.now[p] = this.compute(this.from[p], this.to[p]);
	},
/*	Function:	custom
		The function you'll actually use to execute a transition; alters a css property (or numerous properties)
		from the start state to the end state for the duration that this Fx.Styles object was configured with.
		
		Example:
		>myEffects.custom({'height': [10, 100], 'width': [900, 300]});
		
		This will transition the height of the element that this Fx.Styles applies to (see <Fx.Styles.initialize>)
		from 10px to 100px, and the width from 900px to 300px, for the duration that was passed in when
		the Fx.Styles was initialized.
		
		Returns:
		the fx object
		
		See Also:
		<Fx.Styles.initialize>, <Fx.Base.setOptions>

	*/
	custom: function(objFromTo){
		if (this.timer && this.options.wait) return;
		var from = {};
		var to = {};
		for (var p in objFromTo){
			from[p] = objFromTo[p][0];
			to[p] = objFromTo[p][1];
		}
		return this.parent(from, to);
	},
/*	Function: increase	
		Increments the effect. Internal function to the Fx.Styles class.
		
		Returns:
		nothing
*/
	increase: function(){
		for (var p in this.now) this.setStyle(this.element, p, this.now[p]);
	}

});

/*	Class: Element
		Extends the Element object for effects.
	*/
Element.extend({
/*	Function: effect
		Applies an <Fx.Style> to an element with the css property and options specified; this a shortcut 
		for <Fx.Style>.
		
		Parameters:
		property - the css property to alter
		options - the fx options (see: <Fx.Base.setOptions>)
		
		Example:
		>var myEffect = $('myElement').effect('height', {duration: 1000, transition: fx.linear});
		>myEffect.custom(10, 100);
		
		Returns:
		new <Fx.Style>
		
		See Also:
		<Fx.Style>
	*/
	effect: function(property, options){
		return new Fx.Style(this, property, options);
	},
/*	Function: effects
		Applies an <Fx.Styles> to an element with the options specified; this is a shortcut for
		<Fx.Styles>.
		
		Parameters:
		options - the fx options (see: <Fx.Base.setOptions>)
		
		Example:
		>var myEffects = new fx.Styles('myElement', {duration: 1000, transition: fx.linear});
 		>myEffects.custom({'height': [10, 100], 'width': [900, 300]});
		
		Returns:
		new <Fx.Styles>

		
		See Also:
		<Fx.Styles>
		*/
	effects: function(options){
		return new Fx.Styles(this, options);
	}

});
/*	Class: Fx.Transitions
		A collection of transition equations for use with the <Fx> Class.
		
		See Also:
		<Fxtransitions.js> for a whole bunch of transitions.
		
		Credits:
		Easing Equations, (c) 2003 Robert Penner (http://www.robertpenner.com/easing/), Open Source BSD License.
	*/
Fx.Transitions = {
/*	Function: linear
		Linear transition for Mootools <Fx.Base>.
	*/
	linear: function(t, b, c, d){
		return c*t/d + b;
	},
/*	Function: sineInOut
		sineInOut transition for Mootools <Fx.Base>.
	*/
	sineInOut: function(t, b, c, d){
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	}
};
/*	Script: Dom.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>

	  Function: $S()
			Selects DOM elements based on css selector(s).
			
			Parameters:
			selectors - any number of css selectors
			
			Example:
			(start code)
			$S('a') //an array of all anchor tags on the page
			$S('a', 'b') //an array of all anchor and bold tags on the page
			$S('#myElement') //document.getElementById('myElement'); $('myElement')
			$S('#myElement a.myClass') //an array of all anchor tags with the class 
			                           //"myClass" within the DOM element with id "myElement"
			(end)

		Returns:
		array - array of all the dom elements matched		
  */
function $S(){
	var els = [];
	$A(arguments).each(function(sel){
		if ($type(sel) == 'string') els.extend(document.getElementsBySelector(sel));
		else if ($type(sel) == 'element') els.push($(sel));
	});
	return $Elements(els);
};
/*  Function: $$
			Synonymous with <$S()>
  */
var $$ = $S;

/*  Function: $E 
			Selects a single (i.e. the first found) Element based on the selector passed in and an optional filter element.
			
			Parameters:
			selector - the css selector to match
			filter - optional; a DOM element to limit the scope of the selector match; defaults to document.
			
			Example:
			>$E('a', 'myElement') //find the first anchor tag inside the DOM element with id 'myElement'
			
			Returns:
			a DOM element - the first element that matches the selector
 */
function $E(selector, filter){
	return ($(filter) || document).getElement(selector);
};

/*	Function: $ES
		Returns a collection of Elements that match the selector passed in limited to the scope of the optional filter.
		See Also: <Element.getElements> for an alternate syntax.
				
		Parameters:
		selector - css selector to match
		filter - optional, will limit the css selector match to the filter element
		
		Examples:
		>$ES("a") //gets all the anchor tags; synonymous with $S("a")
		>$ES('a','myElement') //get all the anchor tags within $('myElement')
		
		Retunrs:
		array - an array of dom elements that match the selector within the filter
	*/
function $ES(selector, filter){
	return ($(filter) || document).getElementsBySelector(selector);
};

/*  Function: $Elements
			Extends the passed in elements collection with the methods of the  
			Elements class. This is a private method only useful inside Dom.js.
			
			Parameters:
			elements - the elements to apply the Elements Class to.
			
			Returns:
			<Elements>
  */
function $Elements(elements){
	return Object.extend(elements, new Elements);
};

/*  Class: Element
			Extends the javascript Element prototype.
  */

Element.extend({
/*  Function: getElements 
			Gets all the elements within an element that match the given (single) selector.
			
			Parameters:
			selector - the css selector to match
			
			Example:
			>$('myElement').getElements('a') // get all anchors within myElement
			
			Returns:
			<Elements>
			
			See Also:
			<getElementsBySelector>
 */
	getElements: function(selector){
		var filters = [];
		selector.clean().split(' ').each(function(sel, i){
			var bits = [];
			var param = [];
			var attr = [];
			if (bits = sel.test('^([\\w]*)')) param['tag'] = bits[1] || '*';
			if (bits = sel.test('([.#]{1})([\\w-]*)$')){
				if (bits[1] == '.') param['class'] = bits[2];
				else param['id'] = bits[2];
			}
			if (bits = sel.test('\\[["\'\\s]{0,1}([\\w-]*)["\'\\s]{0,1}([\\W]{0,1}=){0,2}["\'\\s]{0,1}([\\w-]*)["\'\\s]{0,1}\\]$')){
				attr['name'] = bits[1];
				attr['operator'] = bits[2];
				attr['value'] = bits[3];
			}
			if (i == 0){
				if (param['id']){
					var el = this.getElementById(param['id']);
					if (el && (param['tag'] == '*' || $(el).getTag() == param['tag'])) filters = [el];
					else return false;
				} else {
					filters = $A(this.getElementsByTagName(param['tag']));
				}
			} else {
				filters = $Elements(filters).filterByTagName(param['tag']);
				if (param['id']) filters = $Elements(filters).filterById(param['id']);
			}
			if (param['class']) filters = $Elements(filters).filterByClassName(param['class']);
			if (attr['name']) filters = $Elements(filters).filterByAttribute(attr['name'], attr['value'], attr['operator']);
		
		}, this);
		filters.each(function(el){
			$(el);
		});
		return $Elements(filters);
	},
/*  Function: getElement
			Returns the first element within the applied element; optional selector can be passed in as a filter.
			
			Parameters:
			selector - returns first element to match the selector.
			
			Example:
			>$('myElement').getElement('a') //get the first anchor tag inside myElement.
			
			Returns:
			a DOM element - the first element that matches the selector within the filter
			
			See Also:
			$E()
  */
	getElement: function(selector){
		return this.getElementsBySelector(selector)[0];
	},
/*  Function: getElementsBySelector
			Returns the elements inside the applied element that match the selector(s) applied.
			
			Parameters:
			selector - any number of css selectors, seperated by commas.	
			
			Example:
			>$('myElement').getElementsBySelector('a, b, u'); //returns all anchor, bold, and underline elements in myElement.
			
			Returns:
			array - returns all the elements that match the selector within the applied Element.

			See Also:
			<getElements>

  */
	getElementsBySelector: function(selector){
		var els = [];
		selector.split(',').each(function(sel){
			els.extend(this.getElements(sel));
		}, this);
		return $Elements(els);
	}

});
document.extend = Object.extend;
/*  Class: document 
			Extends the document object of the DOM.
 */

document.extend({
/*  Function: getElementsByClassName 
			Returns all the elements that match a specific class name.
 */
	getElementsByClassName: function(className){
		return document.getElements('.'+className);
	},
	getElement: Element.prototype.getElement,
	getElements: Element.prototype.getElements,
	getElementsBySelector: Element.prototype.getElementsBySelector
	
});

/*  Class: Elements
			A collection (array) of DOM elements.
  */
var Elements = new Class({
/*  Function: action
			Applies the supplied actions collection to each Element in the collection.
			
			Parameters:
			actions - an Object with key/value pairs for the actions to apply.
			
			Actions:
			initialize - apply this functionality to the element immediately.
			event - add event function to the element
			
			Example:
			(start code)
			$S('a').action({
				initialize: function() {
					this.addClassName("anchor");
				},
				click: function(){
					alert('clicked!');
				},
				mouseover: function(){
					alert('mouseover!');
				}
			});
			(end)
			
			REturns:
			nothing
  */
	action: function(actions){
		this.each(function(el){
			el = $(el);
			if (actions.initialize) actions.initialize.apply(el);
			for(var action in actions){
				var evt = false;
				if (action.test('^on[\\w]{1,}')) el[action] = actions[action];
				else if (evt = action.test('([\\w-]{1,})event$')) el.addEvent(evt[1], actions[action]);
			}
		});
	},
/*  Function: filterById
			Return an array of Elements that match the id passed in.
			
			Parameters:
			id - the id to use for the filter.
			
			Example:
			>$S('a', 'b', 'u').filterById('needle');
			Returns any anchor, bold, or underline tags with id=needle.
			
			Returns:
			array - every matched DOM element.
  */
	filterById: function(id){
		var found = [];
		this.each(function(el){
			if (el.id == id) found.push(el);
		});
		return found;
	},
/*  Function: filterByClassName
			Return an array of Elements that have the class name passed in.
			
			Parameters:
			className - the className to use for the filter.
			
			Example:
			>$S('a', 'b', 'u').filterByClassName('needles');
			Returns any anchor, bold, or underline tags with the className "needles".
			
			Returns:
			array - every matched DOM element.
  */
	filterByClassName: function(className){
		var found = [];
		this.each(function(el){
			if ($Element(el, 'hasClass', className)) found.push(el);
		});
		return found;
	},
/*  Function: filterByTagName
			Return an array of Elements that have the tag name passed in.
			
			Parameters:
			tagName - the tagName to use for the filter.
			
			Example:
			>$S('.haystack').filterByTagName('a');
			Returns any anchor tag with the className "haystack".
			
			Returns:
			array - every matched DOM element.
  */
	filterByTagName: function(tagName){
		var found = [];
		this.each(function(el){
			found.extend($A(el.getElementsByTagName(tagName)));
		});
		return found;
	},
/*  Function: filterByAttribute
			Returns an array of DOM elements filtered on attributes that match those passed in.
			
			Parameters:
			name - the attribute name.
			value - the value to match.
			operator - any of the following:
								 - *= : test if your value is contained in the string
								 - = : tests if your value matches the string
								 - ^= : tests if your value is at the begging of the string
								 - $= : tests if your value is at the end of the string
			
			Returns:
			array - every matched DOM element.
			  */
	filterByAttribute: function(name, value, operator){
		var found = [];
		this.each(function(el){
			var att = el.getAttribute(name);
			if(!att) return;
			if (!operator) return found.push(el);
			
			switch(operator){
				case '*=': if (att.test(value)) found.push(el); break;
				case '=': if (att == value) found.push(el); break;
				case '^=': if (att.test('^'+value)) found.push(el); break;
				case '$=': if (att.test(value+'$')) found.push(el);
			}

		});
		return found;
	}

});
/*  Note: Elements is an <Object.Native>  */
new Object.Native(Elements);
/*	Script: Ajax.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>

		Class: Ajax
		Ajax class for <http://mootools.net> framework.
		
	*/
var Ajax = ajax = new Class({
/* Function: setOptions
			Applies the options passed into the instance of the Ajax object. Internal function to the Ajax class. 
			
			Parameters:
			options - object containing the key/value pairs for the instance.
			
			See Also:
			<Ajax.initialize> for option list
  */
	setOptions: function(options){
		this.options = {
			method: 'post',
			postBody: null,
			async: true,
			onComplete: Class.empty,
			onStateChange: Class.empty,
			update: null,
			evalScripts: false
		};
		Object.extend(this.options, options || {});
	},
/*  Function: initialize
			Initializes the Ajax object (called upon instantiation).
			
			Options:
			method - 'post' or 'get' - the prototcol for the request; optional.
								defaults to 'post'
			postBody - if you choose post as method, you can write parameters here.
							 - You can pass in a *query string*, an *element*, or an *object*.
							 - If you pass in an *element*, all its children will be inspected for
							 - form inputs and their values converted into a query string.
							 - If you pass in an *object*, it will be converted to a query string.
			async - boolean: asynchronous option; true uses asynchronous requests (the default).
							Synchronous calls are dangerous; be careful.
			onComplete - function to execute when the ajax completes.
			onStateChange - function to execute when the state of the XMLHttpRequest changes.
			update - DOM element to insert the response text of the XHR into upon completion of the request.
			evalScripts - boolean; default is false. Execute scripts in the response text when 
								the response is complete.

			Example:
			>var myAjax = new Ajax(url, {method: 'get'});								
  */
	initialize: function(url, options){
		this.setOptions(options);
		this.url = url;
		this.transport = this.getTransport();
	},
/*  Function: request
			Executes the ajax request.
			
			Example:
			(start code)
			var myAjax = new Ajax(url, {method: 'get'});
			myAjax.request();
			
			OR
			
			new Ajax(url, {method: 'get'}).request();
			(end)
  */
	request: function(){
		this.transport.open(this.options.method, this.url, this.options.async);
		this.transport.onreadystatechange = this.onStateChange.bind(this);
		if (this.options.method == 'post'){
			this.transport.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			if (this.transport.overrideMimeType) this.transport.setRequestHeader('Connection', 'close');
		}
		switch($type(this.options.postBody)){
			case 'element': this.options.postBody = $(this.options.postBody).toQueryString(); break;
			case 'object': this.options.postBody = Object.toQueryString(this.options.postBody);
		}
		if($type(this.options.postBody) == 'string') this.transport.send(this.options.postBody);
		else this.transport.send(null);
		return this;
	},
/*  Function: onStateChange
			Executes functions when the Ajax object's state changes.  Internal function to the Ajax class. 
  */
	onStateChange: function(){
		this.options.onStateChange.bind(this).delay(10);
		if (this.transport.readyState == 4 && this.transport.status == 200){
			if (this.options.update) $(this.options.update).setHTML(this.transport.responseText);
			this.options.onComplete.pass([this.transport.responseText, this.transport.responseXML], this).delay(20);
			if (this.options.evalScripts) this.evalScripts.delay(30, this);
			this.transport.onreadystatechange = Class.empty;
			this.callChain();
		}
	},
/*  Function: evalScripts
			Executes scripts in the XHR response text on completion of the request. Internal function to the Ajax class.
  */
	evalScripts: function(){
		if(scripts = this.transport.responseText.match(/<script[^>]*?>[\S\s]*?<\/script>/g)){
			scripts.each(function(script){
				eval(script.replace(/^<script[^>]*?>/, '').replace(/<\/script>$/, ''));
			});
		}
	},
/*  Function: getTransport
			Creates an instance of the XMLHttpRequest object. Internal function to the Ajax class. 
			
			Returns:
			XMLHttpRequest object.
			 */
	getTransport: function(){
		if (window.XMLHttpRequest) return new XMLHttpRequest();
		else if (window.ActiveXObject) return new ActiveXObject('Microsoft.XMLHTTP');
	}

});

/*  Note: Ajax implements Chain  */
Ajax.implement(new Chain);
/*  Class: Object
			Extends the javascript Object prototype.
			
			Function: toQueryString
			Converts all the values in an object to a query string.
			
			Parameters:
			source - the source object to convert.
			
			Returns:
			the query string.
			
			Example:
			>Object.toQueryString({apple: "red", lemon: "yellow"});
			> > "apple=red&lemon=yellow"
  */
Object.toQueryString = function(source){
	var queryString = [];
	for (var property in source) queryString.push(encodeURIComponent(property)+'='+encodeURIComponent(source[property]));
	return queryString.join('&');
};

/*  Class: Element
			Extends the javascript Element prototype.  */
Element.extend({
/*  Function: send
			Sends a form and it's input values via ajax.
			
			Parameters:
			options - option collection for ajax request. See <Ajax.initialize> for option list.
			
			Example:
			(start code)
			<form id="myForm" action="formHandler.jsp">
			<input name="email" value="bob@bob.com">
			<input name="zipCode" value="90210">
			</form>

			
			<script>
			 $('myForm').send()
			</script>
			(end)
			
			This will execute an ajax request to the following url:
			formHandler.jsp?method=post&email=bob@bob.com&zipCode=90210
  */
	send: function(options){
		options = Object.extend(options, {postBody: this.toQueryString(), method: 'post'});
		return new Ajax(this.getProperty('action'), options).request();
	},
/*  Function: toQueryString
			Converts the inputs that are an Element's children to a query string.
			
			Example:
			(start code)
			<form id="myForm" action="formHandler.jsp">
			<input name="email" value="bob@bob.com">
			<input name="zipCode" value="90210">
			</form>
			
			<script>
			 $('myForm').toQueryString()
			</script>

			(end)
			
			Returns: email=bob@bob.com&zipCode=90210
  */
	toQueryString: function(){
		var queryString = [];
		$A(this.getElementsByTagName('*')).each(function(el){
			$(el);
			var name = el.name || false;
			if (!name) return;
			var value = false;
			switch(el.getTag()){
				case 'select': value = el.getElementsByTagName('option')[el.selectedIndex].value; break;
				case 'input': if ( (el.checked && ['checkbox', 'radio'].test(el.type)) || (['hidden', 'text', 'password'].test(el.type)) ) 
					value = el.value; break;
				case 'textarea': value = el.value;
			}
			if (value) queryString.push(encodeURIComponent(name)+'='+encodeURIComponent(value));
		});
		return queryString.join('&');
	}
});
/*	Script: Drag.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>

		Class: Drag
		Allows the user to drag an element around the window and provides the hooks to take action when
		they release the object. The Base class of Drag allows the user to manipulate any numerical css 
		property of an element. This could be height, top, margin, border, whatever. Typically it's 
		height/width for resizing and top/left for moving.
		
		See Also:
		<Drag.Move> for moving the element around the screen.
	*/
var Drag = {};
/*	Class: Drag.Base
		The base class of the Drag class.
	*/
Drag.Base = new Class({
/*	Function: setOptions
		Applies the options for the <Drag.Base> object. Internal function to the Drag class.
		
		Parameters:
		options - an object of option key/values.
		
		Options:
		handle - the element (or element id) to act as the handle for this draggable element (optional).
						 defaults to false (the whole element is clickable to drag).
		unit - 'px' by default (other values include things like 'em' for fonts or '%').
		onStart - function to execute when the user starts to drag (on mousedown); optional.
							defaults to no action.
		onComplete - function to execute when the user completes the drag (on mouseup); optional.
							defaults to no action.
		onDrag - function to execute when the user starts the drag (after mousedown, when they 
						 actually start to move the object); optional. defaults to no action.
		xMax - the maximum value for the x property of the drag (x defaults to top)
		xMin - the minium value for the x property of the drag (x defaults to top)
		yMax - the maximum value for the y property of the drag (y defaults to left)
		yMin - the minium value for the y property of the drag (y defaults to left)
		
		Returns:
		nothing
		
		See Also:
		<Drag.Base.initialize>

	*/	
	setOptions: function(options){
		this.options = Object.extend({
			handle: false,
			unit: 'px', 
			onStart: Class.empty, 
			onComplete: Class.empty, 
			onDrag: Class.empty,
			xMax: false,
			xMin: false,
			yMax: false,
			yMin: false
		}, options || {});
	},
/*	Function: initialize
		Initializes a <Drag.Base> object with the passed in parameters.
		
		Parameters:
		el - the element to allow dragging
		xModifier - examples include "top" or "height", the former for moving an element, the latter for resizing.
		yModifier - examples include "left" or "width", the former for moving an element, the latter for resizing.
		options - the options for the Drag effect; see <Drag.setOptions> for parameters.
	*/
	initialize: function(el, xModifier, yModifier, options){
		this.setOptions(options);
		this.element = $(el);
		this.handle = $(this.options.handle) || this.element;
		if (xModifier) this.xp = xModifier.camelCase();
		if (yModifier) this.yp = yModifier.camelCase();
		this.handle.onmousedown = this.start.bind(this);
	},
/*	Function: start
		Starts the Drag event (onmousedown); used internally by the <Drag> class.
		
		Parameters:
		evt - the event object (onmousedown)
		
		Returns:
		false
	*/
	start: function(evt){
		evt = evt || window.event;
		this.startX = evt.clientX;
		this.startY = evt.clientY;
		
		this.handleX = this.startX - this.handle.getLeft();
		this.handleY = this.startY - this.handle.getTop();
		
		this.set(evt);
		this.options.onStart.pass(this.element, this).delay(10);
		document.onmousemove = this.drag.bind(this);
		document.onmouseup = this.end.bind(this);
		return false;
	},
/*	Function: addStyles
		Applies the styles necessary for dragging; used internally by the <Drag> class.
		addStyles actually moves the element as the user drags it.
		
		Parameters:
		x - the offset to move the element along its x axis.
		y - the offset to move the element along its y axis.
		
		Returns:
		nothing
	*/	
	addStyles: function(x, y){
		if (this.xp){
			var stylex = this.element.getStyle(this.xp).toInt();
		
			var movex = function(val){
				this.element.setStyle(this.xp, val+this.options.unit);
			}.bind(this);
		
			if (this.options.xMax && stylex >= this.options.xMax){
				if (this.clientX <= this.handleX+this.handle.getLeft()) movex(stylex+x);
				if (stylex > this.options.xMax) movex(this.options.xMax);
			} else if(this.options.xMin && stylex <= this.options.xMin){
				if (this.clientX >= this.handleX+this.handle.getLeft()) movex(stylex+x);
				if (stylex < this.options.xMin) movex(this.options.xMin);
			} else movex(stylex+x);
		}
		if (this.yp){
			var styley = this.element.getStyle(this.yp).toInt();

			var movey = function(val){
				this.element.setStyle(this.yp, val+this.options.unit);
			}.bind(this);

			if (this.options.yMax && styley >= this.options.yMax){
				if (this.clientY <= this.handleY+this.handle.getTop()) movey(styley+y);
				if (styley > this.options.yMax) movey(this.options.yMax);
			} else if(this.options.yMin && styley <= this.options.yMin){
				if (this.clientY >= this.handleY+this.handle.getTop()) movey(styley+y);
				if (styley < this.options.yMin) movey(this.options.yMin);
			} else movey(styley+y);
		}
	},
/*	Function: Drag
		Captures the movement of the user's mouse and moves the element to keep up.
		Internal function to the Drag class.
		
		Parameters:
		evt - the event object (onmousemove)
		
		Returns:
		false
	*/
	drag: function(evt){
		evt = evt || window.event;
		this.clientX = evt.clientX;
		this.clientY = evt.clientY;
		this.options.onDrag.pass(this.element, this).delay(5);
		this.addStyles((this.clientX-this.lastMouseX), (this.clientY-this.lastMouseY));
		this.set(evt);
		return false;
	},
/*	Function: set
		Updates the coordinates of the mouse location. Internal function to the Drag class.
		
		Parameters:
		evt - the event object (onmousemove)
		
		Returns: 
		false
	*/
	set: function(evt){
		this.lastMouseX = evt.clientX;
		this.lastMouseY = evt.clientY;
		return false;
	},
/*	Function: end
		Stops the Drag event (onmouseup) and executes the onComplete function if it was assigned on initialize.
		Internal function to the Drag class.
		
		Returns:
		nothing
	*/
	end: function(){
		document.onmousemove = null;
		document.onmouseup = null;
		this.options.onComplete.pass(this.element, this).delay(10);
	}

});

/*	Class: Drag.Move
		Allows the user to move an element around the window by dragging.		
		Extends <Drag.Base>; see that function for additional functionality.
	*/
Drag.Move = Drag.Base.extend({
/*	Function: extendedOptions
		Sets options specific to Drag.Move in addition to <Drag.Base.setOptions>.
		Internal function to the Drag.Move class.
		
		Parameters:
		options - an object of option key/values.
		
		Options:
		onSnap - function to execute when the element has been dragged the snapDistance (see below). 
						 until a user drags an element the snapDistance, it remains fixed; once the user reaches
						 that distance, the element "snaps" and starts to follow the mouse. 						 
		droppables - array of DOM elements into which the dragged item can be released
		snapDistance - integer in pixel representing the distance between the mousedown
									 and the mouseposition to start the drag (the "snap").
		snap - boolean; true to turn snap on (default)
		xModifier - the modifier to handle left and right dragging; defaults to left.
								This will modify the css property "left" incrimentally as the user
								drags the element.
		yModifier - the modifier to handle up and down dragging; defaults to top.
								This will modify the css property "top" incrimentally as the user
								drags the element.
		container - boolean false or element or element id.
							- false means the element is not constrained (default)
							- element means the element can only be dragged within the confines of that elements dimensions.
		
		Returns:
		nothing
	*/
	extendOptions: function(options){
		this.options = Object.extend(this.options || {}, Object.extend({
			onSnap: Class.empty,
			droppables: [],
			snapDistance: 8,
			snap: true,
			xModifier: 'left',
			yModifier: 'top',
			container: false
		}, options || {}));
	},
/*	Function: initialize
		Returns a new Drag.Move object with the parameters passed in.
		
		Parameters:
		el - the element that can be dragged
		options - the options for the <Drag.Move> object; see <Drag.Move.extendOptions> and <Drag.Base.setOptions>

		
		Returns:
		nothing
	*/
	initialize: function(el, options){
		this.extendOptions(options);
		this.container = $(this.options.container);
		this.parent(el, this.options.xModifier, this.options.yModifier, this.options);
	},
/*	Function: start
		Starts the Drag event (onmousedown); used internally by the <Drag> class.
		Here it adds a few more details to the object specific to <Drag.Move>.
		Internal function to the Drag.Move class.
		
		Parameters:
		evt - the event object (onmousedown)

		Returns: 
		false
	*/
	start: function(evt){
		if (this.options.container) {
			var cont = $(this.options.container).getPosition();
			Object.extend(this.options, {
				xMax: cont.right-this.element.offsetWidth,
				xMin: cont.left,
				yMax: cont.bottom-this.element.offsetHeight,
				yMin: cont.top
			});
		}
		this.parent(evt);
		if (this.options.snap) document.onmousemove = this.checkAndDrag.bind(this);
		return false;
	},
/*	Function: drag
		Captures the movement of the user's mouse and moves the element to keep up.
		Internal function to the Drag.Move class.
		
		Parameters:
		evt - the event object (onmousemove)

		Returns: 
		false
	*/
	drag: function(evt){
		this.parent(evt);
		this.options.droppables.each(function(drop){
			if (this.checkAgainst(drop)){
				if (drop.onOver && !drop.dropping) drop.onOver.pass([this.element, this], drop).delay(10);
				drop.dropping = true;
			} else {
				if (drop.onLeave && drop.dropping) drop.onLeave.pass([this.element, this], drop).delay(10);
				drop.dropping = false;
			}
		}, this);
		return false;
	},
/*	Function: checkAndDrag
		Checks to see if the user has dragged the element far enough to initiate a drag event.
		Internal function to the Drag.Move class.
		See: options snap and snapDistance in <Drag.Move.extendedOptions>

		Returns: 
		false
	*/
	checkAndDrag: function(evt){
		evt = evt || window.event;
		var distance = Math.round(Math.sqrt(Math.pow(evt.clientX - this.startX, 2)+Math.pow(evt.clientY - this.startY, 2)));
		if (distance > this.options.snapDistance){
			this.set(evt);
			this.options.onSnap.pass(this.element, this).delay(10);
			document.onmousemove = this.drag.bind(this);
			this.addStyles(-(this.startX-evt.clientX), -(this.startY-evt.clientY));
		}
		return false;
	},
/*	Function: checkAgainst
		Checks to see if the dragged element has escaped the boundaries of a containing element.
		Internal function to the Drag.Move class.		
		
		Parameters:
		el - the element to check the dragged element against

		Returns: 
		false - the element has escaped the boundaries
		true - the element is within the boundaries
	*/
	checkAgainst: function(el){
		x = this.clientX+Window.getScrollLeft();
		y = this.clientY+Window.getScrollTop();
		var el = $(el).getPosition();
		return (x > el.left && x < el.right && y < el.bottom && y > el.top);
	},
/*	Function: end
		Stops the Drag event (onmouseup) and executes the onComplete function if it was assigned on initialize;
		used internally by the <Drag.Move> class.
		
		Returns:
		the Drag.Move object 
		
		See Also:
		<Drag.Base.end>

	*/
	end: function(){
		this.parent();
		this.options.droppables.each(function(drop){
			if (drop.onDrop && this.checkAgainst(drop)) drop.onDrop.pass([this.element, this], drop).delay(10);
		}, this);
	}
});

/*	Class: Element
		Extends the javascript Element object for dragging functionality.
	*/
Element.extend({
/*	Function: makeDraggable
		Makes an element draggable with the supplied options.
		
		Parameters:
		options - see <Drag.Move.extendedOptions> and <Drag.Base.setOptions> for acceptable options.

		Returns: 
		<Drag.Move>
	*/
	makeDraggable: function(options){
		return new Drag.Move(this, options);
	},
/*	Function: makeResizeable
		Makes an element resizable (by dragging) with the supplied options.
		
		Parameters:
		options - see <Drag.Base.setOptions> for acceptable options.

		Returns: 
		<Drag.Base>
	*/
	makeResizable: function(options){
		return new Drag.Base(this, 'width', 'height', options);
	},
/*	Function: getPosition
		Returns an object with width, height, left, right, top, and bottom.
		
		Example:
		(start code)
		$('myElement').getPosition()
	 	> {
				width: #,
				height: #,
				left: #,
				top: #,
				right: #,
				bottom: #
			}
		
		Returns:
		the element
	*/
	getPosition: function(){
		var obj = {};
		obj.width = this.offsetWidth;
		obj.height = this.offsetHeight;
		obj.left = this.getLeft();
		obj.top = this.getTop();
		obj.right = obj.left + obj.width;
		obj.bottom = obj.top + obj.height;
		return obj;
	}
});
/*	Script: Window.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>

		Class: Window
		Window class for <http://mootools.net> framework.
	*/

var Window = {
/*	Function: extend
		Base functionality to allow you to extend the Window object.
		
		See Also:
		<Class.extend>
	*/	
	extend: Object.extend,
/*	Function: getWidth
		Returns the width of the browser.
	*/
	getWidth: function(){
		return window.innerWidth || document.documentElement.clientWidth || 0;
	},
/*	Function: getHeight
		Returns the height of the browser.
		
		Returns:
		int - the height of the browser window.	*/
	getHeight: function(){
		return window.innerHeight || document.documentElement.clientHeight || 0;
	},
/*	Function: getScrollHeight
		Returns the scrollHeight of the window (DHTML property of the height
		of the scroll view of an element; it includes the element padding but not its margin).
		
		Returns:
		int - the scroll height of the window
		
		See Also:
		<http://developer.mozilla.org/en/docs/DOM:element.scrollHeight>
	*/
	getScrollHeight: function(){
		return document.documentElement.scrollHeight;
	},
/*	Function: getScrollWidth
		Returns the scrollWidth of the window (DHTML property of the width
		of the scroll view of an element; it includes the element padding but not its margin).
		
		Returns:
		int - the scroll width of the window
		
		See Also:
		<http://developer.mozilla.org/en/docs/DOM:element.scrollWidth>
	*/
	getScrollWidth: function(){
		return document.documentElement.scrollWidth;
	},
/*	Function: getScrollTop
		Returns the scrollTop of the window (the number of pixels that the content 
		of an element is scrolled upward).
		
		Returns:
		int - the scroll top
		
		See Also:
		<http://developer.mozilla.org/en/docs/DOM:element.scrollTop>	*/
	getScrollTop: function(){
		return document.documentElement.scrollTop || window.pageYOffset || 0;
	},
/*	Function: getScrollLeft
		Returns the scrollLeft of the window (the number of pixels that the content 
		of an element is scrolled to the left).
		
		Returns:
		int - the scroll left
		
		See Also:
		<http://developer.mozilla.org/en/docs/DOM:element.scrollLeft>	*/
	getScrollLeft: function(){
		return document.documentElement.scrollLeft || window.pageXOffset || 0;
	},
	
/*	Function: onDomReady
		Executes the passed in function when the DOM is ready (but before window.onload).
		
		Credits:
		(c) Dean Edwards/Matthias Miller/John Resig
		remastered for mootools
		
		Parameters:
		init - the function to execute when the DOM is ready
		
		Example:
		> Window.onDomReady(function(){alert('the dom is ready');});
	*/
	onDomReady: function(init){
		var listen = document.addEventListener;
		var state = document.readyState;
		if (listen) document.addEventListener("DOMContentLoaded", init, false); //moz || opr9
		if (state) { //saf || ie
			document.write('<script id="_ie_load_" defer="true"></script>');
			var scr = $('_ie_load_');
			if (scr.readyState){ //ie
				scr.onreadystatechange = function() {
					if (this.readyState.test(/complete|loaded/)) init();
				};
			} else { //saf
				if (state.test(/complete|loaded/)) init();
				else return Window.onDomReady.pass(init).delay(10);
			}
		} else if (!listen || window.opera && navigator.appVersion.toInt() < 9) { //others
			window.addEvent('init', init);
		}
	}	
};
/*	Script: Cookie.js
		Cookie creator - yummy! - part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Credits: 
		based on the functions by Peter-Paul Koch (http://quirksmode.org)
		
		Dependencies:
		<Moo.js>, <String.js>

	*/

/*	Class: Cookie
		Class for creating, getting, and removing cookies.
	*/
var Cookie = {
/*	Function: set
		Sets a cookie in the browser.
		
		Parameters:
		key - the key (name) for the cookie
		value - the value to set
		duration - how long the cookie should remain (in days); optional - defaults to 1 year.
		
		Example:
		>Cookie.set("username", "bob", 1) //save this for a day
		
	*/
	set: function(key, value, duration){
		var date = new Date();
		date.setTime(date.getTime()+((duration || 365)*86400000));
		document.cookie = key+"="+value+"; expires="+date.toGMTString()+"; path=/";
	},
/*	Function: get
		Gets the value of a cookie.
		
		Parameters:
		key - the name of the cookie you wish to retrieve.
		
		Example:
		>Cookie.get("username")
		> > bob
	*/
	get: function(key){
		var myValue, myVal;
		document.cookie.split(';').each(function(cookie){
			if(myVal = cookie.trim().test(key+'=(.*)')) myValue = myVal[1];
		});
		return myValue;
	},
/*	Function: remove
		Removes a cookie from the browser.
		
		Parameters:
		key - the name of the cookie to remove
		
		Examples:
		>Cookie.remove("username") //bye-bye
	*/
	remove: function(key){
		this.set(key, '', -1);
	}

};
/*	Script: Json.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		See: <http://www.json.org/>
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>

		Class: Json
		Json class for <http://mootools.net> framework.
		See: <http://www.json.org/>
	*/
var Json = {
/*	Function: toString
		Converts an object (string, function, object, or array) to a json object.
		
		Parameters:
		el - a string, function, object, or array to convert
		
		Returns:
		A json string
		
		Example:
		(start code)
		Json.toString({apple: 'red', lemon: 'yellow'}); //convert an object
		> "{"apple":"red","lemon":"yellow"}" //don't get hung up on the quotes; it's just a string.
		Json.toString(['one','two','three']); //array to json
		> "["one","two","three"]"
		Json.toString(function(){alert('hi');}); //function to json
		> "function () { alert("hi"); }"
		Json.toString('hi there');
		> ""hi there"" //string to json
		(end)
	*/
	toString: function(el){
		var string = [];
		
		var isArray = function(array){
			var string = [];
			array.each(function(ar){
				string.push(Json.toString(ar));
			});
			return string.join(',');
		};
		
		var isObject = function(object){
			var string = [];
			for (var property in object) string.push('"'+property+'":'+Json.toString(object[property]));
			return string.join(',');
		};
		
		switch($type(el)){
			case 'string': string.push('"'+el+'"'); break;
			case 'function': string.push(el); break;
			case 'object': string.push('{'+isObject(el)+'}'); break;
			case 'array': string.push('['+isArray(el)+']');
		}
		
		return string.join(',');
	},
/*	Function: evaluate
		converts a json string to an object.
		
		Parameters:
		str - the string to evaluate.
		
		Example:
		>Json.evaluate('{"apple":"red","lemon":"yellow"}');
		> > {apple: 'red', lemon: 'yellow'}
	*/
	evaluate: function(str){
		return eval('(' + str + ')');
	}
};
/*	Script: Sortables.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>, <Fx.js>, <Drag.js>

		Class: Sortables
		Creates an interface for drag and drop resorting of a list.
		
		See also:
		<Drag.js>
	*/
var Sortables = new Class({
/*	Function: setOptions
		Applies the options for the fx object.
		
		Parameters:
		options - an object of option key/values.
		
		Options:
		handles - boolean false (no handles) or a collection of elements to be used for drag handles.
						  defaults to false.
		fxDuration - the duration in ms of the effects applied to the dragged element and its clone 
								(the item dragged gets left behind and a clone follows the mouse so the user can
								see where the item was and where they are dropping it). defaults to 250.
		fxTransition - the transition for the effects (see also: <Fx.Transitions> and <Fxtransitions.js>
		maxOpacity - the opacity for the dragged clone
		onComplete - function executed when the item is dropped
		onStart - function executed when the item is first dragged
		contain - boolean; true keeps the dragged item constrained to it's parent element, false does not (default)
	*/
	setOptions: function(options) {
		this.options = {
			handles: false,
			fxDuration: 250,
			fxTransition: Fx.Transitions.sineInOut,
			maxOpacity: 0.5,
			onComplete: Class.empty,
			onStart: Class.empty,
			contain: false
		};
		Object.extend(this.options, options || {});
	},
/*	Function: initialize
		Returns a new Sortables object with the elements and options passed in.
		
		Parameters:
		elements - the collection of elements that are sortable.
		options - options for the Sortables object; see <Sortables.setOptions>
	*/
	initialize: function(elements, options){
		this.setOptions(options);
		this.options.handles = this.options.handles || elements;
		var trash = new Element('div').injectInside($(document.body));
		$A(elements).each(function(el, i){
			var copy = $(el).clone().setStyles({
				'position': 'absolute',
				'opacity': '0',
				'display': 'none'
			}).injectInside(trash); //make a copy for dragging
			var elEffect = el.effect('opacity', {
				duration: this.options.fxDuration,
				wait: false,
				transition: this.options.fxTransition
			}).set(1); //set up an effect for the element left behind
			var copyEffects = copy.effects({
				duration: this.options.fxDuration,
				wait: false,
				transition: this.options.fxTransition,
				onComplete: function(){
					copy.setStyle('display', 'none');
				}
			}); //set up an effect for the copy that's going to be dragged
			
			var yMax = false;
			var yMin = false;
			if (this.options.contain){
				yMax = $(el.parentNode).getTop()+el.parentNode.offsetHeight-el.offsetHeight;
				yMin = el.parentNode.getTop();
			}
			//move the copy around
			var dragger = new Drag.Move(copy, {
				handle: this.options.handles[i],
				yMax: yMax,
				yMin: yMin,
				xModifier: false,
				onStart: function(){
					this.options.onStart.bind(this).delay(10);
					copy.setHTML(el.innerHTML).setStyles({
						'display': 'block',
						'opacity': this.options.maxOpacity,
						'top': el.getTop()+'px',
						'left': el.getLeft()+'px'
					});
					elEffect.custom(elEffect.now, this.options.maxOpacity);
				}.bind(this),
				onComplete: function(){
					this.options.onComplete.bind(this).delay(10);
					copyEffects.custom({
						'opacity': [this.options.maxOpacity, 0],
						'top': [copy.getTop(), el.getTop()]
					});
					elEffect.custom(elEffect.now, 1);
				}.bind(this),
				onDrag: function(){
					if (el.getPrevious() && copy.getTop() < (el.getPrevious().getTop())) el.injectBefore(el.getPrevious());
					else if (el.getNext() && copy.getTop() > (el.getNext().getTop())) el.injectAfter(el.getNext());
				}
			});
		}, this);
	}
});
/*	Script: Fxpack.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>, <Fx.js>

		
		Class: Fx.Scroll
		The scroller effect; scrolls an element or the window to a location. 
		Extends <Fx.Base>; see that Class for additional functionality.
	*/
Fx.Scroll = Fx.Base.extend({
/*	Function: initialize
		creates a new <Fx.Scroll> object with the values passed in.
		
		Parameters:
		el - the element to apply the style transition to
		options - the fx options (see: <Fx.Base.setOptions>)
	*/
	initialize: function(el, options) {
		this.element = $(el);
		this.setOptions(options);
	},
/*	Function: down
		Scrolls an element down to the bottom of its scroll height.
		
		Returns:
		the fx object.
	*/	
	down: function(){
		return this.custom(this.element.scrollTop, this.element.scrollHeight-this.element.offsetHeight);
	},
/*	Function: down
		Scrolls an element down to the top of its scroll height.
		
		Returns:
		the fx object.
	*/
	up: function(){
		return this.custom(this.element.scrollTop, 0);
	},
/*	Function: increase
		Incriments the scroll (used internally by the effect).
	*/
	increase: function(){
		this.element.scrollTop = this.now;
	}
});
/*	Class: Fx.Slide
		The slide effect; slides an element in horizontally or vertically. Similar to
		resizing the width of an element (via <Fx.Style>) but won't make all your text
		wrap or anything.
		
		Note:
		This effect works on any block element, but the element *cannot be positioned*;
		no margins or absolute positions. To position the element, put it inside another
		element (a wrapper div, for instance) and position that instead.
		
		Extends <Fx.Base>; see that Class for additional functionality.
	*/
Fx.Slide = Fx.Base.extend({
/*	Function: initialize
		creates a new <Fx.Slide> object with the values passed in.
		Extends <Fx.Base>; see that Class for additional functionality.
		
		Parameters:
		el - the element to apply the slide to
		options - the fx options (see: <Fx.Base.setOptions>)
		
		Example:
		(start code)
		var mySlider = new Fx.Slide('myElement', {duration: 500});
		mySlider.horizontal() //set it to be a horizontal slider (vertical by default)
		mySlider.show() //show it
		mySlider.toggle() //toggle it
		
		ALSO
		var mySlider = new Fx.Slide('myElement', {duration: 500}).horizontal();
			//set it to be a horizontal slider (vertical by default)
		(end)
	*/
	initialize: function(el, options){
		this.element = $(el);
		this.wrapper = new Element('div').injectAfter(this.element).setStyle('overflow', 'hidden').adopt(this.element);
		this.setOptions(options);
		this.now = [];
	},
/*	Function: setNow
		Assigns the "now" values of the effect to a specific value based on the point in time between
		the beginning of the effect and the assigned end of it (beginning + duration) applied to the
		transition equation. now[0] is the height of the element and now[1] is the margin.
		
		See also:
		<Fx.Base.compute>
	*/
	setNow: function(){
		[0,1].each(function(i){
			this.now[i] = this.compute(this.from[i], this.to[i]);
		}, this);
	},
/*	Function: vertical
		Sets up this slide effect to toggle vertically.			

		Returns:
		the fx object.
*/
	vertical: function(){
		this.margin = 'top';
		this.layout = 'height';
		this.startPosition = [this.element.scrollHeight, '0'];
		this.endPosition = ['0', -this.element.scrollHeight];
		this.mode = 'vertical';
		return this;
	},
/*	Function: horizontal
		Sets up this slide effect to toggle horizontally
		
		Returns:
		the fx object.
	*/
	horizontal: function(){
		this.margin = 'left';
		this.layout = 'width';
		this.startPosition = [this.element.scrollWidth, '0'];
		this.endPosition = ['0', -this.element.scrollWidth];
		this.mode = 'horizontal';
		return this;
	},
/*	Function: hide	
		Slides the element out of view.

		See Also:
		<Fx.Slide.vertical> and <Fx.Slide.horizontal> to determine how this toggle works.
		
		Returns:
		the fx object.
	*/
	hide: function(mode){
		if (mode) this[mode]();
		else if (this.mode) this[this.mode]();
		else this.vertical();
		this.wrapper.setStyle(this.layout, '0');
		this.element.setStyle('margin-'+this.margin, -this.element['scroll'+this.layout.capitalize()]+this.options.unit);
		return this;
	},
/*	Function: show
		Slides the element into view.
		
		See Also:
		<Fx.Slide.vertical> and <Fx.Slide.horizontal> to determine how this toggle works.
		
		Returns:
		the fx object.
	*/
	show: function(mode){
		if (mode) this[mode]();
		else if (this.mode) this[this.mode]();
		else this.vertical();
		this.wrapper.setStyle(this.layout, this.element['scroll'+this.layout.capitalize()]+this.options.unit);
		this.element.setStyle('margin-'+this.margin, '0');
		return this;
	},
/*	Function: toggle
		Hides or shows a slide element, depending on its state; accepts the optional mode parameter.
		
		Parameter:
		mode - either 'horizontal' or 'vertical'; slides the slider element up and down or sideways.
					 defaults to vertical.
		
		Returns:
		the fx object.
	*/
	toggle: function(mode){
		if (this.timer && this.options.wait) return;
		if (mode) this[mode]();
		else if (this.mode) this[this.mode]();
		else this.vertical();
		if (this.wrapper['offset'+this.layout.capitalize()] > 0) return this.custom(this.startPosition, this.endPosition);
		else return this.custom(this.endPosition, this.startPosition);
	},
/*	Function: increase
		Incriments the slide (used internally by the effect).
	*/
	increase: function(){		
		this.wrapper.setStyle(this.layout, this.now[0]+this.options.unit);
		this.element.setStyle('margin-'+this.margin, this.now[1]+this.options.unit);
	}
	
});

/*	Class: Fx.Color
		Smoothly transitions the color of an element; 
		Extends <Fx.Base>; see that Class for additional functionality.
		
		Credits:
		fx.Color, originally by Tom Jensen (http://neuemusic.com) MIT-style LICENSE.
		
		Example:
		(start code)
		var myColorFx = new Fx.Color('myElement', 'color', {duration: 500});
		myColorFx.custom('000000', 'FF0000') //fade from black to red
		(end)
		
		See <Fx.Color.custom> for alternate syntax.
	*/
Fx.Color = Fx.Base.extend({
/*	Function: initialize
		Returns a new Fx.Color with the values passed in.
		
		Parameters:
		el - the element to alter
		property - any css element that accepts a color value (border, color, background-color, etc.)
		options - the fx options (see: <Fx.Base.setOptions>)
	*/	
	initialize: function(el, property, options){
		this.element = $(el);
		this.setOptions(options);
		this.property = property;
		this.now = [];
	},

/*	Function: custom
		Transitions the color of the element specified in <initialize> smoothly from one color to the next.
		
		Parameters:
		from - the starting color
		to - the ending color
		
		Both values can be any of the following formats:
		'#333' - css shorthand with the hash
		'333' - or without the hash
		'#333333' - css longhand with the hash
		'333333' - without the hash
		
		Returns:
		the fx object.
	*/
	custom: function(from, to){
		return this.parent(from.hexToRgb(true), to.hexToRgb(true));
	},

/*	Function: setNow
		Assigns the "now" values of the effect to a specific value based on the point in time between
		the beginning of the effect and the assigned end of it (beginning + duration) applied to the
		transition equation. this.now is an array of the three color values (r, g, and b).
		
		See also:
		<Fx.Base.compute>

	*/
	setNow: function(){
		[0,1,2].each(function(i){
			this.now[i] = Math.round(this.compute(this.from[i], this.to[i]));
		}, this);
	},
/*	Function: increase
		Incriments the color values (used internally by the effect).
	*/
	increase: function(){
		this.element.setStyle(this.property, "rgb("+this.now[0]+","+this.now[1]+","+this.now[2]+")");
	},
/*	Function: fromColor
		Transitions from the color passed in to the current color of the element.
		
		Parameters:
		color - the color to transition *from* to the current color of the element.
		
		Example:
		>myColorFx.fromColor('F00') //transition from red to whatever color the element is currently
	*/
	fromColor: function(color){
		return this.custom(color, this.element.getStyle(this.property));
	},
/*	Function: toColor
		Transitions to the color passed in from the current color of the element.
		
		Parameters:
		color - the color to transition *to* from the current color of the element.
		
		Example:
		>myColorFx.toColor('F00') //transition from whatever color the element is currently to red
	*/
	toColor: function(color){
		return this.custom(this.element.getStyle(this.property), color);
	}
});
/*	Script: Fxutils.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>, <Fx.js>

		Class: Fx.Height
		Alters the height of an element. Extends <Fx.Style>; see that Class for additional functionality.
		
		Example:
		>var myHFx = new Fx.Height('myElementId', {duration: 500});
	*/

Fx.Height = Fx.Style.extend({
/*	Function: initialize
		Creates an Fx.Height class with the supplied values.
		
		Parameters:
		el - the element to apply the effect to
		options - the fx options (see: <Fx.Base.setOptions>)
	*/	
	initialize: function(el, options){
		this.parent(el, 'height', options);
		this.element.setStyle('overflow', 'hidden');
	},
/*	Function: toggle
		Toggles the height of an element from zero to it's scrollHeight.
	*/
	toggle: function(){
		if (this.element.offsetHeight > 0) return this.custom(this.element.offsetHeight, 0);
		else return this.custom(0, this.element.scrollHeight);
	},
/*	Function: show
		Size the element to its full scrollHeight.
	*/	
	show: function(){
		return this.set(this.element.scrollHeight);
	}
	
});

/*	Class: Fx.Width
		Alters the width of an element. Extends <Fx.Style>; see that Class for additional functionality.
		
		Example:
		>var myWFx = new Fx.Width('myElementId', {duration: 500});
	*/
Fx.Width = Fx.Style.extend({
/*	Function: initialize
		Creates an Fx.Width class with the supplied values.
		
		Parameters:
		el - the element to apply the effect to
		options - the fx options (see: <Fx.Base.setOptions>)
	*/	
	initialize: function(el, options){
		this.parent(el, 'width', options);
		this.element.setStyle('overflow', 'hidden');
		this.iniWidth = this.element.offsetWidth;
	},
/*	Function: toggle
		Toggles the element from zero width to its full width.
	*/	
	toggle: function(){
		if (this.element.offsetWidth > 0) return this.custom(this.element.offsetWidth, 0);
		else return this.custom(0, this.iniWidth);
	},
/*	Function: show
		Sizes the element to its full width.	*/	
	show: function(){
		return this.set(this.iniWidth);
	}
});

/*	Class: Fx.Opacity
		Alters the opacity of an element. Extends <Fx.Style>; see that Class for additional functionality.
		
		Example:
		>var myOFx = new Fx.Opacity('myElementId', {duration: 500});
	*/
Fx.Opacity = Fx.Style.extend({
/*	Function: initialize
		Creates an Fx.Opacity class with the supplied values.
		
		Parameters:
		el - the element to apply the effect to
		options - the fx options (see: <Fx.Base.setOptions>)	*/
	initialize: function(el, options){
		this.parent(el, 'opacity', options);
		this.now = 1;
	},
/*	Function: toggle
		Toggles the element from transparent to visible
	*/	
	toggle: function(){
		if (this.now > 0) return this.custom(1, 0);
		else return this.custom(0, 1);
	},
/*	Function: show
		Sets the element's opacity to 1 (100%).
	*/
	show: function(){
		return this.set(1);
	}
});
/*	Script: Fxtransitions.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		None
		
		Credits:
		Easing Equations v1.5
		(c) 2003 Robert Penner, all rights reserved. <http://www.robertpenner.com/easing/>, Open Source BSD License.
		- demos: <http://www.robertpenner.com/easing/easing_demo.html>

		
		Class: Fx.Transitions
		A collection of tweaning transitions for use with the <Fx.Base> classes. For details on all of these,

		- source: <http://www.robertpenner.com/easing/>
		- demos: <http://www.robertpenner.com/easing/easing_demo.html>
*/


Fx.Transitions = {
/*	constant: linear
	*/	
	linear: function(t, b, c, d){
		return c*t/d + b;
	},
/*	constant: quadIn
	*/
	quadIn: function(t, b, c, d){
		return c*(t/=d)*t + b;
	},
/*	constant: quatOut
	*/
	quadOut: function(t, b, c, d){
		return -c *(t/=d)*(t-2) + b;
	},

/*	constant: quadInOut
	*/
	quadInOut: function(t, b, c, d){
		if ((t/=d/2) < 1) return c/2*t*t + b;
		return -c/2 * ((--t)*(t-2) - 1) + b;
	},

/*	constant: cubicIn
	*/
	cubicIn: function(t, b, c, d){
		return c*(t/=d)*t*t + b;
	},

/*	constant: cubicOut
	*/
	cubicOut: function(t, b, c, d){
		return c*((t=t/d-1)*t*t + 1) + b;
	},

/*	constant: cubicInOut
	*/
	cubicInOut: function(t, b, c, d){
		if ((t/=d/2) < 1) return c/2*t*t*t + b;
		return c/2*((t-=2)*t*t + 2) + b;
	},

/*	constant: quartIn
	*/
	quartIn: function(t, b, c, d){
		return c*(t/=d)*t*t*t + b;
	},

/*	constant: quartOut
	*/
	quartOut: function(t, b, c, d){
		return -c * ((t=t/d-1)*t*t*t - 1) + b;
	},

/*	constant: quartInOut
	*/
	quartInOut: function(t, b, c, d){
		if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
		return -c/2 * ((t-=2)*t*t*t - 2) + b;
	},

/*	constant: quintIn
	*/
	quintIn: function(t, b, c, d){
		return c*(t/=d)*t*t*t*t + b;
	},

/*	constant: quintOut
	*/
	quintOut: function(t, b, c, d){
		return c*((t=t/d-1)*t*t*t*t + 1) + b;
	},

/*	constant: quintInOut
	*/
	quintInOut: function(t, b, c, d){
		if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
		return c/2*((t-=2)*t*t*t*t + 2) + b;
	},

/*	constant: sineIn
	*/
	sineIn: function(t, b, c, d){
		return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
	},

/*	constant: sineOut
	*/
	sineOut: function(t, b, c, d){
		return c * Math.sin(t/d * (Math.PI/2)) + b;
	},

/*	constant: sineInOut
	*/
	sineInOut: function(t, b, c, d){
		return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
	},

/*	constant: expoIn
	*/
	expoIn: function(t, b, c, d){
		return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
	},

/*	constant: expoOut
	*/
	expoOut: function(t, b, c, d){
		return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
	},

/*	constant: expoInOut
	*/
	expoInOut: function(t, b, c, d){
		if (t==0) return b;
		if (t==d) return b+c;
		if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
		return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
	},

/*	constant: circIn
	*/
	circIn: function(t, b, c, d){
		return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
	},

/*	constant: circOut
	*/
	circOut: function(t, b, c, d){
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	},

/*	constant: circInOut
	*/
	circInOut: function(t, b, c, d){
		if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
		return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
	},

/*	constant: elasticIn
	*/
	elasticIn: function(t, b, c, d, a, p){
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3; if (!a) a = 1;
		if (a < Math.abs(c)){ a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin(c/a);
		return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
	},

/*	constant: elasticOut
	*/
	elasticOut: function(t, b, c, d, a, p){
		if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3; if (!a) a = 1;
		if (a < Math.abs(c)){ a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin(c/a);
		return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
	},

/*	constant: elasticInOut
	*/
	elasticInOut: function(t, b, c, d, a, p){
		if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5); if (!a) a = 1;
		if (a < Math.abs(c)){ a=c; var s=p/4; }
		else var s = p/(2*Math.PI) * Math.asin(c/a);
		if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
	},

/*	constant: backIn
	*/
	backIn: function(t, b, c, d, s){
		if (!s) s = 1.70158;
		return c*(t/=d)*t*((s+1)*t - s) + b;
	},

/*	constant: backOut
	*/
	backOut: function(t, b, c, d, s){
		if (!s) s = 1.70158;
		return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
	},

/*	constant: backInOut
	*/
	backInOut: function(t, b, c, d, s){
		if (!s) s = 1.70158;
		if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
		return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
	},

/*	constant: bounceIn
	*/
	bounceIn: function(t, b, c, d){
		return c - Fx.Transitions.bounceOut (d-t, 0, c, d) + b;
	},

/*	constant: bounceOut
	*/
	bounceOut: function(t, b, c, d){
		if ((t/=d) < (1/2.75)){
			return c*(7.5625*t*t) + b;
		} else if (t < (2/2.75)){
			return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
		} else if (t < (2.5/2.75)){
			return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
		} else {
			return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
		}
	},

/*	constant: bounceInOut
	*/
	bounceInOut: function(t, b, c, d){
		if (t < d/2) return Fx.Transitions.bounceIn(t*2, 0, c, d) * .5 + b;
		return Fx.Transitions.bounceOut(t*2-d, 0, c, d) * .5 + c*.5 + b;
	}	
	
};
/*	Script: Tips.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>, <Fx.js>

		
		Credits:
		Tips.js is based on Bubble Tooltips (<http://web-graphics.com/mtarchive/001717.php>) by 
		Alessandro Fulcitiniti <http://web-graphics.com>


		Example:
		(start code)
		<img src="/images/i.png" title="The body of the tooltip is stored in the title" tooltitle="The Title of the Tooltip" class="toolTipImg"/>
		<script>
			var myTips = new Tips($S('.toolTipImg'), {
				maxTitleChars: 50, //I like my captions a little long
				maxOpacity: .9, //let's leave a little transparancy in there
			});
		</script>
		(end)
		
		Class: Tips
		Display a tip on any element with a title and/or href.
	*/
var Tips = new Class({
/*	Function: setOptions
		Sets the options for the tip.
		
		Options:
		transitionStart - the transition effect to use to show the tip (see <Fx.Transitions> and <Fx.Transitions.js>).
											defaults to Fx.sinoidal.
		transitionEnd - the transition effect to use to hide the tip.
											defaults to Fx.sinoidal.
		maxTitleChars - the maximum number of characters to display in the title of the tip.
											defaults to 30.
		fxDuration - the duration (in ms) for the transition effect when the tip to appears and disappears.
										defaults to 150.
		maxOpacity - how opaque to make the tooltip (0 = 0% opaque, 1= 100% opaque). defaults to 1.
		timeOut - the delay to wait to show the tip (how long the user must hover to have the tooltip appear).
										defaults to 100.
		className - the class name to apply to the tooltip
	*/
	setOptions: function(options){
		this.options = {
			transitionStart: fx.sinoidal,
			transitionEnd: fx.sinoidal,
			maxTitleChars: 30,
			fxDuration: 150,
			maxOpacity: 1,
			timeOut: 100,
			className: 'tooltip'
		}
		Object.extend(this.options, options || {});
	},
/*	Function: initialize
		Returns a new <Tips> object with the parameters passed in.
		
		Parameters:
		elements - a collection of DOM elements to which to apply the tooltips
		options - the options to apply to them (see <Tips.setOptions>)
	*/
	initialize: function(elements, options){
		this.elements = elements;
		this.setOptions(options);
		this.toolTip = new Element('div').addClassName(this.options.className).setStyle('position', 'absolute').injectInside(document.body);
		this.toolTitle = new Element('H4').injectInside(this.toolTip);
		this.toolText = new Element('p').injectInside(this.toolTip);
		this.fx = new fx.Style(this.toolTip, 'opacity', {duration: this.options.fxDuration, wait: false}).hide();
		$A(elements).each(function(el){
			$(el).myText = el.title || false;
			if (el.myText) el.removeAttribute('title');
			if (el.href){
				if (el.href.test('http://')) el.myTitle = el.href.replace('http://', '');
				if (el.href.length > this.options.maxTitleChars) el.myTitle = el.href.substr(0,this.options.maxTitleChars-3)+"...";
			}
			if (el.myText && el.myText.test('::')){
				var dual = el.myText.split('::');
				el.myTitle = dual[0].trim();
				el.myText = dual[1].trim();
			} 
			el.onmouseover = function(){
				this.show(el);
				return false;
			}.bind(this);
			el.onmousemove = this.locate.bindAsEventListener(this);
			el.onmouseout = function(){
				this.timer = $clear(this.timer);
				this.disappear();
			}.bind(this);
		}, this);
	},
/*	Function: show
		Sets up the content inside the tooltip.
	*/
	show: function(el){
		this.toolTitle.innerHTML = el.myTitle;
		this.toolText.innerHTML = el.myText;
		this.timer = $clear(this.timer);
		this.fx.options.transition = this.options.transitionStart;
		this.timer = this.appear.delay(this.options.timeOut, this);
	},
/*	Function: appear
		Shows the tooltip.
	*/
	appear: function(){
		this.fx.custom(this.fx.now, this.options.maxOpacity);
	},
/*	Function: locate
		Moves a tooltip to be next to the mouse.
	*/
	locate: function(evt){
		var doc = document.documentElement;
		this.toolTip.setStyles({'top': evt.clientY + doc.scrollTop + 15 + 'px', 'left': evt.clientX + doc.scrollLeft - 30 + 'px'});
	},
/*	Function: disappear
		Hides the tooltip.	*/
	disappear: function(){
		this.fx.options.transition = this.options.transitionEnd;
		this.fx.custom(this.fx.now, 0);
	}
});
/*	Script: Accordion.js
		part of mootools.js - by Valerio Proietti (http://mad4milk.net). MIT-style license.
		
		Dependencies:
		<Moo.js>, <Function.js>, <Array.js>, <String.js>, <Element.js>, <Fx.js>

		Class: Fx.Elements
		Fx.Elements allows you to apply any number of styles trantisions to a selection of elements.
		Extends <Fx.Base>; see that class for additional functionality.
	*/

Fx.Elements = Fx.Base.extend({
/*	Function: initialize
		Returns a new Fx.Elements object with the elements and options passed in.
		
		Parameters:
		elements - the elements to collect and alter in the Fx.Elements object
		options - the options to apply (see <Fx.Base.setOptions>)
	*/
	initialize: function(elements, options){
		this.elements = [];
		elements.each(function(el){
			this.elements.push($(el));
		}, this);
		this.setOptions(options);
		this.now = {};
	},
/*	Function: setNow
		Used internally to this function to alter the properties passed in with <custom>.
	*/
	setNow: function(){
		for (var i in this.from){
			var iFrom = this.from[i];
			var iTo = this.to[i];
			var iNow = this.now[i] = {};
			for (var p in iFrom) iNow[p] = this.compute(iFrom[p], iTo[p]);
		}
	},
/*	Function: custom
		Applies the passed in style transitions to each object named (see example). Each item
		in the collection is refered to as a numerical string ("1" for instance). The first
		item is "1", the second "2", etc.
		
		Example:
		(start code)
		//let's get all the anchors and use the defaults for the Fx.Elements object
		var myFE = new Fx.Elements($('a'), {}); 
		myFE.custom({
			'1': { //let's change the first element's opacity and width
				'opacity': [0,1],
				'width': [100,200]
			},
			'2': { //and the second one's opacity
				'opacity': [0.2, 0.5]
			}
		});
	*/
	custom: function(objObjs){
		if (this.timer && this.options.wait) return;
		var from = {};
		var to = {};
		for (var i in objObjs){
			var iProps = objObjs[i];
			var iFrom = from[i] = {};
			var iTo = to[i] = {};
			for (var prop in iProps){
				iFrom[prop] = iProps[prop][0];
				iTo[prop] = iProps[prop][1];
			}
		}
		return this.parent(from, to);
	},
/*	Function: increase	
		Increments the effect(s).
*/

	increase: function(){
		for (var i in this.now){
			var iNow = this.now[i];
			for (var p in iNow) this.setStyle(this.elements[i.toInt()-1], p, iNow[p]);
		}
	}

});
/*	Class: Fx.Accordion
		The Fx.Accordion function creates a group of elements that are expanded one
		at a time when their handles are clicked. For instance, let's say you had
		a list of stories, and each story had a headline and a sentence below it
		describing it. An Accordion effect would hide all the descriptions (except one)
		and, when the user clicked on any header, hide the visible one and show the
		selected one, reusing the same space to show each additional detail. Extends
		<Fx.Elements>.
	*/
Fx.Accordion = Fx.Elements.extend({
/*	Function: extendOptions
		 Extends the options of the Fx.Accordion object with the options passed in.
		 
		 Parameters: 
		 options - the options to apply to the Accordion
		 
		 Options:
		 start - either 'open-first' or 'first-open'. 
		 				'open-first' will slide that element open. 
						'first-open' will just show that element as open immediately with no transition.
		 fixedHeight - boolean false, or the fixed height of an open element.
		 fixedWidth - boolean false, or the fixed width of an open element.
		 alwaysHide - boolean. false hides the visible element that wasn't clicked, 
		 						 true allows elements to remain open.
		 wait - boolean. false (default) means that open and close transitions can overlap (so if you click
			 on items before the previous finishes transitioning, the clicked transition will also fire). true
			 means that if one element is sliding open or closed, clicking on another will have no effect.
		 onActive - function to execute when an element is starts to show
		 onBackground - function to execute when an element starts to hide
		 height - boolean true (default) means transition the height of the element when it is activated/deactivated,
		 					false means leave the height alone.
		 opacity - boolean true (default) means transition the opacity of the element when it is activated/deactivated,
		 					false means leave the opacity alone.
		 width - boolean true means transition the width of the element when it is activated/deactivated,
		 					false (default) means leave the width alone.
	*/
	extendOptions: function(options){
		Object.extend(this.options, Object.extend({
			start: 'open-first',
			fixedHeight: false,
			fixedWidth: false,
			alwaysHide: false,
			wait: false,
			onActive: Class.empty,
			onBackground: Class.empty,
			height: true,
			opacity: true,
			width: false
		}, options || {}));
	},
/*	Function: initialize
		Creates a new Fx.Accordion with the elements and options passed in.
		
		Parameters:
		togglers - DOM elements that activate an item in the accordion
		elements - the elements to show/hide when the togglers are clicked
		options - options for the accordion. see: <Fx.Accordion.extendOptions> and 
							<Fx.Base.setOptions> for available options.
	*/
	initialize: function(togglers, elements, options){
		this.parent(elements, options);
		this.extendOptions(options);
		this.previousClick = 'nan';
		togglers.each(function(tog, i){
			$(tog).addEvent('click', function(){this.showThisHideOpen(i)}.bind(this));
		}, this);
		this.togglers = togglers;
		this.h = {}; this.w = {}; this.o = {};
		this.elements.each(function(el, i){
			this.now[i+1] = {};
			$(el).setStyles({'height': 0, 'overflow': 'hidden'});
		}, this);
		switch(this.options.start){
			case 'first-open': this.elements[0].setStyle('height', this.elements[0].scrollHeight); break;
			case 'open-first': this.showThisHideOpen(0); break;
		}
	},
/*	Function: hideThis
		Hides a specific item in the Accordion.
		
		Parameters:
		i - the index of the item to hide.
	*/
	hideThis: function(i){
		if (this.options.height) this.h = {'height': [this.elements[i].offsetHeight, 0]};
		if (this.options.width) this.w = {'width': [this.elements[i].offsetWidth, 0]};
		if (this.options.opacity) this.o = {'opacity': [this.now[i+1]['opacity'] || 1, 0]};
	},
/*	Function: showThis
		Shows a specific item in the Accordion.
		
		Parameters:
		i - the index of the item to show.
	*/
	showThis: function(i){
		if (this.options.height) this.h = {'height': [this.elements[i].offsetHeight, this.options.fixedHeight || this.elements[i].scrollHeight]};
		if (this.options.width) this.w = {'width': [this.elements[i].offsetWidth, this.options.fixedWidth || this.elements[i].scrollWidth]};
		if (this.options.opacity) this.o = {'opacity': [this.now[i+1]['opacity'] || 0, 1]};
	},
/*	Function: showThisHideOpen
		Shows a specific item and hides all others.
		
		Parameters:
		iToShow - the index of the item to show.
	*/
	showThisHideOpen: function(iToShow){
		if (iToShow != this.previousClick || this.options.alwaysHide){
			this.previousClick = iToShow;
			var objObjs = {};
			var err = false;
			var madeInactive = false;
			this.elements.each(function(el, i){
				this.now[i] = this.now[i] || {};
				if (i != iToShow){
					this.hideThis(i);
				} else if (this.options.alwaysHide){
					if (el.offsetHeight == el.scrollHeight){
						this.hideThis(i);
						madeInactive = true;
					} else if (el.offsetHeight == 0){
						this.showThis(i);
					} else {
						err = true;
					}
				} else if (this.options.wait && this.timer){
					this.previousClick = 'nan';
					err = true;
				} else {
					this.showThis(i);
				}
				objObjs[i+1] = Object.extend(this.h, Object.extend(this.o, this.w));
			}, this);
			if (err) return;
			if (!madeInactive) this.options.onActive.call(this, this.togglers[iToShow], iToShow);
			this.togglers.each(function(tog, i){
				if (i != iToShow || madeInactive) this.options.onBackground.call(this, tog, i);
			}, this);
			return this.custom(objObjs);
		}
	}
});