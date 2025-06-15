<!DOCTYPE html>
<html>
<head>
    <title>403 Forbidden</title>
    <style>
        body { 
            text-align: center; 
            padding: 50px; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            line-height: 1.6;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #dc3545; 
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 30px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 5px;
            direction: rtl; /* للغة العربية */
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>403 | Permission Denied</h1>
        <div class="error-message">
            {{ $exception->getMessage() }}
        </div>
        <a href="{{ url('/homepage') }}" class="btn">  Return to Home Page</a>
    </div>
</body>
</html>