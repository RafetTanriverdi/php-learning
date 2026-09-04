<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Support\Facades\Log;

class LogProductCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event): void
    {
        Log::info('New Product Created', [
            'product_id' => $event->product->id,
            'name' => $event->product->name,
        ]);
    }
}
