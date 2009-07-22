{include file="header.tpl"} 
{include file="searchHForm.tpl"} 
	<div class="span-24 column ppalContainer">
		<div class="span-18">
			<div class="span-18 column headerComments">
				<div class="span-15 title_blue">Search results for "{$queryString}"<sub></sub></div>
			</div>
			
			
			{foreach key=name_fk item=result from=$results}
			<div class="span-18 result">
				<div class="span-2 last avatar2"></div>
					<div class="span-16 last">
						<div class="span-11 column">
							<div class="span-11 last title_result"><a href="speciesPage.php?species={$result.name_fk}">{$result.scientific_name}</a></div>
							<div class="span-11 last result_list">
								<ul>
								    <li></li>
  									<li><a href="">Animalia</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Chordata</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Mammalia</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Carnivora</a><img class="lista" src="/images/arrowlist.jpg"></li>
  									<li><a href="">Felidae</a><img class="lista" src="/images/arrowlist.jpg"></li>
   									<li><a href="" class="ultimo">Puma</a></li>
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
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			</div>
		</div>
	</div>
		

	<div class="span-24 last separator40"></div>
{include file="footer.tpl"} 