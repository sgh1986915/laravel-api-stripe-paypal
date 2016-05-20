/*
 * jQuery File Upload Plugin JS Example 8.9.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function () {
    'use strict';

    $('.btn-finalize').click(function(){
        $('#fileupload').addClass('fileupload-processing');
        $( "#fileupload" ).prop( "disabled", true );
        $.ajax({
            url:'server/php/finalize.php',
            dataType:'json'
        }).always(function(){
            $('#fileupload').removeClass('fileupload-processing');
            $( "#fileupload" ).prop( "disabled", false);
            
        }).done(function(resp){
        	if(resp.error){
        		alert(resp.error);
        	}
        	else 	window.location.replace("http://www.whoisxmlapi.com/thankyou.php");
           
        }).fail(function( textStatus, errorThrown){
        	alert("failed: "+errorThrown)
        });



     });

    $('#fileupload').fileupload({
        autoUpload:true,
        submit: function (e, data) {
            var $this = $(this);


         },


    });
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        disableImageResize: false,
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: 'server/php/'
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/cors/result.html?%s'
        )
    );


        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});

        });

});
