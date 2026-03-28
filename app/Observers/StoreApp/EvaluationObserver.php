<?php

namespace App\Observers\StoreApp;

use App\Models\StoreEvaluation;
use App\Models\User;

class EvaluationObserver
{
    /**
     * Handle the evaluation "created" event.
     */
    public function created(StoreEvaluation $evaluation): void
    {
        $store = User::findOrFail($evaluation->store_id);
        $store->update([
            'rating' => round(StoreEvaluation::where('store_id', $evaluation->store_id)->avg('rating'), 1)
        ]);
    }
}
