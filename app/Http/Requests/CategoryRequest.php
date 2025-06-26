<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => [
                'required',
                'max:100',
                Rule::unique('categories', 'name')->ignore($id),
            ],
            'type' => 'required|in:alat,bahan,ruangan',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Nama maksimal 100 karakter!',
            'name.unique' => 'Tag sudah ada!',
            'type.required' => 'Tipe tidak boleh kosong!',
            'type.in' => 'Tipe tidak valid!',
        ];
    }
}
