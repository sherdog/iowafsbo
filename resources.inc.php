<div id="resourcecontainer">
<ul>
    <? 
	$resouceResults = dbQuery('SELECT * FROM page WHERE page_show_resource = 1');
	while($rec = dbFetchArray($resouceResults)){
	?>
    	<li><a href="<?=$rec['page_name']?>"><?=stripslashes($rec['page_htmltitle'])?></a></li>
    <?
	}
	?>
</ul>
</div>