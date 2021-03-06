jQuery(document).ready(function($) {

    // Get form data.
    function getFormData(form) {

        // Set.
        var unindexedArray = jQuery(form).serializeArray();
        var indexedArray = {};

        // Map.
        $.map(unindexedArray, function(n, i) {
            indexedArray[n['name']] = n['value'];
        });

        // Return.
        return indexedArray;

    }

    // Get form on submit.
    $('input.rr-submit').on('click', function(e) {

        // Stop.
        e.preventDefault();

        // Get data.
        var formJSON = getFormData('form#rr-register-form');

        // Add class.
        $('.rr-inner').addClass('rr-customer');
        $('.rr-inner').addClass('rr-loading');
        $('.rr-inner').html('<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="864px" height="250px" viewBox="0 0 864 250" enable-background="new 0 0 864 250" xml:space="preserve"><g id="first-r"><g><rect x="104.648" y="173.705" fill="#A81B29" width="35.782" height="39.08"/><path fill="#A81B29" d="M192.628,167.779c16.047-6.822,26.563-19.92,26.563-39.84v-0.367c0-12.728-3.876-22.504-11.437-30.066c-8.67-8.67-22.319-13.834-42.054-13.834h-36.161c-0.622,17.494-8.68,31.53-23.074,40.373l33.972,49.638h15.855l26.006,39.102h41.132L192.628,167.779z M183.404,130.338c0,9.406-7.192,15.309-19.182,15.309H140.43v-31.171h23.609c11.804,0,19.365,5.164,19.365,15.492V130.338z"/></g></g><path id="last-r" fill="#F80040" d="M95.662,121.325c16.048-6.826,26.559-19.92,26.559-39.84v-0.369c0-12.727-3.872-22.502-11.433-30.066c-8.67-8.666-22.319-13.832-42.056-13.832H7.683V68.02h35.782h23.609c11.804,0,19.367,5.165,19.367,15.492v0.369c0,9.407-7.194,15.308-19.182,15.308H43.465v-0.017H7.683v67.156h35.782v-39.104h15.862l26.006,39.104h41.132L95.662,121.325z"/><g id="right"><g><path fill="#F80040" d="M275.17,17.903h61.052c19.734,0,33.383,5.165,42.052,13.834c7.563,7.563,11.435,17.339,11.435,30.065v0.367c0,19.92-10.513,33.016-26.559,39.841l30.802,45.001H352.82l-26.006-39.1h-15.862v39.1H275.17V17.903z M334.745,79.875c11.99,0,19.184-5.902,19.184-15.307v-0.371c0-10.328-7.563-15.493-19.367-15.493h-23.609v31.171H334.745z"/><path fill="#F80040" d="M408.697,17.903h35.782v129.109h-35.782V17.903z"/><path fill="#F80040" d="M463.842,82.829v-0.369c0-37.627,29.511-67.139,69.351-67.139c22.687,0,38.732,6.824,52.382,18.629l-21.026,25.453c-9.224-7.746-18.262-12.174-31.172-12.174c-18.628,0-33.016,15.495-33.016,35.23v0.369c0,21.025,14.571,35.965,35.044,35.965c8.854,0,15.495-1.844,20.658-5.349V97.766h-25.269V71.577h59.207v57.916c-13.648,11.436-32.463,20.104-56.071,20.104C493.722,149.596,463.842,121.93,463.842,82.829z"/><path fill="#F80040" d="M609.912,17.903h35.782v48.324h45.926V17.903h35.782v129.109H691.62v-49.06h-45.926v49.06h-35.782V17.903z"/><path fill="#F80040" d="M781.803,49.259h-38.549V17.903h113.063v31.356h-38.734v97.752h-35.78V49.259z"/></g></g><g id="report"><path fill="#A81B29" d="M275.17,163.162h31.339c8.84,0,15.772,2.611,20.291,7.031c3.716,3.814,5.825,9.037,5.825,15.168v0.199c0,11.551-6.929,18.482-16.673,21.295l18.983,26.621H320.27l-17.276-24.51h-15.47v24.51H275.17V163.162z M305.605,198.016c8.841,0,14.464-4.621,14.464-11.75v-0.201c0-7.537-5.423-11.654-14.567-11.654h-17.979v23.605H305.605z"/><path fill="#A81B29" d="M372.906,163.162h52.131v11.047h-39.779v18.285h35.258v11.148h-35.258v18.781h40.281v11.053h-52.633V163.162z"/><path fill="#A81B29" d="M465.316,163.162h27.725c16.473,0,26.717,9.34,26.717,23.504v0.201c0,15.77-12.656,24.006-28.124,24.006h-13.965v22.604h-12.353V163.162z M492.036,199.723c9.338,0,15.167-5.223,15.167-12.557v-0.197c0-8.238-5.928-12.559-15.167-12.559h-14.367v25.313H492.036z"/><path fill="#A81B29" d="M554.409,198.518v-0.199c0-19.791,15.27-36.363,36.865-36.363c21.597,0,36.665,16.375,36.665,36.162v0.201c0,19.787-15.267,36.361-36.865,36.361C569.478,234.68,554.409,218.307,554.409,198.518z M614.982,198.518v-0.199c0-13.662-9.946-24.912-23.908-24.912c-13.96,0-23.706,11.051-23.706,24.711v0.201c0,13.66,9.944,24.91,23.906,24.91C605.238,223.229,614.982,212.18,614.982,198.518z"/><path fill="#A81B29" d="M668.019,163.162h31.337c8.842,0,15.772,2.611,20.293,7.031c3.716,3.814,5.825,9.037,5.825,15.168v0.199c0,11.551-6.929,18.482-16.677,21.295l18.987,26.621h-14.666l-17.278-24.51h-15.468v24.51h-12.354V163.162z M698.453,198.016c8.84,0,14.465-4.621,14.465-11.75v-0.201c0-7.537-5.427-11.654-14.565-11.654h-17.98v23.605H698.453z"/><path fill="#A81B29" d="M782.827,174.613h-22.398v-11.451h57.154v11.451h-22.4v58.863h-12.355V174.613z"/></g></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255); display: block; shape-rendering: auto;" width="58px" height="58px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"><circle cx="50" cy="50" r="32" stroke-width="10" stroke="#a81b29" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round"> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1.5384615384615383s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform></circle></svg>');

        // Send via AJAX.
        $.ajax({
            type: 'POST',
            url: admin_vars.ajax_url,
            data: {
                action: 'rr_create_customer',
                form: formJSON,
            },
            success: function(res) {
                // Send via AJAX.
                $.ajax({
                    type: 'POST',
                    url: admin_vars.ajax_url,
                    data: {
                        action: 'rr_login_customer',
                    },
                    success: function(resp) {
                        // Login.
                        window.location.href = resp;
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            },
            error: function(err) {
                console.log(err);
            }
        });

    });

    // Check customer.
    if($('.rr-customer').length > 0) {
        // Send via AJAX.
        $.ajax({
            type: 'POST',
            url: admin_vars.ajax_url,
            data: {
                action: 'rr_check_customer',
            },
            success: function(res) {
                // Replace.
                $('.rr-customer').html(res);
                // Remove.
                $('.rr-customer').removeClass('rr-loading');
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    // Show/hide password.
    $(document).on('click', 'span.show-pass', function() {
        // Check password. 
        if($('span.hide-password').is(':visible')) {
            // Hide.
            $('span.hide-password').hide();
            // Show.
            $('span.hide-password-obfus').show();
        } else {
            // Hide.
            $('span.hide-password-obfus').hide();
            // Show.
            $('span.hide-password').show();
        }
    });

    // Auto-login.
    $(document).on('click', 'span#rr-account-login', function() {
        // Send via AJAX.
        $.ajax({
            type: 'POST',
            url: admin_vars.ajax_url,
            data: {
                action: 'rr_login_customer',
            },
            success: function(res) {
                // Login.
                window.open(res);
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    // Cancel.
    $(document).on('click', 'span#rr-account-cancel', function() {
        // Confirm.
        var conf = confirm('Are you sure you want to cancel?');
        // Check.
        if(conf == true) {
            // Send via AJAX.
            $.ajax({
                type: 'POST',
                url: admin_vars.ajax_url,
                data: {
                    action: 'rr_login_customer',
                },
                success: function(res) {
                    // Login.
                    window.open(res);
                },
                error: function(err) {
                    console.log(err);
                }
            });
        } else {
            // Nothing.
        }
    });

});