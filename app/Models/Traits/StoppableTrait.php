<?php

namespace App\Models\Traits;

use App\Models\StopList;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait StoppableTrait
{
    /**
     * @return MorphMany
     */
    public function stopLists(): MorphMany
    {
        return $this->morphMany(StopList::class, 'stoppable');
    }

    /**
     * @return MorphMany
     */
    public function currentStopLists(): MorphMany
    {
        return $this->stopLists()
            ->where('from', '<=', now())
            ->where('to', '>=', now());
    }
}
