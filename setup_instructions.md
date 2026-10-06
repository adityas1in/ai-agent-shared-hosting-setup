# Shared Hosting Setup - Complete Hindi Guide

## 📋 Quick Setup (10 minutes)

### Step 1: Python Environment Setup

```bash
# SSH se login karo apne hosting par
# Terminal mein ye commands run karo:

cd ~
python3 -m venv ai_bot_env
source ai_bot_env/bin/activate
pip install --upgrade pip
```

### Step 2: Python Files Setup

```bash
# Directory banao
mkdir -p ~/ai_bot_service
cd ~/ai_bot_service

# Repository se files download karo
# Ya copy-paste karo: requirements.txt, app.py, .env.example

# .env file banao
cp .env.example .env

# Apni Groq API key add karo .env mein
# https://console.groq.com par free account banao
# API key: gsk_xxxxxxx
```

### Step 3: Dependencies Install

```bash
source ai_bot_env/bin/activate
pip install -r requirements.txt
```

### Step 4: Test Karo

```bash
# Flask server start karo
python app.py

# Doosre terminal se test karo:
curl http://localhost:5000/health

# Expected output:
# {"status": "ok", "service": "AI Agent API", ...}
```

### Step 5: Production Setup (gunicorn)

```bash
pip install gunicorn

# Background mein chalao:
gunicorn -w 4 -b 127.0.0.1:5000 app:app &
```

---

## 🔗 Laravel Integration

### Step 1: Service File

```bash
# Apne Laravel project mein:
cd /path/to/laravel/project

# Service file copy karo
cp /path/to/AIAgentService.php app/Services/

# Controller file copy karo
cp /path/to/AIChatController.php app/Http/Controllers/
```

### Step 2: Routes

```bash
# routes/api.php mein ye add karo:

use App\Http\Controllers\AIChatController;

Route::prefix('ai')->group(function () {
    Route::post('/support', [AIChatController::class, 'support']);
    Route::post('/code', [AIChatController::class, 'coding']);
    Route::post('/chat', [AIChatController::class, 'chat']);
});
```

### Step 3: .env Configuration

```bash
# .env mein add karo:
AI_AGENT_URL=http://127.0.0.1:5000/api
AI_AGENT_TIMEOUT=30
```

### Step 4: Test Karo

```bash
# Postman ya curl se test karo:
curl -X POST http://localhost:8000/api/ai/support \
  -H "Content-Type: application/json" \
  -d '{"query": "Namaste kaise ho?"}'
```

---

## 💻 Chat Widget Setup

### Step 1: Blade Component

```bash
# Chat widget file copy karo:
cp /path/to/chat-widget.blade.php resources/views/components/
```

### Step 2: Layout mein Include

```blade
<!-- resources/views/layouts/app.blade.php mein add karo: -->

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ... body ke andar ... -->

@include('components.chat-widget')
```

### Step 3: Test

```bash
# Browser mein apna website open karo
# Bottom-right mein chat widget dikhai dena chahiye
```

---

## 🚀 Production Deployment (cPanel)

### Method 1: Cron Job (Recommended)

```bash
1. cPanel login karo
2. Cron Jobs mein jao
3. New Cron Job add karo:

Command:
cd /home/username/ai_bot_service && source ai_bot_env/bin/activate && gunicorn -w 4 -b 127.0.0.1:5000 app:app &

Timing: @reboot (Server restart par automatically start ho jayega)
```

### Method 2: Supervisor (Advanced)

```bash
# Supervisor install karo (agar available hai)
sudo apt-get install supervisor

# Config file:
[program:ai_bot_service]
command=/home/username/ai_bot_env/bin/gunicorn -w 4 -b 127.0.0.1:5000 app:app
directory=/home/username/ai_bot_service
autostart=true
autorestart=true
stderr_logfile=/home/username/ai_bot_service/logs/stderr.log
stdout_logfile=/home/username/ai_bot_service/logs/stdout.log
```

### Method 3: Manual Background Process

