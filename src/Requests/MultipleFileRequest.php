<?php

namespace Ensue\Snap\Requests;

use Illuminate\Validation\Rule;

class MultipleFileRequest extends SnapRequest
{
    public function rules(): array
    {
        $type = $this->get('type');
        return [
            'files' => 'required|array',
            'type' => "required|in:image,document,video",
            'files.*' => ['required',
                'file',
                Rule::when($type == 'image', ['mimes:jpg,png,jpeg,svg']),
                Rule::when($type == 'document', ['mimes:pdf,docx,doc,xlsx,xls,ppt,pptx,jpg,png,jpeg']),
                Rule::when($type == 'video', ['mimes:mp4']),
            ],
        ];
    }
}
