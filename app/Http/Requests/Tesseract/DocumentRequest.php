<?php

namespace App\Http\Requests\Tesseract;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // permissions checking
    }

    public function rules()
    {
        return [
            'name'  => 'required',
            'file'  => 'required|mimetypes:image/jpeg,image/png,application/pdf',
        ];
    }

    public function messages()
    {
        return [
            'file.mimetypes' => 'You must upload document either in .pdf, .jpg or .png format.',
        ];
    }
}
