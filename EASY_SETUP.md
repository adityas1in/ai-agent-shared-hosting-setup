# 🚀 Shared Hosting Setup - Super Easy (Copy-Paste Ready!)

**⚠️ IMPORTANT: Terminal mein copy-paste nahi hota?**
- Right-click → Paste use karo
- Ya Shift+Insert try karo
- Ya manually type karo (slow but sure)

---

## 📝 Step-by-Step Instructions

### ✅ STEP 1: Check Python (2 minutes)

**Kya karna hai:** Dekho Python hai ki nahi

```bash
python3 --version
```

**Output dekho:**
```
Python 3.8.0 ya usse zyada = ✅ GOOD
Python 2.7 ya error = ❌ PROBLEM
```

**Agar error aaye:**
```bash
python --version
```

---

### ✅ STEP 2: Create Folder (1 minute)

**Kya karna hai:** Apne home folder mein ai_bot_service folder banao

```bash
cd ~
mkdir -p ai_bot_service
cd ai_bot_service
```

**Verify:**
```bash
pwd
# Should show: /home/username/ai_bot_service
```

---

### ✅ STEP 3: Create Virtual Environment (2 minutes)

**Kya karna hai:** Python ke liye separate environment banao

```bash
python3 -m venv ai_bot_env
```

**Ya agar python3 nahi chalega:**
```bash
python -m venv ai_bot_env
```

**Verify:**
```bash
ls ai_bot_env
# Should show: bin, include, lib, pyvenv.cfg
```

---

### ✅ STEP 4: Activate Virtual Environment (1 minute)

**Kya karna hai:** Environment ko ON karo

```bash
source ai_bot_env/bin/activate
```

**Verify:** Terminal prompt mein `(ai_bot_env)` likha hona chahiye

```
(ai_bot_env) username@host:~/ai_bot_service$
```

---

### ✅ STEP 5: Copy Project Files (2 minutes)

**Kya karna hai:** GitHub se files download/copy karo

**Option A: Git se download karo (agar git hai)**
```bash
cd ~/ai_bot_service
git clone https://github.com/adityas1in/ai-agent-shared-hosting-setup.git .
```

**Option B: Manual copy karo**
- Go to: https://github.com/adityas1in/ai-agent-shared-hosting-setup
- Download files:
  - `requirements.txt`
  - `app.py`
  - `.env.example`
  - Upload apne server par `~/ai_bot_service/` mein

**Verify:**
```bash
ls -la
# Should show: app.py, requirements.txt, .env.example
```

---

### ✅ STEP 6: Setup .env File (2 minutes)

**Kya karna hai:** API key set karo

**Step 1: .env file banao**
```bash
cp .env.example .env
```

**Step 2: File mein Groq API key add karo**
```bash
nano .env
```

**Ya vi se:**
```bash
vi .env
```

**File mein ye likha hona chahiye:**
```
GROQ_API_KEY=gsk_xxxxxxxxxxxxx
FLASK_ENV=production
FLASK_DEBUG=0
PORT=5000
```

**API key kaha se milega?**
1. https://console.groq.com par jao
2. Sign up karo (free)
3. API keys section se copy karo
4. `.env` mein paste karo

**File save karo:**
- nano mein: `Ctrl+X` → `Y` → `Enter`
- vi mein: `:wq` → `Enter`

**Verify:**
```bash
cat .env
# Should show: GROQ_API_KEY=gsk_...
```

---

### ✅ STEP 7: Install Dependencies (3-5 minutes)

**Kya karna hai:** Python libraries install karo

```bash
pip install --upgrade pip
```

**Phir dependencies install karo:**
```bash
pip install -r requirements.txt
```

**Wait karo... installation chalega (2-5 minutes)**

**Verify:**
```bash
pip list
# Should show: flask, groq, requests, etc.
```

---

### ✅ STEP 8: Test Locally (2 minutes)

**Kya karna hai:** Flask server chalao aur dekho kaam kar raha hai ki nahi

```bash
python app.py
```

**Output:**
```
 * Running on http://127.0.0.1:5000
```

**Doosre terminal window mein (NEW terminal kholo):**

