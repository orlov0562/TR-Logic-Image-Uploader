<?php

namespace App\Models;

use Illuminate\Http\Request;

class RequestImageReader
{
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Read images items from request
     *
     * @param \Illuminate\Http\Request $request
     * @return array of string
     */

    public function getImageItems(){
        return array_merge(
            $this->getImageItemsFromJson(),
            $this->getImageItemsFromMultipartForm(),
            $this->getImageItemsFromForm()
        );
    }

    /**
     * Read images from JSON request
     *
     * @param \Illuminate\Http\Request $request
     * @return array of string
     */
    protected function getImageItemsFromJson()
    {
        $ret = [];

        if (!$this->request->isJson()) return $ret;

        $items = $this->request->json()->all();

        if (!$items) return $ret;

        foreach($items as $item) {
            if (!trim($item)) continue;
            $ret[] = $item;
        }

        return $ret;
    }

    /**
     * Read images from multipart/form-data form
     *
     * @param \Illuminate\Http\Request $request
     * @return array of string
     */
    protected function getImageItemsFromMultipartForm()
    {
        $ret = [];

        if ($this->request->isJson()) return $ret;

        $files = $this->request->file('imageFile');
        if (!$files) return $ret;

        foreach($files as $file) {
            $ret[] = $file->getRealPath();
        }

        return $ret;
    }

    /**
     * Read images from form
     *
     * @param \Illuminate\Http\Request $request
     * @return array of string
     */
    protected function getImageItemsFromForm()
    {
        $ret = [];

        if ($this->request->isJson()) return $ret;

        $items = $this->request->post('imageFile');
        if (!$items) return $ret;

        foreach($items as $item) {
            if (!trim($item)) continue;
            $ret[] = $item;
        }

        return $ret;
    }

}