<?php

namespace Wepa\Blog\Models;

use Astrotomic\Translatable\Translatable;
use Coderflex\Laravisit\Concerns\CanVisit;
use Coderflex\Laravisit\Concerns\HasVisits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Wepa\Blog\Database\Factories\PostFactory;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;
use Wepa\Core\Http\Traits\SeoModelTrait;
use Wepa\Core\Models\Seo;

/**
 * Wepa\Blog\Models\Post
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property int $seo_id
 * @property string $cover
 * @property string $cover_title
 * @property string $cover_alt
 * @property string $start_at
 * @property int $visits
 * @property int $likes
 * @property int $position
 * @property int $draft
 * @property Seo $seo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PostTranslation|null $translation
 * @property-read Collection|PostTranslation[] $translations
 * @property-read int|null $translations_count
 *
 * @method static PostFactory factory(...$parameters)
 * @method static Builder|Post listsTranslations(string $translationField)
 * @method static Builder|Post newModelQuery()
 * @method static Builder|Post newQuery()
 * @method static Builder|Post notTranslatedIn(?string $locale = null)
 * @method static Builder|Post orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Post orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Post orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static Builder|Post query()
 * @method static Builder|Post translated()
 * @method static Builder|Post translatedIn(?string $locale = null)
 * @method static Builder|Post whereCategoryId($value)
 * @method static Builder|Post whereCreatedAt($value)
 * @method static Builder|Post whereId($value)
 * @method static Builder|Post whereLikes($value)
 * @method static Builder|Post wherePosition($value)
 * @method static Builder|Post wherePublish($value)
 * @method static Builder|Post whereStartAt($value)
 * @method static Builder|Post whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|Post whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static Builder|Post whereUpdatedAt($value)
 * @method static Builder|Post whereUserId($value)
 * @method static Builder|Post whereVisits($value)
 * @method static Builder|Post withTranslation()
 *
 * @mixin \Eloquent
 */
class Post extends Model implements CanVisit
{
    use HasFactory;
    use PositionModelTrait;
    use Translatable;
    use HasVisits;
    use SeoModelTrait;

    public array $translatedAttributes = [
        'title',
        'summary',
        'body',
        'cover_title',
        'cover_alt',
    ];

    public $translationForeignKey = 'post_id';

    protected $appends = ['total_visits', 'category_name', 'url'];

    protected array $attrsArray = [];

    protected $fillable = [
        'user_id',
        'category_id',
        'seo_id',
        'cover',
        'start_at',
        'video_cover',
        'likes',
        'position',
        'draft',
        'survey_id',
        'created_at',
        'updated_at',
    ];

    protected $table = 'blog_posts';

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

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(Seo::class, 'model_id', 'id')
            ->where('model_type', '=', self::class)
            ->withDefault([
                'controller' => PostController::class,
                'action' => 'show',
            ]);
    }

    public function toArray(): array
    {
        $collection = collect(parent::toArray())
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

    protected function categoryName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->category->name ?? null
        );
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->seo->slug
        );
    }

    protected function totalVisits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->visits()->count()
        );
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => request()->root().'/'.$this->seo->slug
        );
    }

    public function seoDefaultParams(): array
    {
        return [
            'package' => 'blog',
            'controller' => PostController::class,
            'action' => 'show',
            'change_freq' => Seo::CHANGE_FREQUENCY_NEVER,
            'priority' => 0.7,
            'page_type' => 'article',
        ];
    }

    public function seoRouteParams(): array
    {
        return ['post' => $this->id];
    }

    public function seoRequestParams(): array
    {
        return [];
    }
}
