var lightbox = null;

var createGallery = function(imageUrls) {
   var gallery = $('<div/>',{class:'gallery'});
    for (var i=0; i<imageUrls.length; i++) {
    	var galleryItem = $('<a/>',{
    		href:imageUrls[i].image,
    		class:'gallery-item',
    		target:'_blank'
    	});
    	galleryItem.append($('<img/>',{src:imageUrls[i].thumb+'?_r='+Math.random()}));
    	gallery.append(galleryItem);
    }

    $('#result').html('').append('<hr><h2>Uploaded images</h2>').append(gallery);
    lightbox = $('.gallery a').simpleLightbox({
    	overlay:true
    });
};

$(function(){
	$('#form-upload-files').submit(function(e){

		var form = $('#form-upload-files').get(0);
		var formData = new FormData(form);
		var formInputs = $('#form-upload-files input');

		formInputs.prop("disabled", true);

		$('#result').html('Uploading..');

        $.ajax({
        	url: apiEndpoint.upload,
            type: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            cache: false,
			success: function (data) {
				createGallery(data.output);
            },
            error: function (e) {
            	console.log('Error: ', e);
				$('#result').html(
					'<h2> Upload error #'+e.status+'</h2>'
					+'<p>' + e.statusText+': '+ e.responseJSON.message+'</p>'
				);
            },
            complete: function(e){
            	formInputs.prop("disabled", false);
            }
		});

		return false;
	});

	$('#form-upload-files-submit-as-json').click(function(){
		var formInputs = $('#form-upload-files input');
		var formTextInputs = $('#form-upload-files input[type=text]');
		var formFileInputs = $('#form-upload-files input[type=file]');

		var formData = [];

		for (var i=0; i<formTextInputs.length; i++){
			if (!formTextInputs[i].value.trim()) continue;
			formData.push(formTextInputs[i].value.trim());
		}

		var sendFormDataAsJson = function(){
            $.ajax({
            	url: apiEndpoint.upload,
                type: 'POST',
                data: JSON.stringify(formData),
                contentType:'application/json; charset=utf-8',
                dataType: 'json',
                cache: false,
				success: function (data) {
					createGallery(data.output);
                },
                error: function (e) {
                	console.log('Error: ', e);
					$('#result').html(
						'<h2> Upload error #'+e.status+'</h2>'
						+'<p>' + e.statusText+': '+ e.responseJSON.message+'</p>'
					);
                },
                complete: function(e){
                	formInputs.prop("disabled", false);
                }
			});
		};

		var fileReaderCallback = function(dataUrlEncodedItems) {
			if (dataUrlEncodedItems.length) {
				formData.push.apply(formData, dataUrlEncodedItems);
			}
			sendFormDataAsJson();
		};

		var fileReaderStorage = {
			files: [],
			processed: [],
			results: [],
			loadFilesFromInputs:function(inputs){
				for (var i=0; i<inputs.length; i++){
					if (inputs[i].files.length) {
						this.files.push(inputs[i].files[0]);
						this.processed.push(false);
						this.results.push(null);
					}
				}
			},
			isAllProcessed: function(){
				var ret = true;
				for (var i=0; i<this.processed.length; i++) {
					if (this.processed[i] !== true) {
						ret = false;
						break;
					}
				}
				return ret;
			}
		};

		fileReaderStorage.loadFilesFromInputs(formFileInputs);

		if (!fileReaderStorage.files.length) {
			sendFormDataAsJson();
		} else {
			for (var i=0; i<fileReaderStorage.files.length; i++){
				var reader = new FileReader();
				reader.readAsDataURL(fileReaderStorage.files[i]);
				reader.onload = (function(idx) {
					return function(e) {
						fileReaderStorage.results[idx] = e.target.result;
						fileReaderStorage.processed[idx] = true;
						if (fileReaderStorage.isAllProcessed()) {
							fileReaderCallback(fileReaderStorage.results);
						}
					};
				})(i);
				reader.onerror = function (e) {
					console.log('Error: ', e);
				};
			}
		}

		return false;
	});

});
