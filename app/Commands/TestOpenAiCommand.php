<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use OpenAI;
use OpenAI\Responses\Chat\CreateStreamedResponseChoice;

class TestOpenAiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-openai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OpenAI API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = OpenAI::factory()
            ->withApiKey(config('llm.api_key'))
            ->withBaseUri(config('llm.base_uri'))
            ->make();

        $stream = $client->chat()->createStreamed([
            'model' => config('llm.model'),
            'messages' => [
                ['role' => 'user', 'content' => '世界上最高的山是什么？'],
            ],
        ]);

        foreach ($stream as $response) {
            dump($response->choices[0]->toArray());
            // echo $response->choices[0]->toArray()['delta']['content'];
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
