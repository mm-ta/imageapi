<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class ResizeImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'image' => ['required'],
            'w' => [
                'required',
                'w' => 'regex: /\d(\.?\d+)%?$/', // to match numeric values: 20%, 20.11%, 1920, 90.34
            ],
            'h' => ['regex: /\d(\.?\d+)%?$/'],
            'album_id' => 'exists:albums,id',
        ];

        $image = $this->all()['image'] ?? false;

        if ($image && $image instanceof UploadedFile) {
            $rules['image'][] = 'image';
        } else { // then it should be a link to the image
            $rules['image'][] = 'url';
        }

        return $rules;
    }
}
