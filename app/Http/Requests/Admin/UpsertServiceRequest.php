<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'category' => Str::squish($this->cleanInput($this->input('category'))),
            'icon' => Str::squish($this->cleanInput($this->input('icon'))),
            'points' => $this->cleanInput($this->input('points')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:2000'],
            'category' => ['required', 'string', 'max:60'],
            'icon' => ['required', 'string', Rule::in($this->allowedIcons())],
            'points' => ['required', 'string', 'max:1200'],
        ];
    }

    /**
     * @return array<int, string>
     */
    private function allowedIcons(): array
    {
        return [
            'shield',
            'lock',
            'key',
            'door-open',
            'camera',
            'eye',
            'scan',
            'radio',
            'headset',
            'phone',
            'route',
            'map-pin',
            'car',
            'truck',
            'building',
            'home',
            'factory',
            'warehouse',
            'users',
            'user-check',
            'badge',
            'clipboard',
            'alarm',
            'siren',
            'fire',
            'hard-hat',
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
            'category.required' => 'Kategori layanan wajib diisi.',
            'category.max' => 'Kategori layanan maksimal 60 karakter.',
            'icon.required' => 'Ikon layanan wajib dipilih.',
            'icon.in' => 'Ikon layanan tidak valid.',
            'points.required' => 'Bullet points layanan wajib diisi.',
            'points.max' => 'Bullet points layanan maksimal 1200 karakter.',
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
