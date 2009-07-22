package org.gbif.maps
{
	import com.google.maps.overlays.TileLayerOverlay;
	
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.utils.Timer;

	public class CustomWMSTileLayerOverlay extends TileLayerOverlay
	{
		[Bindable]
		public var numRunningRequest:Number=0;
		
		private var previousNum:Number;
		
		public function CustomWMSTileLayerOverlay(ctlo:CustomWMSTileLayer)
		{
			//numRunningRequest = ctlo.numRunningRequest;
			super(ctlo);
			
			//control that the runningRequest is not getting stalled in a number
			//Seems that some requerst never arrive neither by fault or by loaded
			// so the numRunningRequest can stay forever with 1 or something like this.
			//Google Maps do not provide a way to know when somethign has finished loading.
			initRequestController();
			
			
		}	
		
		public function setAlpha(value:Number):void {
			this.foreground.alpha=value;
		}
		
		private function initRequestController():void {
			var t:Timer = new Timer(8000,0);
			t.addEventListener(TimerEvent.TIMER,checkRequests,false,0,true);
			t.start();
		}	
		
		
		private function checkRequests(event:Event):void {
			if (previousNum == numRunningRequest) {
				previousNum = 0;
				numRunningRequest = 0;
			} else {
				previousNum = numRunningRequest
			}
		}
	}
}