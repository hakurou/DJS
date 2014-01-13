
import "demo/Model.djs";

namespace demo
{
	class App
	{
		model = null;
		static version = "1.0.0";
		
		construct(obs)
		{
			this.model = new demo.Model();
			this.model.attachObserver(obs);	
		}
		
		launch()
		{
			this.bindEvent();
		}
		
		bindEvent()
		{
			var self = this;
			var node = document.querySelector("#todo_input");
			node.addEventListener("change", function(event){
				self.addItem(event, node);
			});
		}
		
		addItem(event, node)
		{
			var self = this;
			var item = document.createElement("li");
			item.appendChild(document.createTextNode(node.value));
			this.model.add(node.value);
			node.value = "";
			var list = document.querySelector("#todo_list").appendChild(item);
			
			item.addEventListener("click", function(event){
				self.deleteItem(event, item);
			});
		}
		
		deleteItem(event, item)
		{
			this.model.remove(item.innerHTML);
			item.parentNode.removeChild(item);
		}
	}
}