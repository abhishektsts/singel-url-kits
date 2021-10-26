define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'worldlinepayment',
                component: 'Worldline_Worldline/js/view/payment/method-renderer/worldlinepayment-method'
            }
        );
        return Component.extend({});
    }
);