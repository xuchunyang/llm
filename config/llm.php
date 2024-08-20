<?php

return [
    'api_key' => env('LLM_API_KEY'),
    'base_uri' => env('LLM_BASE_URI', 'api.openai.com/v1'),
    'model' => env('LLM_MODEL', 'gpt-4o-mini'),
];
