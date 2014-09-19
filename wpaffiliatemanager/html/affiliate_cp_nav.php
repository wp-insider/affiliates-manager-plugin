	<div id="aff-controls" class="affiliate-cp-subnav">
	<ul>
	<?php foreach($this->viewData['navigation'] as $link) { list($linkText, $linkHref) = $link; ?>
		<li><a href="<?php echo $linkHref?>"><?php echo $linkText?></a></li>
	<?php } ?>
	</ul>
	</div>