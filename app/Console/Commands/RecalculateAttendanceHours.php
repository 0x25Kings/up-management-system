<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculateAttendanceHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:recalculate {--all : Recalculate all records} {--date= : Recalculate for specific date (Y-m-d)} {--force-ot : Force recalculate overtime even if hours unchanged}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate attendance hours with lunch break deduction';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Attendance::whereNotNull('time_in')
            ->whereNotNull('time_out');

        if ($this->option('date')) {
            $query->whereDate('date', $this->option('date'));
        }

        $attendances = $query->get();

        if ($attendances->isEmpty()) {
            $this->info('No attendance records found to recalculate.');
            return 0;
        }

        $this->info("Recalculating {$attendances->count()} attendance records...");
        
        $bar = $this->output->createProgressBar($attendances->count());
        $bar->start();

        $updated = 0;
        $forceOt = $this->option('force-ot');
        
        foreach ($attendances as $attendance) {
            $oldHours = $attendance->hours_worked;
            $newHours = $attendance->calculateHoursWorked();
            
            $needsUpdate = abs($oldHours - $newHours) > 0.01;
            
            if ($needsUpdate || $forceOt) {
                if ($needsUpdate) {
                    $attendance->hours_worked = $newHours;
                }
                $attendance->calculateOvertimeUndertime();
                $attendance->save();
                $updated++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Updated {$updated} attendance records.");

        return 0;
    }
}
