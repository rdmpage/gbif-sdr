package org.gbif.Event{
	
	import flash.events.Event;

	public class DownloadEvent extends Event{
		
		public var dType:String;
		
		public function DownloadEvent(type:String, _dType:String, bubbles:Boolean=true, cancelable:Boolean=false){
			dType=_dType;	
			super(type, bubbles, cancelable);
		}
		
	}
}