if( typeof toto == "undefined")
	var toto = {};

if( typeof toto.tata == "undefined")
	toto.tata = {};
(function(__namespace__) {
	toto.tata.test = "hello";
	console.log("in");

	toto.tata.Test = (function() {

		function Test() {

			console.log("in construct");

		}

		return Test;
	})();
	toto.tata.tata = function() {
	}
	if( typeof bizarre == "undefined")
		var bizarre = {};

	if( typeof bizarre.chelou == "undefined")
		bizarre.chelou = {};
	(function(__namespace__) {

	})(toto.tata.bizarre.chelou);

})(toto.tata);

var __hasProp = {}.hasOwnProperty;
var __extends = function(child, parent) {
	for(var key in parent) {
		if(__hasProp.call(parent, key))
			child[key] = parent[key];
	}
	function ctor() {
		this.constructor = child;
	}


	ctor.prototype = parent.prototype;
	child.prototype = new ctor();
	child.__super__ = parent.prototype;
	return child;
};
var Test2 = (function() {

	__extends(Test2, toto.tata.Test);

	function Test2() {

		return Test2.__super__.constructor.apply(this, arguments);

	}

	return Test2;
})();
console.log("out");
var e = new Test2();
