var crudui = angular.module('crudui', [
    'medias'
]);

// ----------------------------------------------------------------------------------------------------
//		Module Config (config providers here)
// ----------------------------------------------------------------------------------------------------
crudui.config(['$locationProvider', '$httpProvider', '$compileProvider', function($locationProvider, $httpProvider, $compileProvider)
{
    // By default, Angular sends post params as a json object in the query body.
    // It can be retreived using file_get_contents('php://input').
    // But this will be deprecated as PHP 5.6, so we prefer transforming angular request to x-www-form-urlencoded
    // This way, params can be retrieved using $_POST
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    $httpProvider.defaults.transformRequest = function(data){
        if (data === undefined) {
            return data;
        }
        return $.param(data);
    };

    // Allow href to download data and blob
    $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|chrome-extension|data|blob):/);

    // $http does not provide any access to the underlying xhr object.
    // $http does not provide any callback on the xhr's "progress" event.
    // So, this "hack" allows us to set an additionnal "__XHR__" header on http requests doing something like :
    //    headers: {
    //    __XHR__: function() {
    //        return function(xhr) {
    //            xhr.upload.addEventListener("progress", function(event) {
    //                console.log("uploaded " + ((event.loaded/event.total) * 100) + "%");
    //            });
    //        };
    //    }
    XMLHttpRequest.prototype.setRequestHeader = (function(sup) {
        return function(header, value) {
            if ((header === "__XHR__") && angular.isFunction(value))
                value(this);
            else
                sup.apply(this, arguments);
        };
    })(XMLHttpRequest.prototype.setRequestHeader);
}]);

crudui.run(['$rootScope', function($rootScope)
{
    $('[data-toggle="tooltip"]').tooltip();
}]);

crudui.filter("trustedUrl", ['$sce', function($sce) {
    return function(url){
        return $sce.trustAsResourceUrl(url);
    }
}]);

crudui.directive('sortable', ['$rootScope', '$http', function($rootScope, $http){
    return{
        compile: function(element, attrs){
            return function(scope, element, attrs){
                $rootScope.savingPosition = false;
                element.find("tbody").disableSelection().sortable({
                    placeholder: "position-placeholder",
                    opacity: 0.9,
                    revert: true,
                    scroll: true,
                    scrollSensitivity: 30,
                    scrollSpeed: 30,
                    update: function( event, ui ){
                        $rootScope.savingPosition = true;
                        var container = ui.item.parent();
                        var beforeItem = container.children().eq(ui.item.index() - 1);
                        var beforeItemId = (ui.item.index() > 0) ? beforeItem.attr('id') : 0;
                        console.log(ui.item.attr('id'), beforeItemId);

                        $http.post(ui.item.attr('update-url'), {id: ui.item.attr('id'), beforeItemId: beforeItemId}).then(function(r){
                            $rootScope.savingPosition = false;
                            console.log(r.data);
                        }, function(r){
                            console.log(r);
                        });
                    }
                });
            };
        }
    };
}]);

/*crudui.directive('listCheckbox', ['$http', function($http){
    return{
        restrict: 'A',
        link: function(scope, elt, attrs){
            var data = {
                id: attrs.itemId,
                type: attrs.fieldType,
                name: attrs.fieldName
            }
            var url = attrs.updateUrl;
            elt.change(function(){
                data.value = $(this).is(':checked');
                $rootScope.savingPosition = true;
                $http.post(url, data).then(function(r){
                    $rootScope.savingPosition = false;
                    //console.log(r);
                }).then(function(r){
                    $rootScope.savingPosition = false;
                });
            });
        }
    };
}]);*/

