<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReqCheckCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'req:check';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Check requirements for app";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('PHP version: '.(version_compare(phpversion(), '7.2.0', '<') ? 'ERR < 7.2' : 'OK 7.2+' ) );
            $this->info('GD Extension: '. (extension_loaded('gd') ? 'Loaded' : 'ERR: Not Loaded' ));
            $this->info('PCNTL Extension: '. (extension_loaded('pcntl') ? 'Loaded' : 'ERR: Not Loaded' ));
            $this->info('PCNTL Functions: '. (function_exists('pcntl_signal') ? 'Available' : 'ERR: Not Available' ));
        } catch (\Exception $e) {
            $this->error("An error occurred");
        }
    }
}