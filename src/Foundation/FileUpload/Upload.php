<?php

namespace Ensue\Snap\Foundation\FileUpload;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * Trait Upload
 *
 * @package App\System\Foundation\FileUpload
 */
trait Upload
{
    /**
     * @param Request $request
     * @param string $storePath
     * @param bool $public
     * @param string $fileKey
     *
     * @return array Array containing image path and thumb path
     */
    public function upload(Request $request, string $storePath = '', bool $public = true, string $fileKey = 'file'): array
    {
        $file = $request->file($fileKey);

        return $this->uploadFile($file, $storePath, $public);
    }

    /**
     * core function of file upload
     *
     * @param  $file
     * @param string $storePath
     * @param bool $public
     * @return array
     */
    public function uploadFile($file, string $storePath = '', bool $public = true): array
    {
        $createThumb = false;
        $mimeType = $file->getClientMimeType();
        if (!$storePath) {
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/png':
                case 'image/bmp':
                case 'image/gif':
                    $storePath = config('fileupload.temp_dir_folder') . '/' . config('fileupload.image_directory');
                    $createThumb = config('fileupload.create_thumb');
                    break;
                case 'video/x-flv':
                case 'video/mp4':
                case 'application/x-mpegURL':
                case 'video/MP2T':
                case 'video/3gpp':
                case 'video/quicktime':
                case 'video/x-msvideo':
                case 'video/x-ms-wmv':
                    $storePath = config('fileupload.temp_dir_folder') . '/' . config('fileupload.video_directory');
                    break;
                default:
                    $storePath = config('fileupload.temp_dir_folder') . '/' . config('fileupload.document_directory');
                    break;
            }
        }

        if ($public) {
            $path = $file->storePublicly($storePath);
            $visibility = 'public';
            //remove the public prefix from the path
        } else {
            $path = $file->store($storePath);
            $visibility = 'private';
        }
        $thumbPath = '';

        if ($createThumb === true) {
            try {
                $image = Image::make($file);
                $ratio = $image->height() / $image->width();
                $width = config('fileupload.thumb_width', 100);
                $thumb = $image->resize($width, $ratio * $width);

                $tp = config('fileupload.temp_dir_folder') . '/' . config('fileupload.image_thumb_directory') . '/' . filename_from_path($path, config('fileupload.thumb_suffix'));

                if (config('filesystems.default', 'local') === 'local') {
                    Storage::put($tp, $image, $visibility);
                } else {
                    $thumbPathInfo = PHP_OS === "WINNT" ? pathinfo($thumb->basePath(), PATHINFO_EXTENSION) : $thumb->basePath();
                    Storage::put($tp, $thumb->save($thumbPathInfo, 100));                }
                $thumbPath = Storage::url($tp);
            } catch (Exception $exception) {
                $thumbPath = null;
            }
        }

        return [
            'path' => Storage::url($path),
            'thumb_path' => $thumbPath,
            'type' => $mimeType,
        ];
    }

    /**
     * @param  $source
     * @return string
     */
    public function moveFromTemp($source): string
    {
        //if file doesn't content temp dir then return from here
        if (!Str::contains($source, '/' . 'temp' . '/')) {
            return $source;
        }
        $fileSystem = config('filesystems.default', 'local');
        if ($fileSystem == 'local' || $fileSystem == 'public') {
            $source = str_replace(config('app.url'), '', $source);
            $source = str_replace('/storage' . '/', '', $source);
        } else {
            $replace_uri = config('filesystems.disks.' . $fileSystem . '.storage_api_uri');
            $source = str_replace($replace_uri . '/', '', $source);
        }

        $destinationPath = str_replace(config('fileupload.temp_dir_folder') . '/', '', $source);
        if (!Storage::exists($source)) {
            return Storage::url($destinationPath);
        }
        if (!Storage::exists($destinationPath)) {
            Storage::move($source, $destinationPath);
        }
        return Storage::url($destinationPath);
    }

    /**
     * @param Request $request
     * @param string $storePath
     * @param bool $public
     * @param string $fileKey
     * @return array
     */
    public function multipleUpload(Request $request, string $storePath = "", bool $public = true, string $fileKey = "files"): array
    {
        $files = $request->file($fileKey);
        $result = [];
        foreach ($files as $file) {
            array_push($result, $this->uploadFile($file, $storePath, $public));
        }

        return $result;
    }

}
