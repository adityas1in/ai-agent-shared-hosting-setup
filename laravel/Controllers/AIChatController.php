<?php

namespace App\Http\Controllers;

use App\Services\AIAgentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIChatController extends Controller
{
    protected $aiService;
    
    public function __construct(AIAgentService $aiService)
    {
        $this->aiService = $aiService;
    }
    
    /**
     * Customer support endpoint
     * 
     * POST /api/ai/support
     * Body: {"query": "Mera product kaise kaam karta hai?"}
     */
    public function support(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|max:1000'
        ]);
        
        $result = $this->aiService->getSupport($validated['query']);
        
        return response()->json($result);
    }
    
    /**
     * Coding help endpoint
     * 
     * POST /api/ai/code
     * Body: {"query": "Laravel mein controller kaise banate hain?"}
     */
    public function coding(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|max:2000'
        ]);
        
        $result = $this->aiService->getCodingHelp($validated['query']);
        
        return response()->json($result);
    }
    
    /**
     * General chat endpoint
     * 
     * POST /api/ai/chat
     * Body: {"message": "Namaste, kaise ho?"}
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $result = $this->aiService->chat($validated['message']);
        
        return response()->json($result);
    }
}
