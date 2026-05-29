define([
    'ko',
    'Study_InventoryFulfillment/js/ko/extenders/numeric',
], function (
    ko
){
    'use strict';
    const boxConfiguration = () => {
        const divisor = 139;
        const data  = {
            length: ko.observable().extend({numeric: true}),
            width: ko.observable().extend({numeric: true}),
            height: ko.observable().extend({numeric: true}),
            weight: ko.observable().extend({numeric: true}),
            unitsPerBox : ko.observable().extend({numeric: true}),
            numberOfBoxes : ko.observable().extend({numeric: true}),
        };
        /* refer for box validation https://www.ups.com/us/en/support/shipping-support/shipping-dimensions-weight.page*/
       /*ko.computed(() => {
           console.log('Dimensions: ', data.length() * data.width() * data.height());
       });
       require('uiRegistry').get('boxConfigurations').boxConfigurationsModel.boxConfigurations()[0].height()
       */

       data.dimensionalWeight = ko.computed(() => {
           let result  = data.length() * data.width() * data.height() / divisor;
           return Math.round(result * data.numberOfBoxes());
       });

       data.totalWeight = ko.computed(() => {
           return data.numberOfBoxes() * data.width() ;
       });

       data.billableWeight = ko.computed(() => {
           return data.totalWeight() > data.dimensionalWeight()
           ? data.totalWeight()
               : data.dimensionalWeight();
       });
        return data;
    };

    return {
        boxConfigurations: ko.observableArray([boxConfiguration()]),
        isSuccess: ko.observable(false),
        numberOfBoxes: function () {
          return  ko.computed(() => {
              return this.boxConfigurations().reduce(function (runningTotal, boxConfiguration) {
                  return runningTotal + (boxConfiguration.numberOfBoxes() || 0);
              }, 0)
          })
        },

        shipmentWeight: function (){
          return ko.computed(() => {
              return this.boxConfigurations().reduce(function (runningTotal, boxConfiguration) {
                  return runningTotal + (boxConfiguration.weight() || 0);
              }, 0)
          })
        },

        billableWeight: function (){
          return ko.computed(() => {
              return this.boxConfigurations().reduce(function (runningTotal, boxConfiguration) {
                  return runningTotal + (boxConfiguration.billableWeight() || 0);
              }, 0)
          })
        },
        add: function () {
            this.boxConfigurations.push(boxConfiguration());
        },
        delete: function (index) {
            this.boxConfigurations.splice(index, 1);
        }
    }
});
