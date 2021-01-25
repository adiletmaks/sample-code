<?php

namespace App\Models;

use App\Models\Contracts\Imageable;
use App\Models\Traits\ImageableTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Additive.
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property bool $active
 * @property int $additive_category_id
 * @property bool $is_default
 * @property int|null $sort_weight
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AdditiveCategory $additiveCategory
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereAdditiveCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereSortWeight($value)
 * @mixin \Eloquent
 * @property string|null $external_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereExternalId($value)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Additive onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Additive whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Additive withoutTrashed()
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Image[] $images
 * @property-read int|null $images_count
 */
final class Additive extends Model implements Imageable
{
    use SoftDeletes, ImageableTrait;

    protected $fillable = [
        'name', 'price', 'additive_category_id', 'external_id', 'deleted_at', 'is_default', 'sort_weight',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     * In Laravel 7 date format was changed.
     * @link https://laravel.com/docs/7.x/upgrade#date-serialization
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return BelongsTo
     *
     * @psalm-return BelongsTo<AdditiveCategory>
     */
    public function additiveCategory(): BelongsTo
    {
        return $this->belongsTo(AdditiveCategory::class);
    }
}
