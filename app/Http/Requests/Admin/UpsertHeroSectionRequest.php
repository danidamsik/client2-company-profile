<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpsertHeroSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'eyebrow' => Str::squish($this->cleanInput($this->input('eyebrow'))),
            'badge' => Str::squish($this->cleanInput($this->input('badge'))),
            'title' => Str::squish($this->cleanInput($this->input('title'))),
            'subtitle' => $this->cleanInput($this->input('subtitle')),
            'primary_cta_label' => Str::squish($this->cleanInput($this->input('primary_cta_label'))),
            'primary_cta_url' => Str::squish($this->cleanInput($this->input('primary_cta_url'))),
            'secondary_cta_label' => Str::squish($this->cleanInput($this->input('secondary_cta_label'))),
            'secondary_cta_url' => Str::squish($this->cleanInput($this->input('secondary_cta_url'))),
            'note' => Str::squish($this->cleanInput($this->input('note'))),
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
            'badge' => ['nullable', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:160'],
            'subtitle' => ['required', 'string', 'max:700'],
            'primary_cta_label' => ['nullable', 'string', 'max:60'],
            'primary_cta_url' => ['nullable', 'string', 'max:255'],
            'secondary_cta_label' => ['nullable', 'string', 'max:60'],
            'secondary_cta_url' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:180'],
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
            'title.required' => 'Judul hero wajib diisi.',
            'title.max' => 'Judul hero maksimal 160 karakter.',
            'subtitle.required' => 'Subjudul hero wajib diisi.',
            'subtitle.max' => 'Subjudul hero maksimal 700 karakter.',
            'image.image' => 'File hero harus berupa gambar.',
            'image.mimes' => 'Gambar hero harus berformat JPG atau PNG.',
            'image.max' => 'Ukuran gambar hero maksimal 2MB.',
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
