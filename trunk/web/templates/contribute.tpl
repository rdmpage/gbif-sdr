{* {include file="header.tpl"}  *}
{include file="headerGbif.tpl"} 

<div class="span-24 column ppalContainer">
	<div class="span-24 column ppalContainer">
		<div class="span-24">
				<div class="span-24 column headerComments">
					<div class="span-24 title_blue">Contribute</div>
					<div class="textcontainer">	
						<p>If you want to see your data in the project you have to contact 
							<a href="mailto:helpdesk@gbif.org">helpdesk@gbif.org</a><br><br></p>
						<p>The project accepts data distributions in the following formats:<br><br></p>
								<ul>
					        	<li><b>Shapefiles:</b> Normally the shapefiles will have one record per distribution_unit 
						(a population or a part of the distribution with a specific status) with a geometry of MULTIPOLYGON. 
						But any kind of format can be considered.<br><br></li>
					        	<li><b>Darwin Core Distribution Extension:</b> by using Named Areas (like counties, countries, 
						TDWG areas, etc.), a Darwin Core Distribution file can be generated attached to a core darwin core document. 
						This kind of format will be imported very quickly. It might be that you need to provide a shapefile with 
						the Named Areas used in the distribution extension, like provinces or states for a specific country 
						that we dont have already improted</b></li>

					        	</ul>	
						<p>Please contact us to analyze your dataset and work with you on the best way to incorporate it<br><br></p>					
					</div>
				</div>
			</div>		
		</div>
	</div>
</div>

{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"} 