<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // For custom scopes
use App\Models\Counter;

class Sequence extends Model
{
    protected $table = 'sequences';

    // Define polymorphic relationship
    public function sequencable()
    {
        return $this->morphTo();
    }

    protected $fillable = ['sequencable_type', 'sequencable_id', 'current_number'];

    /**
     * Retrieve the next sequence number from the counters table
     * based on the model type (e.g., Member, Invoice, Budget)
     *
     * @param string $modelType The model type to get the sequence for
     * @return int The current sequence number
     */
    public static function getSequenceForModel($modelType)
    {
        $sequenceField = self::getSequenceFieldByModel($modelType);
        $counter = Counter::first();

        if ($counter) {
            return $counter->$sequenceField;
        }

        throw new \Exception("Counter record not found for model type: {$modelType}");
    }

    /**
     * Increment the sequence number for the given model type
     * and update it in the counters table.
     *
     * @param string $modelType The model type to increment the sequence for
     * @return int The updated sequence number
     */
    public static function incrementSequenceForModel($modelType)
    {
        $sequenceField = self::getSequenceFieldByModel($modelType);
        $counter = Counter::first();

        if ($counter) {
            $counter->$sequenceField++;
            $counter->save();
            return $counter->$sequenceField;
        }

        throw new \Exception("Unable to find counter data for model type: {$modelType}");
    }

    /**
     * Get the corresponding sequence field name based on the model type.
     *
     * @param string $modelType The model type to retrieve the sequence field for.
     * @return string The corresponding sequence field.
     */
    private static function getSequenceFieldByModel($modelType)
    {
        switch ($modelType) {
            case 'Member':
                return 'member_subscription_sequence';
            case 'Invoice':
                return 'disbursement_order_sequence';
            case 'Budget':
                return 'supply_order_sequence';
            default:
                throw new \Exception("Invalid model type provided: {$modelType}");
        }
    }

    /**
     * Scope to get sequences by model type and a date range
     *
     * @param Builder $query
     * @param string $modelType The model type to filter by (e.g., 'Member', 'Invoice', 'Budget')
     * @param string $from The start date for the date range
     * @param string $to The end date for the date range
     * @return Builder The query builder instance
     */
    public function scopeByModelTypeAndDateRange(Builder $query, $modelType, $from, $to)
    {
        return $query->where('sequencable_type', $modelType)
                     ->whereBetween('created_at', [$from, $to]);
    }
}
