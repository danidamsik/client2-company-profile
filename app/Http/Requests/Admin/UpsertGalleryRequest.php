<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpsertGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'caption' => $this->cleanInput($this->input('caption')),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'caption' => ['required', 'string', 'max:255'],
            'image' => [$this->isMethod('post') ? 'required' : 'nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'caption.required' => 'Caption galeri wajib diisi.',
            'caption.max' => 'Caption galeri maksimal 255 karakter.',
            'image.required' => 'Gambar galeri wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar galeri harus berformat JPG atau PNG.',
            'image.max' => 'Ukuran gambar galeri maksimal 2MB.',
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
