<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  
  <?php if (is_array($error_warning) && count($error_warning) > 0) { 
    	foreach($error_warning as $error) { ?>
		    <div class="warning"><?php echo $error; ?></div>
  <?php }} ?>
    
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span><?php echo $merchant_code; ?></td>
            <td><input type="text" name="worldline_merchant_code" value="<?php echo $worldline_merchant_code; ?>" placeholder="<?php echo $merchant_code; ?>" id="input-merchant-code" class="form-control" /></td>
          </tr>    
          
          <tr>
            <td><span class="required">*</span><?php echo $request_type; ?></td>
            <td>
                <select name="worldline_request_type" id="input-mode" class="form-control">
                	<?php if ($worldline_request_type == 'T') { ?>
                	<option value="T" selected="selected"><?php echo $request_type_T; ?></option>
                	<?php } else { ?>
                	<option value="T"><?php echo $request_type_T; ?></option>
                	<?php } ?>              
                </select>
            </td>
          </tr>
          
          <tr>
            <td><span class="required">*</span><?php echo $key; ?></td>
            <td><input type="text" name="worldline_key" value="<?php echo $worldline_key; ?>" placeholder="<?php echo $key; ?>" id="input-key" class="form-control" /></td>
          </tr>
          
          <tr>
            <td><span class="required">*</span><?php echo $iv; ?></td>
            <td><input type="text" name="worldline_iv" value="<?php echo $worldline_iv; ?>" placeholder="<?php echo $iv; ?>" id="input-iv" class="form-control" /></td>
          </tr>
          
          <tr>
            <td><span class="required">*</span><?php echo $webservice_locator; ?></td>
            <td>
                <select name="worldline_webservice_locator" id="input-mode" class="form-control">
	                <?php if ($worldline_webservice_locator == 'Test') { ?>
	                <option value="Test" selected="selected"><?php echo 'TEST'; ?></option>
	                <?php } else { ?>
	                <option value="Test"><?php echo 'TEST'; ?></option>
	                <?php } ?>
	                
	                <?php if ($worldline_webservice_locator == 'Live') { ?>
	                <option value="Live" selected="selected"><?php echo 'LIVE'; ?></option>
	                <?php } else { ?>
	                <option value="Live"><?php echo 'LIVE'; ?></option>
	                <?php } ?>
              	</select>
            </td>
          </tr>
          
  		  <tr>
            <td><?php echo $order_status; ?></td>
          </tr>

          <tr>
            <td><span class="required">*</span><?php echo $order_status_confirm; ?></td>
            <td>
              <select name="worldline_order_status_confirm" id="input-order-status-confirm" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_confirm) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td><span class="required">*</span><?php echo $order_status_complete; ?></td>
            <td>
              <select name="worldline_order_status_complete" id="input-order-status-complete" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_complete) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td><span class="required">*</span><?php echo $order_status_failure; ?></td>
            <td>
              <select name="worldline_order_status_failure" id="input-order-status-failure" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_failure) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td><span class="required">*</span><?php echo $order_status_cancel; ?></td>
            <td>
              <select name="worldline_order_status_cancel" id="input-order-status-cancel" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_cancel) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>

          <tr>
            <td><span class="required">*</span><?php echo $order_status_abort; ?></td>
            <td>
              <select name="worldline_order_status_abort" id="input-order-status-abort" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_abort) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>

          <tr>
          <br>
          <br>
          </tr>

          <tr>
            <td><span class="required">*</span><?php echo $status; ?></td>
            <td>
                <select name="worldline_status" id="input-status" class="form-control">
	                <?php if ($worldline_status == "1") { ?>
	                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	                <option value="0"><?php echo $text_disabled; ?></option>
	                <?php } else { ?>
	                <option value="1"><?php echo $text_enabled; ?></option>
	                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	                <?php } ?>
              	</select>
            </td>
          </tr>
          
          <tr>
            <td><span class="required">*</span><?php echo $sort_order; ?></td>
            <td><input type="text" name="worldline_sort_order" value="<?php echo $worldline_sort_order; ?>" placeholder="<?php echo $sort_order; ?>" id="input-sort_order" class="form-control" /></td>
          </tr>
          
          <tr>
            <td><span class="required">*</span><?php echo $merchant_scheme_code; ?></td>
            <td><input type="text" name="worldline_merchant_scheme_code" value="<?php echo $worldline_merchant_scheme_code; ?>" placeholder="<?php echo $merchant_scheme_code; ?>" id="input-merchant-scheme-code" class="form-control" /></td>
          </tr>
          
        </table>
      </form>
    </div>
  </div>
</div>

<?php echo $footer; ?> 