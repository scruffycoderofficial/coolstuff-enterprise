<?php

/*
 *     This file is part of the CoolStuff IT Solutions package.
 *
 *         (c) Luyanda Siko <sikoluyanda@gmail.com>
 *
 *     For the full copyright and license information, please view the LICENSE
 *     file that was distributed with this source code.
 */

namespace App\Console\Commands;

use Chatify\ChatifyMessenger;
use Illuminate\Console\Command;

class ListPusherChannelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:channels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var
     */
    protected $chatifyMessenger;

    /**
     * ListPusherChannelssCommand constructor.
     *
     * @param ChatifyMessenger $chatifyMessenger
     */
    public function __construct(ChatifyMessenger $chatifyMessenger)
    {
        $this->chatifyMessenger = $chatifyMessenger;

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pusher = new \Pusher\Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER'),
            ]
        );

        $channels = $pusher->trigger(
            'my-channel',
            'my_event',
            'hello world',
            ['info' => 'subscription_count']
        );

        dump($channels);
    }
}
