<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Queue;
use App\Models\QueueEntry;

class QueueAnalyticsController extends Controller
{
    public function index(Module $module) {
        $queue = $module->queue;
        $allQueueEntries = $queue->queueEntries()->get();

        $leftEntries = $allQueueEntries->whereNotNull('left_at');
        $currentEntries = $allQueueEntries->whereNull('left_at');

        $averageWait = $this->getAverageWait($leftEntries);
        $longestWait = $this->getLongestWait($leftEntries);
        $queueLength = $currentEntries->count();
        $totalHelped = $leftEntries->count();

        $lengthOverTime = $allQueueEntries
                                ->groupBy(fn($entry) => $entry->joined_at->copy()->floorMinutes(10)->format('H:i'))
                                ->map(fn($joinGroup) => $joinGroup->count());

        $assistanceTypeBreakdown = $allQueueEntries
                                    ->groupBy('assistance_type')
                                    ->map(fn($typeGroup) => $typeGroup->count());

        return(view('queues.analytics', [
            'module' => $module,
            'averageWait' => $averageWait,
            'longestWait' => $longestWait,
            'currentLength' => $queueLength,
            'lengthOverTime' => $lengthOverTime,
            'totalHelped' => $totalHelped,
            'assistanceTypeBreakdown' => $assistanceTypeBreakdown,
        ]));
    }

    private function getAverageWait($leftEntries)
    {
        if($leftEntries->isEmpty()) {
            return '00:00';
        }

        $averageWait = $leftEntries->avg(function($entry){
            return $entry->left_at->diffInSeconds($entry->joined_at, true);
        });

        return gmdate('i:s', $averageWait);
    }

    private function getLongestWait($leftEntries)
    {
        if($leftEntries->isEmpty()) {
            return '00:00';
        }

        $longestWait = $leftEntries->max(function($entry){
            return $entry->left_at->diffInSeconds($entry->joined_at, true);
        });

        return gmdate('i:s', $longestWait);
    }

    private function getSeenStudents($queue) {
        // get the finished queue entries
        return $queue->queueEntries()
        ->whereNotNull('left_at')
        ->get();
    }
}
