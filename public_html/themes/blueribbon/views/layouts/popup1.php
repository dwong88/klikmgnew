<?php $this->beginContent('//layouts/mainpopup'); ?>
<div id="popup-container">
	<div id="popup-title">
		<?php echo $this->_popuptitle; ?>
	</div>
	<div id="popup-content">
		<?php echo $content; ?>
	</div>
</div>
<?php $this->endContent(); ?>