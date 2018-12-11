<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\ImageStorage;

class ApiUploadTest extends TestCase
{
    const TEST_IMG_BASE64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsBAMAAACLU5NGAAAAG1BMVEUAmf////8fpf9fv/8/sv+f2P/f8v9/zP+/5f8U6SNkAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAC1UlEQVR4nO3Xz0/aYBzH8doW8biOH3IsoswjDJd4bBnuTBez7GjN5nbUxOxMXaL+2fs+T4t92g6ywyO7vF8J7Ye0ha/fp34BxwEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMD/5c5mYRE/njVT02j2vkj+bN5IllwEQdC/1fUlQTAIq6nJl2PBDx1PJD3UkiWtwX3ong9UjA/n7q9ONTUlT3NnlK1UgcF9+C6dVJIt7Uht75bSon4omywy01/c66t6shley+a0W0l2HbyRx1uVxldm2ihTj1A27k0l2dWWQuKlTh0zbXQXOn4vTxMjWeZJWUmo48BMG01Dp6V76uytjGRZSxaxWIPMTEo7L9KLzAsSx9m/0knWv0yWDWXZ8pVQy1Mmfexa76Yr43xXTtnTS63Wv0x2uTdyqxzmeTopk9756v/S8SpLOpZSYz3sHK9jJKv85KF80XhSpmKv2lVp1oWateuqO0ay6OtjcCk7rxg7w9syFVVLu4xmtZ7TXqQKjfRT99BINst6Tr9H28pS7TKa1Xp+7F++flnyeue9rWX5/VF1WIyy5Q7KkrdYbivLidNV9Xyvt5Oy2t2XGzae+LVbXg4HYe2Cu2h9WM6OX+OWV2QOvYyFqEzrw9PsunaBDLr1WOgaybbB5nGqZlY+uwzyQfP649Rxfm7+8NEzK661a3+1iw8f1aC8OW4laWpm1dslDSqaMzSTZV5nyxcbPbNq7ZpOdvHFZl/+3gN9xw5XZtIl65lVa1cqz/TXV1cdLZMtJ+rd3GyiPq/lxd00MpNRXpzv3M9qe6o6Of6mNt1KsmU8ODsaJXokfOrNj5NuNQkv3/lhXlb6e370IVAjwU8vjy6CqJKsOZffUj39lu6XZmryMrlAt8w5SZvJGn/x8svzeNFMTYtFWCR3ETUSAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD4N38AsX524K81XMUAAAAASUVORK5CYII=';
    const TEST_IMG_URL = 'https://raw.githubusercontent.com/orlov0562/FilesForExternalTests/master/test-file.jpg';
    const TEST_IMG_BROKEN_URL = 'https://raw.githubusercontent.com/orlov0562/FilesForExternalTests/master/no-test-file.jpg';
    const TEST_NOT_IMG_URL = 'https://raw.githubusercontent.com/orlov0562/FilesForExternalTests/master/test-file.pdf';

    /**
     * Check GET request to endpoint
     *
     * @return void
     */
    public function testEmptyGetRequest()
    {
        $response = $this->get('/api/upload');
        $response->assertStatus(405);
    }

