<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use File;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $timestamp= date('Y-m-d H-i-s');
        $schedule->call(function () {
        $files = glob(\Storage::disk('nfs')->url('zip/*.zip'));
        foreach($files as $file)
        {
           $filelastmodified = filemtime($file);
           if((time() - $filelastmodified) > 2*3600)
           {
           File::delete($file);
           }
        }
           
        })->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
