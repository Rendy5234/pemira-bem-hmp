<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Pemira BEM & HMP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
        }
        .checkbox-group {
            margin-bottom: 20px;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }
        .checkbox-group input {
            margin-right: 8px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #1d4ed8;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        .demo-accounts {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .demo-accounts p {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .demo-accounts table {
            width: 100%;
            font-size: 12px;
        }
        .demo-accounts td {
            padding: 5px 0;
        }
        .demo-accounts td:first-child {
            color: #666;
        }
        .demo-accounts td:last-child {
            color: #999;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Panel</h1>
            <p>Pemira BEM & HMP</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            
            <div class="form-group">
                <label>Username</label>
                <input 
                    type="text" 
                    name="username" 
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="remember">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="demo-accounts">
            <p>Akun Demo:</p>
            <table>
                <tr>
                    <td><strong>Super Admin:</strong> superadmin</td>
                    <td>super123</td>
                </tr>
                <tr>
                    <td><strong>Admin BEM:</strong> admin_bem</td>
                    <td>admin123</td>
                </tr>
                <tr>
                    <td><strong>Read Staff:</strong> readstaf</td>
                    <td>read123</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>