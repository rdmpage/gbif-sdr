package org.gbif.maps
{
	import com.google.maps.Copyright;
	import com.google.maps.CopyrightCollection;
	import com.google.maps.LatLng;
	import com.google.maps.LatLngBounds;
	import com.google.maps.TileLayerBase;
	
	import flash.display.DisplayObject;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.geom.Point;
	import flash.net.URLRequest;

	public class CustomWMSTileLayer extends TileLayerBase
	{
		
		private var srvNum:Number=0;
		private var loader:CustomTile;		
		private var MAGIC_NUMBER:Number=6378137.0;
	    private var offset:Number=16777216;
	    private var radius:Number=offset / Math.PI; 		
		private var speciesId:Number;
		private var resource_id:Number;
		private var colorizeColor:Number;
		private var d_type:String;
		
		
		public var ctlo:CustomWMSTileLayerOverlay;
		
		public function CustomWMSTileLayer(speciesId:Number,resource_id:Number,colorizeColor:Number=NaN,_d_type="1")
		{
				this.speciesId=speciesId;
				this.resource_id=resource_id;
				this.colorizeColor= colorizeColor;
				this.d_type= _d_type;
			
			var copyrightCollection:CopyrightCollection = new CopyrightCollection();
			copyrightCollection.addCopyright(new Copyright("ennefox", new LatLngBounds(new LatLng(-180, 90), new LatLng(180, -90)), 21,"ennefox"));			
			super(copyrightCollection, 0, 23,0.7);
			
		}
		
		public override function loadTile(tile:Point,zoom:Number):DisplayObject {
			
			loader = new CustomTile(colorizeColor);
			loader.loader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler,false,0,true);
			loader.loader.contentLoaderInfo.addEventListener(Event.COMPLETE, loaded,false,0,true);
			
			
           	var tileUrl:String;
/* 			if (zoom>zoomChange) {
				tileUrl= dynamicTileServerUrl;					
			} else {
				tileUrl= staticTileServerUrl;	
			}
		
           	srvNum++;
           	if (srvNum>3)
           		srvNum=0;		
           		
           	if (tileUrl.indexOf("|N|")>0)
           		tileUrl = tileUrl.replace("|N|",srvNum); */
           	
           	
           	var zoomLevel:Number = 17 - zoom;
           	var tileIndexLL:Point = new Point(256*tile.x, 256*(tile.y+1));
           	var tileIndexUR:Point = new Point(256*(tile.x+1), 256*(tile.y));
           	var bbox:String;
           	var LL:LatLng = new LatLng(YToL(zoomLevel,tileIndexLL.y),XToL(zoomLevel,tileIndexLL.x));
           	var UR:LatLng = new LatLng(YToL(zoomLevel,tileIndexUR.y),XToL(zoomLevel,tileIndexUR.x));
           	bbox =  dd2MercMetersLng(LL.lngRadians())+","+dd2MercMetersLat(LL.latRadians()) + "," + dd2MercMetersLng(UR.lngRadians())+","+dd2MercMetersLat(UR.latRadians());
          	
           	tileUrl = "/wmsproxy.php?resource_id="+resource_id+"&d_type="+d_type+"&species_id="+speciesId+"&x=|X|&y=|Y|&z=|Z|&bbox=" + bbox;
           	
           	tileUrl = tileUrl.replace("|X|",tile.x);	
           	tileUrl = tileUrl.replace("|Y|",tile.y);	
           	tileUrl = tileUrl.replace("|Z|",zoom);	
           	
           	
           	
           	
            loader.loader.load(new URLRequest(tileUrl));
			ctlo.numRunningRequest++;
			
			
            return loader;           	           		
		}
		
		private function ioErrorHandler(event:IOErrorEvent):void {
			event.currentTarget.removeEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
			ctlo.numRunningRequest--;
		}
		
		private function loaded(event:Event):void {
			event.currentTarget.removeEventListener(Event.COMPLETE, loaded);
			ctlo.numRunningRequest--;
			
		}
		
		private function dd2MercMetersLng(p_lng:Number):Number { 
			return Number((MAGIC_NUMBER * p_lng).toFixed(4)); 
		}
		
		private function dd2MercMetersLat(p_lat:Number):Number {
			if (p_lat >= 85) p_lat=85;
			if (p_lat <= -85) p_lat=-85;
			return Number((MAGIC_NUMBER * Math.log(Math.tan(p_lat / 2 + Math.PI / 4))).toFixed(4));
		}   		
	  
	  //TRANSFORMATION EQUATIONS FROM PIXEL TO LATLON
		private function LToX(z:Number,x:Number):Number {
			return (offset+radius*x*Math.PI/180)>>z;
		}

		private function LToY(z:Number,y:Number):Number {
			return (offset-radius*Math.log((1+Math.sin(y*Math.PI/180))/(1-Math.sin(y*Math.PI/180)))/2)>>z;
		}

		private function XToL(z:Number,x:Number):Number {
        	return (((x<<z)-offset)/radius)*180/Math.PI;
		}

		private function YToL(z:Number,y:Number):Number {
		    return (Math.PI/2-2*Math.atan(Math.exp(((y<<z)-offset)/radius)))*180/Math.PI;
		} 		
		
		
		
	}
}