<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sistema de Gestão de Livros') }}</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        
        .container {
            text-align: center;
            max-width: 480px;
            padding: 2rem;
        }
        
        .book-icon {
            width: 160px;
            height: 160px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .book-svg {
            width: 120px;
            height: 120px;
            fill: #fff;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.5));
        }
        
        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: #fff;
        }
        
        .login-message {
            font-size: 1.2rem;
            color: #ccc;
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        
        .login-btn {
            display: inline-block;
            background-color: #fff;
            color: #000;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .login-btn:hover {
            background-color: #f0f0f0;
            color: #000;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 1rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .login-message {
                font-size: 1.1rem;
            }
            
            .book-icon {
                width: 130px;
                height: 130px;
            }
            
            .book-svg {
                width: 90px;
                height: 90px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="book-icon">
            <svg class="book-svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
                <!-- Linha central para simular livro aberto -->
                <line x1="12" y1="5" x2="12" y2="21" stroke="#fff" stroke-width="0.5" stroke-opacity="0.7" />
            </svg>
        </div>
        
        <h1>Gestão de Livros</h1>
        
        <p class="login-message">
            Faça login para acessar o sistema =)
        </p>
        
        <a href="{{ route('login') }}" class="login-btn">
            Fazer Login
        </a>
    </div>
</body>
</html>