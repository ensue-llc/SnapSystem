<?php

namespace Ensue\Snap\Controllers;

use Ensue\Snap\Event\FileUpload;
use Ensue\Snap\Requests\FileRequest;
use Ensue\Snap\Requests\MultipleFileRequest;
use Illuminate\Http\JsonResponse;

class FileController extends SnapController
{
    /**
     * @param FileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadResources(FileRequest $request) : JsonResponse
    {
        $type = $request->get('type');
        $result = $this->upload($request);
        if ($type === 'document') {
            event(new FileUpload($request->file('file')->getClientOriginalName(), $result['path']));
        }
        return $this->responseOk($result);
    }

    /**
     * @param MultipleFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadMultipleResources(MultipleFileRequest $request) : JsonResponse
    {
        $files = $request->file('files');
        $result = [];
        $type = $request->get('type');

        foreach ($files as $key => $file) {
            $fileName = $file->getClientOriginalName();
            $response = $this->uploadFile($file);
            $response['filename'] = $fileName;
            $result[] = $response;
            if ($type === 'document') {
                event(new FileUpload($fileName, $result[$key]['path']));
            }
        }

        return $this->responseOk($result);
    }
}
