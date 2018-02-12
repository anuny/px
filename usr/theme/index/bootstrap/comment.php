<?php defined('PX') or die; ?>
{include header} 
<body style="padding-top:70px">
  <div class="container">	
  {foreach $comments $key $val}
	  {if $val['_level']==0}
	    <li><b>#{$val['_root']}<a href="{$val['url']}">{$val['author']}</a></b> {$val['created']}
	  {else}
	    <li style="padding-left:1em"><a href="{$val['url']}">{$val['author']}：@{$val['_author']}</a> {$val['created']}
	  {/if}
	  <p>{$val['content']}</p></li>
     
  {/foreach}
		
  </div>
  {include footer}
</body> 
</html> 
