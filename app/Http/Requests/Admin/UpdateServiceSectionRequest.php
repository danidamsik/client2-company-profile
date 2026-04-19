<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateServiceSectionRequest extends FormRequest
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
            'cta_label' => Str::squish($this->cleanInput($this->input('cta_label'))),
            'cta_url' => Str::squish($this->cleanInput($this->input('cta_url'))),
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
            'cta_label' => ['nullable', 'string', 'max:60'],
            'cta_url' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul section layanan wajib diisi.',
            'title.max' => 'Judul section layanan maksimal 180 karakter.',
            'subtitle.required' => 'Deskripsi section layanan wajib diisi.',
            'subtitle.max' => 'Deskripsi section layanan maksimal 900 karakter.',
            'cta_label.max' => 'Label CTA maksimal 60 karakter.',
            'cta_url.max' => 'URL CTA maksimal 255 karakter.',
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
