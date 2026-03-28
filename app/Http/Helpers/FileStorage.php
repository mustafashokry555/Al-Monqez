<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

trait FileStorage
{
    private function uploadFile($request, $folder, $record = null, $uploadedFileName = 'image', $recordFileName = 'image')
    {
        if (!$record) {
            if (!$request->hasFile($uploadedFileName)) return null;
            return Storage::putFile($folder, $request->file($uploadedFileName));
        }

        $path = $record->$recordFileName;
        if (!$request->hasFile($uploadedFileName)) return (!$path) ? null : $path;

        if ($path)   $this->deleteFile($path);
        return Storage::putFile($folder, $request->file($uploadedFileName));
    }

    /*-----------------------------------------------------------------------------------------------*/

    private function uploadMultipleFiles($request, $folder, $uploadedFilesName = 'images')
    {
        $paths = [];

        if ($request->$uploadedFilesName) {
            foreach ($request->file($uploadedFilesName) as $image) {
                $path = Storage::putFile($folder, $image);
                array_push($paths, $path);
            }
        }

        return $paths;
    }

    /*-----------------------------------------------------------------------------------------------*/

    private function deleteFile($path)
    {
        if ($path) {
            Storage::delete($path);
        }
    }
}
