package com.vizzuality.map
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
		private var referenceCode:String;
		
		
		
		public function CustomWMSTileLayer(referenceCode:String)
		{
			this.referenceCode=referenceCode;	
			
			var copyrightCollection:CopyrightCollection = new CopyrightCollection();
			copyrightCollection.addCopyright(new Copyright("ennefox", new LatLngBounds(new LatLng(-180, 90), new LatLng(180, -90)), 21,"ennefox"));			
			super(copyrightCollection, 0, 23,0.7);
			
		}
		
		public override function loadTile(tile:Point,zoom:Number):DisplayObject {
			
			loader = new CustomTile();
			
			
           	var tileUrl:String;           	
           	
           	var zoomLevel:Number = 17 - zoom;
           	var tileIndexLL:Point = new Point(256*tile.x, 256*(tile.y+1));
           	var tileIndexUR:Point = new Point(256*(tile.x+1), 256*(tile.y));
           	var bbox:String;
           	var LL:LatLng = new LatLng(YToL(zoomLevel,tileIndexLL.y),XToL(zoomLevel,tileIndexLL.x));
           	var UR:LatLng = new LatLng(YToL(zoomLevel,tileIndexUR.y),XToL(zoomLevel,tileIndexUR.x));
           	bbox =  dd2MercMetersLng(LL.lngRadians())+","+dd2MercMetersLat(LL.latRadians()) + "," + dd2MercMetersLng(UR.lngRadians())+","+dd2MercMetersLat(UR.latRadians());
          	
           	tileUrl = "http://ec2-174-129-85-138.compute-1.amazonaws.com:8080/geoserver/wms?transparent=true&WIDTH=256&SRS=EPSG%3A900913&LAYERS=groms%3Anamed_areas&HEIGHT=256&STYLES=&FORMAT=image%2Fpng&TILED=true&TILESORIGIN=-180%2C-90&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&EXCEPTIONS=application%2Fvnd.ogc.se_inimage&CQL_FILTER=code%3D%27"+referenceCode+"%27%20&bbox=" + bbox;           	
           	
           	
            loader.loader.load(new URLRequest(tileUrl));
			
			
            return loader;           	           		
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