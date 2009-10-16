package org.vizzuality.v3d
{
	import org.papervision3d.core.proto.MaterialObject3D;
	import org.papervision3d.objects.primitives.Plane;

	public class MapContainer extends Plane{
		
		public var index:Number;
		public var commonName:String;
		public var scientificName:String;
		public var imgURL:String;
		public var nubid:Number;
		
		public function MapContainer(index:Number,nubId:Number,commonName:String,scientificName:String,imgURL:String,material:MaterialObject3D=null, width:Number=0, height:Number=0, segmentsW:Number=0, segmentsH:Number=0)
		{
			this.index = index;
			this.commonName = commonName;
			this.scientificName = scientificName;
			this.imgURL = imgURL;
			this.nubid = nubid;
			super(material, width, height, segmentsW, segmentsH);
		}
		
	}
}