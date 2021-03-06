{* {include file="header.tpl"}  *}
{include file="headerGbif.tpl"} 

<script type="text/javascript" src="/js/swfobject.js" ></script>
{include file="searchHForm.tpl"} 

	<div class="span-24 widgetContainer" id="widgetContainer">
		Alternative content...
	</div>	
	
	<div class="span-24 column ppalContainer">
		<div class="span-18">
			<div class="span-18 column headerComments">
				<div class="span-15 title_blue">Comments about this distribution map<!-- <sub>(5)</sub> --></div>
				<!--<div><input class="commentButton" value="Comment now"></div> -->
			</div>
			<ol id="update" class="timeline">
			    {foreach key=id item=comment from=$comments}
			        {if $comment == null}
			        	There isn't comments
			        {else}
				        <div class="span-18 comments">
	                		<div class="span-2 avatar">
	                		</div>
	                		<div class="span-16 contenedor">
	                			<span class="span-16 title_comment">by <span class="title_comment_u last"> {$comment.username} </span> {$comment.created_when} </span>
	                			<div class="span-16 text_comment">{$comment.commenttext}</div>
	                		</div>
	                	</div>
	                {/if}
			    {foreachelse}
			        <div class="span-17 no_result">There are no comments to this species.</div>
			    {/foreach}
                
			</ol>
			<div class="span-18" id="flash" align="left"></div>
			<form method="post" action="#">
				<div id="commentArea" class="span-18 post">
				    {if $username ne ""}
				    	<div class="title_gray">Post your comment now</div>
					    <textarea class="span-17" name="comment" id="comment"></textarea>
					    <input type="button" class="last commentButtonPost" value="Comment now" onclick="commentAction()"/>	
    			    {else}
        				<div class="span-12 title_logout"><a href="#" onclick="$('#login_form').modal();return false;">Login</a> or <a href="/register.php">register</a> to post your comment</div>
    			    {/if}
				</div>
			</form>
		</div>
		
		<div class="span-6 last rightColumn">
	        <div class="span-1 contributors">
				<div class="title_blue">Contributors</div>
				<div class="separator_small"></div>
				{foreach key=id item=source from=$sources}
			        <div class="avatar"><img src="/images/sources/{$source.resource_id}.jpg"></div>
			    {/foreach}    
			</div>
			<div class="separator6"></div>		
			<div class="span-1 species_details">
				<div class="title_blue">Taxonomy</div>
			    <div class="separator_small"></div>
			    <div class="text_comment_right">
			     <ul class="ListSpeciesDetails">
			     {if $kingdom}<li class="Kingdom">Kingdom: <b>{$kingdom}</b></li>{/if}
			     {if $phylum}<li class="Phylum">Phylum: <b>{$phylum}</b></li> {/if}
			     {if $class}<li class="Class">Class: <b>{$class}</b></li> {/if}
			     {if $order}<li class="Order">Order: <b>{$order}</b></li> {/if}
			     {if $family}<li class="Family">Family: <b>{$family}</b></li> {/if}
			     {if $genus}<li class="Genus">Genus: <b>{$genus}</b></li> {/if}
			     {if $scientificName}<li class="Species">Species: <b>{$scientificName}</b></li> {/if}
			     </ul>
			    </div>
			</div>
			<div class="separator6"></div>
			<div class="span-1 related_species">
			<div class="title_blue">Popular Species</div>
			    <div class="separator_small"></div>
			    {foreach key=nub_usage_id item=spec from=$popularSpecies}
			        <div class="avatar"><a href="speciesPage.php?id={$spec.nub_usage_id}"><img src="/ecatImage.php?id={$spec.nub_usage_id}" width="51" height="52"></a></div>
			    {/foreach}
				<a href="" class="view_more">view more</a>
			</div>		
		</div>
	</div>
		

	<div class="span-24 last separator40"></div>

	{literal}
	<script type="text/javascript">


		function commentAction() {

				var comment = $("#comment").val();
			    var dataObj = ({comment : comment,
			        method: 'addComment',
			        speciesId: {/literal}{$speciesId}{literal}
			        });

				if(comment=='') {
			    	alert('Error, comment area empty');
			    } else {
					$("#flash").show();
					$("#flash").fadeIn(400).html('<img src="/images/ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Loading Comment...</span>');
					$.ajax({
						type: "POST",
			 	 		url: "ajaxController.php",
			   			data: dataObj,
			  			cache: false,
			  			success: function(html){
			  				$("ol#update").append(html);
			  				$("ol#update li:last").fadeIn("slow");
			    			document.getElementById('comment').value='';
			  				$("#flash").hide();
			  			}
			 		});
				}
				return false;
		};		
		

		var so = new SWFObject("swf/SDRwidget.swf", "swf", "100%", "500", "9"); 
		so.addParam("allowFullScreen", "true");
		so.addVariable("swf", "");
		so.addVariable("scientificName", "{/literal}{$scientificName}{literal}");
		so.addVariable("geoserverEndPoint", "{/literal}{$smarty.const.GEOSERVER_URL}{literal}");
		so.addVariable("dataEndPoint", "{/literal}{$smarty.const.DATA_URL}{literal}");	
		so.addVariable("wmsProxy", "{/literal}{$smarty.const.WMS_PROXY}{literal}");		
		so.addVariable("ecatServices", "{/literal}{$smarty.const.ECAT_SERVICES}{literal}");	
		{/literal}{if $smarty.get.source neq ""}{literal}
			so.addVariable("source", "{/literal}{$smarty.get.source}{literal}");
		{/literal}{/if}{literal}
		

		
		{/literal}{if $smarty.get.location neq ""}{literal}
			so.addVariable("location", "{/literal}{$smarty.get.location}{literal}");
		{/literal}{/if}{literal}		
		so.addVariable("speciesId", "{/literal}{$speciesId}{literal}");
		so.addVariable("nub_concept_id", "{/literal}{$nub_concept_id}{literal}");
		
		so.addVariable("api_key", "{/literal}{$smarty.const.GMAP_KEY}{literal}");	
		
		so.write("widgetContainer");

	</script>
	{/literal}

{* {include file="footer.tpl"}  *}
{include file="footerGbif.tpl"}