```bash
# Terminal se:
cd ~/ai_bot_service
source ai_bot_env/bin/activate
nohup gunicorn -w 4 -b 127.0.0.1:5000 app:app > logs/app.log 2>&1 &

# Process check karo:
ps aux | grep gunicorn
```

---

## 🔍 Monitoring & Logs

### Logs Check Karo

```bash
# Flask app logs
cd ~/ai_bot_service
tail -f logs/app.log

# Ya direct output:
grep "ERROR" logs/app.log
```

### API Health Check

```bash
# Health endpoint test karo:
curl http://127.0.0.1:5000/health

# Response:
# {"status": "ok", "service": "AI Agent API", "groq_status": "connected"}
```

### Performance Monitor

```bash
# RAM/CPU usage
free -h
df -h

# Process check
ps aux | grep python
ps aux | grep gunicorn
```

---

## 🐛 Troubleshooting

### Problem 1: "Connection refused" error

```bash
# Solution:
# 1. Python service running hai check karo:
ps aux | grep gunicorn

# 2. Port 5000 available hai check karo:
lsof -i :5000

# 3. Restart service:
killall gunicorn
cd ~/ai_bot_service
source ai_bot_env/bin/activate
gunicorn -w 4 -b 127.0.0.1:5000 app:app &
```

### Problem 2: "GROQ_API_KEY not found"

```bash
# Solution:
# .env file check karo
cat ~/.env

# Hona chahiye:
# GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxx

# Nahi ho to set karo:
echo "GROQ_API_KEY=gsk_xxxxx" >> ~/ai_bot_service/.env
```

### Problem 3: Laravel se connection timeout

```bash
# Solution:
# 1. .env check karo:
grep AI_AGENT ~/.env

# Correct URL hona chahiye:
# AI_AGENT_URL=http://127.0.0.1:5000/api

# 2. Timeout increase karo agar slow hai:
AI_AGENT_TIMEOUT=60

# 3. Logs check karo:
tail -f storage/logs/laravel.log
```

### Problem 4: Chat widget nahi show ho raha

```bash
# Solution:
# 1. Browser console check karo (F12)
# 2. CSRF token hai check karo:
# <meta name="csrf-token" content="...">

# 3. Routes check karo:
php artisan route:list | grep ai

# 4. API call manual test karo:
curl -X POST http://localhost:8000/api/ai/support \
  -H "Content-Type: application/json" \
  -d '{"query": "Test"}'
```

---

## 📊 Performance Expectations

- **1000 requests/day**: ✅ Easy handle hoga
- **Response time**: ~2-5 seconds per query
- **API calls/second**: ~0.01 (safe zone)
- **Groq free limit**: 30,000 requests/day (abundant)
- **Shared hosting load**: Minimal (lightweight)

---

## 🔐 Security Best Practices

```bash
# 1. .env file permissions
chmod 600 ~/ai_bot_service/.env
chmod 600 storage/logs/laravel.log

# 2. API validation enable karo (already done in code)
# 3. Rate limiting (optional, add later)
# 4. HTTPS use karo production mein
```

---

## 📞 Support & Resources

- **Groq API**: https://console.groq.com
- **Flask Docs**: https://flask.palletsprojects.com
- **Laravel Docs**: https://laravel.com/docs
- **GitHub Issue**: Post here if problem ho

---

## ✅ Checklist

- [ ] Python 3.8+ installed
- [ ] Virtual environment created
- [ ] requirements.txt installed
- [ ] .env file setup with GROQ_API_KEY
- [ ] Python app running (gunicorn)
- [ ] Laravel service created
- [ ] Laravel controller created
- [ ] Routes added
- [ ] .env configured with AI_AGENT_URL
- [ ] Chat widget imported
- [ ] Website test kiya
- [ ] Production deployment done
- [ ] Logs monitoring setup

---

**अब आप ready हो!** 🚀

कोई भी issue हो तो GitHub issue open karo ya documentation check karo.
