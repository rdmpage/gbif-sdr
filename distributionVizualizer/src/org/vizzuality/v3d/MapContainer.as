package org.vizzuality.v3d
{
	import org.papervision3d.core.proto.MaterialObject3D;
	import org.papervision3d.objects.primitives.Plane;

	public class MapContainer extends Plane
	{
		public function MapContainer(material:MaterialObject3D=null, width:Number=0, height:Number=0, segmentsW:Number=0, segmentsH:Number=0)
		{
			super(material, width, height, segmentsW, segmentsH);
		}
		
	}
}