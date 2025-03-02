<?php

namespace Wepa\Blog\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property array $translations
 */
class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'translations.*.name' => 'string|required',
            'translations.*.description' => 'string|required',
        ];
    }

    public function messages()
    {
        $messages = [];

        foreach ($this->input('translations') as $locale => $value){
            $messages["translations.{$locale}.name.required"] = __('blog::categories.name_required_default_lang', ['locale' => $locale]);
            $messages["translations.{$locale}.description.required"] = __('blog::categories.description_required_default_lang', ['locale' => $locale]);
        }

        return $messages;
    }
}
