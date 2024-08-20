<?php

namespace App\Commands;

use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;
use OpenAI;
use function Laravel\Prompts\textarea;
use function Termwind\render;

class ChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat';

    private OpenAI\Client $client;

    private string $model;

    private array $messages = [];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->createOpenAiClient();

        while (true) {
            $input = textarea('发消息');
            if (!$input) {
                $this->info('再见');
                break;
            }

            $this->send($input);
        }
    }

    private function createOpenAiClient(): void
    {
        $api_key = config('llm.api_key');
        $base_uri = config('llm.base_uri', 'api.openai.com/v1');
        $model = config('llm.model', 'gpt-4o-mini');

        if (!($api_key && $base_uri && $model)) {
            $this->error('请先配置 OpenAI API Key、Base URI 和 Model');
            exit(1);
        }

        $this->model = $model;
        $this->client = OpenAI::factory()
            ->withApiKey($api_key)
            ->withBaseUri($base_uri)
            ->make();
        render(sprintf(<<<'HTML'
            <div class="py-1 ml-2">
                <div class="px-1 bg-green-500 text-black">LLM</div>
                <span class="ml-1">
                正在使用
                  API key: <em class="text-green-500">%s</em>,
                  Base uri: <em class="text-green-500">%s</em>,
                  Model: <em class="text-green-500">%s</em>, 请在 <em class="text-blue-500">~/.llm/config.php</em> 中修改配置
                </span>
            </div>
HTML, Str::of($api_key)->limit(8, '***'), $base_uri, $model));
    }

    public function send(string $input): void
    {
        $this->messages[] = ['role' => 'user', 'content' => $input];
        $stream = $this->client->chat()->createStreamed([
            'model' => $this->model,
            'messages' => $this->messages,
        ]);

        $output = '';
        foreach ($stream as $response) {
            $data = $response->choices[0]->toArray();
            $content = $data['delta']['content'];
            echo $content;
            $output .= $content;

            if ($data['finish_reason'] === 'stop') {
                $this->newLine(2);
                $this->messages[] = ['role' => 'assistant', 'content' => $output];
            }
        }
    }
}
