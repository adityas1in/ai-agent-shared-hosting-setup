<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIAgentService
{
    private $apiUrl;
    private $timeout;
    
    public function __construct()
    {
        // AI backend ka URL
        // Local development: http://localhost:5000/api
        // Production (shared hosting): http://127.0.0.1:5000/api
        $this->apiUrl = env('AI_AGENT_URL', 'http://127.0.0.1:5000/api');
        $this->timeout = (int) env('AI_AGENT_TIMEOUT', 30);
    }
    
    /**
     * Customer support query
     * 
     * @param string $query Customer ka question
     * @return array Response array
     */
    public function getSupport(string $query): array
    {
        try {
            if (empty($query)) {
                return [
                    'success' => false,
                    'error' => 'Query cannot be empty'
                ];
            }
            
            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/support", [
                    'query' => $query
                ]);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'response' => $response->json('response'),
                    'type' => 'support'
                ];
            }
            
            Log::warning('AI Support API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return [
                'success' => false,
                'error' => 'AI service responded with error: ' . $response->status()
            ];
            
        } catch (\Exception $e) {
            Log::error('AI Support Error: ' . $e->getMessage(), [
                'query' => substr($query, 0, 100),
                'exception' => $e
            ]);
            
            return [
                'success' => false,
                'error' => 'Failed to connect to AI service. Please try again later.'
            ];
        }
    }
    
    /**
     * Coding assistant query
     * 
     * @param string $query Coding question
     * @return array Response array
     */
    public function getCodingHelp(string $query): array
    {
        try {
            if (empty($query)) {
                return [
                    'success' => false,
                    'error' => 'Query cannot be empty'
                ];
            }
            
            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/code", [
                    'query' => $query
                ]);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'response' => $response->json('response'),
                    'type' => 'coding'
                ];
            }
            
            Log::warning('AI Coding API error', [
                'status' => $response->status()
            ]);
            
            return [
                'success' => false,
                'error' => 'Coding assistant error'
            ];
            
        } catch (\Exception $e) {
            Log::error('AI Coding Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Failed to get coding help. Please try again later.'
            ];
        }
    }
    
    /**
     * General chat
     * 
     * @param string $message Chat message
     * @return array Response array
     */
    public function chat(string $message): array
    {
        try {
            if (empty($message)) {
                return [
                    'success' => false,
                    'error' => 'Message cannot be empty'
                ];
            }
            
            $response = Http::timeout($this->timeout)
                ->post("{$this->apiUrl}/chat", [
                    'message' => $message
                ]);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'response' => $response->json('response')
                ];
            }
            
            Log::warning('AI Chat API error', [
                'status' => $response->status()
            ]);
            
            return [
                'success' => false,
                'error' => 'Chat service error'
            ];
            
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Chat service unavailable. Please try again later.'
            ];
        }
    }
}
