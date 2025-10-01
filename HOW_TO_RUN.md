# How to Run the Event Management System

## 🚀 Quick Start (Everything Already Installed)

### Step 1: Open Terminal
Open your terminal/command prompt

### Step 2: Navigate to Project
```bash
cd "/Users/hiteshsharma/Downloads/Web application/event-management-system"
```

### Step 3: Start Server
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Step 4: Open Browser
Go to: **http://127.0.0.1:8000**

## 🔑 Login Credentials (All passwords: `password`)

### Organizer Accounts:
- `orgnizer@test.com` - Has 5 events
- `test@example.com` - Has 2 events  
- `john@example.com` - Has 3 events
- `sarah@example.com` - Has 6 events
- `mike@example.com` - Has 3 events

### Attendee Accounts:
- `alice@example.com`
- `bob@example.com`
- `carol@example.com`
- `david@example.com`
- `emma@example.com`

## 🛠️ Troubleshooting

### If Server Won't Start (Port Busy):
```bash
lsof -ti:8000 | xargs kill -9
php artisan serve --host=127.0.0.1 --port=8000
```

### If Database Issues:
```bash
php artisan migrate:fresh --seed
```

## 📋 What You Can Do

### As Organizer:
- View dashboard with event statistics
- Create, edit, delete events
- View events report (raw SQL)
- Manage bookings

### As Attendee:
- Browse and book events
- View "My Bookings"
- See personalized recommendations
- Filter events with AJAX

## 🎯 Quick Test
1. **Login as Organizer:** `orgnizer@test.com` / `password`
2. **Check Dashboard:** See your 5 events
3. **Login as Attendee:** `alice@example.com` / `password`
4. **Book Events:** Try booking some events

## Stop Server
Press `Ctrl + C` in terminal