crudui.directive('listInput', ['$rootScope', '$http', function($rootScope, $http){
    return{
        restrict: 'A',
        link: function(scope, elt, attrs){
            var data = {
                id: attrs.itemId,
                type: attrs.fieldType,
                name: attrs.fieldName
            }
            var url = attrs.updateUrl;
            
            elt.change(function(){
                data.value = $(this).val();
                $rootScope.savingPosition = true;

                if(attrs.fieldType == 'json'){
                    console.log('list-input change', attrs.fieldType, data.value);
                }

                $http.post(url, data).then(function(r){
                    $rootScope.savingPosition = false;
                    //console.log(r);
                }).then(function(r){
                    $rootScope.savingPosition = false;
                });
            });
        }
    };
}]);

crudui.directive('checkbox', ['$rootScope', '$http', function($rootScope, $http){
    return{
        restrict: 'E',
        scope: {
            
        },
        transclude: true,
        template: '<div class="checkbox-wrapper" ng-class="{\'checked\': checked}"><label for="{{fieldName + \'-\' + itemId}}"><input type="checkbox" ng-model="checked" ng-true-value="true" ng-false-value="false" id="{{fieldName + \'-\' + itemId}}"><div class="inside"><div class="icon"><i class="fa fa-check"></i><i class="fa fa-close"></i></div><div class="text">{{title}}</div></div></label></div>',
        link: function(scope, elt, attrs){
            scope.title = attrs.title;
            scope.itemId = attrs.itemId;
            scope.fieldName = attrs.fieldName;
            scope.checked = (attrs.value == 'true');

            var data = {
                id: attrs.itemId,
                type: attrs.fieldType,
                name: attrs.fieldName
            };
            var url = attrs.updateUrl;
            elt.find('input').change(function(){
                data.value = $(this).is(':checked');
                $rootScope.savingPosition = true;
                $http.post(url, data).then(function(r){
                    $rootScope.savingPosition = false;
                    //console.log(r);
                }).then(function(r){
                    $rootScope.savingPosition = false;
                });
            });
        }
    };
}]);

crudui.directive('multiCheck', ['$interval', '$filter', function($interval, $filter){
    return{
        restrict: 'E',
        scope: true,
        template: '<div class="multi-check">\
            <div ng-repeat="opt in opts">\
                <div class="checkbox-wrapper" ng-class="{\'checked\': opt.checked}">\
                    <label for="{{name + \'-\' + opt.value}}">\
                        <input type="checkbox" ng-model="opt.checked" ng-true-value="1" ng-false-value="0" ng-change="update()" id="{{name + \'-\' + opt.value}}">\
                        <div class="inside">\
                            <div class="icon">\
                                <i class="fa fa-check"></i>\
                                <i class="fa fa-close"></i>\
                            </div>\
                            <div class="text">{{opt.label}}</div>\
                        </div>\
                    </label>\
                </div>\
            </div>\
            <input type="hidden" name="{{name}}" ng-value="valuesString">',

        link: function(scope, elt, attrs)
        {
            scope.name = attrs.name;

            scope.values = JSON.parse(attrs.values);
            console.log(scope.values);
            if(!scope.values)
                scope.values = [];
            
            scope.opts = [];
            angular.forEach(JSON.parse(attrs.options), function(v, k){
                scope.opts.push({
                    value: parseInt(k),
                    label: v,
                    checked: (scope.values.indexOf(parseInt(k)) === -1) ? 0 : 1
                });
            });

            scope.update = function(){
                var selected = [];
                angular.forEach(scope.opts, function(opt){
                    if(opt.checked){
                        selected.push(opt.value);
                    }
                });
                scope.valuesString = JSON.stringify(selected);
            };
            scope.update();
        }
    };
}]);

crudui.controller('CrudListController', ['$scope', '$window', function($scope, $window)
{
    $scope.openCreateDialog = function(e){
        e.preventDefault();
        $("#create-dialog").modal('show');
    };

    $scope.destroy = function(route_url, item_id){
        if(confirm('Êtes-vous sûr ?')){
            $window.location.href = route_url + '/delete-item/' + item_id;
        }
    };
}]);

