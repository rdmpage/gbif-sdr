package org.vizzuality.event{
	
	import flash.events.Event;

	public class ChangePanelEvent extends Event{
		
		public var nextItem:Number;
		
		public function ChangePanelEvent(type:String, nextItem:Number, bubbles:Boolean=false, cancelable:Boolean=false){
			super(type, bubbles, cancelable);
			this.nextItem = nextItem;
		}
		
	}
}