{* {include file="header.tpl"}  *}
{include file="headerGbif.tpl"} 

<div class="span-24 column ppalContainer">
	<div class="span-24">
			<div class="span-24 column headerComments">
				<div class="span-24 title_blue">Species Distribution Repository API</div>
				<div class="textcontainer">	
				<p>The SDR API consist of 2 kinds of services:<br><br></p>
				    <ul>
				        <li><b>Widget/Visualization API:</b> Allows you to embed our visualization widgets on your project page, blog, etc.</li>
    	        		
	        		<li><b>Data API:</b> Access programatically the data from the project.</li>
	        	</ul>
	        	<div class="span-24 title_blue">Widget/Visualization API</div>
	        	<p>You can embed the visualization widget displayed on these pages. For the moment you need to use the 
					species ID provided by GBIF (nub_usage_id) as provided by the <a href="http://ecat-dev.gbif.org/api/clb.php">ECAT services</a>.<br><br>
					The parameter <b>id</b> is always required.<br>
					The parameter <b>source</b> filter only by one specific source (optional)<br>
					The parameter <b>location</b> zooms the map to the specified location as geocoded by Google services (optional)<br>
					<br><br>Examples:</p>
				<ul>
				    <li><b>All sources for a species:</b> <a href="http://{$smarty.server.SERVER_NAME}/widget.php?id=13809813">http://{$smarty.server.SERVER_NAME}/widget.php?id=13809813</a></li> 	        		
	        		<li><b>Only one specific source:</b> <a href="http://{$smarty.server.SERVER_NAME}/widget.php?id=13809813&source=GROMS">http://{$smarty.server.SERVER_NAME}/widget.php?id=13809813&source=GROMS</a></li>
	        		<li><b>Only one specific source in Canada:</b> <a href="http://{$smarty.server.SERVER_NAME}/widget.php?id=13900738&location=Canada&source=CANADENSYS">http://{$smarty.server.SERVER_NAME}/widget.php?id=13900738&location=Canada&source=CANADENSYS</a></li>

	        	</ul>					
	        	<div class="span-24 title_blue">Data API</div>
				<p>All services are available as JSON and AMF. To use the services in AMF please contact <a href="mailto:jatorre@vizzuality.com">jatorre@vizzuality.com</a></p><br><br>
				<ul>
	        	<li><b>getSpeciesDetailsByNameId:</b><br> <a href="{$smarty.const.DATA_URL}json.php/SDRServices.getSpeciesDetailsByNameId/13900738">
		{$smarty.const.DATA_URL}json.php/SDRServices.getSpeciesDetailsByNameId/13900738</a></li>
		    	<li><b>getSpeciesDetailsByNameId (only CANADENSYS):</b><br> <a href="{$smarty.const.DATA_URL}json.php/SDRServices.getSpeciesDetailsByNameId/13900738/CANADENSYS">
		{$smarty.const.DATA_URL}json.php/SDRServices.getSpeciesDetailsByNameId/13900738/CANADENSYS</a></li>	        	

		    	<li><b>getDistributionsBySource (RED_LIST2008):</b><br> <a href="{$smarty.const.DATA_URL}json.php/SDRServices.getDistributionsBySource/RED_LIST2008/0">
		{$smarty.const.DATA_URL}json.php/SDRServices.getDistributionsBySource/RED_LIST2008/0</a></li>	        	

		    	<li><b>getSources:</b><br> <a href="{$smarty.const.DATA_URL}json.php/SDRServices.getSources">
		{$smarty.const.DATA_URL}json.php/SDRServices.getSources</a></li>	        	

		    	<li><b>getDistributionUnitsByLatLng:</b><br> <a href="{$smarty.const.DATA_URL}json.php/SDRServices.getDistributionUnitsByLatLng/30/10">
		{$smarty.const.DATA_URL}json.php/SDRServices.getDistributionUnitsByLatLng/30/10</a></li>

		    	<li><b>getSpeciesDistributionUnitsById:</b><br> <a href="{$smarty.const.DATA_URL}json.php/SDRServices.getSpeciesDistributionUnitsById/13149503">
		{$smarty.const.DATA_URL}json.php/SDRServices.getSpeciesDistributionUnitsById/13149503</a></li>


	        	</ul>
	
				<div class="span-24 title_blue">Named Area Repository</div>
				<p>The Named Area Repository (NAR) is a datastore to keep geometries of areas that are used for describing species distributions. If a source specify that a certain species is present in Spain, NAR can be used to retrieve the geometry for Spain. There are multiple References imported into the Named Area Repository, like ISO2 Country Names, TDWG areas, etc. If you need another reference in order to be able to import your data into SDR, please let us know and we will import it.<br><br>
					
We have an application to visualize the existing sources in NAR. Check it out to see what is existing:<a href="/nar/">Named Area Repository Visualizer</a><br><br>
					
					NAR exposes a set of services too that can be accessed the same way as the previous SDR services.</p><br><br>
							<ul>
				        	<li><b>getReferences:</b><br> <a href="{$smarty.const.DATA_URL}json.php/NARServices.getReferences">
					{$smarty.const.DATA_URL}json.php/NARServices.getReferences</a>: List available imported sources in NAR.</li>
				        	<li><b>getReferenceCodes:</b><br> <a href="{$smarty.const.DATA_URL}json.php/NARServices.getReferenceCodes/iso3">
					{$smarty.const.DATA_URL}json.php/NARServices.getReferenceCodes/iso3</a>: Get all the area codes for certain reference.</li>
				        	<li><b>getNamedAreaDetails:</b><br> <a href="{$smarty.const.DATA_URL}json.php/NARServices.getNamedAreaDetails/iso3/ATG">
					{$smarty.const.DATA_URL}json.php/NARServices.getNamedAreaDetails/iso3/ATG</a>: Get all the details available for a certain area within a reference. It includes a geoJSON element with the geometry.</li>										
					</ul>				
	        	<div class="span-24 title_blue">Examples</div>
				<p>To demonstrate the use of the API we have developed an application that lets you visualize the different
					distributions available from the IUCN Red List (carnivora).</p><br><br>
			</div>
			
			
			
			</div>
		</div>

	</div>
</div>
			
{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"}  