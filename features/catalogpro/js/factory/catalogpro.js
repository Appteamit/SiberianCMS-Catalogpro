/**
 * catalogpro factory
 */
angular
    .module('starter')
    .factory('Catalogpro', function (Application, Customer, $state, $pwaRequest, $ionicPlatform, $session, Dialog, SB, $ionicPopup, $translate) {
        var factory = {};
        factory.value_id = null;
        factory.category_id = null;
        factory.settings = {};
        factory.categories = {};
         
        factory.setCategories = function (categories) {
            factory.categories = categories;
            return factory;
        };

        factory.getCategories = function () {
            return factory.categories;
        };

        factory.setValueId = function (valueId) {
            factory.value_id = valueId;
            return factory;
        };

        factory.getValueId = function () {
            return factory.value_id;
        };

        factory.findAll = function (refresh) {
            return $pwaRequest.post('catalogpro/mobile_view/find-all', {
                urlParams: {
                    value_id: factory.value_id                   
                },
                refresh: refresh
            });
        };

        factory.loadMoreProducts = function (offset) {
            return $pwaRequest.post('catalogpro/mobile_product/load-products', {
                urlParams: {
                    value_id: factory.value_id,
                    offset: offset                   
                },
                refresh: true
            });
        };

        factory.findAllProducts = function (refresh, offset = 0, category_id = null) {
            return $pwaRequest.post('catalogpro/mobile_product/load-products', {
                urlParams: {
                    value_id: factory.value_id,
                    offset: offset,
                    category_id: category_id                 
                },
                refresh: refresh
            });
        };

        factory.findProductsSearch = function (refresh, offset = 0, search = null) {
            return $pwaRequest.post('catalogpro/mobile_product/load-products', {
                urlParams: {
                    value_id: factory.value_id,
                    offset: offset,
                    search: search                 
                },
                refresh: refresh
            });
        };

        factory.findProductDetailsById = function (product_id) {
            return $pwaRequest.post('catalogpro/mobile_product/product-details', {
                urlParams: {
                    value_id: factory.value_id,
                    product_id: product_id,
                },
                refresh: true
            });
        };
        factory.getComment = function (comment_id) {
            return $pwaRequest.post('catalogpro/mobile_product/get-comment', {
                urlParams: {
                    value_id: factory.value_id,
                    comment_id: comment_id,
                },
                refresh: true
            });
        };

        factory.markAsFavorite = function (product_id) {
            return $pwaRequest.post('catalogpro/mobile_product/mark-as-favorite', {
                urlParams: {
                    value_id: factory.value_id,
                    product_id: product_id,
                },
                refresh: true
            });
        };

        factory.saveRating = function (rating) {
            return $pwaRequest.post('catalogpro/mobile_product/save-rating', {
                urlParams: {
                    value_id: factory.value_id,
                },
                data: rating,
                refresh: true
            });
        };
        factory.submitReport = function (report) {
            return $pwaRequest.post('catalogpro/mobile_product/submit-report', {
                urlParams: {
                    value_id: factory.value_id,
                },
                data: report,
                refresh: true
            });
        };


        factory.findFavoritesProducts = function (refresh, offset = 0) {
            return $pwaRequest.post('catalogpro/mobile_product/load-products-favorites', {
                urlParams: {
                    value_id: factory.value_id,
                    offset: offset,
                },
                refresh: refresh
            });
        };


        
       
        return factory;
    });