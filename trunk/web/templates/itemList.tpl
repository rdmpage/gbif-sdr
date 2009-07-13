{foreach from=$data item="entry"}
	<h1>Title: {$entry.title|escape}</h1>
	<h2>Description: {$entry.description|escape}</h2>
	<p>Content: {$entry.content|escape}</p>
	<p>Date: {$entry.pubdate|date_format:"%d/%m/%y"}</p>
	<p><a href="{$entry.link}">Link</a></p>
	<p>Source:{$entry.source|escape}</p>

	<hr />
{foreachelse}
    <p>No hay items</p>
{/foreach}	