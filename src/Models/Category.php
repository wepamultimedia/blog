<?php
namespace Wepa\Blog\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wepa\Blog\Database\Factories\CategoryFactory;
use Wepa\Core\Models\Traits\PositionTrait;


/**
 * Wepa\Blog\Models\Category
 *
 * @property int $id
 * @property int $parent_id
 * @property int $position
 * @property int $publish
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Wepa\Blog\Models\CategoryTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wepa\Blog\Models\CategoryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Category translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTranslation()
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;
	use PositionTrait;
	use Translatable;
	
	
	/**
	 * @return CategoryFactory
	 */
	protected static function newFactory(): CategoryFactory
	{
		return CategoryFactory::new();
	}
	
	protected $table = 'blog_categories';
	protected $fillable = [
		'parent_id',
		'position',
		'publish',
	];
	public array $translatedAttributes = [
		'name'
	];
	public $translationForeignKey = 'category_id';
	
	public $timestamps = false;
}
