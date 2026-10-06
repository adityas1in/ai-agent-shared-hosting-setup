# Shared Hosting Setup - Complete Hindi Guide

## 📋 Quick Setup (10 minutes)

### Step 0: System Requirements Check (Do First!)

```bash
# SSH se login karo apne hosting par
# Terminal mein ye commands run karo:

# 1. Python version check karo
python3 --version
# Expected: Python 3.8 ya usse zyada
# Example output: Python 3.9.13

# 2. Python location check karo
which python3
# Expected: /usr/bin/python3

# 3. Pip check karo
pip3 --version
# Expected: pip 20.0 ya usse zyada
# Example output: pip 21.1.3 from /usr/bin/pip3

# 4. Agar python3 nahi milta to python try karo:
python --version
which python

# 5. Curl check karo (downloads ke liye)
curl --version
# Expected: curl output with version

# 6. RAM check karo
free -h
# Expected: Minimum 256MB available

# 7. Disk space check karo
df -h
# Expected: Minimum 5GB free space

# 8. Linux version check karo
uname -a
# This will show your Linux system info

# 9. Check if pip3 is available separately
pip3 list | head
# Shows installed packages
```

**Expected Output Example:**
```
Python 3.9.13
pip 21.1.3
curl 7.68.0
RAM: 2GB available
Disk: 50GB available
```

**Agar problem ho:**
```bash
# Python 3 nahi milta to hosting provider se contact karo
# Ya apne control panel mein Python 3 enable karo

# CPanel mein:
# Select PHP Version → ya Python se enable kar
# Ya contact support: "Enable Python 3.8 or higher"
```

---

### Step 1: Python Environment Setup

```bash
# SSH se login karo apne hosting par
# Terminal mein ye commands run karo:

cd ~

# Virtual environment banao
python3 -m venv ai_bot_env

# Agar 'python3' error deta hai:
python -m venv ai_bot_env

# Verify that virtual environment is created
ls -la ai_bot_env
# You should see: bin, include, lib, pyvenv.cfg

# Activate karo
source ai_bot_env/bin/activate

# Verify activation (prompt mein (ai_bot_env) likha hona chahiye)
echo $VIRTUAL_ENV
# Expected output: /home/username/ai_bot_env

# Pip upgrade karo
pip install --upgrade pip

# Verify pip version
pip --version
```

### Step 2: Python Files Setup

```bash
# Directory banao
mkdir -p ~/ai_bot_service
cd ~/ai_bot_service

# Repository se files download karo
# Ya copy-paste karo: requirements.txt, app.py, .env.example

# List karo dekho files present hain:
ls -la

# .env file banao
cp .env.example .env

# Verify .env created:
ls -la .env

# Apni Groq API key add karo .env mein
# https://console.groq.com par free account banao
# API key: gsk_xxxxxxx

nano .env
# Or vi .env (edit karo)

# Verify .env content:
cat .env
# Should show: GROQ_API_KEY=gsk_xxxxx
```

### Step 3: Dependencies Install

```bash
# Ensure virtual environment is activated
source ai_bot_env/bin/activate

# Verify activation:
which python
# Should show: /home/username/ai_bot_env/bin/python

# Install requirements
pip install -r requirements.txt

# Verify installation:
pip list
# Should show: flask, groq, requests, etc.

# Check specific packages:
python -c "import flask; print(flask.__version__)"
python -c "import groq; print(groq.__version__)"
```

### Step 4: Test Karo

```bash
# Ensure virtual environment is activated
source ai_bot_env/bin/activate

# Flask server start karo
python app.py

# Expected output:
# * Running on http://127.0.0.1:5000
# * Debug mode: off

# Doosre terminal window mein (naya terminal kholo):
# Terminal 2 - Test karo

# Health endpoint test:
curl http://localhost:5000/health

# Expected response:
# {"status": "ok", "service": "AI Agent API", ...}

# Support endpoint test:
curl -X POST http://localhost:5000/api/support \
  -H "Content-Type: application/json" \
  -d '{"query": "Namaste, kaise ho?"}'

# Expected response:
# {"success": true, "response": "...", "type": "support"}

# Coding endpoint test:
curl -X POST http://localhost:5000/api/code \
  -H "Content-Type: application/json" \
  -d '{"query": "Laravel mein controller kaise banate hain?"}'

# Chat endpoint test:
curl -X POST http://localhost:5000/api/chat \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello"}'

# Stop server:
# Terminal 1 mein: Ctrl+C press karo
```

### Step 5: Production Setup (gunicorn)

