<?php

namespace App\Http\Requests\V1\Files;

use App\Http\Requests\V1\AppFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'id' => [
                'required'
            ],
            'files.*' => [
                'required',
                'mimes:pdf,txt,doc,docx,xls,xlsx,csv,ppt,pptx,zip,rar,7z,tar,jpg,jpeg,png,bmp,tiff,tif,svg',
                'file',
                'max:1024000',
            ],
        ];
        return AppFormRequest::rules($rules);
    }

    public function attributes()
    {
        $attributes = [
            'files.*' => 'archivo'
        ];
        return AppFormRequest::attributes($attributes);
    }
}
