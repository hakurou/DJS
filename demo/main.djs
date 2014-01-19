

(function(){
	
	import "App.djs";
	import "Observer.djs";
	import "ObserverNbItems.djs";
	
	var app = new demo.App(new demo.ObserverNbItems());
	app.launch();

})();
