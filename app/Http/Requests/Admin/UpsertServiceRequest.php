<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpsertServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => Str::squish($this->cleanInput($this->input('title'))),
            'description' => $this->cleanInput($this->input('description')),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul layanan wajib diisi.',
            'title.max' => 'Judul layanan maksimal 120 karakter.',
            'description.required' => 'Deskripsi layanan wajib diisi.',
            'description.max' => 'Deskripsi layanan maksimal 2000 karakter.',
        ];
    }

    private function cleanInput(mixed $value): string
    {
        $input = (string) $value;
        $input = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $input) ?? '';
        $input = preg_replace('/<style\b[^>]*>.*?<\/style>/is', '', $input) ?? '';

        return trim(strip_tags($input));
    }
}
