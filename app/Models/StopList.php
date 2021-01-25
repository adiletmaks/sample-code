<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\StopList.
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property int $operator_id
 * @property int $filial_id
 * @property string $stoppable_type
 * @property int $stoppable_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|self newModelQuery()
 * @method static Builder|self newQuery()
 * @method static Builder|self query()
 * @method static Builder|self whereCreatedAt($value)
 * @method static Builder|self whereFilialId($value)
 * @method static Builder|self whereFrom($value)
 * @method static Builder|self whereId($value)
 * @method static Builder|self whereOperatorId($value)
 * @method static Builder|self whereStoppableId($value)
 * @method static Builder|self whereStoppableType($value)
 * @method static Builder|self whereTo($value)
 * @method static Builder|self whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read Model|\Eloquent $stoppable
 */
final class StopList extends Model
{
    protected $fillable = [
        'from', 'to', 'filial_id', 'operator_id', 'stoppable_type', 'stoppable_id',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function stoppable(): MorphTo
    {
        return $this->morphTo();
    }
}
