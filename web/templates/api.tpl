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
				    <li><b>All sources for a species:</b> <a href="http://ec2-174-129-77-94.compute-1.amazonaws.com/widget.php?id=13809813">http://ec2-174-129-77-94.compute-1.amazonaws.com/widget.php?id=13809813</a></li> 	        		
	        		<li><b>Only one specific source:</b> <a href="http://ec2-174-129-77-94.compute-1.amazonaws.com/widget.php?id=13809813&source=GROMS">http://ec2-174-129-77-94.compute-1.amazonaws.com/widget.php?id=13809813&source=GROMS</a></li>
	        		<li><b>Only one specific source in Canada:</b> <a href="http://ec2-174-129-77-94.compute-1.amazonaws.com/widget.php?id=13900738&location=Canada&source=CANADENSYS">http://ec2-174-129-77-94.compute-1.amazonaws.com/widget.php?id=13900738&location=Canada&source=CANADENSYS</a></li>

	        	</ul>					
	        	<div class="span-24 title_blue">Data API</div>
				<p>Some more documentation needed</p><br><br>
				<ul>
	        	<li>getSpeciesDetailsByNameId: <a href="http://ec2-174-129-77-94.compute-1.amazonaws.com/amfphp/json.php/SDRServices.getSpeciesDetailsByNameId/13900738">
		http://ec2-174-129-77-94.compute-1.amazonaws.com/amfphp/json.php/SDRServices.getSpeciesDetailsByNameId/13900738</a></li>
		    	<li>getSpeciesDetailsByNameId (only CANADENSYS): <a href="http://ec2-174-129-77-94.compute-1.amazonaws.com/amfphp/json.php/SDRServices.getSpeciesDetailsByNameId/13900738/CANADENSYS">
		http://ec2-174-129-77-94.compute-1.amazonaws.com/amfphp/json.php/SDRServices.getSpeciesDetailsByNameId/13900738/CANADENSYS</a></li>	        	

		    	<li>getDistributionsBySource (RED_LIST2008): <a href="http://ec2-174-129-77-94.compute-1.amazonaws.com/amfphp/json.php/SDRServices.getDistributionsBySource/RED_LIST2008/0">
		http://ec2-174-129-77-94.compute-1.amazonaws.com/amfphp/json.php/SDRServices.getDistributionsBySource/RED_LIST2008/0</a></li>	        	

	        	</ul>
	        	<div class="span-24 title_blue">Examples</div>
			</div>
			
			
			
			</div>
		</div>

	</div>
</div>
			
{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"}  