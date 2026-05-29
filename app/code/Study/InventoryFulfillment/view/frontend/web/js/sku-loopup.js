define(
    [
        'uiComponent',
        'ko',
        'mage/storage',
        'jquery',
        'mage/translate',
        'Study_InventoryFulfillment/js/model/sku',
    ], function(
        Component,
        ko,
        storage,
        $,
        $t,
        skuModel
    ) {
    'use strict';

    return Component.extend({

        defaults: {
           // sku: '<em>ABC' + (1 + 2 + 3) + '</em>'
            sku: skuModel.sku,
            placeholder: $t('Example: %1').replace('%1', '24-MB01'),
            messageResponse: ko.observable(''),
            isSuccess: skuModel.isSuccess,
        },
        initialize(){
            this._super();
            console.log('The skuLookup component has been loaded.');
        },

        handleSubmit: function(){
            this.messageResponse('');
            this.isSuccess(false);
            $('body').trigger('processStart');
            storage.get(`rest/V1/products/${this.sku()}`)
                .done(response => {
                    this.messageResponse($t('Product found! %1').replace('%1', `<strong>${response.name}</strong>`));
                    this.isSuccess(true);
                })
                .fail(() => {
                    this.messageResponse($t('Product not found.'));
                    this.isSuccess(false);
                })
                .always(() => {
                    $('body').trigger('processStop');
                })
            console.log(this.sku() +' SKU Confirmed.');
        }
    });
})
