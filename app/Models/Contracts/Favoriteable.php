<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Favoriteable
{
    public function favorites(): MorphMany;
}