crudui.controller('JsonController', ['$scope', function($scope)
{
    $scope.items = [];
    $scope.fieldname = 'fieldname';

    $scope.initItems = function(fieldname, items){
        $scope.fieldname = fieldname;
        $scope.items = JSON.parse(items);
        if(!angular.isArray($scope.items))
            $scope.items = [];
        $("#" + $scope.fieldname).val(JSON.stringify($scope.items));
    };
    $scope.addItem = function(){
        $scope.items.push({});
    }
    $scope.updateItems = function(){
        $("#" + $scope.fieldname).val(JSON.stringify($scope.items)).trigger('change');
    }
    $scope.removeItem = function(item){
        var idx = $scope.items.indexOf(item);
        if(idx !== -1){
            $scope.items.splice(idx, 1);
            $scope.updateItems();
        }
    }
}]);

crudui.directive('multicheck', ['$interval', '$filter', function($interval, $filter){
    return{
        restrict: 'E',
        scope: {},
        template: '<div ng-repeat="opt in opts"><label id=""><input type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="opt.checked" ng-change="update()"> {{opt.label}}</label></div><input type="hidden" name="{{name}}" ng-value="value">',
        link: function(scope, elt, attrs){
            scope.name = attrs.name;
            scope.options = JSON.parse(attrs.options);
            scope.value = attrs.value;
            if(!scope.value)
                scope.value = '[]';
            scope.model = JSON.parse(scope.value);

            scope.opts = [];
            angular.forEach(scope.options, function(v, k){
                scope.opts.push({
                    value: k,
                    label: v,
                    checked: (scope.value.indexOf(k) === -1) ? 0 : 1
                });
            });

            console.log(scope.opts);

            scope.update = function(){
                scope.model = [];
                angular.forEach(scope.opts, function(opt){
                    if(opt.checked){
                        scope.model.push(opt.value);
                    }
                });
                scope.value = JSON.stringify(scope.model);
            };
        }
    };
}]);


crudui.directive('tinymce', ['$rootScope', '$http', function($rootScope, $http)
{
    return{
        restrict: 'A',
        replace: true,
        link: function(scope, element, attrs)
        {
            //console.log("textarea#" + attrs.id);

            tinyMCE.baseURL = 'tinymce';
            //console.log('TinyMCE base url : ' + tinyMCE.baseURL);

            // ----------------------------------------------------
            //  TinyMCE images and links from "medias" table
            // ----------------------------------------------------
            var url = attrs.globalMediasUrl;
            if(!attrs.globalMediasUrl){
                console.log('Tinymce directive : global medias url missing !', attrs.globalMediasUrl);
            }
            $http.get(url + '/read-all').then(function(r){
                console.log('medias', r.data);
                var medias = [];
                angular.forEach(r.data, function(media){
                    medias.push({
                        title: media.title + ' (' + media.mime + ')',
                        value: media.filepath
                    });
                });
                var tinyMceImages = medias;
                var tinyMceLinks = medias;

                // ----------------------------------------------------
                //  Options globales pour TinyMCE
                // ----------------------------------------------------
                scope.tinyMCE_options = {
                    selector: "textarea#" + attrs.id,
                    height: 300,
                    resize: "vertical",
                    language : 'fr_FR',
                    theme: "modern",
                    body_class: "tinymce-body",
                    content_css : "/css/tinymce.css",
                    document_base_url: '/', //"<?php echo HTTP_HOST.$this->baseUrl(); ?>",
                    relative_urls: true,
                    convert_urls: false,
                    remove_script_host: true,
                    schema: "html5",
                    inline: false,
                    statusbar: false,
                    forced_root_block: false, // 'p'
                    //media_filter_html: true,
                    //extended_valid_elements:"iframe[src|title|width|height|allowfullscreen|frameborder|class|id],object[classid|width|height|codebase|*],param[name|value|_value|*],embed[type|width|height|src|*]",
                    //extended_valid_elements : "iframe[src|width|height|name|align|allowfullscreen|frameborder]",
                    //fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 18px 20px 22px 24px 26px 28px 30px 36px 42px",
                    image_list: tinyMceImages,
                    link_list: tinyMceLinks,
                    plugins: [
                        "textcolor advlist autolink lists link image charmap print preview anchor emoticons", // media
                        "searchreplace visualblocks code fullscreen charmap",
                        "insertdatetime table contextmenu paste" //moxiemanager
                    ],
                    //menubar: "tools table format view insert edit",
                    menubar: false,
                    //toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                    toolbar: "undo redo | bold italic | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code",
                    //toolbar2: "fontsizeselect | forecolor backcolor | charmap | emoticons | media",
                    /*style_formats: [
                     {title: 'Bold text', inline: 'b'},
                     {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                     {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                     {title: 'Example 1', inline: 'span', classes: 'example1'},
                     {title: 'Example 2', inline: 'span', classes: 'example2'},
                     {title: 'Table styles'},
                     {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                     ],*/
                    setup: function(editor) {
                        editor.on('init', function(e) {
                            //console.log('Editor initialized', e);
                        });
                    }
                };
                if($rootScope.editorOpts != '')
                    angular.extend(scope.tinyMCE_options, $rootScope.editorOpts);
                tinymce.init(scope.tinyMCE_options);
            });
        }
    };
}]);

