{include file="header.tpl"} 
{include file="searchHForm.tpl"} 	

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
				$("#flash").fadeIn(400).html('<img src="ajax-loader.gif" align="absmiddle">&nbsp;<span class="loading">Loading Comment...</span>');
				$.ajax({
					type: "POST",
		 	 		url: "ajaxController.php",
		   			data: dataObj,
		  			cache: false,
		  			success: function(html){
		  				$("ol#update").append(html);
		  				$("ol#update li:last").fadeIn("slow");
		  				/*document.getElementById('email').value='';
		   				document.getElementById('name').value='';*/
		    			document.getElementById('comment').value='';
						/* $("#name").focus(); */
		  				$("#flash").hide();
		  			}
		 		});
			}
			return false;
	};
		
</script>
{/literal}

	<div class="span-24 widgetContainer">
		<img src="/css/images/widget.jpg">
	</div>	
	
	<div class="span-24 column ppalContainer">
		<div class="span-18">
			<div class="span-18 column headerComments">
				<div class="span-15 title_blue">Comments about this distribution map<sub>(5)</sub></div>
				<div><input class="commentButton" value="Comment now"></div>
			</div>
			<ol id="update" class="timeline">
			</ol>
			<div class="span-18" id="flash" align="left"></div>
			<form method="post" action="#">
				<div class="span-18 post">
				    <div class="title_gray">Post your comment now</div>
				    <textarea class="span-17" name="comment" id="comment"></textarea>
				    <input type="button" class="last commentButtonPost" value="Comment now" onclick="commentAction()"/>
				</div>
			</form>
		</div>
		
		<div class="span-6 last rightColumn">
	        <div class="contributors">
					<div class="title_blue">Contributors</div>
				<div class="separator_small"></div>
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
				<a href="" class="view_more">view more</a>
			</div>		
		</div>
	</div>
		

	<div class="span-24 last separator40"></div>

{include file="footer.tpl"} 
