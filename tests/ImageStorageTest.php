<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\ImageStorage;
use Intervention\Image\Facades\Image as Image;

class ImageStorageTest extends TestCase
{
    /**
     * Check saveImage method
     *
     * @return void
     */
    public function testSaveImageMethod()
    {

        Storage::fake(ImageStorage::STORAGE_DISK);
        $file = UploadedFile::fake()->image('fake-test-image.jpg');

        $image = Image::make($file->getRealPath());

        $prefix = 'prefix';
        $postfix = 'postfix';

        $filename = $prefix.'-image-'.$postfix.'.'.ImageStorage::OUTPUT_FORMAT;
        $thumbFilename = $prefix.'-thumb-image-'.$postfix.'.'.ImageStorage::OUTPUT_FORMAT;

        $result = ImageStorage::saveImage($image, $prefix, $postfix);

        Storage::disk(ImageStorage::STORAGE_DISK)->assertExists($filename);
        Storage::disk(ImageStorage::STORAGE_DISK)->assertExists($thumbFilename);

        $fileuri = url(Storage::disk(ImageStorage::STORAGE_DISK)->url($filename));
        $thumburi = url(Storage::disk(ImageStorage::STORAGE_DISK)->url($thumbFilename));

        $this->assertEquals($result, [
            'filename' => $filename,
            'fileuri' => $fileuri,
            'thumb_filename' => $thumbFilename,
            'thumb_fileuri' => $thumburi,
        ]);
    }
}