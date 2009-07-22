{* {include file="header.tpl"}  *}
{include file="headerGbif.tpl"} 

			<div class="span-5 first separator_lr"></div>
			<div class="span-14 centerContainer">
			
				<div class="span-14 searchCont">
					<div class="text1">We are still developing SDR</div>
					<div class="text2">...but you can search for any species in our database</div>
					<div class="span-14 searchContainer">
					    <form id="searchForm" action="searchresult.php" method="GET">
    						<div class="titleContainer"><p>Search</p></div>
    						<div class="inputContainer"><input class="searchText" name="q"></div>
    						<div class="buttonContainer"><div class="searchButton"><a href="#" onClick="$('#searchForm').submit()">Search!</a></div></div>
						</form>
					</div>
					<a href="speciesPage.php?id=13815711&n=Puma+concolor" class="text3">e.g. Puma concolor</a>
				</div>
			
			</div>
			<div class="span-5 last separator_lr"></div>

{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"} 