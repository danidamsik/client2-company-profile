<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpsertAboutSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'eyebrow' => Str::squish($this->cleanInput($this->input('eyebrow'))),
            'title' => Str::squish($this->cleanInput($this->input('title'))),
            'subtitle' => $this->cleanInput($this->input('subtitle')),
            'advantages' => $this->cleanInput($this->input('advantages')),
            'vision' => $this->cleanInput($this->input('vision')),
            'mission' => $this->cleanInput($this->input('mission')),
            'values_text' => $this->cleanInput($this->input('values_text')),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['required', 'string', 'max:900'],
            'advantages' => ['nullable', 'string', 'max:2000'],
            'vision' => ['nullable', 'string', 'max:700'],
            'mission' => ['nullable', 'string', 'max:700'],
            'values_text' => ['nullable', 'string', 'max:700'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tentang kami wajib diisi.',
            'title.max' => 'Judul tentang kami maksimal 180 karakter.',
            'subtitle.required' => 'Deskripsi tentang kami wajib diisi.',
            'subtitle.max' => 'Deskripsi tentang kami maksimal 900 karakter.',
            'image.image' => 'File tentang kami harus berupa gambar.',
            'image.mimes' => 'Gambar tentang kami harus berformat JPG atau PNG.',
            'image.max' => 'Ukuran gambar tentang kami maksimal 2MB.',
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
