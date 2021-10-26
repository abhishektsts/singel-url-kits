<div class="buttons">
  <div class="right">   
    <a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a>
  </div>
</div>
<script type="text/javascript">
$('#button-confirm').live('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=payment/worldline/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function(response) {
		location = '<?php echo $url; ?>';
		}
	});
});
</script>
