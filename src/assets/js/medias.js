var medias = angular.module('medias', [
    'ngMessages'    
]);

// ----------------------------------------------------------------------------------------------------
//		Module Config (config providers here)
// ----------------------------------------------------------------------------------------------------
medias.config(['$locationProvider', '$httpProvider', '$compileProvider', function($locationProvider, $httpProvider, $compileProvider)
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


medias.directive('uploader', ['$http', '$sce', '$timeout', function($http, $sce, $timeout)
{
    return{
        restrict: 'EA',
        scope: true,
        link: function(scope, elt, attrs)
        { 
            scope.data = null;
            scope.data_url = null;
            scope.original = {w: 0, h: 0}, // original image size
            scope.display = {w: 500, h: 400};
            scope.modified = {x: 0, y: 0, w: 500, h: 500}, // final image
            scope.scale = 1;
            scope.format = 'jpg';
            scope.title = '';
            scope.width = 0;
            scope.height = 0;
            scope.width_forced = false;
            scope.antialiasing = true;
            scope.outputNeedsRefresh = true;
            scope.loading = false;
            scope.uploading = false;
            scope.upload_progress = 0;
            scope.upload_total = 0;
            scope.decache = new Date().getTime();
            scope.savingPosition = false;

            scope.grayscale = false;
            scope.brightness = 0;
            
            var input = elt.find('.file-input');
            var preview = elt.find('.preview');
            var cropper = elt.find('.cropper');
            var medias = elt.find('.medias > .row');
            var dialog = elt.find('.uploader-dialog');

            input.change(function(){
                scope.readFile(this);
                scope.$apply();
            });

            medias.disableSelection().sortable({
                update: function( event, ui ){
                    scope.savingPosition = true;
                    $http.post(attrs.routeUrl + '/update-medias-position', {id: attrs.itemId, mediaId: ui.item.attr('media-id'), position: ui.item.index()}).then(function(r){
                        scope.savingPosition = false;
                        console.log(r);
                    }, function(r){
                        console.log(r);
                    });
                }
            });

            scope.openImageUploader = function(){
                dialog.modal('show');
            };

            scope.closeImageUploader = function(){
                dialog.modal('hide');
            };

            if(attrs.multiple)
            {
                scope.medias = {};
                $http.get(attrs.routeUrl + '/medias/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                    scope.medias = r.data;
                });
            }
            else
            {
                scope.media = {};
                $http.get(attrs.routeUrl + '/media/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                    scope.media = r.data;
                });
            }

            // Canvas to display as background (outside crop area)
            var c_back = elt.find('.canvas-back')[0];
            var ctx_back = c_back.getContext("2d");
            ctx_back.canvas.width = scope.display.w; // initial size
            ctx_back.canvas.height = scope.display.h; // initial size

            // Canvas to display as foreground (inside crop area)
            var c = elt.find('.canvas-cropped')[0];
            var ctx = c.getContext("2d");
            ctx.canvas.width = scope.display.w; // initial size
            ctx.canvas.height = scope.display.h; // initial size
            preview.height(scope.display.h); // used to correctly size overlay

            // Original image stored in an off-sreen buffer
            var buffer = document.createElement('canvas');
            var ctx_buffer = buffer.getContext("2d");

            // Buffer used to perform filtering (same size as original)
            var buffer_filtered = document.createElement('canvas');
            var ctx_buffer_filtered = buffer_filtered.getContext("2d");

            scope.readFile = function(input)
            {
                scope.loading = true;
                if(input.files && input.files[0])
                {
                    scope.file = input.files[0];
                    scope.title = scope.file.name.replace(/\.[^/.]+$/, "");

                    var reader = new FileReader();
                    reader.onload = function(e)
                    {
                        var image = new Image();
                        image.onload = function()
                        {
                            scope.resetFilters();

                            scope.original.w = this.width;
                            scope.original.h = this.height;

                            scope.width = this.width;
                            scope.height = this.height;

                            scope.modified.x = 0;
                            scope.modified.y = 0;
                            scope.modified.w = this.width;
                            scope.modified.h = this.height;

                            scope.display.w = 500;
                            scope.scale = scope.display.w / scope.original.w;
                            scope.display.h = scope.original.h * scope.scale;
                            if(scope.display.h > 500){
                                scope.display.h = 500;
                                scope.scale = scope.display.h / scope.original.h;
                                scope.display.w = scope.original.w * scope.scale;
                            }

                            ctx.canvas.width = scope.display.w;
                            ctx.canvas.height = scope.display.h;
                            ctx_back.canvas.width = scope.display.w;
                            ctx_back.canvas.height = scope.display.h;
                            
                            preview.width(scope.display.w);
                            preview.height(scope.display.h);
                            preview.css('margin-left', (500 - scope.display.w) / 2 + 'px');
                            
                            ctx_buffer.canvas.width = scope.original.w;
                            ctx_buffer.canvas.height = scope.original.h;
                            ctx_buffer_filtered.canvas.width = scope.original.w;
                            ctx_buffer_filtered.canvas.height = scope.original.h;

                            ctx_buffer.drawImage(image, 0, 0);//, scope.original.w, scope.original.h, 0, 0, scope.original.w, scope.original.h);
                            
                            scope.updateImage();

                            scope.loading = false;
                            scope.$apply();
                        };
                        image.src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            scope.updateImage = function()
            {
                console.log("=> Update image filtering and display");
                scope.loading = true;

                $timeout(function()
                {
                    // Update the full buffer with filtering
                    // (this buffer's data are copied onto display buffers)
                    var pixels = ctx_buffer.getImageData(0, 0, buffer.width, buffer.height);
                    pixels = scope.applyFilters(pixels);
                    ctx_buffer_filtered.putImageData(pixels, 0, 0);

                    ctx_back.drawImage(buffer_filtered, 0, 0, scope.original.w, scope.original.h,
                        0, 0, scope.display.w, scope.display.h);
                    
                    ctx.drawImage(buffer_filtered, 0, 0, scope.original.w, scope.original.h,
                        0, 0, scope.display.w, scope.display.h);

                    scope.outputNeedsRefresh = true;
                    scope.loading = false;
                }, 100);
            }

            scope.updateOutput = function(callback)
            {
                scope.loading = true;
                $timeout(function()
                {
                    // Create output buffer
                    scope.buffer_out = document.createElement('canvas');
                    var ctx_buffer_out = scope.buffer_out.getContext("2d");

                    var scale = scope.width / scope.modified.w;
                    if(scale < 1){
                        if(scope.antialiasing)
                        {
                            console.log("==> rescale with antialiasing", scale);

                            // crop output buffer
                            ctx_buffer_out.canvas.width = scope.modified.w;
                            ctx_buffer_out.canvas.height = scope.modified.h;

                            // draw filtered image data from crop area
                            pixels = ctx_buffer_filtered.getImageData(scope.modified.x, scope.modified.y, scope.modified.w, scope.modified.h);
                            ctx_buffer_out.putImageData(pixels, 0, 0);

                            // rescale output buffer with donwsampling
                            scope.buffer_out = downScaleCanvas(scope.buffer_out, scale);
                        }
                        else
                        {
                            console.log("==> rescale (no antialiasing)", scale);

                            // rescale & crop output buffer
                            ctx_buffer_out.canvas.width = scope.width;
                            ctx_buffer_out.canvas.height = scope.height;

                            // draw filtered image data from crop area (without antialiasing)
                            ctx_buffer_out.drawImage(buffer_filtered, scope.modified.x, scope.modified.y, scope.modified.w, scope.modified.h,
                                0, 0, scope.buffer_out.width, scope.buffer_out.height);
                        }
                    }
                    else{
                        console.log("==> No rescale");
                        // crop output buffer
                        ctx_buffer_out.canvas.width = scope.modified.w;
                        ctx_buffer_out.canvas.height = scope.modified.h;

                        // draw filtered image data from crop area
                        pixels = ctx_buffer_filtered.getImageData(scope.modified.x, scope.modified.y, scope.modified.w, scope.modified.h);
                        ctx_buffer_out.putImageData(pixels, 0, 0);
                    }

                    scope.data = ctx_buffer_out.getImageData(0, 0, scope.buffer_out.width, scope.buffer_out.height);
                    var mime = 'image/jpeg';
                    if(scope.format == 'jpg') mime = 'image/jpeg';
                    else if(scope.format == 'png') mime = 'image/png';
                    scope.data_url = scope.buffer_out.toDataURL(mime);
                    scope.trusted_data_url = $sce.trustAsResourceUrl(scope.data_url);

                    scope.loading = false;
                    scope.outputNeedsRefresh = false;
                    if(callback)
                        callback();
                }, 100);
            }

            scope.updateWidthForced = function()
            {
                if(scope.width_forced){
                    scope.width_forced_value = scope.width;
                    scope.height = Math.floor(scope.width * scope.modified.h / scope.modified.w);
                }else{
                    scope.width = scope.modified.w;
                    scope.height = scope.modified.h;
                }
                scope.outputNeedsRefresh = true;
            }

            scope.resetFilters = function()
            {
                scope.width_forced = false;
                scope.grayscale = false;
                scope.brightness = 0;
            }

            // http://www.html5rocks.com/en/tutorials/canvas/imagefilters/
            scope.applyFilters = function(pixels)
            {
                var d = pixels.data;
                if(scope.grayscale)
                {
                    console.log("Filtering : Grayscale");
                    for(var i=0; i<d.length; i+=4) {
                        var r = d[i];
                        var g = d[i+1];
                        var b = d[i+2];
                        // CIE luminance for the RGB
                        // The human eye is bad at seeing red and blue, so we de-emphasize them.
                        var v = 0.2126*r + 0.7152*g + 0.0722*b;
                        d[i] = d[i+1] = d[i+2] = v;
                    }
                }
                if(scope.brightness)
                {
                    console.log("Filtering : Brightness", scope.brightness);
                    for (var i=0; i<d.length; i+=4) {
                        d[i] += scope.brightness;
                        d[i+1] += scope.brightness;
                        d[i+2] += scope.brightness;
                    }
                }
                return pixels;
            }

            scope.upload = function(e)
            {
                e.preventDefault();
                scope.uploading = true;
                scope.upload_progress = 0;
                scope.upload_total = 0;

                var data = {
                    id: attrs.itemId,
                    collection: attrs.collection,
                    mime: scope.file.type,
                    title: scope.title,
                    format: scope.format,
                    modified: scope.modified,
                    width: scope.width,
                    height: scope.height
                };

                var blob = dataURLToBlob(scope.data_url);
                
                $http({
                    method: 'POST',
                    url: attrs.routeUrl + (attrs.multiple ? '/upload-medias' : '/upload-media'),
                    headers: {
                        'Content-Type': undefined, // Manually setting ‘Content-Type’: multipart/form-data will fail to fill in the boundary parameter of the request.
                        '__XHR__': function(){
                            return function(xhr) {
                                xhr.upload.addEventListener("progress", function(event) {
                                    console.log("upload progress " + ((event.loaded/event.total) * 100) + "%", xhr, event);
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
                        formData.append(attrs.collection, blob);
                        return formData;
                    }
                })
                .then(function (r) {
                    console.log('upload completed', r);
                    scope.uploading = false;
                    if(attrs.multiple)
                        scope.medias = r.data;
                    else
                        scope.media = r.data;
                    scope.decache = new Date().getTime();
                    scope.closeImageUploader();
                }, function (r) {
                    console.log('upload error', r);
                    scope.uploading = false;
                    scope.closeImageUploader();
                });
            }

            scope.deleteMedia = function(mediaId)
            {
                $http.post(attrs.routeUrl + '/delete-media', {id: attrs.itemId, mediaId: mediaId}).then(function(r){
                    if(attrs.multiple)
                        scope.medias = r.data;
                    else
                        scope.media = {};
                    scope.decache = new Date().getTime();
                });
            }




            function dataURLToBlob(dataURL)
            {
                var BASE64_MARKER = ';base64,';
                if (dataURL.indexOf(BASE64_MARKER) == -1) {
                    var parts = dataURL.split(',');
                    var contentType = parts[0].split(':')[1];
                    var raw = decodeURIComponent(parts[1]);

                    return new Blob([raw], {type: contentType});
                }
                var parts = dataURL.split(BASE64_MARKER);
                var contentType = parts[0].split(':')[1];
                console.log(contentType);
                var raw = window.atob(parts[1]);
                var rawLength = raw.length;

                var uInt8Array = new Uint8Array(rawLength);

                for (var i = 0; i < rawLength; ++i) {
                    uInt8Array[i] = raw.charCodeAt(i);
                }

                return new Blob([uInt8Array], {type: contentType});
            }

            function blobToFile(blob, fileName){
                //A Blob() is almost a File() - it's just missing the two properties below which we will add
                blob.lastModifiedDate = new Date();
                blob.name = fileName;
                return blob;
            }



            // ----------------------------------------------------------------
            //  Crop with number inputs
            // ----------------------------------------------------------------
            scope.updateWidth = function(){
                scope.modified.w = Math.min(scope.modified.w, scope.original.w);
                if(scope.modified.x + scope.modified.w > scope.original.w)
                    scope.modified.x = scope.original.w - scope.modified.w;
            }
            scope.updateHeight = function(){
                scope.modified.h = Math.min(scope.modified.h, scope.original.h);
                if(scope.modified.y + scope.modified.h > scope.original.h)
                    scope.modified.y = scope.original.h - scope.modified.h;
            }
            scope.updateX = function(){
                scope.modified.x = Math.min(scope.modified.x, scope.original.w);
                if(scope.modified.x + scope.modified.w > scope.original.w)
                    scope.modified.w = scope.original.w - scope.modified.x;
            }
            scope.updateY = function(){
                scope.modified.y = Math.min(scope.modified.y, scope.original.h);
                if(scope.modified.y + scope.modified.h > scope.original.h)
                    scope.modified.h = scope.original.h - scope.modified.y;
            }

            // ----------------------------------------------------------------
            //  Crop with mouse
            // ----------------------------------------------------------------
            scope.cropper_down = false;
            scope.top_down = false;
            scope.bottom_down = false;
            scope.left_down = false;
            scope.right_down = false;
            scope.top_left_down = false;
            scope.top_right_down = false;
            scope.bottom_left_down = false;
            scope.bottom_right_down = false;

            scope.dragStartX = 0;
            scope.dragStartY = 0;
            scope.cropper = {x:0, y:0, w:0, y:0};

            $(document).mouseup(function()
            {
                if(scope.cropper_down || scope.top_down || scope.bottom_down || scope.left_down || scope.right_down
                     || scope.top_left_down || scope.top_right_down || scope.bottom_left_down || scope.bottom_right_down)
                {
                    scope.outputNeedsRefresh = true;
                }
                scope.cropper_down = false;
                scope.top_down = false;
                scope.bottom_down = false;
                scope.left_down = false;
                scope.right_down = false;
                scope.top_left_down = false;
                scope.top_right_down = false;
                scope.bottom_left_down = false;
                scope.bottom_right_down = false;
                scope.$apply();
            });

            function initMousePositions(e)
            {
                e.stopPropagation();
                scope.dragStartX = e.clientX;
                scope.dragStartY = e.clientY;
                scope.cropper.x = cropper.position().left;
                scope.cropper.y = cropper.position().top;
                scope.cropper.w = cropper.width();
                scope.cropper.h = cropper.height();
                scope.$apply();
            }

            cropper.mousedown(function(e){
                scope.cropper_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.top').mousedown(function(e){
                scope.top_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.bottom').mousedown(function(e){
                scope.bottom_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.left').mousedown(function(e){
                scope.left_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.right').mousedown(function(e){
                scope.right_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.top-left').mousedown(function(e){
                scope.top_left_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.top-right').mousedown(function(e){
                scope.top_right_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.bottom-left').mousedown(function(e){
                scope.bottom_left_down = true;
                initMousePositions(e);
            });
            cropper.find('.grab.bottom-right').mousedown(function(e){
                scope.bottom_right_down = true;
                initMousePositions(e);
            });
            
            $(document).mousemove(function(e){
                e.stopPropagation();
                if(scope.cropper_down)
                {
                    var dx = e.clientX - scope.dragStartX;
                    var dy = e.clientY - scope.dragStartY;

                    var x = (scope.cropper.x + dx) / scope.scale;
                    var y = (scope.cropper.y + dy) / scope.scale;

                    x = Math.max(0, Math.floor(x));
                    y = Math.max(0, Math.floor(y));

                    if(x + scope.modified.w > scope.original.w)
                        x = scope.original.w - scope.modified.w;

                    if(y + scope.modified.h > scope.original.h)
                        y = scope.original.h - scope.modified.h;

                    scope.modified.x = x;
                    scope.modified.y = y;
                }
                else if(scope.top_down)         {dragTop(e);}
                else if(scope.bottom_down)      {dragBottom(e);}
                else if(scope.left_down)        {dragLeft(e);}
                else if(scope.right_down)       {dragRight(e);}
                else if(scope.top_left_down)    {dragTop(e); dragLeft(e);}
                else if(scope.top_right_down)   {dragTop(e); dragRight(e);}
                else if(scope.bottom_left_down) {dragBottom(e); dragLeft(e);}
                else if(scope.bottom_right_down){dragBottom(e); dragRight(e);}
                
                if(scope.cropper_down || scope.top_down || scope.bottom_down || scope.left_down || scope.right_down
                     || scope.top_left_down || scope.top_right_down || scope.bottom_left_down || scope.bottom_right_down)
                {
                    if(scope.width_forced){
                        scope.width = Math.min(scope.width_forced_value, scope.modified.w);
                        scope.height = Math.floor(scope.modified.h * scope.width / scope.modified.w);
                    }else{
                        scope.width = scope.modified.w;
                        scope.height = scope.modified.h;
                    }
                    scope.$apply();
                }
            });

            function dragTop(e)
            {
                var dy = e.clientY - scope.dragStartY;
                var y = (scope.cropper.y + dy) / scope.scale;
                
                y = Math.max(0, Math.floor(y));
                y = Math.min(scope.modified.y + scope.modified.h, Math.floor(y));
                var h = scope.modified.y + scope.modified.h - y;

                scope.modified.y = y;
                scope.modified.h = h;
            }

            function dragBottom(e)
            {
                var dy = e.clientY - scope.dragStartY;
                var h = (scope.cropper.h + dy) / scope.scale;

                h = Math.max(0, Math.floor(h));
                h = Math.min(scope.original.h - scope.modified.y, Math.floor(h));
                
                scope.modified.h = h;
            }

            function dragLeft(e)
            {
                var dx = e.clientX - scope.dragStartX;
                var x = (scope.cropper.x + dx) / scope.scale;
                
                x = Math.max(0, Math.floor(x));
                x = Math.min(scope.modified.x + scope.modified.w, Math.floor(x));
                var w = scope.modified.x + scope.modified.w - x;

                scope.modified.x = x;
                scope.modified.w = w;
            }

            function dragRight(e)
            {
                var dx = e.clientX - scope.dragStartX;
                var w = (scope.cropper.w + dx) / scope.scale;

                w = Math.max(0, Math.floor(w));
                w = Math.min(scope.original.w - scope.modified.x, Math.floor(w));
                
                scope.modified.w = w;
            }






            // http://stackoverflow.com/questions/18922880/html5-canvas-resize-downscale-image-high-quality

            // scales the image by (float) scale < 1
            // returns a canvas containing the scaled image.
            function downScaleImage(img, scale) {
                var imgCV = document.createElement('canvas');
                imgCV.width = img.width;
                imgCV.height = img.height;
                var imgCtx = imgCV.getContext('2d');
                imgCtx.drawImage(img, 0, 0);
                return downScaleCanvas(imgCV, scale);
            }

            // scales the canvas by (float) scale < 1
            // returns a new canvas containing the scaled image.
            function downScaleCanvas(cv, scale) {
                if (!(scale < 1) || !(scale > 0)) throw ('scale must be a positive number <1 ');
                var sqScale = scale * scale; // square scale = area of source pixel within target
                var sw = cv.width; // source image width
                var sh = cv.height; // source image height
                var tw = Math.floor(sw * scale); // target image width
                var th = Math.floor(sh * scale); // target image height
                var sx = 0, sy = 0, sIndex = 0; // source x,y, index within source array
                var tx = 0, ty = 0, yIndex = 0, tIndex = 0; // target x,y, x,y index within target array
                var tX = 0, tY = 0; // rounded tx, ty
                var w = 0, nw = 0, wx = 0, nwx = 0, wy = 0, nwy = 0; // weight / next weight x / y
                // weight is weight of current source point within target.
                // next weight is weight of current source point within next target's point.
                var crossX = false; // does scaled px cross its current px right border ?
                var crossY = false; // does scaled px cross its current px bottom border ?
                var sBuffer = cv.getContext('2d').
                getImageData(0, 0, sw, sh).data; // source buffer 8 bit rgba
                var tBuffer = new Float32Array(3 * tw * th); // target buffer Float32 rgb
                var sR = 0, sG = 0,  sB = 0; // source's current point r,g,b
                /* untested !
                var sA = 0;  //source alpha  */    

                for (sy = 0; sy < sh; sy++) {
                    ty = sy * scale; // y src position within target
                    tY = 0 | ty;     // rounded : target pixel's y
                    yIndex = 3 * tY * tw;  // line index within target array
                    crossY = (tY != (0 | ty + scale)); 
                    if (crossY) { // if pixel is crossing botton target pixel
                        wy = (tY + 1 - ty); // weight of point within target pixel
                        nwy = (ty + scale - tY - 1); // ... within y+1 target pixel
                    }
                    for (sx = 0; sx < sw; sx++, sIndex += 4) {
                        tx = sx * scale; // x src position within target
                        tX = 0 |  tx;    // rounded : target pixel's x
                        tIndex = yIndex + tX * 3; // target pixel index within target array
                        crossX = (tX != (0 | tx + scale));
                        if (crossX) { // if pixel is crossing target pixel's right
                            wx = (tX + 1 - tx); // weight of point within target pixel
                            nwx = (tx + scale - tX - 1); // ... within x+1 target pixel
                        }
                        sR = sBuffer[sIndex    ];   // retrieving r,g,b for curr src px.
                        sG = sBuffer[sIndex + 1];
                        sB = sBuffer[sIndex + 2];

                        /* !! untested : handling alpha !!
                           sA = sBuffer[sIndex + 3];
                           if (!sA) continue;
                           if (sA != 0xFF) {
                               sR = (sR * sA) >> 8;  // or use /256 instead ??
                               sG = (sG * sA) >> 8;
                               sB = (sB * sA) >> 8;
                           }
                        */
                        if (!crossX && !crossY) { // pixel does not cross
                            // just add components weighted by squared scale.
                            tBuffer[tIndex    ] += sR * sqScale;
                            tBuffer[tIndex + 1] += sG * sqScale;
                            tBuffer[tIndex + 2] += sB * sqScale;
                        } else if (crossX && !crossY) { // cross on X only
                            w = wx * scale;
                            // add weighted component for current px
                            tBuffer[tIndex    ] += sR * w;
                            tBuffer[tIndex + 1] += sG * w;
                            tBuffer[tIndex + 2] += sB * w;
                            // add weighted component for next (tX+1) px                
                            nw = nwx * scale
                            tBuffer[tIndex + 3] += sR * nw;
                            tBuffer[tIndex + 4] += sG * nw;
                            tBuffer[tIndex + 5] += sB * nw;
                        } else if (crossY && !crossX) { // cross on Y only
                            w = wy * scale;
                            // add weighted component for current px
                            tBuffer[tIndex    ] += sR * w;
                            tBuffer[tIndex + 1] += sG * w;
                            tBuffer[tIndex + 2] += sB * w;
                            // add weighted component for next (tY+1) px                
                            nw = nwy * scale
                            tBuffer[tIndex + 3 * tw    ] += sR * nw;
                            tBuffer[tIndex + 3 * tw + 1] += sG * nw;
                            tBuffer[tIndex + 3 * tw + 2] += sB * nw;
                        } else { // crosses both x and y : four target points involved
                            // add weighted component for current px
                            w = wx * wy;
                            tBuffer[tIndex    ] += sR * w;
                            tBuffer[tIndex + 1] += sG * w;
                            tBuffer[tIndex + 2] += sB * w;
                            // for tX + 1; tY px
                            nw = nwx * wy;
                            tBuffer[tIndex + 3] += sR * nw;
                            tBuffer[tIndex + 4] += sG * nw;
                            tBuffer[tIndex + 5] += sB * nw;
                            // for tX ; tY + 1 px
                            nw = wx * nwy;
                            tBuffer[tIndex + 3 * tw    ] += sR * nw;
                            tBuffer[tIndex + 3 * tw + 1] += sG * nw;
                            tBuffer[tIndex + 3 * tw + 2] += sB * nw;
                            // for tX + 1 ; tY +1 px
                            nw = nwx * nwy;
                            tBuffer[tIndex + 3 * tw + 3] += sR * nw;
                            tBuffer[tIndex + 3 * tw + 4] += sG * nw;
                            tBuffer[tIndex + 3 * tw + 5] += sB * nw;
                        }
                    } // end for sx 
                } // end for sy

                // create result canvas
                var resCV = document.createElement('canvas');
                resCV.width = tw;
                resCV.height = th;
                var resCtx = resCV.getContext('2d');
                var imgRes = resCtx.getImageData(0, 0, tw, th);
                var tByteBuffer = imgRes.data;
                // convert float32 array into a UInt8Clamped Array
                var pxIndex = 0; //  
                for (sIndex = 0, tIndex = 0; pxIndex < tw * th; sIndex += 3, tIndex += 4, pxIndex++) {
                    tByteBuffer[tIndex] = Math.ceil(tBuffer[sIndex]);
                    tByteBuffer[tIndex + 1] = Math.ceil(tBuffer[sIndex + 1]);
                    tByteBuffer[tIndex + 2] = Math.ceil(tBuffer[sIndex + 2]);
                    tByteBuffer[tIndex + 3] = 255;
                }
                // writing result to canvas.
                resCtx.putImageData(imgRes, 0, 0);
                return resCV;
            }
        }
    }
}]);