```bash
# Ensure virtual environment is activated
source ai_bot_env/bin/activate

# Install gunicorn
pip install gunicorn

# Verify installation:
which gunicorn
gunicorn --version

# Create logs directory
mkdir -p logs

# Background mein chalao:
nohup gunicorn -w 4 -b 127.0.0.1:5000 app:app > logs/app.log 2>&1 &

# Verify running:
ps aux | grep gunicorn

# Expected output:
# username  12345  0.0  0.5 ...gunicorn -w 4 -b 127.0.0.1:5000 app:app

# Check logs:
tail logs/app.log

# Health check:
curl http://127.0.0.1:5000/health
```

---

## 🔗 Laravel Integration

### Step 1: Service File

```bash
# Apne Laravel project mein:
cd /path/to/laravel/project

# Service file copy karo
cp /path/to/AIAgentService.php app/Services/

# Verify:
ls -la app/Services/AIAgentService.php

# Controller file copy karo
cp /path/to/AIChatController.php app/Http/Controllers/

# Verify:
ls -la app/Http/Controllers/AIChatController.php
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

# Verify routes added:
php artisan route:list | grep ai
```

### Step 3: .env Configuration

```bash
# .env mein add karo:
AI_AGENT_URL=http://127.0.0.1:5000/api
AI_AGENT_TIMEOUT=30

# Verify .env:
grep AI_AGENT .env
```

### Step 4: Test Karo

```bash
# Laravel server start karo:
php artisan serve

# Doosre terminal se test karo:
curl -X POST http://localhost:8000/api/ai/support \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"query": "Namaste kaise ho?"}'

# Expected response:
# {"success": true, "response": "...", "type": "support"}
```

---

## 💻 Chat Widget Setup

### Step 1: Blade Component

```bash
# Chat widget file copy karo:
cp /path/to/chat-widget.blade.php resources/views/components/

# Verify:
ls -la resources/views/components/chat-widget.blade.php
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
# http://localhost:8000

# Bottom-right mein chat widget dikhai dena chahiye
# Click karo aur message bhejo
```

---

## 🚀 Production Deployment (cPanel)

### Method 1: Cron Job (Recommended)

```bash
1. cPanel login karo
2. Cron Jobs mein jao
3. New Cron Job add karo:

Command:
cd /home/username/ai_bot_service && source ai_bot_env/bin/activate && nohup gunicorn -w 4 -b 127.0.0.1:5000 app:app > logs/app.log 2>&1 &

Timing: @reboot (Server restart par automatically start ho jayega)

# Manual test:
/home/username/ai_bot_service && source ai_bot_env/bin/activate && nohup gunicorn -w 4 -b 127.0.0.1:5000 app:app > logs/app.log 2>&1 &
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

# Kill process (agar karna ho):
killall gunicorn
```

---

## 🔍 Monitoring & Logs

### Logs Check Karo

```bash
# Flask app logs
cd ~/ai_bot_service
tail -f logs/app.log

# Last 50 lines:
tail -50 logs/app.log

# Search for errors:
grep "ERROR" logs/app.log

# Real-time monitoring:
tail -f logs/app.log | grep -i error
```

### API Health Check

```bash
# Health endpoint test karo:
curl http://127.0.0.1:5000/health

# Response:
# {"status": "ok", "service": "AI Agent API", "groq_status": "connected"}

# Verbose test (headers dekho):
curl -v http://127.0.0.1:5000/health
```

### Performance Monitor

```bash
# RAM/CPU usage
free -h
free -m

# Disk usage
df -h

# Top processes
top -b -n 1 | head -20

# Python process details
ps aux | grep python

# gunicorn process details
ps aux | grep gunicorn

# Network connections
netstat -tuln | grep 5000

# Check if port 5000 is listening
lsof -i :5000
```

---

## 🐛 Troubleshooting

### Problem 1: "Connection refused" error

```bash
# Solution:
# 1. Python service running hai check karo:
ps aux | grep gunicorn
ps aux | grep python

# Nahi mil raha to service start karo:
cd ~/ai_bot_service
source ai_bot_env/bin/activate
gunicorn -w 4 -b 127.0.0.1:5000 app:app &

# 2. Port 5000 available hai check karo:
lsof -i :5000
netstat -tuln | grep 5000

# 3. Restart service:
killall gunicorn
cd ~/ai_bot_service
source ai_bot_env/bin/activate
gunicorn -w 4 -b 127.0.0.1:5000 app:app &

# 4. Check firewall:
sudo ufw status
# Port 5000 open hona chahiye (ya localhost par kaam karta hai)
```

