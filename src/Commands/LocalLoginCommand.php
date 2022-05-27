<?php

namespace Spatie\LocalLogin\Commands;

use Illuminate\Console\Command;

class LocalLoginCommand extends Command
{
    public $signature = 'laravel-local-login';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
