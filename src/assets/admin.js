window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

window.axios = require('axios');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//require('angular');
//require('angular-sanitize');
//require('angular-messages');
//require('jqueryui');
//require('jquery-ui-touch-punch');

//require('../../../../vendor/helori/laravel-cms/src/assets/js/medias');
//require('../../../../vendor/helori/laravel-cms/src/assets/js/crudui');
//require('../../../../vendor/helori/laravel-cms/src/assets/js/file-uploader');

/*angular.module('crudui').run(['$rootScope', function($rootScope)
{
    $rootScope.editorOpts = {
        toolbar: "undo redo | bold italic | forecolor | styleselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code",
        fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 18px 20px 22px 24px 26px 28px 30px",
        textcolor_map: [
            "000000", "Noir",
            "FFFFFF", "Blanc",
            "2A6096", "Bleu",
            "8D3694", "Violet",
            "E0E0E0", "Gris clair"
        ]
    };
}]);
*/