<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to eSewa...</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9fafb;
            color: #111827;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #60a5fa;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .logo {
            max-width: 120px;
            margin-bottom: 1rem;
        }
        .text-sm {
            font-size: 0.875rem;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- We use an eSewa green color for the loader and branding here -->
        <style>
            .loader { border-top-color: #60BB46; }
        </style>
        <div class="loader"></div>
        <h2 style="margin: 0 0 0.5rem 0;">Redirecting...</h2>
        <p class="text-sm">Please wait while we redirect you to eSewa's secure payment gateway.</p>

        <form action="{{ $esewaForm['action_url'] }}" method="POST" id="esewa-form">
            @foreach($esewaForm as $key => $value)
                @if($key !== 'action_url')
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <noscript>
                <button type="submit" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #60BB46; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Click here if not redirected automatically
                </button>
            </noscript>
        </form>
    </div>

    <script>
        // Automatically submit the form to redirect to eSewa
        setTimeout(function() {
            document.getElementById('esewa-form').submit();
        }, 500); // slight delay for visual transition
    </script>
</body>
</html>
