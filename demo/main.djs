

(function(){
	
	import "demo/App.djs";
	import "demo/Observer.djs";
	import "demo/ObserverNbItems.djs";
	
	var app = new demo.App(new demo.ObserverNbItems());
	app.launch();

})();