crudui.controller('GlobalMediasController', ['$scope', '$http', function($scope, $http)
{
    $scope.items = [];
    $scope.route_url = '';
    $scope.files = null;
    $scope.uploading = true;
    $scope.upload_progress = 0;
    $scope.upload_total = 0;
    $scope.decache = new Date().getTime();
    $scope.filters = [];

    $scope.init = function(route_url){
        $scope.route_url = route_url;
        $scope.refresh();
    };

    $scope.refresh = function()
    {
        $http.get($scope.route_url + '/read-all').then(function(r){
            $scope.items = r.data;
            console.log(r.data);
        });
    };

    var input = $('#global-medias-file-input');
    input.change(function()
    {
        $scope.files = this.files;
        $scope.title = $scope.files[0].name.replace(/\.[^/.]+$/, "");       
        console.log('Files ready', $scope.files);
        $scope.$apply();
    });

    $scope.upload = function(e)
    {
        e.preventDefault();
        $scope.uploading = true;
        $scope.upload_progress = 0;
        $scope.upload_total = 0;
        
        $http({
            method: 'POST',
            url: $scope.route_url + '/upload',
            headers: {
                'Content-Type': undefined, // Manually setting ‘Content-Type’: multipart/form-data will fail to fill in the boundary parameter of the request.
                '__XHR__': function(){
                    return function(xhr) {
                        xhr.upload.addEventListener("progress", function(event) {
                            console.log("upload progress " + ((event.loaded / event.total) * 100) + "%", xhr, event);
                            $scope.upload_progress = event.loaded;
                            $scope.upload_total = event.total;
                            $scope.$apply();
                        });
                    }
                }
            },
            data: {
                title: $scope.title
            },
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    formData.append(key, value);
                }); 
                //formData.append(attrs.collection, blob);
                formData.append('file', $scope.files[0], $scope.files[0].name);
                return formData;
            }
        })
        .then(function (r) {
            console.log('upload completed', r);
            $scope.decache = new Date().getTime();
            $scope.uploading = false;
            $scope.refresh();
        }, function (r) {
            console.log('upload error', r);
            $scope.uploading = false;
        });
    }

    $scope.delete = function(mediaId)
    {
        $http.post($scope.route_url + '/delete', {id: mediaId}).then(function(r){
            $scope.decache = new Date().getTime();
            $scope.refresh();
        });
    }

    $scope.update = function(mediaId, title)
    {
        $http.post($scope.route_url + '/update', {id: mediaId, title: title}).then(function(r){
            console.log(r);
        });
    }

    $scope.download = function(mediaId)
    {
        window.location.href = $scope.route_url + '/download/' + mediaId;
    }
}]);


