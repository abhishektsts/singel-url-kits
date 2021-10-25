

{capture name=path}
	<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'htmlall':'UTF-8'}" rel="nofollow" title="{l s='Go back to the Checkout' mod='Worldline'}">
	{l s='Checkout' mod='Worldline'}
	{l s='Cards / UPI / Netbanking / Wallets' mod='Worldline'}
{/capture}



{assign var='current_step' value='payment'}

{if isset($api_errors)}
	<div class="errors">
	{foreach $api_errors as $error}
		<div class='alert alert-danger error'>{$error}</div>
	{/foreach}
    </div>
{/if}

<P>Selected Payment Method : <b>{$checkout_label}</b></p>

<form action="{$link->getModuleLink('Worldline', 'request', [], true)|escape:'htmlall':'UTF-8'}" method="post">
{if isset($showPhoneBox)}
	<div style="padding:20px 0px;margin:20px 0;">
		<label>Mobile No.</label>
		<input class='form-controls' type="text" name='mobile' value="{$mobile}">
		<div>
		<input class='btn btn-primary' type='submit' name='updatePhone' value='Update Phone'>
		</div>
	</div>
{else}
	<input type="hidden" name="confirm" value="1" />
		
	<p class="cart_navigation" id="cart_navigation">
		<a href="{$link->getPageLink('order', true)}?step=3" class="button_large">{l s='Other payment methods' mod='Worldline'}</a>
		<input type="submit" value="{l s='Confirm Order' mod='Worldline'}" class="exclusive_large" />
	</p>
{/if}
</form>