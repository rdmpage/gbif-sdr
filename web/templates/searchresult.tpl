{* {include file="header.tpl"}  *}
{include file="headerGbif.tpl"} 
{include file="searchHForm.tpl"} 
	<div class="span-24 column ppalContainer">
		<div class="span-18">
			<div class="span-18 column headerComments">
				<div class="span-15 title_blue">Search results for "{$queryString}"<sub></sub></div>
			</div>
			
			
			{foreach key=id item=result from=$results}
				{if $result==null}
					No results							
				{else}			
					<div class="span-18 {cycle values="result,result2"}">
						<div class="span-2 last avatar2">
						    {if $result.imageURL==null}
						        <img src="/images/noPicture.jpg" width="51" height="52" />
						    {else}
						        <img src="{$result.imageURL}" width="51" height="52" />
						    {/if}
						    </div>
							<div class="span-16 last">
								<div class="span-11 column">
									<div class="span-11 last title_result"><a href="speciesPage.php?id={$result.id}&n={$result.name|escape:"url"}">{$result.name}</a></div>
									<div class="span-11 last result_list">
										<ul>
										    <li></li>
		  									<li><a href="">{$result.kingdom}</a><img class="lista" src="/images/arrowlist.jpg"></li>
		  									<li><a href="">{$result.phylum}</a><img class="lista" src="/images/arrowlist.jpg"></li>
		  									<li><a href="">{$result.class}</a><img class="lista" src="/images/arrowlist.jpg"></li>
		  									<li><a href="">{$result.order}</a><img class="lista" src="/images/arrowlist.jpg"></li>
		  									<li><a href="">{$result.family}</a><img class="lista" src="/images/arrowlist.jpg"></li>
		   									<li><a href="" class="ultimo">{$result.genus}</a></li>
										</ul>
									</div>	
								</div>	
								<div class="span-2 last">
									<div class="span-2 last sources">{$result.numResources|default:'0'}</div>
									<div class="span-2 last sourcesTitle">source(s)</div>
								</div>		
								<div class="span-3 last">
									<div class="span-3 last ocurrences">{$result.numOccurrences|default:'?'}</div>
									<div class="span-3 last ocurrencesTitle">ocurrences</div>
								</div>
						</div>
					</div>
				{/if}
			{foreachelse}
		        No results
		    {/foreach}
					
		</div>
		
		<div class="span-6 last rightColumn">
			<div class="popular_species">
				<div class="title_blue">Most popular species</div>
			    <div class="separator_small"></div>
			    {foreach key=nub_usage_id item=spec from=$popularSpecies}
			        <div class="avatar"><a href="speciesPage.php?id={$spec.nub_usage_id}"><img src="/ecatImage.php?id={$spec.nub_usage_id}" width="51" height="52"></a></div>
			    {/foreach}

			</div>
		</div>
	</div>
		

	<div class="span-24 last separator40"></div>

{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"}