<?php

namespace App\Models\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait ImageableTrait
{
    /**
     * @return MorphMany
     *
     * @psalm-return \Illuminate\Database\Eloquent\Relations\MorphMany<\App\Models\Image>
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
