angular.module('crudui').directive('fileUploader', ['$http', '$sce', '$timeout', function($http, $sce, $timeout)
{
    return{
        restrict: 'EA',
        scope: true,
        link: function(scope, elt, attrs)
        { 
            scope.media = null;
            scope.uploading = false;
            scope.upload_progress = 0;
            scope.upload_total = 0;
            scope.decache = new Date().getTime();
            scope.saving_position = false;
            scope.loaded = false;
            scope.error = null;

            // -------------------------------------------------------
            //  File input 
            // -------------------------------------------------------
            /*scope.input = elt.find('.file-input');
            //console.log(scope.input);
            scope.input.change(function(){
                scope.file = this.files[0];
                scope.title = scope.file.name.replace(/\.[^/.]+$/, "");
                //console.log(scope.file);
                scope.$apply();
                scope.upload();
            });*/

            elt.on('change', '.file-input', function(e){
                scope.file = e.target.files[0];
                scope.title = scope.file.name.replace(/\.[^/.]+$/, "");

                scope.files = [];
                scope.titles = [];
                for(var i=0; i<e.target.files.length; ++i){
                    scope.files.push(e.target.files[i]);
                    scope.titles.push(e.target.files[i].name.replace(/\.[^/.]+$/, ""));
                }

                scope.$apply();
                scope.upload();
                this.value = null;
            }).on('click', '.file-input', function(e){
                this.value = null;
            });

            // -------------------------------------------------------
            //  Get single medias
            // -------------------------------------------------------
            if(!attrs.multiple)
            {
                $http.get(attrs.routeUrl + '/media/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                    scope.media = r.data;
                    scope.loaded = true;
                });
            }

            // -------------------------------------------------------
            //  Get multiple medias
            // -------------------------------------------------------
            if(attrs.multiple)
            {
                scope.medias = {};
                $http.get(attrs.routeUrl + '/medias/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                    scope.medias = r.data;
                    scope.loaded = true;
                });
            }

            // -------------------------------------------------------
            //  Multiple medias ordering
            // -------------------------------------------------------
            var medias = elt.find('.medias');
            medias.disableSelection().sortable({
                update: function( event, ui ){
                    scope.saving_position = true;
                    $http.post(attrs.routeUrl + '/update-medias-position', {id: attrs.itemId, mediaId: ui.item.attr('media-id'), position: ui.item.index()}).then(function(r){
                        scope.saving_position = false;
                        scope.medias = r.data;
                    }, function(r){
                        scope.saving_position = false;
                    });
                }
            });

            // -------------------------------------------------------
            //  Upload
            // -------------------------------------------------------
            scope.upload = function()
            {
                //console.log('upload id', attrs.itemId);
                scope.uploading = true;
                scope.upload_progress = 0;
                scope.upload_total = 0;
                scope.error = null;

                var data = {
                    id: attrs.itemId,
                    collection: attrs.collection,
                    mime: scope.file.type,
                    //title: scope.title,
                    titles: scope.titles
                };

                $http({
                    method: 'POST',
                    url: attrs.routeUrl + (attrs.multiple ? '/upload-medias' : '/upload-media'),
                    headers: {
                        'Content-Type': undefined, // Manually setting ‘Content-Type’: multipart/form-data will fail to fill in the boundary parameter of the request.
                        '__XHR__': function(){
                            return function(xhr) {
                                xhr.upload.addEventListener("progress", function(event) {
                                    //console.log("upload progress " + ((event.loaded/event.total) * 100) + "%", xhr, event);
                                    scope.upload_progress = event.loaded;
                                    scope.upload_total = event.total;
                                    scope.$apply();
                                });
                            }
                        }
                    },
                    data: data,
                    transformRequest: function (data, headersGetter) {
                        var formData = new FormData();
                        angular.forEach(data, function (value, key) {
                            formData.append(key, value);
                        });
                        console.log('request', scope.file, scope.files);
                        formData.append(attrs.collection, scope.file, scope.file.name);
                        for(var i=0; i<scope.files.length; ++i){
                            formData.append(attrs.collection + i, scope.files[i], scope.files[i].name);
                        }
                        return formData;
                    }
                })
                .then(function (r) {
                    console.log('upload completed', r.data);
                    scope.uploading = false;
                    if(attrs.multiple)
                        scope.medias = r.data;
                    else
                        scope.media = r.data;
                    scope.decache = new Date().getTime();
                }, function (r) {
                    console.log('upload error', r);
                    scope.uploading = false;
                    scope.error = r;
                });
            }

            // -------------------------------------------------------
            //  Delete
            // -------------------------------------------------------
            scope.deleteMedia = function(mediaId)
            {
                $http.post(attrs.routeUrl + '/delete-media', {id: attrs.itemId, mediaId: mediaId}).then(function(r){
                    if(attrs.multiple)
                        scope.medias = r.data;
                    else
                        scope.media = null;
                    scope.decache = new Date().getTime();
                });
            }

            // -------------------------------------------------------
            //  Rename
            // -------------------------------------------------------
            scope.renameMedia = function(media, title)
            {
                $http.post(attrs.routeUrl + '/rename-media', {id: attrs.itemId, mediaId: media.id, title: title}).then(function(r){
                    angular.copy(r.data, media);
                    scope.decache = new Date().getTime();
                });
            }

            // -------------------------------------------------------
            //  Optimize
            // -------------------------------------------------------
            scope.optimizeMedia = function(media)
            {
                $http.post(attrs.routeUrl + '/optimize-media', {id: attrs.itemId, mediaId: media.id}).then(function(r){
                    angular.copy(r.data, media);
                    scope.decache = new Date().getTime();
                });
            }
        }
    }
}]);
