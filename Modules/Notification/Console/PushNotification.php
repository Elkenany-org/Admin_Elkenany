<?php

namespace Modules\Notification\Console;

use Illuminate\Console\Command;
use Modules\Notification\Entities\Notification;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push notification with schedule time and date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $notifications =  Notification::where('status',0)->where('date_at',today())->get();
        foreach ($notifications as $k =>$v){
            $time = date('H:i',strtotime($v->time_at));
            $now = date('H:i');
            if($time == $now){
                $v->update(['status'=>1]);
                info('push notification from schedule');
            }
        }


    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
