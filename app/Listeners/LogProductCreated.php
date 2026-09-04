<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Jobs\ProcessProductCreated;

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
        ProcessProductCreated::dispatch($event->product);
    }
}
