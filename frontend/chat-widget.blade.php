<!-- Chat Widget - Blade Component -->
<!-- resources/views/components/chat-widget.blade.php -->

<div id="ai-chat-widget" class="chat-widget" data-status="closed">
    <!-- Chat Header -->
    <div class="chat-header">
        <div class="chat-title">
            <span class="chat-icon">💬</span>
            <h3>Help Desk</h3>
        </div>
        <button class="chat-toggle" onclick="toggleChatWidget()">−</button>
    </div>
    
    <!-- Chat Messages -->
    <div class="chat-messages" id="chatMessages">
        <div class="message bot-message">
            <div class="message-content">
                <p>नमस्ते! 👋</p>
                <p>मैं आपकी मदद कर सकता हूँ। कोई सवाल पूछें?</p>
            </div>
        </div>
    </div>
    
    <!-- Chat Input -->
    <div class="chat-input-area">
        <input 
            type="text" 
            id="userInput" 
            class="chat-input"
            placeholder="अपना सवाल लिखें..."
            onkeypress="handleChatKeyPress(event)"
            maxlength="1000"
        >
        <button class="chat-send-btn" onclick="sendChatMessage()" title="भेजें">➤</button>
    </div>
</div>

<style>
/* Chat Widget Styles */
.chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
    display: flex;
    flex-direction: column;
    z-index: 9999;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border: 1px solid #e0e0e0;
    transition: all 0.3s ease;
}

.chat-widget[data-status="closed"] {
    height: 60px;
}

.chat-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.chat-title {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}

.chat-icon {
    font-size: 20px;
}

.chat-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.chat-toggle {
    background: rgba(255, 255, 255, 0.3);
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 4px;
    transition: background 0.2s;
}

.chat-toggle:hover {
    background: rgba(255, 255, 255, 0.5);
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f9f9f9;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.chat-widget[data-status="closed"] .chat-messages,
.chat-widget[data-status="closed"] .chat-input-area {
    display: none;
}

.message {
    display: flex;
    margin-bottom: 10px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bot-message {
    justify-content: flex-start;
}

.user-message {
    justify-content: flex-end;
}

.message-content {
    max-width: 80%;
    padding: 10px 15px;
    border-radius: 8px;
    word-wrap: break-word;
    line-height: 1.4;
}

.bot-message .message-content {
    background: #e7f3ff;
    color: #333;
    border-bottom-left-radius: 2px;
}

.user-message .message-content {
    background: #667eea;
    color: white;
    border-bottom-right-radius: 2px;
}

.message-content p {
    margin: 0;
}

.message-content p:not(:last-child) {
    margin-bottom: 5px;
}

.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 10px 15px;
    background: #e7f3ff;
    border-radius: 8px;
    width: fit-content;
}

.typing-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #667eea;
    animation: typing 1.4s infinite;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        opacity: 0.3;
        transform: translateY(0);
    }
    30% {
        opacity: 1;
        transform: translateY(-10px);
    }
}

.chat-input-area {
    display: flex;
    gap: 8px;
    padding: 12px;
    background: white;
    border-top: 1px solid #e0e0e0;
    border-radius: 0 0 10px 10px;
}

.chat-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    font-family: inherit;
    resize: none;
    transition: border-color 0.3s;
}

.chat-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.chat-send-btn {
    padding: 8px 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-send-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.chat-send-btn:active {
    transform: scale(0.95);
}

.chat-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Responsive */
@media (max-width: 480px) {
    .chat-widget {
        width: calc(100% - 20px);
        height: 60vh;
        max-height: 500px;
    }
}

/* Scrollbar styling */
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<script>
/**
 * Chat Widget JavaScript
 * Shared Hosting par run karne ke liye
 */

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
const API_BASE = '/api/ai';

/**
 * Send chat message to AI backend
 */
async function sendChatMessage() {
    const input = document.getElementById('userInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    try {
        // Call Laravel API which calls Python backend
        const response = await fetch(`${API_BASE}/support`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ query: message })
        });
        
        const data = await response.json();
        removeTypingIndicator();
        
        if (data.success) {
            addMessage(data.response, 'bot');
        } else {
            addMessage('माफ़ी चाहता हूँ, कुछ समस्या हुई। कृपया बाद में दोबारा कोशिश करें।', 'bot');
        }
    } catch (error) {
        console.error('Chat error:', error);
        removeTypingIndicator();
        addMessage('Service उपलब्ध नहीं है। कृपया बाद में कोशिश करें।', 'bot');
    }
}

/**
 * Add message to chat display
 */
function addMessage(text, sender) {
    const messagesDiv = document.getElementById('chatMessages');
    const messageEl = document.createElement('div');
    messageEl.className = `message ${sender}-message`;
    
    const contentEl = document.createElement('div');
    contentEl.className = 'message-content';
    
    // Parse text and create paragraphs
    const paragraphs = text.split('\n').filter(p => p.trim());
    paragraphs.forEach(para => {
        const p = document.createElement('p');
        p.textContent = para;
        contentEl.appendChild(p);
    });
    
    messageEl.appendChild(contentEl);
    messagesDiv.appendChild(messageEl);
    
    // Auto scroll to bottom
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

/**
 * Show typing indicator
 */
function showTypingIndicator() {
    const messagesDiv = document.getElementById('chatMessages');
    const typingEl = document.createElement('div');
    typingEl.className = 'message bot-message';
    typingEl.id = 'typingIndicator';
    
    const contentEl = document.createElement('div');
    contentEl.className = 'typing-indicator';
    contentEl.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
    
    typingEl.appendChild(contentEl);
    messagesDiv.appendChild(typingEl);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

/**
 * Remove typing indicator
 */
function removeTypingIndicator() {
    const typingEl = document.getElementById('typingIndicator');
    if (typingEl) typingEl.remove();
}

/**
 * Handle Enter key press
 */
function handleChatKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendChatMessage();
    }
}

/**
 * Toggle chat widget open/close
 */
function toggleChatWidget() {
    const widget = document.getElementById('ai-chat-widget');
    const currentStatus = widget.getAttribute('data-status');
    const newStatus = currentStatus === 'closed' ? 'open' : 'closed';
    widget.setAttribute('data-status', newStatus);
}
</script>
