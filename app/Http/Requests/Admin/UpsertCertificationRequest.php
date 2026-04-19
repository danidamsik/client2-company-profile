<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpsertCertificationRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'image' => [$this->isMethod('post') ? 'required' : 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Nama sertifikasi wajib diisi.',
            'title.max' => 'Nama sertifikasi maksimal 150 karakter.',
            'description.required' => 'Deskripsi sertifikasi wajib diisi.',
            'description.max' => 'Deskripsi sertifikasi maksimal 2000 karakter.',
            'image.required' => 'Gambar sertifikasi wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar sertifikasi harus berformat JPG atau PNG.',
            'image.max' => 'Ukuran gambar sertifikasi maksimal 2MB.',
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
