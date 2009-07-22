{include file="header.tpl"} 
<script type="text/javascript" src="js/swfobject.js" ></script>
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
			        <div class="span-18 comments">
                		<div class="span-2 avatar">
                		</div>
                		<div class="span-16 contenedor">
                			<span class="span-16 title_comment">by <span class="title_comment_u last"> {$comment.username} </span> {$comment.created_when} </span>
                			<div class="span-16 text_comment">{$comment.commenttext}</div>
                		</div>
                	</div>
			    {foreachelse}
			        <div class="span-18 text_comment">There are no comments to this species.</div>
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
        				<div class="span-12 title_logout">Login or register now for post your comment</div>
    			    {/if}
				</div>
			</form>
		</div>
		
		<div class="span-6 last rightColumn">
	        <div class="contributors">
				<div class="title_blue">Contributors</div>
				<div class="separator_small"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			</div>
			<div class="separator6"></div>		
			<div class="species_details">
				<div class="title_blue">Species Details</div>
			    <div class="separator_small"></div>
			</div>
			<div class="separator6"></div>
			<div class="related_species">
			<div class="title_blue">Related Species</div>
			    <div class="separator_small"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
			    <div class="avatar"><img src="/css/images/avatar1.jpg"></div>
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
		so.addVariable("api_key", "ABQIAAAAtDJGVn6RztUmxjnX5hMzjRTb-vLQlFZmc2N8bgWI8YDPp5FEVBTeJc72_716EfYqx-s8UGt88XqC9w");
		so.addVariable("scientificName", "{/literal}{$scientificName}{literal}");
		so.addVariable("speciesId", "{/literal}{$speciesId}{literal}");
		so.write("widgetContainer");

	</script>
	{/literal}

{include file="footer.tpl"} 