package {
	
	import flash.display.Sprite;
	
	import mx.events.FlexEvent;

	public class CustomIconSprite extends Sprite {
		  
		  [Embed('marker.png')] private var marker:Class;
		  
		  
		  public function CustomIconSprite(name:String="") {
		  	if (name=="") {
			  	addChild(new marker());		  		  	
		  		cacheAsBitmap = true;
		  	} 

		  }
	}
}