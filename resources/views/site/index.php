<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex,nofollow">
        <meta name="googlebot" content="noindex,nofollow">

		<title>Image Uploader</title>

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=url('vendor/simplelightbox/dist/simplelightbox.min.css')?>">
		<link rel="stylesheet" href="<?=url('media/css/style.css')?>">
		<script>
			var apiEndpoint = {
				upload: '<?=url('api/upload')?>'
			};
		</script>
	</head>
    <body>

		<div class="container">
  			<h1><a href="<?=url()?>">Image Uploader</a></h1>
			<hr>
            <form id="form-upload-files">
          		<div class="form-group">
          			<label>Choose image(s)</label>
      	  		</div>
          		<div class="form-group">
            		<input name="imageFile[]" type="file" class="form-control-file">
          		</div>
          		<div class="form-group">
            		<input name="imageFile[]" type="file" class="form-control-file">
          		</div>
          		<div class="form-group">
            		<input name="imageFile[]" type="file" class="form-control-file">
          		</div>

          		<hr>

          		<div class="form-group">
          			<label>Image URLs or Data URIs</label>
            		<input name="imageFile[]" type="text" class="form-control" value="https://raw.githubusercontent.com/orlov0562/FilesForExternalTests/master/test-file.jpg" placeholder="https:// OR data:image/">
          		</div>
          		<div class="form-group">
            		<input name="imageFile[]" type="text" class="form-control" value="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsBAMAAACLU5NGAAAAG1BMVEUAmf////8fpf9fv/8/sv+f2P/f8v9/zP+/5f8U6SNkAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAC1UlEQVR4nO3Xz0/aYBzH8doW8biOH3IsoswjDJd4bBnuTBez7GjN5nbUxOxMXaL+2fs+T4t92g6ywyO7vF8J7Ye0ha/fp34BxwEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMD/5c5mYRE/njVT02j2vkj+bN5IllwEQdC/1fUlQTAIq6nJl2PBDx1PJD3UkiWtwX3ong9UjA/n7q9ONTUlT3NnlK1UgcF9+C6dVJIt7Uht75bSon4omywy01/c66t6shley+a0W0l2HbyRx1uVxldm2ihTj1A27k0l2dWWQuKlTh0zbXQXOn4vTxMjWeZJWUmo48BMG01Dp6V76uytjGRZSxaxWIPMTEo7L9KLzAsSx9m/0knWv0yWDWXZ8pVQy1Mmfexa76Yr43xXTtnTS63Wv0x2uTdyqxzmeTopk9756v/S8SpLOpZSYz3sHK9jJKv85KF80XhSpmKv2lVp1oWateuqO0ay6OtjcCk7rxg7w9syFVVLu4xmtZ7TXqQKjfRT99BINst6Tr9H28pS7TKa1Xp+7F++flnyeue9rWX5/VF1WIyy5Q7KkrdYbivLidNV9Xyvt5Oy2t2XGzae+LVbXg4HYe2Cu2h9WM6OX+OWV2QOvYyFqEzrw9PsunaBDLr1WOgaybbB5nGqZlY+uwzyQfP649Rxfm7+8NEzK661a3+1iw8f1aC8OW4laWpm1dslDSqaMzSTZV5nyxcbPbNq7ZpOdvHFZl/+3gN9xw5XZtIl65lVa1cqz/TXV1cdLZMtJ+rd3GyiPq/lxd00MpNRXpzv3M9qe6o6Of6mNt1KsmU8ODsaJXokfOrNj5NuNQkv3/lhXlb6e370IVAjwU8vjy6CqJKsOZffUj39lu6XZmryMrlAt8w5SZvJGn/x8svzeNFMTYtFWCR3ETUSAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD4N38AsX524K81XMUAAAAASUVORK5CYII=" placeholder="https:// OR data:image/">
          		</div>
          		<div class="form-group">
            		<input name="imageFile[]" type="text" class="form-control" value="" placeholder="https:// OR data:image/">
          		</div>

          		<hr>

          		<div class="form-group">
            		<input type="submit" class="btn btn-primary" value="Submit">
            		<input type="button" class="btn btn-primary" id="form-upload-files-submit-as-json" value="Submit as JSON">
            		<input type="reset" class="btn btn-default" value="Reset">
          		</div>
            </form>

            <div id="result"></div>
		</div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="<?=url('vendor/simplelightbox/dist/simple-lightbox.min.js')?>"></script>
        <script src="<?=url('media/js/script.js')?>"></script>
    </body>
</html>

