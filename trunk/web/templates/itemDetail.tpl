<h1>Title: {$data.title|escape}</h1>
<h2>Description: {$data.description|escape}</h2>
<p>Content: {$data.content|escape}</p>
<p>Date: {$data.pubdate|date_format:"%d/%m/%y"}</p>
<p><a href="{$data.link}">Link</a></p>
<p>Source:{$data.source|escape}</p>