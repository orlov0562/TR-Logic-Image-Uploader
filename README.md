# TR-Logic-Image-Uploader
Image Uploader for TR Logic LLC

## Requirements
- PHP 7.1.3+ with GD module
- Composer 1.5.2+
- Bower 1.8.4+

## Installation

1. Create project directory and download repository files
```
mkdir image-uploader
cd image-uploader
git clone https://github.com/orlov0562/TR-Logic-Image-Uploader.git .
```
2. Install dependencies via Composer
```
composer install
```
3. Install dependencies via Bower
```
bower install
```
4. Run local web server with PHP
```
php -S localhost:8000 -t public
```
5. Open your browser and visit http://localhost:8000

If everything OK you will get test frontend with "Image Upload Form"

## REST API
### /api/upload endpoint
#### JSON POST requests

You can send POST request encoded as application/json. 

Expected format of data: JSON encoded array of values with one of the following type: URL or DATA URI.

JavaScript Example:
```
var images = [
  'https://raw.githubusercontent.com/orlov0562/FilesForExternalTests/master/test-file.jpg',
  'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsBAMAAACLU5NGAAAAG1BMVEUAmf////8fpf9fv/8/sv+f2P/f8v9/zP+/5f8U6SNkAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAC1UlEQVR4nO3Xz0/aYBzH8doW8biOH3IsoswjDJd4bBnuTBez7GjN5nbUxOxMXaL+2fs+T4t92g6ywyO7vF8J7Ye0ha/fp34BxwEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMD/5c5mYRE/njVT02j2vkj+bN5IllwEQdC/1fUlQTAIq6nJl2PBDx1PJD3UkiWtwX3ong9UjA/n7q9ONTUlT3NnlK1UgcF9+C6dVJIt7Uht75bSon4omywy01/c66t6shley+a0W0l2HbyRx1uVxldm2ihTj1A27k0l2dWWQuKlTh0zbXQXOn4vTxMjWeZJWUmo48BMG01Dp6V76uytjGRZSxaxWIPMTEo7L9KLzAsSx9m/0knWv0yWDWXZ8pVQy1Mmfexa76Yr43xXTtnTS63Wv0x2uTdyqxzmeTopk9756v/S8SpLOpZSYz3sHK9jJKv85KF80XhSpmKv2lVp1oWateuqO0ay6OtjcCk7rxg7w9syFVVLu4xmtZ7TXqQKjfRT99BINst6Tr9H28pS7TKa1Xp+7F++flnyeue9rWX5/VF1WIyy5Q7KkrdYbivLidNV9Xyvt5Oy2t2XGzae+LVbXg4HYe2Cu2h9WM6OX+OWV2QOvYyFqEzrw9PsunaBDLr1WOgaybbB5nGqZlY+uwzyQfP649Rxfm7+8NEzK661a3+1iw8f1aC8OW4laWpm1dslDSqaMzSTZV5nyxcbPbNq7ZpOdvHFZl/+3gN9xw5XZtIl65lVa1cqz/TXV1cdLZMtJ+rd3GyiPq/lxd00MpNRXpzv3M9qe6o6Of6mNt1KsmU8ODsaJXokfOrNj5NuNQkv3/lhXlb6e370IVAjwU8vjy6CqJKsOZffUj39lu6XZmryMrlAt8w5SZvJGn/x8svzeNFMTYtFWCR3ETUSAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD4N38AsX524K81XMUAAAAASUVORK5CYII='
];

var postData = JSON.stringify(images);
```
#### POST requests

You can send POST request encoded with "application/x-www-form-urlencoded" or "multipart/form-data" if you want to send files.

Expected format of data: one or multiple inputs with name "imageFile[]" that contains one of the following value: FILE, URL or DATA URI.

#### RESPONSE
In case when you sent wrong request, for example with not existing url or not image MIME, you will get a JSON answer in next formay
```
{
    code: <HTTP-CODE>,
    message: <Message that clarify what had happend>
}
```

In case if upload was succefull you will get next answer
```
{
    code: 200,
    output: [
        0: {
          image: <FULL-URL-TO-UPLOADED-IMAGE-1>
          thumb: <FULL-URL-TO-UPLOADED-IMAGE-1-THUMBNAIL>
        },
        1: {
          image: <FULL-URL-TO-UPLOADED-IMAGE-2>
          thumb: <FULL-URL-TO-UPLOADED-IMAGE-2-THUMBNAIL>
        },
        ...
    ]
}
```

## OS signals
The API support SIGTERM, SIGHUP signals from OS if pcntl php module loaded and pcntl_async_signals, pcntl_signal functions enabled. You can check this requirements with cli command
```
php artisan req:check
```
But pay attention that CLI and WEB SERVER configuration can be different, therefore additional checks with [phpinfo();](http://php.net/manual/en/function.phpinfo.php) function may be required.

In case when upload process stopped via this signal, the API will return response with next JSON answer
```
{
    code: 503
    message: Service unavailable try to repeat your request later
}
```

## Tests
You can find PHPUnit tests in /tests/* folder. To run tests execute next command from the project dir
```
./vendor/bin/phpunit
```
## Other
### How I can view examples
The best example of usage is frontend page with "Image Uploader Form". Open developer console of your browser and just play with a values of this form. You will see all requests, responses and it's values into developer console.

### How I can send requests directly to API endpoint
- You can write your own client according to requirements described in this readme.
- You can use one of tons applications which can do requests to REST api. The good one is [Postman](https://www.getpostman.com/)

### What key software you are using here?
- [Lumen - PHP Micro-Framework By Laravel](https://lumen.laravel.com/) as a backbone
- [Intervention Image](http://image.intervention.io/) for image processing
- [Bootstrap](https://getbootstrap.com/), [JQuery](https://jquery.com/) and [SimpleLightBox](http://simplelightbox.com/) for frontend
