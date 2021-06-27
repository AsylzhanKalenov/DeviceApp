try {
    window.$ = window.jQuery = require('laraveltest/public/js/jquery');

    $(document).ready(function(){
        console.log('Success!');
    });
} catch (e) {}