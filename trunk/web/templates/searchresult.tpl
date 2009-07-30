{* {include file="header.tpl"}  *}
{include file="headerGbif.tpl"} 
{include file="searchHForm.tpl"} 
	<div class="span-24 column ppalContainer">
		<div class="span-18">
			<div class="span-18 column headerComments">
				<div class="span-15 title_blue">Search results for "{$queryString}"<sub></sub></div>
			</div>
			
			
			{foreach key=name_fk item=result from=$results}
			<div class="span-18 {cycle values="result,result2"}">
				<div class="span-2 last avatar2"><img src="/flickrImage.php?q={$result.scientific_name|escape:"url"}" width="51" height="52" /></div>
					<div class="span-16 last">
						<div class="span-11 column">
							<div class="span-11 last title_result"><a href="speciesPage.php?id={$result.name_fk}&n={$result.scientific_name|escape:"url"}">{$result.scientific_name}</a></div>
							<div class="span-11 last result_list">
								<ul>
								    <li></li>
  									<li><a href="">Kingdom</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Phylum</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Class</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Order</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Family</a><img class="lista" src="/images/arrowlist.jpg"></li>
   									<li><a href="" class="ultimo">Genus</a></li>
								</ul>
							</div>	
						</div>	
						<div class="span-2 last">
							<div class="span-2 last sources">{$result.num_sources|default:'0'}</div>
							<div class="span-2 last sourcesTitle">source(s)</div>
						</div>		
						<div class="span-3 last">
							<div class="span-3 last ocurrences">{$result.num_occurrences|default:'?'}</div>
							<div class="span-3 last ocurrencesTitle">ocurrences</div>
						</div>
				</div>
			</div>
			{foreachelse}
		        No results
		    {/foreach}
					
		</div>
		
		<div class="span-6 last rightColumn">
			<div class="popular_species">
				<div class="title_blue">Most popular species</div>
			    <div class="separator_small"></div>
			    {foreach key=id item=species from=$popularSpecies}
			        <div class="avatar"><a href="speciesPage.php?id={$species.id}&n={$species.scientific_name|escape:"url"}"><img src="/flickrImage.php?q={$species.scientific_name|escape:"url"}" alt="{$species.scientific_name}" title="{$species.scientific_name}" width="51" height="52"></a></div>
			    {/foreach}

			</div>
		</div>
	</div>
		

	<div class="span-24 last separator40"></div>

{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"}