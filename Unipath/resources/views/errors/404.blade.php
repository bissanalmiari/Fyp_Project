<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | UniPath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'Blanche';
            src: url("{{ asset('fonts/Blanche.ttf') }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        :root {
            --bg: #F6F4FE;
            --card: #F1ECFB;
            --primary: #8B5AF0;
            --primary-dark: #7446DB;
            --text: #3F3464;
            --muted: #7D72A8;
            --border: #D8C7FF;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: var(--bg);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .shape-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 700px;
            opacity: 0.55;
            pointer-events: none;
        }

        .shape-bottom {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 500px;
            opacity: 0.45;
            pointer-events: none;
        }

        .error-container {
            width: 100%;
            max-width: 920px;
            position: relative;
            z-index: 2;
        }

        .error-card {
            background: rgba(241, 236, 251, 0.92);
            border: 1px solid var(--border);
            border-radius: 36px;
            padding: 62px 40px;
            text-align: center;
            box-shadow: 0 24px 60px rgba(139, 90, 240, 0.10);
            backdrop-filter: blur(8px);
        }

        .error-image {
            width: 180px;
            margin-bottom: 20px;
            animation: float 4s ease-in-out infinite;
        }

        .brand {
            font-family: 'Blanche', cursive;
            color: var(--primary);
            font-size: 38px;
            line-height: 1;
            margin-bottom: 22px;
            letter-spacing: 1px;
        }

        .error-code {
            font-family: 'Blanche', cursive;
            font-size: 120px;
            line-height: 0.9;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 58px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 22px;
            color: var(--text);
        }

        .error-text {
            max-width: 640px;
            margin: 0 auto 34px;
            font-size: 17px;
            line-height: 1.9;
            color: var(--muted);
        }

        .error-actions {
            display: flex;
            justify-content: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 190px;
            padding: 16px 28px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8B5AF0, #9F73FF);
            color: var(--white);
            box-shadow: 0 12px 28px rgba(139, 90, 240, 0.22);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(139, 90, 240, 0.28);
        }

        .btn-secondary {
            border: 1px solid var(--border);
            color: var(--primary);
            background: transparent;
        }

        .btn-secondary:hover {
            background: rgba(139, 90, 240, 0.06);
        }

        @media (max-width: 768px) {
            .error-card {
                padding: 46px 24px;
                border-radius: 28px;
            }

            .brand {
                font-size: 30px;
            }

            .error-code {
                font-size: 88px;
            }

            .error-title {
                font-size: 40px;
            }

            .error-text {
                font-size: 15px;
                line-height: 1.8;
            }

            .btn {
                width: 100%;
            }

            .shape-top {
                width: 420px;
            }

            .shape-bottom {
                width: 320px;
            }
        }
    </style>
</head>
<body>
    <img src="{{ asset('images/Shape1.png') }}" class="shape-top" alt="">
    <img src="{{ asset('images/Shape4.png') }}" class="shape-bottom" alt="">

    <div class="error-container">
        <div class="error-card"style="transform: translateY(-20PX)">
            <img src="{{ asset('images/grad-hat2.png') }}" class="error-image" alt="Graduation cap"style="transform: translate(-70PX, 60px)">
            <div class="error-code"style="transform: translateY(-30PX)">404</div>
            <h1 class="error-title">Page not found</h1>
            <p class="error-text">
                The page you are looking for may have been moved, deleted, or the link may be incorrect.
                Let’s get you back to something useful.
            </p>

            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
    </div>
</body>
</html>