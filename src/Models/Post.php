<?php

namespace Wepa\Blog\Models;


use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wepa\Core\Models\Traits\PositionTrait;


/**
 * Wepa\Blog\Models\Post
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property int $create_at
 * @property int $update_at
 * @property string $start_at
 * @property int $vitits
 * @property int $likes
 * @property int $position
 * @property int $publish
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdateAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereVitits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withTranslation()
 * @mixin \Eloquent
 * @property-read \Wepa\Blog\Models\PostTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wepa\Blog\Models\PostTranslation[] $translations
 * @property-read int|null $translations_count
 */
class Post extends Model
{
	use HasFactory;
	use PositionTrait;
	use Translatable;
	
	protected $fillable = [
		'user_id',
		'category_id',
		'start_at',
		'visits',
		'likes',
		'position',
		'publish',
	];
	
	public array $translatedAttributes = [
		'title',
		'summary',
		'body',
	];
	public $translationForeignKey = 'post_id';
	protected $table = 'blog_posts';
	
}
