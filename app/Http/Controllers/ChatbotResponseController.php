<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;

class ChatbotResponseController extends Controller
{
    protected $chatbotService;
    
    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }
    
    public function index()
    {
        return view('chatbot.index');
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);
        
        $response = $this->chatbotService->processMessage($request->input('message'));
        
        return response()->json([
            'response' => $response
        ]);
    }
}