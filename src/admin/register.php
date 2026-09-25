<?php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();

$error = '';$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password =$_POST['password'] ?? '';
    $confirm  =$_POST['confirm'] ?? '';

    if (empty($username) || empty($password)) {$error = 'All fields are required.';
    } elseif (strlen($password) < 8) {$error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {$error = 'Passwords do not match.';
    } else {
        $pdo = getPDO();
        $stmt =$pdo->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {$error = 'That username is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt =$pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);$success = 'Account created! <a href="login.php" style="color: var(--accent); font-weight: 600;">Log in &rarr;</a>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin - Rural Bank of Paete</title>
    <style>
        /* CSS Variables */
        :root {
            --bg-base: #0a0f1d;
            --bg-fluid: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 60%);
            --grid-line: rgba(255, 255, 255, 0.04);
            --card-bg: #111827;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --input-bg: #1e293b;
            --input-border: #334155;
            --accent: #3b82f6; 
            --accent-hover: #2563eb;
        }

        [data-theme="light"] {
            --bg-base: #f8fafc;
            --bg-fluid: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.1), transparent 60%);
            --grid-line: rgba(0, 0, 0, 0.05);
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --input-bg: #f1f5f9;
            --input-border: #cbd5e1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            cursor: none !important;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.5s ease, color 0.5s ease;
            position: relative;
            overflow: hidden;
        }

        /* --- Custom Cursor --- */
        .cursor-dot {
            width: 6px;
            height: 6px;
            background-color: var(--accent);
            position: fixed;
            top: 0; left: 0;
            border-radius: 50%;
            z-index: 9999;
            pointer-events: none;
            transform: translate(-50%, -50%);
        }

        .cursor-outline {
            width: 34px;
            height: 34px;
            border: 1px solid var(--accent);
            position: fixed;
            top: 0; left: 0;
            border-radius: 50%;
            z-index: 9998;
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s;
        }

        /* --- Background Grid & Fluid --- */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(var(--grid-line) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: -3;
        }

        body::after {
            content: "";
            position: absolute;
            inset: -50%;
            background: var(--bg-fluid);
            z-index: -2;
            animation: fluidFlow 15s infinite alternate ease-in-out;
            pointer-events: none;
        }

        @keyframes fluidFlow {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(5%, 5%) scale(1.1); }
            100% { transform: translate(-5%, -5%) scale(1); }
        }

        /* --- Shooting Grid Lines --- */
        .lines-container {
            position: absolute;
            inset: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .shooting-line {
            position: absolute;
            width: 1px;
            height: 80px;
            opacity: 0.6;
            border-radius: 4px;
            animation-name: shootDown;
            animation-timing-function: linear;
            animation-fill-mode: forwards;
        }

        .shooting-line.horizontal {
            width: 80px;
            height: 1px;
            animation-name: shootRight;
        }

        @keyframes shootDown {
            0% { transform: translateY(-120px); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(100vh); opacity: 0; }
        }

        @keyframes shootRight {
            0% { transform: translateX(-120px); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateX(100vw); opacity: 0; }
        }

        /* --- Theme Toggle --- */
        .theme-toggle {
            position: absolute;
            top: 24px;
            right: 24px;
            background: var(--card-bg);
            border: 1px solid var(--input-border);
            color: var(--text-main);
            padding: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .theme-toggle:hover { transform: scale(1.1); }
        .theme-toggle svg { width: 20px; height: 20px; }
        #icon-sun { display: none; }
        [data-theme="light"] #icon-sun { display: block; }
        [data-theme="light"] #icon-moon { display: none; }

        /* --- Registration Card --- */
        .auth-container {
            display: flex;
            background: var(--card-bg);
            width: 100%;
            max-width: 450px;
            border-radius: 16px;
            border: 1px solid var(--grid-line);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            z-index: 1;
            margin: 20px;
            flex-direction: column;
            padding: 40px;
        }

        .form-header { margin-bottom: 32px; text-align: center; }
        .form-header h1 { font-size: 1.8rem; font-weight: 600; margin-bottom: 8px; line-height: 1.2; }
        .form-header p { color: var(--text-sub); font-size: 0.95rem; }

        .input-group { margin-bottom: 20px; }
        .input-group label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 6px;
            color: var(--text-sub);
        }
        .input-group input {
            width: 100%;
            padding: 14px 16px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }
        .input-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover { background: var(--accent-hover); }

        .sub-link {
            text-align: center;
            margin-top: 24px;
            font-size: 0.9rem;
            color: var(--text-sub);
        }
        .sub-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .success-msg {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        /* Highlight cursor when hovering over interactive elements */
        a:hover ~ .cursor-outline, 
        button:hover ~ .cursor-outline, 
        input:hover ~ .cursor-outline {
            width: 50px;
            height: 50px;
            background-color: rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body>

    <!-- Custom Cursor -->
    <div class="cursor-dot" data-cursor-dot></div>
    <div class="cursor-outline" data-cursor-outline></div>

    <!-- Background Lines Container -->
    <div class="lines-container" id="lines-container"></div>

    <!-- Theme Toggle -->
    <button class="theme-toggle" id="theme-btn">
        <svg id="icon-moon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
        <svg id="icon-sun" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>

    <div class="auth-container">
        <div class="form-header">
            <h1>Create Admin</h1>
            <p>Setup a new administrative account.</p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-msg"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="confirm">Confirm Password</label>
                <input type="password" name="confirm" required>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>
        <?php endif; ?>

        <div class="sub-link">
            <a href="login.php">&larr; Back to Login</a>
        </div>
    </div>

    <script>
        // --- Theme Toggle Logic ---
        const themeBtn = document.getElementById('theme-btn');
        const htmlElement = document.documentElement;

        if (localStorage.getItem('admin-theme') === 'light' || 
           (!('admin-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            htmlElement.setAttribute('data-theme', 'light');
        }

        themeBtn.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            if (currentTheme === 'light') {
                htmlElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('admin-theme', 'dark');
            } else {
                htmlElement.setAttribute('data-theme', 'light');
                localStorage.setItem('admin-theme', 'light');
            }
        });

        // --- Custom Cursor Logic ---
        const cursorDot = document.querySelector('[data-cursor-dot]');
        const cursorOutline = document.querySelector('[data-cursor-outline]');
        
        window.addEventListener('mousemove', (e) => {
            const posX = e.clientX;
            const posY = e.clientY;
            
            cursorDot.style.left = `${posX}px`;
            cursorDot.style.top = `${posY}px`;
            
            cursorOutline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 400, fill: "forwards" });
        });

        // --- Animated Shooting Lines Logic (Subtle Version) ---
        const linesContainer = document.getElementById('lines-container');
        const gridSize = 40; 
        
        const colors = [
            'rgba(239, 68, 68, 0.6)',   // Red
            'rgba(34, 197, 94, 0.6)',   // Green
            'rgba(234, 179, 8, 0.6)',   // Yellow
            'rgba(56, 189, 248, 0.6)',  // Light Blue
            'rgba(29, 78, 216, 0.6)',   // Dark Blue
            'rgba(255, 255, 255, 0.5)'  // White
        ];

        function createShootingLine() {
            const line = document.createElement('div');
            const isHorizontal = Math.random() > 0.5;
            const color = colors[Math.floor(Math.random() * colors.length)];
            const duration = Math.random() * 2.5 + 1.5; 
            
            line.classList.add('shooting-line');
            line.style.animationDuration = `${duration}s`;
            line.style.opacity = '0.6'; 
            
            if (isHorizontal) {
                line.classList.add('horizontal');
                const randomY = Math.floor(Math.random() * (window.innerHeight / gridSize)) * gridSize;
                line.style.top = `${randomY}px`;
                line.style.left = '-80px'; 
                
                line.style.background = `linear-gradient(to right, transparent, ${color}, transparent)`;
                line.style.boxShadow = `0 0 6px ${color}`; 
            } else {
                const randomX = Math.floor(Math.random() * (window.innerWidth / gridSize)) * gridSize;
                line.style.left = `${randomX}px`;
                line.style.top = '-80px'; 
                
                line.style.background = `linear-gradient(to bottom, transparent, ${color}, transparent)`;
                line.style.boxShadow = `0 0 6px ${color}`;
            }
            
            linesContainer.appendChild(line);
            
            setTimeout(() => {
                line.remove();
            }, duration * 1000);
        }

        function spawnLoop() {
            createShootingLine();
            const nextSpawnTime = Math.random() * 800 + 400; 
            setTimeout(spawnLoop, nextSpawnTime);
        }
        
        spawnLoop();
    </script>
</body>
</html>