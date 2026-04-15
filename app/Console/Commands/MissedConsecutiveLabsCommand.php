<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MissedConsecutiveLabsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:missed-consecutive-labs-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find students who have missed 2 or more consecutive lab deadlines and alert the module coordinator of this.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::with(['students', 'lecturer'])->where();
    }
}
