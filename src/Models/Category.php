<?php

namespace Wepa\Blog\Models;

use Astrotomic\Translatable\Translatable;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wepa\Blog\Database\Factories\CategoryFactory;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;
use Wepa\Core\Http\Traits\SeoModelTrait;
use Wepa\Core\Models\Seo;

/**
 * Wepa\Blog\Models\Category
 *
 * @property int $id
 * @property int $parent_id
 * @property int $seo_id
 * @property int $position
 * @property int $published
 * @property int $countChildren
 * @property string $name
 * @property string $description
 * @property Seo $seo
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read CategoryTranslation|null $translation
 * @property-read Collection|CategoryTranslation[] $translations
 * @property-read int|null $translations_count
 *
 * @method static Builder|Category listsTranslations(string $translationField)
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category notTranslatedIn(?string $locale = null)
 * @method static Builder|Category orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Category orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Category orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Category query()
 * @method static Builder|Category translated()
 * @method static Builder|Category translatedIn(?string $locale = null)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category wherePosition($value)
 * @method static Builder|Category wherePublish($value)
 * @method static Builder|Category whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Category whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category withTranslation()
 *
 * @mixin Eloquent
 */
class Category extends Model
{
    use SeoModelTrait;
    use HasFactory;
    use PositionModelTrait;
    use Translatable;

    public $timestamps = false;

    public array $translatedAttributes = ['name', 'description'];

    public $translationForeignKey = 'category_id';

    protected array $attrsArray = [];

    protected $fillable = ['parent_id', 'seo_id', 'position', 'published', 'created_at', 'updated_at'];

    protected $table = 'blog_categories';

    /**
     * @return $this
     */
    public function addTranslationToArray(): static
    {
        $this->translationsToArray = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function attrsToArray(array|string $attrs = []): static
    {
        if (is_array($attrs)) {
            $this->attrsArray = array_merge($this->attrsArray, $attrs);
        } else {
            $this->attrsArray[] = $attrs;
        }

        return $this;
    }

    public function seoDefaultParams(): array
    {
        return [
            'package' => 'blog',
            'controller' => PostController::class,
            'action' => 'index',
            'change_freq' => Seo::CHANGE_FREQUENCY_WEEKLY,
            'priority' => 0.7,
            'page_type' => 'website',
        ];
    }

    public function seoRequestParams(): array
    {
        return $this->id ? ['categoryId' => $this->id] : [];
    }

    public function seoRouteParams(): array
    {
        return [];
    }

    public function toArray(): array
    {
        $collection = collect(parent::toArray())
            ->merge(['countChildren' => $this->countChildren])
            ->except(['translations']);

        foreach ($this->attrsArray as $attr) {
            if ($attr === 'translations') {
                $collection = $collection->merge([$attr => $this->getTranslationsArray()]);
            } else {
                $collection = $collection->merge([$attr => $this->{$attr}]);
            }
        }

        return $collection->toArray();
    }

    protected function countChildren(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->where(['parent_id' => $this->id])->count()
        );
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
