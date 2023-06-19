<?php

namespace Ensue\Snap\Requests;

use Illuminate\Validation\Rule;

final class FileRequest extends SnapRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $type =$this->get('type');
        return [
            "type" => "required|in:image,document,video",
            'file' => ['required',
                'file',
                Rule::when($type=='image',['mimes:jpg,png,jpeg,svg']),
                Rule::when($type=='document',['mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,jpg,png,jpeg']),
                Rule::when($type=='video',['mimes:mp4']),
            ],
        ];
    }
}
