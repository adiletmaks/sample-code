<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Stoppable
{
    public function stopLists(): MorphMany;
}
