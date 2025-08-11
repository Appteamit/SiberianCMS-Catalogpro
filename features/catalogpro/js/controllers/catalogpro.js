/**
 * catalogpro Home version 1 controllers
 */
angular.module('starter')
    .controller('CatalogproHomeController', function (Dialog, Loader, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro, $ionicHistory, $timeout) {
        angular.extend(this, $controller('CatalogproProductsFunctionsController', {
            Dialog: Dialog,
            $rootScope: $rootScope,
            $scope: $scope,
            $stateParams: $stateParams
        }));
        $scope.custom_filter = {
            'product_id':'',
            'category_id':''
        };
        $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
        $scope.is_loading = false;
        $scope.payout = {};
        $scope.settings = Catalogpro.settings;
        $scope.use_pull_refresh = true;
        $scope.pull_to_refresh = false;

        $scope.loadContent = function (pullToRefresh) {
            $scope.is_loading = true;
            Catalogpro.findAll(pullToRefresh).success(function (data) {
                $scope.page_title = data.page_title;
                $scope.settings = Catalogpro.settings = data.settings;
                $scope.payout = data;
                Catalogpro.setCategories(data.categories);
                $scope.categories = Catalogpro.getCategories();
                $scope.categories = data.categories;
                console.log('-----',data.categories,'-------');
                $scope.can_load_older_items = !!data.products.length;
                $scope.is_loading = false;

                if ($scope.pull_to_refresh) {
                    $scope.$broadcast('scroll.refreshComplete');
                    $scope.pull_to_refresh = false;
                }
            }, function (error) {
                $scope.is_loading = false;
                Dialog.alert($translate.instant("Error", "catalogpro"), error.message, $translate.instant("OK") , -1);
            });
            
        };

     $scope.loadContent();

    $scope.pullToRefresh = function () {
        $scope.pull_to_refresh = true;
        $scope.loadContent(true);
    };

   $scope.loadMoreProducts = function() {
        Catalogpro.loadMoreProducts($scope.payout.products.length).success(function (data) {
            $scope.can_load_older_items = !!data.products.length;
            $scope.payout.products = $scope.payout.products.concat(data.products);
            $rootScope.$broadcast("refreshPageSize");        
        }).error(function (error) {
            $scope.can_load_older_items = false; 
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        }).finally(function () {          
            $scope.$broadcast('scroll.infiniteScrollComplete');
        });
    }


}).controller('CatalogproProductsController', function (Dialog, Loader, $timeout, $ionicSideMenuDelegate, $ionicHistory, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro) {
         angular.extend(this, $controller('CatalogproProductsFunctionsController', {
            Dialog: Dialog,
            $rootScope: $rootScope,
            $scope: $scope,
            $stateParams: $stateParams
         }));

        $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
        $scope.category_id = Catalogpro.category_id = $stateParams.category_id;
        $scope.is_scan = $stateParams.is_scan;
        $scope.is_loading = false;
        $scope.payout = {
            products: {}
        };
        $scope.settings = Catalogpro.settings; console.log('settingsssss',  $scope.settings);
        $scope.use_pull_refresh = true;
        $scope.pull_to_refresh = false;
        $scope.page_title = "Filter Products";
        $ionicSideMenuDelegate.canDragContent(true);
        $scope.custom_filter = {
            'product_id':'',
            'category_id':''
        };
        $scope.loadContent = function (pullToRefresh) {
            $scope.custom_filter.category_id = $stateParams.category_id;
            $scope.categories = Catalogpro.getCategories();
            $scope.is_loading = true;          
            $scope.payout.products = {};
            Catalogpro.findAllProducts(pullToRefresh, $scope.payout.products.length, $scope.category_id).success(function (data) {
                $scope.can_load_older_items = !!data.products.length;
                $scope.payout = data;
                $scope.settings = Catalogpro.settings = data.settings;
                $scope.is_loading = false;
                if ($scope.pull_to_refresh) {
                    $scope.$broadcast('scroll.refreshComplete');
                    $scope.pull_to_refresh = false;
                }

            }, function (error) {
                $scope.is_loading = false;
                Dialog.alert($translate.instant("Error", "catalogpro"), error.message, $translate.instant("OK") , -1);
            });
        };

     
        $scope.checkLoading = function(){ 
           if($scope.is_scan == 1){
             Loader.show();
                $timeout( function(){ 
                    $ionicHistory.nextViewOptions({
                                historyRoot: true,
                                disableAnimate: false
                        });

                    $state
                        .go('home')
                        .then(function () {
                             $state.go("catalogpro-home", { value_id: $scope.value_id }, { reload: true })
                                    .then(function () {
                                            Loader.hide();
                                            $state.go("catalogpro-products", { value_id: $scope.value_id, category_id: $scope.category_id}, { reload: true });
                                    });
                        });
                }, 3000 );
            }else{
                $scope.loadContent(false);
            }
        }  

        $scope.checkLoading();

        $scope.pullToRefresh = function () {
            $scope.pull_to_refresh = true;
            $scope.loadContent(true);
        };

       $scope.loadMoreProducts = function() {
           Catalogpro.findAllProducts(false, $scope.payout.products.length, $scope.category_id).success(function (data) {
                $scope.can_load_older_items = !!data.products.length;
                $scope.payout.products = $scope.payout.products.concat(data.products);
                $rootScope.$broadcast("refreshPageSize");        
            }).error(function (error) {
                $scope.can_load_older_items = false; 
                Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
            }).finally(function () {          
                $scope.$broadcast('scroll.infiniteScrollComplete');
            });
        }



}).controller('CatalogproProductsFunctionsController', function (Dialog, Loader, $filter, Customer, $ionicSlideBoxDelegate, $timeout, $ionicModal, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro) {
    $scope.currentCustomerId = Customer.id;
    $scope.starArray = [1, 2, 3, 4, 5];
    $scope.starSelectedValues = 0;


  /**
     *catalogpro Image 
     */
    $scope.catalogproImage = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/application' + image;
        } else {
            return "./features/catalogpro/assets/media/default-image.png"
        }
    };


    /**
     *catalogpro Image 
     */
    $scope.catalogproProductImage = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/application' + image;
        } else {
            return "./features/catalogpro/assets/media/no-product-image.png"
        }
    }; 


    /**
     * Filter options item
     */
    $scope.homeProductFilterMenu = function() {
        $scope.custom_filter.category_id = $stateParams.category_id;
        $scope.categories = Catalogpro.getCategories();
    };
   /**
     * close edit item
     */
    $scope.closehomeProductFilterMenu = function (){
        // $scope.productFilterMenuModal.remove();
    }

    /**
     *go to product by category Image 
     */
    $scope.custom_filter = {
        'product_id':'',
        'category_id':''
    };
    $scope.goToProductByCategory = function (category_id) {   
        Loader.show();
        $scope.closehomeProductFilterMenu();
        $timeout( function(){
            Loader.hide();
            $state.go("catalogpro-products", { value_id: $scope.value_id, category_id: category_id  }, { reload: true });
        }, 1500 ); 
    };

    /**
    * Filter options item
    */
    $scope.productDetailsById = function(product_id) {
        $state.go("catalogpro-product_details", { value_id: $scope.value_id,product_id:product_id});
    };
   
   /**
     * close edit item
     */
    $scope.closeProductDetailsModal = function (){
        $scope.productDetailsModal.remove();
    }

    $scope.checkInAppLinks = function ($event) { 
       // A links
        if ($event.target.attributes.hasOwnProperty('data-state')) {
             console.log('data-state');
             $scope.closeProductDetailsModal();
        }          
    };


    /**
     * Mark as Favorite
     */
    $scope.markAsFavorite = function(product_id) {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }

        Loader.show();
        Catalogpro.markAsFavorite(product_id).success(function (data) {
            $scope.product_details.product.is_favorite = data.is_favorite;
            Loader.hide();
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }


 /**
    *add rating and review
    */
    $scope.addRatingAndReview = function(product_id) {
        
        $scope.rating = {
            product_id: product_id,
            rating_number : 0,
            comment_text: ''
        };
        
        $ionicModal.fromTemplateUrl('features/catalogpro/assets/templates/l1/modal/add_comment.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) { 
            $scope.addRatingAndReviewModal = modal;
            $scope.addRatingAndReviewModal.show();
        });
    };
   
   /**
     * close edit rating
     */
    $scope.closeAddRatingAndReview = function (){
        $scope.addRatingAndReviewModal.remove();
    }

    $scope.starSelected = function(starSelectedValues){
        $scope.rating.rating_number= starSelectedValues;
        $scope.starSelectedValues = starSelectedValues;
    }


    /**
     * save Rating
     */
    $scope.saveRating = function() {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }

        Loader.show();
        Catalogpro.saveRating($scope.rating).success(function (data) {
            $scope.closeAddRatingAndReview();
            $scope.closeProductDetailsModal();
            $scope.productDetailsById($scope.rating.product_id);
            Dialog.alert($translate.instant("Thank you", "catalogpro") ,$translate.instant("Your review has been submitted successfully!", "catalogpro") , "OK", -1, "catalogpro");         
      
            Loader.hide();
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }

    /**
     *   date convert
     */
    $scope.convertDate = function (date) {
          return $filter("moment_calendar")( (new Date(date)).getTime());
    };

      /**
     *Customer avatar
     */
    $scope.customer_avatar = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/customer' + image;
        } else {
            return "./features/catalogpro/assets/media/customer-placeholder.png"
        }
    }; 
    
}).controller('CatalogproProductsSearchController', function (Dialog, Loader, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro) {
         angular.extend(this, $controller('CatalogproProductsFunctionsController', {
            Dialog: Dialog,
            $rootScope: $rootScope,
            $scope: $scope,
            $stateParams: $stateParams
         }));

        $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
        $scope.is_loading = false;
        $scope.payout = {
            products: {},
            search_text: ''
        };
        $scope.settings = Catalogpro.settings;
        $scope.use_pull_refresh = true;
        $scope.pull_to_refresh = false;
        $scope.page_title = $translate.instant("Search Product", "catalogpro");
     
        $scope.loadSearchContent = function (pullToRefresh = true) {
            $scope.payout.search_text = document.getElementById("productSearch").value;  
            
            if($scope.payout.search_text == '') {
                $scope.payout.products = {};
                if ($scope.pull_to_refresh) {
                    $scope.$broadcast('scroll.refreshComplete');
                    $scope.pull_to_refresh = false;
                }
                return false;
            }

            $scope.is_loading = true;          
            $scope.payout.products = {};
            Catalogpro.findProductsSearch(pullToRefresh, $scope.payout.products.length, $scope.payout.search_text).success(function (data) {
                $scope.can_load_older_items = !!data.products.length;
                $scope.payout = data;
                $scope.is_loading = false;
                if ($scope.pull_to_refresh) {
                    $scope.$broadcast('scroll.refreshComplete');
                    $scope.pull_to_refresh = false;
                }

            }, function (error) {
                $scope.pull_to_refresh = false;
                $scope.is_loading = false;
                Dialog.alert($translate.instant("Error", "catalogpro"), error.message, $translate.instant("OK") , -1);
            });
        };

        
        $scope.pullToRefresh = function () {
            $scope.pull_to_refresh = true;
            $scope.loadSearchContent(true);
        };

        $scope.loadMoreProducts = function() {
           $scope.payout.search_text = document.getElementById("productSearch").value;  
           
           Catalogpro.findProductsSearch(false, $scope.payout.products.length, $scope.payout.search_text).success(function (data) {
                $scope.can_load_older_items = !!data.products.length;
                $scope.payout.products = $scope.payout.products.concat(data.products);
                $rootScope.$broadcast("refreshPageSize");        
            }).error(function (error) {
                $scope.can_load_older_items = false; 
                Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
            }).finally(function () {          
                $scope.$broadcast('scroll.infiniteScrollComplete');
            });
        }



}).controller('CatalogproProductFavoritesController', function (Dialog, Loader, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro) {
         angular.extend(this, $controller('CatalogproProductsFunctionsController', {
            Dialog: Dialog,
            $rootScope: $rootScope,
            $scope: $scope,
            $stateParams: $stateParams
         }));

        $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
        $scope.is_loading = false;
        $scope.payout = {
            products: {}
        };
        $scope.settings = Catalogpro.settings;
        $scope.use_pull_refresh = true;
        $scope.pull_to_refresh = false;
        $scope.page_title = "Filter Products";
       
        $scope.loadContent = function (pullToRefresh) {
            if(!Customer.isLoggedIn()){
               Customer.loginModal($scope);
               return false;
            }

            $scope.is_loading = true;          
            $scope.payout.products = {};
            Catalogpro.findFavoritesProducts(pullToRefresh, $scope.payout.products.length).success(function (data) {
                $scope.can_load_older_items = !!data.products.length;
                $scope.payout = data;
                $scope.is_loading = false;
                if ($scope.pull_to_refresh) {
                    $scope.$broadcast('scroll.refreshComplete');
                    $scope.pull_to_refresh = false;
                }

            }, function (error) {
                $scope.is_loading = false;
                Dialog.alert($translate.instant("Error", "catalogpro"), error.message, $translate.instant("OK") , -1);
            });
        };

        $scope.loadContent(false);

        $scope.pullToRefresh = function () {
            $scope.pull_to_refresh = true;
            $scope.loadContent(true);
        };

       $scope.loadMoreProducts = function() {
           Catalogpro.findFavoritesProducts(false, $scope.payout.products.length).success(function (data) {
                $scope.can_load_older_items = !!data.products.length;
                $scope.payout.products = $scope.payout.products.concat(data.products);
                $rootScope.$broadcast("refreshPageSize");        
            }).error(function (error) {
                $scope.can_load_older_items = false; 
                Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
            }).finally(function () {          
                $scope.$broadcast('scroll.infiniteScrollComplete');
            });
        }
}).controller('CatalogproProductDetailsController', function (Dialog, Loader, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro, $timeout, $ionicModal, $filter, $ionicPopup) {
        angular.extend(this, $controller('CatalogproProductsFunctionsController', {
        Dialog: Dialog,
        $rootScope: $rootScope,
        $scope: $scope,
        $stateParams: $stateParams
    }));

    $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
    $scope.is_loading = false;
    $scope.product_details = {};
    $scope.payout = {
        products: {}
    };
    
    $scope.settings = Catalogpro.settings;
    $scope.use_pull_refresh = true;
    $scope.pull_to_refresh = false;
    $scope.page_title = "Filter Products";
    $scope.currentCustomerId = Customer.id;
    $scope.starArray = [1, 2, 3, 4, 5];
    $scope.starSelectedValues = 0;
    $scope.report = function(comment_id){
        $state.go("catalogpro-report", { value_id: $scope.value_id,comment_id:comment_id, product_id:$stateParams.product_id});
    };

    $scope.loadContent = function (pullToRefresh) {
        if(!Customer.isLoggedIn()){
            Customer.loginModal($scope);
            return false;
        }
        $scope.is_loading = true;          
        Catalogpro.findProductDetailsById($stateParams.product_id).success(function (data) {
            $timeout( function(){
                $scope.product_details  = data;
                Loader.hide();
                $scope.is_loading = false;
            }, 300 ); 
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    };

    $scope.showPopup = function(image, title) {
        image = $scope.catalogproImage(image);
        $ionicPopup.show({
            template: '<img src="' + image + '" style="max-width:100%;max-height:100%;">',
            title: title,
            scope: $scope,
            buttons: [
                {
                text: $translate.instant("Close", "catalogpro"),
                type: 'button-positive'
                }
            ]
        });
   };
      /**
     *catalogpro Image 
     */
    $scope.catalogproImage = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/application' + image;
        } else {
            return "./features/catalogpro/assets/media/default-image.png"
        }
    };


    /**
     *catalogpro Image 
     */
    $scope.catalogproProductImage = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/application' + image;
        } else {
            return "./features/catalogpro/assets/media/no-product-image.png"
        }
    }; 


    /**
     * Filter options item
     */
    $scope.homeProductFilterMenu = function() {
        Loader.show();
        $ionicModal.fromTemplateUrl('features/catalogpro/assets/templates/l1/modal/filter_menu.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {   
            $scope.categories = Catalogpro.getCategories();
            $scope.productFilterMenuModal = modal;
            $scope.productFilterMenuModal.show();
            Loader.hide();
        });
    };
   /**
     * close edit item
     */
    $scope.closehomeProductFilterMenu = function (){
        $scope.productFilterMenuModal.remove();
    }

    /**
     *go to product by category Image 
     */
    $scope.goToProductByCategory = function (category_id) {   
        
    };
    $scope.productDetailsById = function(product_id) {
        $state.go("catalogpro-product_details", { value_id: $scope.value_id,product_id:product_id});
    };
    $scope.closeProductDetailsModal = function (){
    }
    $scope.checkInAppLinks = function ($event) { 
       // A links
        if ($event.target.attributes.hasOwnProperty('data-state')) {
        }          
    };


    /**
     * Mark as Favorite
     */
    $scope.markAsFavorite = function(product_id) {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }

        Loader.show();
        Catalogpro.markAsFavorite(product_id).success(function (data) {
            $scope.product_details.product.is_favorite = data.is_favorite;
            Loader.hide();
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }


 /**
    *add rating and review
    */
    $scope.addRatingAndReview = function(product_id) {
        $state.go("catalogpro-rating", { value_id: $scope.value_id,product_id:product_id});
    };
   
   /**
     * close edit rating
     */
    $scope.closeAddRatingAndReview = function (){
        $scope.addRatingAndReviewModal.remove();
    }

    $scope.starSelected = function(starSelectedValues){
        $scope.rating.rating_number= starSelectedValues;
        $scope.starSelectedValues = starSelectedValues;
    }


    /**
     * save Rating
     */
    $scope.saveRating = function() {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }
        Loader.show();
        Catalogpro.saveRating($scope.rating).success(function (data) {
            Dialog.alert($translate.instant("Thank you", "catalogpro") ,$translate.instant("Your review has been submitted successfully!", "catalogpro") , "OK", -1, "catalogpro");         
            Loader.hide();
            $state.go("catalogpro-product_details", { value_id: $scope.value_id,product_id:$stateParams.product_id});
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }

    /**
     *   date convert
     */
    $scope.convertDate = function (date) {
          return $filter("moment_calendar")( (new Date(date)).getTime());
    };

      /**
     *Customer avatar
     */
    $scope.customer_avatar = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/customer' + image;
        } else {
            return "./features/catalogpro/assets/media/customer-placeholder.png"
        }
    }; 
    $scope.loadContent(false);
}).controller('CatalogproRatingController', function (Dialog, Loader, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro, $timeout, $ionicModal, $filter) {
        angular.extend(this, $controller('CatalogproProductsFunctionsController', {
        Dialog: Dialog,
        $rootScope: $rootScope,
        $scope: $scope,
        $stateParams: $stateParams
        }));

    $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
    $scope.is_loading = false;
    $scope.product_details = {};
    $scope.payout = {
        products: {}
    };
    
    $scope.settings = Catalogpro.settings;
    $scope.use_pull_refresh = true;
    $scope.pull_to_refresh = false;
    $scope.page_title = "Filter Products";
    $scope.currentCustomerId = Customer.id;
    $scope.starArray = [1, 2, 3, 4, 5];
    $scope.starSelectedValues = 0;
    $scope.loadContent = function (pullToRefresh) {
        $scope.rating = {
            product_id: $stateParams.product_id,
            rating_number : 0,
            comment_text: ''
        };
    };
      /**
     *catalogpro Image 
     */
    $scope.catalogproImage = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/application' + image;
        } else {
            return "./features/catalogpro/assets/media/default-image.png"
        }
    };


    /**
     *catalogpro Image 
     */
    $scope.catalogproProductImage = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/application' + image;
        } else {
            return "./features/catalogpro/assets/media/no-product-image.png"
        }
    }; 


    /**
     * Filter options item
     */
    $scope.homeProductFilterMenu = function() {
        Loader.show();
        $ionicModal.fromTemplateUrl('features/catalogpro/assets/templates/l1/modal/filter_menu.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) {   
            $scope.categories = Catalogpro.getCategories();
            $scope.productFilterMenuModal = modal;
            $scope.productFilterMenuModal.show();
            Loader.hide();
        });
    };
   /**
     * close edit item
     */
    $scope.closehomeProductFilterMenu = function (){
        $scope.productFilterMenuModal.remove();
    }

    /**
     *go to product by category Image 
     */
    $scope.goToProductByCategory = function (category_id) {   
    };
    $scope.productDetailsById = function(product_id) {
        $state.go("catalogpro-product_details", { value_id: $scope.value_id,product_id:product_id});
    };
    $scope.closeProductDetailsModal = function (){
        
    }
    $scope.checkInAppLinks = function ($event) { 
       // A links
        if ($event.target.attributes.hasOwnProperty('data-state')) {
        }          
    };


    /**
     * Mark as Favorite
     */
    $scope.markAsFavorite = function(product_id) {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }

        Loader.show();
        Catalogpro.markAsFavorite(product_id).success(function (data) {
            $scope.product_details.product.is_favorite = data.is_favorite;
            Loader.hide();
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }


 /**
    *add rating and review
    */
    $scope.addRatingAndReview = function(product_id) {
        
        $scope.rating = {
            product_id: product_id,
            rating_number : 0,
            comment_text: ''
        };
        
        $ionicModal.fromTemplateUrl('features/catalogpro/assets/templates/l1/modal/add_comment.html', {
            scope: $scope,
            animation: 'slide-in-up'
        }).then(function(modal) { 
            $scope.addRatingAndReviewModal = modal;
            $scope.addRatingAndReviewModal.show();
        });
    };
   
   /**
     * close edit rating
     */
    $scope.closeAddRatingAndReview = function (){
    }

    $scope.starSelected = function(starSelectedValues){
        $scope.rating.rating_number= starSelectedValues;
        $scope.starSelectedValues = starSelectedValues;
    }


    /**
     * save Rating
     */
    $scope.saveRating = function() {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }

        Loader.show();
        Catalogpro.saveRating($scope.rating).success(function (data) {
            $scope.closeAddRatingAndReview();
            Dialog.alert($translate.instant("Thank you", "catalogpro") ,$translate.instant("Your review has been submitted successfully!", "catalogpro") , "OK", -1, "catalogpro");         
            Loader.hide();
            $state.go("catalogpro-product_details", { value_id: $scope.value_id,product_id:$stateParams.product_id});
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }

    /**
     *   date convert
     */
    $scope.convertDate = function (date) {
          return $filter("moment_calendar")( (new Date(date)).getTime());
    };

      /**
     *Customer avatar
     */
    $scope.customer_avatar = function (image) {      
        if (image != '' && image != null && image != "null") {
            return IMAGE_URL + 'images/customer' + image;
        } else {
            return "./features/catalogpro/assets/media/customer-placeholder.png"
        }
    }; 
    $scope.loadContent(false);
}).controller('CatalogproReportController', function (Dialog, Loader, $controller, Customer, $rootScope, SB, $scope, $state, $stateParams, $translate, Catalogpro, $timeout, $ionicModal, $filter) {
        angular.extend(this, $controller('CatalogproProductsFunctionsController', {
        Dialog: Dialog,
        $rootScope: $rootScope,
        $scope: $scope,
        $stateParams: $stateParams
        }));

    $scope.value_id = Catalogpro.value_id = $stateParams.value_id;
    $scope.is_loading = false;
    $scope.comment = {};
    $scope.payout = {
        products: {}
    };
    
    $scope.settings = Catalogpro.settings;
    $scope.use_pull_refresh = true;
    $scope.pull_to_refresh = false;
    $scope.page_title = "Report Comment";
    $scope.currentCustomerId = Customer.id;
    $scope.starArray = [1, 2, 3, 4, 5];
    $scope.starSelectedValues = 0;
    // Array of abuse report reasons
    $scope.reportReasons = [$translate.instant("Abusive Language", "catalogpro"), $translate.instant("Harassment", "catalogpro"), $translate.instant("Spam", "catalogpro"), $translate.instant("Inappropriate Content", "catalogpro")];

    // Variables to store user input
    $scope.report_data = {
        selectedReason : '',
        additionalComments : '',
        comment_id : $stateParams.comment_id
    }
    $scope.loadContent = function (pullToRefresh) {
        $scope.report_data = {
            selectedReason : '',
            additionalComments : '',
            comment_id : $stateParams.comment_id
        }
        if(!Customer.isLoggedIn()){
            Customer.loginModal($scope);
            return false;
        }
        $scope.is_loading = true;          
        Catalogpro.getComment($stateParams.comment_id).success(function (data) {
            $timeout( function(){
                $scope.comment  = data;
                Loader.hide();
                $scope.is_loading = false;
            }, 300 ); 
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    };

   /**
     * close edit rating
     */
    $scope.closeAddRatingAndReview = function (){
    }

    $scope.starSelected = function(starSelectedValues){
        $scope.rating.rating_number= starSelectedValues;
        $scope.starSelectedValues = starSelectedValues;
    }


    /**
     * save Rating
     */
    $scope.submitReport = function() {
        if(!Customer.isLoggedIn()){
           Customer.loginModal($scope);
           return false;
        }
        if($scope.report_data.selectedReason == ""){
            Dialog.alert($translate.instant("Error", "catalogpro") ,$translate.instant("Please select a reason", "catalogpro") , "OK", -1, "catalogpro");
            return false;
        }
        Loader.show();
        Catalogpro.submitReport($scope.report_data).success(function (data) {
            Dialog.alert($translate.instant("Success", "catalogpro") ,$translate.instant("Report submitted successfully!", "catalogpro") , "OK", -1, "catalogpro");         
            Loader.hide();
            $state.go("catalogpro-product_details", { value_id: $scope.value_id,product_id:$stateParams.product_id});
        }).error(function (error) {
            Loader.hide();
            Dialog.alert($translate.instant("Error", "catalogpro") ,error.message , "OK", -1, "catalogpro");         
        });
    }
    $scope.loadContent(false);
});
