<?php

namespace App\Models\Traits;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait FavoriteableTrait
{
    /**
     * @return MorphMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Favorite>
     */
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }
}
