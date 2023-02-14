<?php

namespace Wepa\Blog\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Wepa\Blog\Database\Factories\PostFactory;
use Wepa\Blog\Http\Controllers\Frontend\PostController;
use Wepa\Core\Http\Traits\Backend\PositionModelTrait;
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
 * @property-read \Wepa\Blog\Models\PostTranslation|null $translation
 * @property-read Collection|\Wepa\Blog\Models\PostTranslation[] $translations
 * @property-read int|null $translations_count
 *
 * @method static \Wepa\Blog\Database\Factories\PostFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Post listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Post translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereVisits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTranslation()
 *
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;
    use PositionModelTrait;
    use Translatable;

    public array $translatedAttributes = [
        'title',
        'summary',
        'body',
        'cover_title',
        'cover_alt',
    ];

    public $translationForeignKey = 'post_id';

    protected $fillable = [
        'user_id',
        'category_id',
        'seo_id',
        'cover',
        'start_at',
        'visits',
        'likes',
        'position',
        'draft',
    ];

    protected $table = 'blog_posts';

    protected array $attrsArray = [];

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

    /**
     * @return HasOne
     */
    public function seo(): HasOne
    {
        return $this->hasOne(Seo::class, 'id', 'seo_id')
            ->withDefault([
                'controller' => PostController::class,
                'action' => 'show',
            ]);
    }

    public function toArray(): array
    {
        $collection = collect(parent::toArray())->except(['translations']);

        foreach ($this->attrsArray as $attr) {
            if ($attr === 'translations') {
                $collection = $collection->merge([$attr => $this->getTranslationsArray()]);
            } else {
                $collection = $collection->merge([$attr => $this->{$attr}]);
            }
        }

        return $collection->toArray();
    }
	
	protected $casts = [
		'start_at' => 'date:d M Y'
	];

    /**
     * @return PostFactory
     */
    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}