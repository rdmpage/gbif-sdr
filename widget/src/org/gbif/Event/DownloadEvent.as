package org.gbif.Event{
	
	import flash.events.Event;

	public class DownloadEvent extends Event{
		
		public var dType:String = new String;
		
		public function DownloadEvent(type:String, dType:String, bubbles:Boolean=false, cancelable:Boolean=false){
			
			super(type, bubbles, cancelable);
		}
		
	}
}