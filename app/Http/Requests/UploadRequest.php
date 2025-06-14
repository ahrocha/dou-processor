<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // forçamos true pois não há autenticação ou autorização no desafio
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'arquivo' => 'required|file|mimes:zip|max:200000', // ~200MB
        ];
    }

        public function messages(): array
    {
        return [
            'arquivo.required' => 'O arquivo é obrigatório.',
            'arquivo.file' => 'O campo deve ser um arquivo válido.',
            'arquivo.mimes' => 'Apenas arquivos .zip são permitidos.',
            'arquivo.max' => 'O arquivo não pode exceder 200MB.',
        ];
    }
}
