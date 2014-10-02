	<div id="aff-controls" class="pure-menu pure-menu-open pure-menu-horizontal wpam-nav-menu">
	<ul>
	<?php foreach($this->viewData['navigation'] as $link) { list($linkText, $linkHref) = $link; ?>
		<li><a href="<?php echo $linkHref?>"><?php echo $linkText?></a></li>
	<?php } ?>
	</ul>
	</div>