```bash
curl http://127.0.0.1:5000/health
```

**Expected output:**
```json
{"status": "ok", "service": "AI Agent API", "groq_status": "connected"}
```

**Server stop karo:**
- First terminal mein: `Ctrl+C` press karo

---

### ✅ STEP 9: Setup Production (gunicorn) (3 minutes)

**Kya karna hai:** Server ko background mein chalao

```bash
pip install gunicorn
```

**Logs folder banao:**
```bash
mkdir -p logs
```

**Server start karo:**
```bash
nohup gunicorn -w 4 -b 127.0.0.1:5000 app:app > logs/app.log 2>&1 &
```

**Verify running:**
```bash
ps aux | grep gunicorn
```

**Should show:**
```
username 12345 gunicorn -w 4 -b 127.0.0.1:5000 app:app
```

**Logs dekho:**
```bash
tail logs/app.log
```

---

## 🔗 Laravel Integration

### ✅ STEP 10: Copy Laravel Files (2 minutes)

**Kya karna hai:** Services aur Controllers add karo Laravel app mein

**Navigate to Laravel:**
```bash
cd /path/to/your/laravel/app
```

**Files copy karo:**
```bash
# Services folder mein
mkdir -p app/Services
cp /path/to/AIAgentService.php app/Services/

# Controllers folder mein (already exists usually)
cp /path/to/AIChatController.php app/Http/Controllers/
```

**Verify:**
```bash
ls app/Services/AIAgentService.php
ls app/Http/Controllers/AIChatController.php
```

---

### ✅ STEP 11: Add Routes (1 minute)

**Kya karna hai:** Routes file mein code add karo

**Open file:**
```bash
nano routes/api.php
```

**Add ye code (end mein):**
```php
use App\Http\Controllers\AIChatController;

Route::prefix('ai')->group(function () {
    Route::post('/support', [AIChatController::class, 'support']);
    Route::post('/code', [AIChatController::class, 'coding']);
    Route::post('/chat', [AIChatController::class, 'chat']);
});
```

**Save karo:** `Ctrl+X` → `Y` → `Enter`

**Verify:**
```bash
php artisan route:list | grep ai
```

---

### ✅ STEP 12: Setup Laravel .env (1 minute)

**Kya karna hai:** AI service URL add karo

**Open .env:**
```bash
nano .env
```

**Add ye lines (end mein):**
```
AI_AGENT_URL=http://127.0.0.1:5000/api
AI_AGENT_TIMEOUT=30
```

**Save karo:** `Ctrl+X` → `Y` → `Enter`

**Verify:**
```bash
grep AI_AGENT .env
```

---

### ✅ STEP 13: Add Chat Widget (1 minute)

**Kya karna hai:** Chat widget ko website mein add karo

**Copy widget file:**
```bash
mkdir -p resources/views/components
cp /path/to/chat-widget.blade.php resources/views/components/
```

**Open layout file:**
```bash
nano resources/views/layouts/app.blade.php
```

**Add ye code (head section mein):**
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

**Add ye code (body ke end mein):**
```blade
@include('components.chat-widget')
```

**Save karo:** `Ctrl+X` → `Y` → `Enter`

---

### ✅ STEP 14: Test Everything (2 minutes)

**Laravel server start karo:**
```bash
php artisan serve
```

**Browser mein jao:**
```
http://localhost:8000
```

**Dekho:**
- ✅ Bottom-right mein chat widget visible hai?
- ✅ Message type kar sakte ho?
- ✅ Response aa raha hai?

---

## 🎯 Quick Reference Table

| Step | Command | Time |
|------|---------|------|
| 1 | `python3 --version` | 30 sec |
| 2 | `cd ~ && mkdir -p ai_bot_service && cd ai_bot_service` | 1 min |
| 3 | `python3 -m venv ai_bot_env` | 2 min |
| 4 | `source ai_bot_env/bin/activate` | 10 sec |
| 5 | Copy files from GitHub | 2 min |
| 6 | `cp .env.example .env` + add API key | 2 min |
| 7 | `pip install -r requirements.txt` | 5 min |
| 8 | `python app.py` | 1 min |
| 9 | `gunicorn -w 4 -b 127.0.0.1:5000 app:app &` | 1 min |
| 10 | Copy Laravel files | 2 min |
| 11 | Add routes | 1 min |
| 12 | Setup .env | 1 min |
| 13 | Add widget | 1 min |
| 14 | Test | 2 min |
| **TOTAL** | | **25 minutes** |

