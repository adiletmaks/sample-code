<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Imageable
{
    public function images(): MorphMany;
}
