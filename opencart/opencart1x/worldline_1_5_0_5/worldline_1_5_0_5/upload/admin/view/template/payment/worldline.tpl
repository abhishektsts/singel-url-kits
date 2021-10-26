<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
          <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
          <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></a></span></div>
        </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
		
            <tr>
				<td><span class="required">*</span> <?php echo $merchant_code; ?></td>
				<td><input type="text" name="worldline_merchant_code" value="<?php echo $worldline_merchant_code; ?>"   	placeholder="<?php echo $merchant_code; ?>"  />
					<?php if ($error_merchant_code) { ?>
					<span class="error"><?php echo $error_merchant_code; ?></span>
					<?php } ?>
				</td>
            </tr>
        
            <tr>
				<td><span class="required">*</span><?php echo $request_type; ?></td>
				<td><select name="worldline_request_type" id="input-mode">
					<?php if ($worldline_request_type == 'T') { ?>
					<option value="T" selected="selected"><?php echo $request_type_T; ?></option>
					<?php } else { ?>
					<option value="T"><?php echo $request_type_T; ?></option>
					<?php } ?>              
				    </select>
				    <?php if ($error_request_type) { ?>
				    <span class="error"><?php echo $error_request_type; ?></span>
				    <?php } ?>
				</td>
            </tr>
		  
        <tr>
            <td><span class="required">*</span> <?php echo $key; ?></td>
            <td><input type="text" name="worldline_key" value="<?php echo $worldline_key; ?>" placeholder="<?php echo $key; ?>" id="input-key"/>
				<?php if ($error_key) { ?>
				<span class="error"><?php echo $error_key; ?></span>
				<?php } ?>
			</td>
		</tr>
		
        <tr>
            <td><span class="required">*</span> <?php echo $iv; ?></td>
            <td><input type="text" name="worldline_iv" value="<?php echo $worldline_iv; ?>" placeholder="<?php echo $iv; ?>" id="input-iv"/>
				<?php if ($error_iv) { ?>
				<span class="error"><?php echo $error_iv; ?></span>
				<?php } ?>
			</td>
        </tr>
		  
        <tr>
            <td><span class="required">*</span><?php echo $webservice_locator; ?></td>
            <td><select name="worldline_webservice_locator" id="input-mode">
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
				<?php if ($error_webservice_locator) { ?>
                <span class="error"><?php echo $error_webservice_locator; ?></span>
                <?php } ?>
			</td>
        </tr>
		
		<tr>
            <td><span class="required">*</span><?php echo $order_status; ?></td>
            <td><select name="worldline_order_status" id="input-order-status"     >
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
                </select>
			    <?php if ($error_order_status) { ?>
                <span class="error"><?php echo $error_order_status; ?></span>
                <?php } ?>
			</td>
        </tr>
		
		<tr>
            <td><?php echo $status; ?></td>
            <td><select name="worldline_status" id="input-status">
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
            <td><span class="required">*</span> <?php echo $sort_order; ?></td>
            <td><input type="text" name="worldline_sort_order" value="<?php echo $worldline_sort_order; ?>" placeholder="<?php echo $sort_order; ?>" id="input-sort_order"/>
				<?php if ($error_sort_order) { ?>
                <span class="error"><?php echo $error_sort_order; ?></span>
                <?php } ?>
			</td>
        </tr>
	    
	    <tr>
            <td><span class="required">*</span> <?php echo $merchant_scheme_code; ?></td>
            <td><input type="text" name="worldline_merchant_scheme_code" value="<?php echo $worldline_merchant_scheme_code; ?>" placeholder="<?php echo $merchant_scheme_code; ?>" id="input-merchant_scheme_code"  />
                <?php if ($error_merchant_scheme_code) { ?>
                <span class="error"><?php echo $error_merchant_scheme_code; ?></span>
                <?php } ?>
             </td>
        </tr>
        
		</table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 