---

## ⚠️ Common Issues & Quick Fixes

### Issue 1: "python3: command not found"
```bash
# Try this instead:
python --version

# Agar ye bhi nahi milta:
# Contact hosting: "Enable Python 3.8+"
```

### Issue 2: "pip command not found"
```bash
# Try:
pip3 --version

# Or:
python3 -m pip --version
```

### Issue 3: "venv: command not found"
```bash
# Install python3-venv:
sudo apt-get install python3-venv
# Or contact hosting provider
```

### Issue 4: "Permission denied"
```bash
# Add executable permission:
chmod +x ai_bot_env/bin/activate
source ai_bot_env/bin/activate
```

### Issue 5: "Connection refused" (Laravel se Python tak)
```bash
# Check if server running:
ps aux | grep gunicorn

# Restart:
killall gunicorn
cd ~/ai_bot_service
source ai_bot_env/bin/activate
nohup gunicorn -w 4 -b 127.0.0.1:5000 app:app > logs/app.log 2>&1 &
```

### Issue 6: Chat widget nahi dikha
```bash
# Check browser console (F12)
# Check meta tag present hai:
grep csrf-token resources/views/layouts/app.blade.php

# Check routes:
php artisan route:list | grep ai

# Check file exists:
ls resources/views/components/chat-widget.blade.php
```

---

## 📱 Copy-Paste Guide

**Terminal mein copy-paste nahi ho raha?**

### Method 1: Right-Click (Easiest)
```
1. Command ko select karo (mouse se drag karo)
2. Right-click karo
3. "Copy" click karo
4. Terminal mein right-click karo
5. "Paste" click karo
```

### Method 2: Keyboard Shortcut
```
1. Command ko select karo
2. Ctrl+C press karo
3. Terminal mein Ctrl+Shift+V press karo (ya Shift+Insert)
```

### Method 3: Type Manually
```bash
# Command type karo slowly
# Copy whole command ek line mein
# Press Enter
```

### Method 4: File Se Read Karo
```bash
# Instructions ko download karo
# Notepad mein open karo
# Side mein rakh ke padhte hue type karo
```

---

## ✅ Final Checklist

```
- [ ] Python installed (python3 --version)
- [ ] ai_bot_service folder created (cd ~/ai_bot_service)
- [ ] Virtual env created (ls ai_bot_env)
- [ ] Virtual env activated ((ai_bot_env) in prompt)
- [ ] Files copied (ls app.py)
- [ ] .env setup with API key (cat .env)
- [ ] Dependencies installed (pip list)
- [ ] Flask test passed (curl http://localhost:5000/health)
- [ ] gunicorn running (ps aux | grep gunicorn)
- [ ] Laravel files copied (ls app/Services/AIAgentService.php)
- [ ] Routes added (php artisan route:list | grep ai)
- [ ] Laravel .env updated (grep AI_AGENT .env)
- [ ] Widget added (ls resources/views/components/chat-widget.blade.php)
- [ ] Chat widget visible in browser ✅
- [ ] Bot responding to messages ✅
```

---

## 🚀 Done! You're Ready!

**Summary:**
- Python AI backend: ✅ Running on localhost:5000
- Laravel integration: ✅ API connected
- Chat widget: ✅ Live on website
- Customer support: ✅ Automated
- Cost: ✅ FREE (Groq + Shared Hosting)

**Next steps:**
1. Deploy to production (cPanel cron job)
2. Add more features
3. Monitor logs
4. Scale as needed

---

**Any issues?**
- Check logs: `tail logs/app.log`
- GitHub: https://github.com/adityas1in/ai-agent-shared-hosting-setup
- Common errors in troubleshooting section above 👆

**Happy coding! 🎉**