    /**
     * Check POST request to endpoint without params
     *
     * @return void
     */
    public function testEmptyPostRequest()
    {
        $response = $this->post('/api/upload');
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'code' => 200,
                'output' => [],
            ]);
        ;
    }

    /**
     * Check POST request to endpoint with empty data
     *
     * @return void
     */

    public function testPostRequestWithEmptyData()
    {
        $response = $this->post('/api/upload',[
            'imageFile' => [''],
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'code' => 200,
                'output' => [],
            ]);
        ;
    }

    /**
     * Check POST request to endpoint with base64 encoded file
     *
     * @return void
     */
    public function testPostRequestWithBase64()
    {
        Storage::fake(ImageStorage::STORAGE_DISK);

        $response = $this->post('/api/upload',[
            'imageFile' => [
                self::TEST_IMG_BASE64,
            ]
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'output' => [
                    '*' => [
                        'image',
                        'thumb',
                    ],
                ],
            ]);
        ;
    }

    /**
     * Check POST request to endpoint with broken base64 encoded file
     *
     * @return void
     */
    public function testPostRequestWithBrokenBase64()
    {
        Storage::fake(ImageStorage::STORAGE_DISK);

        $response = $this->post('/api/upload',[
            'imageFile' => [
                preg_replace('~\d~','0',self::TEST_IMG_BASE64),
            ]
        ]);

        $response
            ->assertStatus(415)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }

    /**
     * Check POST request to endpoint with external image URL
     *
     * @return void
     */
    public function testPostRequestWithUrl()
    {
        Storage::fake(ImageStorage::STORAGE_DISK);

        $response = $this->post('/api/upload',[
            'imageFile' => [
                self::TEST_IMG_URL,
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'output' => [
                    '*' => [
                        'image',
                        'thumb',
                    ],
                ],
            ]);
        ;
    }

    /**
     * Check POST request to endpoint with external broken URL
     *
     * @return void
     */
    public function testPostRequestWithBrokenUrl()
    {
        $response = $this->post('/api/upload',[
            'imageFile' => [
                self::TEST_IMG_BROKEN_URL,
            ],
        ]);

        $response
            ->assertStatus(415)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }

    /**
     * Check POST request to endpoint with wrong Mime
     *
     * @return void
     */
    public function testPostRequestWithWrongMimeUrl()
    {
        $response = $this->post('/api/upload',[
            'imageFile' => [
                self::TEST_NOT_IMG_URL
            ],
        ]);

        $response
            ->assertStatus(415)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }

    /**
     * Check POST request with image uploads
     *
     * @return void
     */
    public function testPostRequestWithImageUploads()
    {

        Storage::fake(ImageStorage::STORAGE_DISK);

        $file = UploadedFile::fake()->image('fake-test-image.jpg');

        $response = $this->post('/api/upload',[
            'imageFile' => [
                $file,
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'output' => [
                    '*' => [
                        'image',
                        'thumb',
                    ],
                ],
            ]);
        ;
    }

    /**
     * Check POST request with image uploads
     *
     * @return void
     */
    public function testPostRequestWithNotImageUploads()
    {

        Storage::fake(ImageStorage::STORAGE_DISK);

        $file = UploadedFile::fake()->create('fake-test-file.pdf');

        $response = $this->post('/api/upload',[
            'imageFile' => [
                $file,
            ],
        ]);

        $response
            ->assertStatus(415)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }

    /**
     * Check POST request to endpoint with multiple data
     *
     * @return void
     */
    public function testPostRequestMultipleData()
    {
        Storage::fake(ImageStorage::STORAGE_DISK);

        $file = UploadedFile::fake()->image('fake-test-image.jpg');

        $response = $this->post('/api/upload',[
            'imageFile' => [
                $file,
                $file,
                $file,
                self::TEST_IMG_URL,
                self::TEST_IMG_BASE64,
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'output' => [
                    '*' => [
                        'image',
                        'thumb',
                    ],
                ],
            ]);
        ;
    }

    /**
     * Check JSON request to endpoint with base64 encoded file
     *
     * @return void
     */

    public function testJsonRequestWithBase64EncodedFile()
    {
        Storage::fake(ImageStorage::STORAGE_DISK);

        $response = $this->json('post', '/api/upload',[
            self::TEST_IMG_BASE64,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'output' => [
                    '*' => [
                        'image',
                        'thumb',
                    ],
                ],
            ]);
        ;
    }

    /**
     * Check JSON request to endpoint with wrong base64 encoded file
     *
     * @return void
     */

    public function testJsonRequestWithWrongBase64EncodedFile()
    {
        Storage::fake(ImageStorage::STORAGE_DISK);

        $response = $this->json('post', '/api/upload',[
            preg_replace('~\d~','0',self::TEST_IMG_BASE64),
        ]);

        $response
            ->assertStatus(415)
            ->assertJsonStructure([
                'code',
                'message'
            ]);
    }
}
