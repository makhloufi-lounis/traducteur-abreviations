/*
 * jQuery File Upload Plugin JS Example 8.9.1
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/* global $, window */

$(function() {
	'use strict';

	// Initialize the jQuery File Upload widget:
	$('#fileupload').fileupload({
		// Uncomment the following to send cross-domain cookies:
		// xhrFields: {withCredentials: true},
		url : '/plugins/jquery-file-upload/server/php/',
		maxNumberOfFiles : 20,
		acceptFileTypes : /(\.|\/)(png|jpg|jpeg|gif)$/i, 
		maxFileSize : 5000000,
		autoUpload:true

	});

	// Enable iframe cross-domain access via redirect option:
	$('#fileupload').fileupload('option', 'redirect', window.location.href.replace(/\/[^\/]*$/, '/cors/result.html?%s'));

	// Load existing files:
	$('#fileupload').addClass('fileupload-processing');

	var myDir = '/images/uploads/' + $(this).find("[name='uniqId']").val() + '/';
	$.ajax({
		// Uncomment the following to send cross-domain cookies:
		// xhrFields: {withCredentials: true},
		url : $('#fileupload').fileupload('option', 'url') + '?otherDir=' + myDir,
		dataType : 'json',
		context : $('#fileupload')[0]
	}).always(function() {
		$(this).removeClass('fileupload-processing');
	}).done(function(result) {
		$(this).fileupload('option', 'done').call(this, $.Event('done'), {
			result : result
		});
	});

});
