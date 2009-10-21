{include file="headerGbif.tpl"} 

			<div class="span-5 first separator_lr"></div>
			<div class="span-14 centerContainer">
			
				<div class="span-16 searchCont">
					<div class="text1">Welcome to the GBIF Species Distribution Repository</div>
					<div class="text2">Here you can find species range maps from different sources.</div>
					<div class="span-16 searchContainer">
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
				<div class="span-14">
					<div class="text2">Imported sources</div>
					<div class="avatar"><img src="/images/sources/1.jpg"></div>
					<div class="avatar"><img src="/images/sources/28.jpg"></div>
					<div class="avatar"><img src="/images/sources/29.jpg"></div>
					<div class="avatar"><img src="/images/sources/27.jpg"></div>
				</div>	
			</div>
			<div class="span-5 last separator_lr"></div>

{include file="footerGbif.tpl"} 