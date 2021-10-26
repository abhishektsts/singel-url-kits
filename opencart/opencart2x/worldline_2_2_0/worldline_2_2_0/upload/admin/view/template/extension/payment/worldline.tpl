<?php  echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">

        <button type="submit" form="form-worldline-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>"
           class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
           class="btn btn-default"><i class="fa fa-reply"></i></a></div>

      <h1><?php echo $heading_title; ?></h1>

      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>

    </div>
  </div>

  <div class="container-fluid">
    <?php if (is_array($error_warning) && count($error_warning) > 0) {
    	foreach($error_warning as $error) { ?>
		    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
		      <button type="button" class="close" data-dismiss="alert">&times;</button>
		    </div>
    <?php }} ?>

    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_for_worldline; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>

      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-worldline-checkout" class="form-horizontal">

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-merchant"><?php echo $merchant_code; ?></label>
            <div class="col-sm-10">
              <input type="text" name="worldline_merchant_code" value="<?php echo $worldline_merchant_code; ?>"
                     placeholder="<?php echo $merchant_code; ?>" id="input-merchant-code" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-mode"><?php echo $request_type; ?></label>
            <div class="col-sm-10">
              <select name="worldline_request_type" id="input-mode" class="form-control">
                <?php if ($worldline_request_type == 'T') { ?>
                    <option value="T" selected="selected"><?php echo $request_type_T; ?></option>
                <?php } else { ?>
                    <option value="T"><?php echo $request_type_T; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-key"><?php echo $key; ?></label>
            <div class="col-sm-10">
              <input type="text" name="worldline_key" value="<?php echo $worldline_key; ?>"
                     placeholder="<?php echo $key; ?>" id="input-key" class="form-control" />
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-iv"><?php echo $iv; ?></label>
            <div class="col-sm-10">
              <input type="text" name="worldline_iv" value="<?php echo $worldline_iv; ?>"
                     placeholder="<?php echo $iv; ?>" id="input-iv" class="form-control" />
            </div>
          </div>
		  
		  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-merchant_scheme_code"><?php echo $merchant_scheme_code; ?></label>
            <div class="col-sm-10">
              <input type="text" name="worldline_merchant_scheme_code" value="<?php echo $worldline_merchant_scheme_code; ?>" placeholder="<?php echo $merchant_scheme_code; ?>" id="input-merchant_scheme_code" class="form-control" />
            </div>
          </div>

          <div class="form-group required" id="webservice_locator" >
            <label class="col-sm-2 control-label" for="input-mode"><?php echo $webservice_locator; ?></label>
            <div class="col-sm-10">
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
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $order_status; ?></label>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" data-toggle="tooltip" title="Status when user Confirms Order"
                   for="input-worldline-order-status-confirm"><?php echo $order_status_confirm; ?></label>
            <div class="col-sm-10">
              <select name="worldline_order_status_confirm" id="input-order-status-confirm" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_confirm) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" data-toggle="tooltip" title="Status when Success from Payment Gateway"
                   for="input-worldline-order-status-complete"><?php echo $order_status_complete; ?></label>
            <div class="col-sm-10">
              <select name="worldline_order_status_complete" id="input-order-status-complete" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_complete) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" data-toggle="tooltip" title="Status when Payment Failed"
                   for="input-worldline-order-status-failure"><?php echo $order_status_failure; ?></label>
            <div class="col-sm-10">
              <select name="worldline_order_status_failure" id="input-order-status-failure" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_failure) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" data-toggle="tooltip" title="Status when Payment Canceled by user"
                   for="input-worldline-order-status-cancel"><?php echo $order_status_cancel; ?></label>
            <div class="col-sm-10">
              <select name="worldline_order_status_cancel" id="input-order-status-cancel" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_cancel) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" data-toggle="tooltip" title="Status when Payment is aborted"
                   for="input-worldline-order-status-abort"><?php echo $order_status_abort; ?></label>
            <div class="col-sm-10">
              <select name="worldline_order_status_abort" id="input-order-status-abort" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $worldline_order_status_abort) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>

          <br>
          <br>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $status; ?></label>
            <div class="col-sm-10">
              <select name="worldline_status" id="input-status" class="form-control">
                <?php if ($worldline_status == "1") { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-sort_order"><?php echo $sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="worldline_sort_order" value="<?php echo $worldline_sort_order; ?>" placeholder="<?php echo $sort_order; ?>" id="input-sort_order" class="form-control" />
            </div>
          </div>

          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>