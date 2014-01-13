
namespace demo
{
	class Model
	{
		list = [];
		observers = [];
		
		construct()
		{
		
		}
		
		add(item)
		{
			this.list.push(item);
			this.update();
		}
		
		remove(item)
		{
			for(var i in this.list)
			{
				if(this.list[i] == item)
				{
					this.list.splice(i, 1);
				}
			}
			
			this.update();
		}
		
		size()
		{
			return this.list.length;
		}
		
		attachObserver(obs)
		{
			this.observers.push(obs);
		}
		
		update()
		{
			if(this.observers.length > 0)
			{
				for(var i in this.observers)
					this.observers[i].update(this);
			}
		}
	}
}