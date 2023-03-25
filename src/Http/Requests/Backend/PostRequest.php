<?php

namespace Wepa\Blog\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Wepa\Core\Http\Traits\Backend\SeoRequestTrait;

/**
 * @property string title
 * @property string summary
 * @property string body
 * @property int category_id
 * @property string start_at
 * @property int draft
 * @property array translations
 */
class PostRequest extends FormRequest
{
    use SeoRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        $locale = config('app.locale');

        return array_merge(parent::messages(), [
            "translations.$locale.title.required" => __('blog::posts.title_required', ['locale' => $locale]),
            "translations.$locale.summary.required" => __('blog::posts.summary_required', ['locale' => $locale]),
            "translations.$locale.body.required" => __('blog::posts.body_required', ['locale' => $locale]),
            'translations.*.title.required' => __('blog::posts.title_required', ['locale' => $locale]),
            'translations.*.summary.required' => __('blog::posts.summary_required', ['locale' => $locale]),
            'translations.*.body.required' => __('blog::posts.body_required', ['locale' => $locale]),
            'cover.required' => __('blog::posts.cover_required'),
            'cover_alt.required' => __('blog::posts.cover_alt_required'),
            'category_id.required' => __('blog::posts.category_required'),
            'start_at.required' => __('blog::posts.start_at_required'),
        ]);
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        $locale = config('app.locale');

        return [
            'cover' => 'required|string',
            'category_id' => 'required',
            'draft' => 'boolean',
            'start_at' => 'required',
            "translations.$locale.title" => 'required|string',
            "translations.$locale.summary" => 'required|string',
            "translations.$locale.body" => 'required|string',
            "translations.$locale.cover_title" => 'required|string',
            "translations.$locale.cover_alt" => 'required|string',
            'translations.*.title' => 'required|string',
            'translations.*.summary' => 'required|string',
            'translations.*.body' => 'required|string',
            'translations.*.cover_title' => 'required|string',
            'translations.*.cover_alt' => 'required|string',
        ];
    }
}
