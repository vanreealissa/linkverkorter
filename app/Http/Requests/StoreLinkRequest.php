<?php

namespace App\Http\Requests;

use App\Models\Link;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'url' => ['required', 'url:http,https', 'max:2048'],
            'code' => ['nullable', 'alpha_dash', 'min:3', 'max:20', Rule::unique('links', 'code'), Rule::notIn(Link::RESERVED_CODES)],
        ];
    }

    public function attributes(): array
    {
        return [
            'url' => 'lange link',
            'code' => 'eigen code',
        ];
    }

    protected function prepareForValidation(): void
    {
        $url = trim((string) $this->input('url'));

        // "voorbeeld.nl" is ook prima: zet er dan https:// voor.
        if ($url !== '' && ! preg_match('#^[a-z][a-z0-9+.-]*://#i', $url)) {
            $url = 'https://'.$url;
        }

        $this->merge(['url' => $url, 'code' => $this->filled('code') ? trim($this->input('code')) : null]);
    }
}
