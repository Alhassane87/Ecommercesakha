<?php

return [
    'provider' => env('CHATBOT_PROVIDER', 'gemini'),
    
    'openai_key' => env('OPENAI_API_KEY'),
    
    'gemini_key' => env('GEMINI_API_KEY'),
    
    'enabled' => env('CHATBOT_ENABLED', true),
    
    'max_history_messages' => env('CHATBOT_MAX_HISTORY', 10),
];
