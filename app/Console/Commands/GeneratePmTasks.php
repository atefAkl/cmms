<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PmSchedule;
use App\Models\PmTask;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GeneratePmTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pm:generate-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates upcoming PM tasks based on schedule frequency';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting PM task generation...');
        
        $schedules = PmSchedule::where('next_due', '<=', Carbon::today())->get();
        
        /** @var PmSchedule $schedule */
        foreach ($schedules as $schedule) {
            // Check if a task for this date already exists for this schedule
            $exists = PmTask::where('pm_schedule_id', $schedule->id)
                                       ->where('scheduled_date', $schedule->next_due)
                                       ->exists();
            if ($exists) continue;

            PmTask::create([
                'pm_schedule_id' => $schedule->id,
                'scheduled_date' => $schedule->next_due,
                'status' => PmTask::STATUS_PENDING,
            ]);

            // Calculate next due date
            $frequency = $schedule->frequency_type;
            $value = $schedule->frequency_value ?: 1;
            
            $nextDue = Carbon::parse($schedule->next_due);
            
            switch ($frequency) {
                case PmSchedule::FREQUENCY_DAILY:
                    $nextDue->addDays($value);
                    break;
                case PmSchedule::FREQUENCY_WEEKLY:
                    $nextDue->addWeeks($value);
                    break;
                case PmSchedule::FREQUENCY_MONTHLY:
                    $nextDue->addMonths($value);
                    break;
                case PmSchedule::FREQUENCY_QUARTERLY:
                    $nextDue->addMonths($value * 3);
                    break;
                case PmSchedule::FREQUENCY_YEARLY:
                    $nextDue->addYears($value);
                    break;
                default:
                    $nextDue->addMonths(1);
                    break;
            }

            $schedule->update([
                'last_performed' => $schedule->next_due,
                'next_due' => $nextDue->toDateString()
            ]);
            
            $this->log("Generated task for schedule ID: {$schedule->id} for date {$schedule->last_performed}");
        }

        $this->info('PM task generation complete.');
    }

    protected function log($msg)
    {
        $this->info($msg);
        Log::info($msg);
    }
}
