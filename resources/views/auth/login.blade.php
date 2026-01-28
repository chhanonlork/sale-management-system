<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-POS </title>
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-digital: #007bff; /* ពណ៌ខៀវឌីជីថល */
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body { 
            background: var(--bg-gradient); 
            font-family: 'Battambang', sans-serif; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0;
        }

        .login-card { 
            width: 100%; 
            max-width: 420px; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px); /* Effect ឌីជីថលបែបកញ្ចក់ */
        }

        .login-header i {
            font-size: 3rem;
            color: var(--primary-digital);
            margin-bottom: 15px;
        }

        .form-label { font-weight: 600; color: #444; }

        /* Input ឌីជីថលរាងមូល */
        .input-group-text {
            background-color: #f8fafc;
            border-right: none;
            color: #6e84a3;
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            border-radius: 0 12px 12px 0;
            padding: 12px;
            background-color: #f8fafc;
            border-left: none;
            transition: 0.3s;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
            background-color: #fff;
        }

        /* ប៊ូតុង Login ឌីជីថល */
        .btn-login {
            background: var(--primary-digital);
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="login-header">
        <i class="fas fa-store-alt"></i> <h3 class="fw-bold mb-1">POS ADMIN</h3>
        <p class="text-muted mb-4 small">សូមបញ្ចូលគណនីដើម្បីគ្រប់គ្រងប្រព័ន្ធ</p>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger border-0 small py-2">
            <ul class="mb-0 list-unstyled">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.perform') }}" class="text-start">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">អ៊ីមែល (Email)</label>
            <div class="input-group shadow-sm">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">លេខសម្ងាត់ (Password)</label>
            <div class="input-group shadow-sm">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-login w-100 mb-3 text-white">
            ចូលប្រើប្រាស់ប្រព័ន្ធ <i class="fas fa-sign-in-alt ms-2"></i>
        </button>
        
        <p class="text-center small text-muted">រក្សាសិទ្ធិគ្រប់យ៉ាង &copy; {{ date('Y') }}</p>
    </form>
</div>

