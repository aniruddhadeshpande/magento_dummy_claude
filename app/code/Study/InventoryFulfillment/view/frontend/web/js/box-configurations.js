define([
    'uiComponent',
    'ko',
    'Study_InventoryFulfillment/js/model/box-configurations',
    'Study_InventoryFulfillment/js/model/sku',
    'jquery',
    'mage/validation'
], function(
    Component,
    ko,
    boxConfigurationsModel,
    skuModel,
    $,
    validate
) {
    'use strict';
    return Component.extend({

        defaults: {
          //boxConfigurations: ko.observableArray([boxConfiguration()])
            boxConfigurationsModel: boxConfigurationsModel,
            isSkuValidationSuccess: skuModel.isSuccess, /** on page load consider property not obervables function **/
            /* static data
            * {
                  length: 10,
                  width: 12,
                  height: 14
              }, {
                  length: 13,
                  width: 15,
                  height: 17
              }*/
        },
        initialize() {
            this._super();
            console.log('The boxConfigurations component has been loaded.');
            skuModel.isSuccess.subscribe((value) => {
                console.log('SKU isSuccess new value', value);
            });

            skuModel.isSuccess.subscribe((value) => {
                console.log('SKU isSuccess old value', value);
            }, null, 'beforeChange')
        },
        handleAdd() {
            boxConfigurationsModel.add()
        },
        handleDelete(index) {
            console.log('this', this);
            console.log('index', index);
            console.log('The boxConfigurations component has been deleted.')
            boxConfigurationsModel.delete(index)
        },
        handleSubmit(){
            $('.box-configurations form input').removeAttr('aria-invalid');
            if ($('.box-configurations form').validate()) {
                boxConfigurationsModel.isSuccess(true)
            } else {
                console.log('Box configuration error.');
                boxConfigurationsModel.isSuccess(false)
            }
            console.log('Submitted box configuration form.');
        }
    });
})
