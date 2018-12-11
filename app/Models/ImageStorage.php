<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class ImageStorage
{
    const THUMB_SIZE_WIDTH = 100;
    const THUMB_SIZE_HEIGHT = 100;

    const STORAGE_DISK = 'public';
    const OUTPUT_FORMAT = 'jpg';

    /**
     * Save image to the storage
     *
     * @param \Intervention\Image\Image $image
     * @return array
     */
    public static function saveImage(\Intervention\Image\Image $image, $prefix=null, $postfix=null)
    {
        // Save main image

        $filename = ($prefix ? $prefix.'-' : '')
                    .'image'
                    .($postfix ? '-'.$postfix : '')
                    .'.'.self::OUTPUT_FORMAT
        ;

        Storage::disk(self::STORAGE_DISK)->put($filename, $image->stream());

        $fileuri = url(Storage::disk(self::STORAGE_DISK)->url($filename));

        // Create and save thumbnail

        $thumbFilename = ($prefix ? $prefix.'-' : '')
                    .'thumb-image'
                    .($postfix ? '-'.$postfix : '')
                    .'.'.self::OUTPUT_FORMAT
        ;

        $image = $image->fit(self::THUMB_SIZE_WIDTH, self::THUMB_SIZE_HEIGHT);

        Storage::disk(self::STORAGE_DISK)->put($thumbFilename, $image->stream());

        $thumburi = url(Storage::disk(self::STORAGE_DISK)->url($thumbFilename));

        return [
            'filename' => $filename,
            'fileuri' => $fileuri,

            'thumb_filename' => $thumbFilename,
            'thumb_fileuri' => $thumburi,
        ];
    }
}
