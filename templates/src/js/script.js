(function (api, $) {
    'use strict';

    // WordPress requires no-js
    api.init = function () {
        $('html').removeClass('no-js');
    }

    // Document ready function
    $(function () {
        api.init();
    });

})(window, jQuery);