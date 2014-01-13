
namespace demo
{
	class ObserverNbItems extends demo.Observer
	{
		update(model)
		{
			document.querySelector("#todo_nb_items").innerHTML = "${model.size()} item(s)";
		}
	}
}