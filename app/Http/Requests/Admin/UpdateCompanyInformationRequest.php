<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateCompanyInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => Str::squish($this->cleanInput($this->input('name'))),
            'email' => Str::lower(Str::squish($this->cleanInput($this->input('email')))),
            'whatsapp' => Str::squish($this->cleanInput($this->input('whatsapp'))),
            'location' => Str::squish($this->cleanInput($this->input('location'))),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'logo' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:40'],
            'location' => ['required', 'string', 'max:180'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, PNG, atau WEBP.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'name.required' => 'Nama company wajib diisi.',
            'email.required' => 'Email company wajib diisi.',
            'email.email' => 'Format email company tidak valid.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'location.required' => 'Lokasi company wajib diisi.',
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
