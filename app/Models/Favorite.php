<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Favorite.
 *
 * @property int $id
 * @property int $user_id
 * @property string $favoriteable_type
 * @property int $favoriteable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $favoriteable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereFavoriteableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereFavoriteableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Favorite whereUserId($value)
 * @mixin \Eloquent
 */
final class Favorite extends Model
{
    protected $fillable = [
        'user_id', 'favoriteable_type', 'favoriteable_id',
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

    public function favoriteable(): MorphTo
    {
        return $this->morphTo();
    }
}