### Problem 2: "GROQ_API_KEY not found"

```bash
# Solution:
# .env file check karo
cat ~/ai_bot_service/.env

# Hona chahiye:
# GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxx

# Nahi ho to set karo:
echo "GROQ_API_KEY=gsk_xxxxx" >> ~/ai_bot_service/.env

# Verify:
cat ~/ai_bot_service/.env

# Restart service:
killall gunicorn
cd ~/ai_bot_service && gunicorn -w 4 -b 127.0.0.1:5000 app:app &
```

### Problem 3: "ModuleNotFoundError: No module named 'flask'"

```bash
# Solution:
# Virtual environment activate hai check karo:
echo $VIRTUAL_ENV
# Should show: /home/username/ai_bot_env

# Nahi to activate karo:
source ~/ai_bot_env/bin/activate

# Requirements install karo:
pip install -r requirements.txt

# Verify:
pip list | grep flask
```

### Problem 4: "Python version too old"

```bash
# Check version:
python3 --version

# Agar 3.8 se kam hai:
# Contact hosting provider
# Ask for: "Python 3.8 or higher"

# Or try alternative:
python --version
which python
```

### Problem 5: Laravel se connection timeout

```bash
# Solution:
# 1. .env check karo:
grep AI_AGENT ~/.env

# Correct URL hona chahiye:
# AI_AGENT_URL=http://127.0.0.1:5000/api

# 2. Timeout increase karo agar slow hai:
AI_AGENT_TIMEOUT=60

# 3. Flask service running hai verify karo:
ps aux | grep gunicorn

# 4. Direct test karo:
curl http://127.0.0.1:5000/health

# 5. Laravel logs check karo:
tail -f storage/logs/laravel.log

# 6. PHP error logs:
tail -f /var/log/apache2/error.log
```

### Problem 6: Chat widget nahi show ho raha

```bash
# Solution:
# 1. Browser console check karo (F12 press karo)
# Console tab mein errors dekho

# 2. CSRF token hai check karo:
# Page source mein (Ctrl+U):
# <meta name="csrf-token" content="...">

# 3. Routes check karo:
php artisan route:list | grep ai

# Expected output:
# POST   api/ai/support
# POST   api/ai/code
# POST   api/ai/chat

# 4. API call manual test karo:
curl -X POST http://localhost:8000/api/ai/support \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: $(grep -oP 'csrf-token" content="\K[^"]*' your_page.html)" \
  -d '{"query": "Test"}'

# 5. File present hai check karo:
ls -la resources/views/components/chat-widget.blade.php
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

# Verify:
ls -la ~/ai_bot_service/.env

# 2. API validation enable karo (already done in code)

# 3. Rate limiting (optional, add later)

# 4. HTTPS use karo production mein
# Contact hosting provider for SSL certificate

# 5. Hide .env file from web:
# .htaccess mein:
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

---

## 📞 Support & Resources

- **Groq API**: https://console.groq.com
- **Flask Docs**: https://flask.palletsprojects.com
- **Laravel Docs**: https://laravel.com/docs
- **GitHub Issue**: Post here if problem ho

---

## ✅ Checklist

- [ ] Python 3.8+ installed (verify: `python3 --version`)
- [ ] Virtual environment created (`ls -la ai_bot_env`)
- [ ] Virtual environment activated (prompt mein `(ai_bot_env)`)
- [ ] requirements.txt installed (`pip list`)
- [ ] .env file setup with GROQ_API_KEY (`cat .env`)
- [ ] Flask server runs locally (`python app.py`)
- [ ] Health endpoint works (`curl http://localhost:5000/health`)
- [ ] gunicorn installed (`which gunicorn`)
- [ ] Python app running in production (`ps aux | grep gunicorn`)
- [ ] Laravel service created (`ls -la app/Services/AIAgentService.php`)
- [ ] Laravel controller created (`ls -la app/Http/Controllers/AIChatController.php`)
- [ ] Routes added (`php artisan route:list | grep ai`)
- [ ] .env configured with AI_AGENT_URL (`grep AI_AGENT .env`)
- [ ] Chat widget imported (`ls -la resources/views/components/chat-widget.blade.php`)
- [ ] Website test kiya (browser mein check karo)
- [ ] Production deployment done (cPanel Cron Job)
- [ ] Logs monitoring setup (`tail -f logs/app.log`)

---

**अब आप ready हो!** 🚀

कोई भी issue हो तो GitHub issue open karo ya documentation check karo.
