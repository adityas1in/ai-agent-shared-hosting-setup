#!/usr/bin/env python3
"""
AI Agent Backend - Flask Server
Shared Hosting par chalane ke liye

Usage:
    source ai_bot_env/bin/activate
    python app.py
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
from groq import Groq
import os
import logging
from datetime import datetime
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Initialize Flask app
app = Flask(__name__)
CORS(app)

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Initialize Groq client
try:
    groq_api_key = os.getenv('GROQ_API_KEY')
    if not groq_api_key:
        logger.error('GROQ_API_KEY not found in .env')
        raise ValueError('GROQ_API_KEY is required')
    client = Groq(api_key=groq_api_key)
    logger.info('Groq client initialized successfully')
except Exception as e:
    logger.error(f'Failed to initialize Groq client: {str(e)}')
    client = None

# ============================================================================
# HEALTH CHECK ENDPOINT
# ============================================================================

@app.route('/health', methods=['GET'])
def health():
    """
    Health check endpoint
    Test karne ke liye: curl http://localhost:5000/health
    """
    return jsonify({
        'status': 'ok',
        'service': 'AI Agent API',
        'timestamp': datetime.now().isoformat(),
        'groq_status': 'connected' if client else 'disconnected'
    }), 200

# ============================================================================
# CUSTOMER SUPPORT ENDPOINT
# ============================================================================

@app.route('/api/support', methods=['POST'])
def support():
    """
    Customer support bot endpoint
    
    Request body:
    {
        "query": "Mera product kaise kaam karta hai?"
    }
    
    Response:
    {
        "success": true,
        "response": "Answer here...",
        "type": "support"
    }
    """
    try:
        if not client:
            return jsonify({
                'success': False,
                'error': 'AI service not initialized'
            }), 503
        
        data = request.get_json()
        if not data:
            return jsonify({
                'success': False,
                'error': 'No JSON body provided'
            }), 400
        
        query = data.get('query', '').strip()
        if not query:
            return jsonify({
                'success': False,
                'error': 'Query field is required'
            }), 400
        
        if len(query) > 2000:
            return jsonify({
                'success': False,
                'error': 'Query too long (max 2000 characters)'
            }), 400
        
        logger.info(f'Support query: {query[:100]}...')
        
        # System prompt for customer support
        system_prompt = """
आप एक customer support agent हैं।
आपको हिंदी और अंग्रेजी दोनों भाषाओं में जवाब देने चाहिए।
Polite, helpful और quick response दें।
अगर समस्या solve न हो सके तो escalate करें।
Response को 500 शब्दों से कम रखें।
        """
        
        response = client.chat.completions.create(
            model="mixtral-8x7b-32768",  # Free model
            messages=[
                {
                    "role": "system",
                    "content": system_prompt
                },
                {
                    "role": "user",
                    "content": query
                }
            ],
            max_tokens=500,
            temperature=0.7
        )
        
        reply = response.choices[0].message.content
        logger.info('Support query processed successfully')
        
        return jsonify({
            'success': True,
            'response': reply,
            'type': 'support'
        }), 200
    
    except Exception as e:
        logger.error(f'Support error: {str(e)}')
        return jsonify({
            'success': False,
            'error': 'Internal server error: ' + str(e)
        }), 500

# ============================================================================
# CODING ASSISTANT ENDPOINT
# ============================================================================

@app.route('/api/code', methods=['POST'])
def code_help():
    """
    Coding assistant endpoint
    
    Request body:
    {
        "query": "Laravel mein controller kaise banate hain?"
    }
    
    Response:
    {
        "success": true,
        "response": "Code and explanation here...",
        "type": "coding"
    }
    """
    try:
        if not client:
            return jsonify({
                'success': False,
                'error': 'AI service not initialized'
            }), 503
        
        data = request.get_json()
        if not data:
            return jsonify({
                'success': False,
                'error': 'No JSON body provided'
            }), 400
        
        query = data.get('query', '').strip()
        if not query:
            return jsonify({
                'success': False,
                'error': 'Query field is required'
            }), 400
        
        if len(query) > 4000:
            return jsonify({
                'success': False,
                'error': 'Query too long (max 4000 characters)'
            }), 400
        
        logger.info(f'Coding query: {query[:100]}...')
        
        # System prompt for coding
        system_prompt = """
आप एक expert coding assistant हैं।
आप PHP, Laravel, Python, JavaScript, SQL में expert हैं।
Production-ready code दें।
Code को well-commented रखें।
Best practices follow करें।
Explanation हिंदी/अंग्रेजी दोनों में दें।
        """
        
        response = client.chat.completions.create(
            model="mixtral-8x7b-32768",
            messages=[
                {
                    "role": "system",
                    "content": system_prompt
                },
                {
                    "role": "user",
                    "content": query
                }
            ],
            max_tokens=1000,
            temperature=0.5
        )
        
        reply = response.choices[0].message.content
        logger.info('Coding query processed successfully')
        
        return jsonify({
            'success': True,
            'response': reply,
            'type': 'coding'
        }), 200
    
    except Exception as e:
        logger.error(f'Coding error: {str(e)}')
        return jsonify({
            'success': False,
            'error': 'Internal server error: ' + str(e)
        }), 500

# ============================================================================
# GENERAL CHAT ENDPOINT
# ============================================================================

@app.route('/api/chat', methods=['POST'])
def chat():
    """
    General purpose chat endpoint
    
    Request body:
    {
        "message": "Namaste, kaise ho?"
    }
    
    Response:
    {
        "success": true,
        "response": "Reply here..."
    }
    """
    try:
        if not client:
            return jsonify({
                'success': False,
                'error': 'AI service not initialized'
            }), 503
        
        data = request.get_json()
        if not data:
            return jsonify({
                'success': False,
                'error': 'No JSON body provided'
            }), 400
        
        message = data.get('message', '').strip()
        if not message:
            return jsonify({
                'success': False,
                'error': 'Message field is required'
            }), 400
        
        if len(message) > 2000:
            return jsonify({
                'success': False,
                'error': 'Message too long (max 2000 characters)'
            }), 400
        
        logger.info(f'Chat message: {message[:100]}...')
        
        response = client.chat.completions.create(
            model="mixtral-8x7b-32768",
            messages=[
                {
                    "role": "user",
                    "content": message
                }
            ],
            max_tokens=500,
            temperature=0.8
        )
        
        reply = response.choices[0].message.content
        logger.info('Chat message processed successfully')
        
        return jsonify({
            'success': True,
            'response': reply
        }), 200
    
    except Exception as e:
        logger.error(f'Chat error: {str(e)}')
        return jsonify({
            'success': False,
            'error': 'Internal server error: ' + str(e)
        }), 500

# ============================================================================
# ERROR HANDLERS
# ============================================================================

@app.errorhandler(404)
def not_found(error):
    return jsonify({
        'success': False,
        'error': 'Endpoint not found'
    }), 404

@app.errorhandler(500)
def internal_error(error):
    logger.error(f'Internal error: {str(error)}')
    return jsonify({
        'success': False,
        'error': 'Internal server error'
    }), 500

# ============================================================================
# MAIN
# ============================================================================

if __name__ == '__main__':
    port = int(os.getenv('PORT', 5000))
    debug = os.getenv('FLASK_ENV') == 'development'
    
    logger.info(f'Starting AI Agent API on port {port}')
    logger.info(f'Debug mode: {debug}')
    
    app.run(
        host='127.0.0.1',
        port=port,
        debug=debug,
        threaded=True
    )
