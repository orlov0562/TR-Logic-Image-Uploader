<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Models\ImageStorage;
use App\Models\RequestImageReader;

class ApiController extends Controller
{
    protected $terminate = false;

    /**
     * Create a new API instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (extension_loaded('pcntl')) {
            pcntl_async_signals(true);
            foreach([SIGTERM, SIGHUP] as $signal) {
                pcntl_signal($signal, function($signo){
                    $this->terminate = true;
                });
            }
        }
    }

    /**
     * Upload endpoint for images
     * @param \App\Models\RequestImageReader $requestReader
     * @return \Illuminate\Http\Response
     */
    public function actionUpload(Request $request, RequestImageReader $requestReader)
    {
        $processedImages = [];

        $imageItems = $requestReader->getImageItems();

        if ($imageItems) {

            $images = [];

            foreach($imageItems as $imageItem) {
                if ($this->terminate) return $this->actionOnTerminate();
                try {
                    $images[] = Image::make($imageItem);
                } catch (\Exception $ex) {
                    return response()->json([
                        'code' => 415,
                        'message' => $ex->getMessage(),
                    ], 415);
                }
            }

            if ($images) {

                // We need this unique id to separate uploaded images between users
                // which can use API at the same time. This uid will be appended to
                // the image filenames as prefix. Don't want implement more complicable
                // solution, believe it's enough for this task case.
                $uid = md5($request->ip());

                try {

                    foreach($images as $k=>$image) {

                        if ($this->terminate) return $this->actionOnTerminate();

                        $result = ImageStorage::saveImage($image, $uid, $k);

                        $processedImages[] = [
                            'image' => $result['fileuri'],
                            'thumb' => $result['thumb_fileuri'],
                        ];

                    }

                } catch (\Exception $ex) {
                    return response()->json([
                        'code' => 500,
                        'message' => $ex->getMessage(),
                    ], 500);
                }

            }

        }

        return response()->json([
            'code' => 200,
            'output' => $processedImages,
        ]);
    }

    protected function actionOnTerminate(){

        // here we can cleen failed uploads or something like this

        return response()->json([
            'code' => 503,
            'message' => 'Service unavailable try to repeat your request later',
        ], 503);
    }
}