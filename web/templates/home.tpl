{include file="headerGbif.tpl"} 

			<div class="span-5 first separator_lr"></div>
			<div class="span-14 centerContainer">
			
				<div class="span-14 searchCont">
					<div class="text1">We are still developing SDR</div>
					<div class="text2">...but you can search for any species in our database</div>
					<div class="span-14 searchContainer">
					    <form id="searchForm" action="javascript: void checkSearch();" method="GET">
    						<div class="titleContainer"><p>Search</p></div>
    						<div class="inputContainer"><input id="searchText" class="searchText" name="q"/></div>
    						<div class="buttonContainer"><input type="submit" class="searchButton" id="search" value="Search!"/></div>
						</form>
					</div>
					<div>
						<span class="column first span-6"><p id="lengthError"></p></span>
						<span class="column last span-6 specieExample"><a href="/speciesPage.php?id=13809813&n=Anas%20penelope" class="text3">e.g. Anas penelope</a></span>
					</div>
				</div>
			
			</div>
			<div class="span-5 last separator_lr"></div>

{include file="footerGbif.tpl"} 