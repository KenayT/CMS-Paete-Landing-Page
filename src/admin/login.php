<?php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();

// If already logged in, redirect straight to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password =$_POST['password'] ?? '';

    if (empty($username) || empty($password)) {$error = 'Please fill in all fields.';
    } else {
        try {
            $pdo = getPDO();
            $stmt =$pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $admin =$stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password,$admin['password_hash'])) {
                $_SESSION['admin_id'] =$admin['id'];
                $_SESSION['admin_username'] =$admin['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (Exception $e) {
            $error = 'Database error: ' .$e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rural Bank of Paete</title>
    <style>
        /* CSS Variables */
        :root {
            --bg-base: #0a0f1d;
            --bg-fluid: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.15), transparent 60%);
            --grid-line: rgba(255, 255, 255, 0.04);
            --card-bg: rgba(17, 24, 39, 0.85);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --input-bg: rgba(30, 41, 59, 0.7);
            --input-border: #334155;
            --accent: #3b82f6; 
            --accent-hover: #2563eb;
            --btn-sec-bg: rgba(30, 41, 59, 0.5);
            --btn-sec-border: rgba(255, 255, 255, 0.12);
        }

        [data-theme="light"] {
            --bg-base: #f8fafc;
            --bg-fluid: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.1), transparent 60%);
            --grid-line: rgba(0, 0, 0, 0.05);
            --card-bg: rgba(255, 255, 255, 0.85);
            --card-border: rgba(0, 0, 0, 0.08);
            --text-main: #0f172a;
            --text-sub: #64748b;
            --input-bg: #f1f5f9;
            --input-border: #cbd5e1;
            --btn-sec-bg: #f8fafc;
            --btn-sec-border: #cbd5e1;
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
            border: 1px solid var(--card-border);
            color: var(--text-main);
            padding: 10px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            backdrop-filter: blur(8px);
        }

        .theme-toggle:hover { transform: scale(1.1); }
        .theme-toggle svg { width: 20px; height: 20px; }
        #icon-sun { display: none; }
        [data-theme="light"] #icon-sun { display: block; }
        [data-theme="light"] #icon-moon { display: none; }

        /* --- Card Container --- */
        .auth-container {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 44px 40px;
            z-index: 1;
            margin: 20px;
            transition: background-color 0.5s ease, border-color 0.5s ease, box-shadow 0.5s ease;
        }

        .form-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.25;
            color: var(--text-main);
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-sub);
        }

        .input-group input {
            width: 100%;
            padding: 13px 16px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 0.95rem;
            outline: none;
            transition: all 0.25s ease;
        }

        .input-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }

        /* --- Primary Action Button --- */
        .submit-btn {
            width: 100%;
            padding: 13px;
            background: var(--accent);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
            display: block;
        }

        .submit-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* --- Divider --- */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 22px 0;
            color: var(--text-sub);
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--grid-line);
        }

        .divider span {
            padding: 0 12px;
        }

        /* --- Secondary Register Button --- */
        .register-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--btn-sec-bg);
            border: 1px solid var(--btn-sec-border);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 0.95rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .register-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-1px);
            background: rgba(59, 130, 246, 0.08);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            text-align: center;
        }

        a:hover ~ .cursor-outline, 
        button:hover ~ .cursor-outline, 
        input:hover ~ .cursor-outline {
            width: 50px;
            height: 50px;
            background-color: rgba(59, 130, 246, 0.12);
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
    <button class="theme-toggle" id="theme-btn" title="Toggle Light/Dark Theme">
        <svg id="icon-moon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
        <svg id="icon-sun" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </button>

    <div class="auth-container">
        <div class="form-header">
            <h1>Welcome to Rural Bank Paete</h1>
        </div>

        <?php if($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="submit-btn">Log in</button>
        </form>

        <div class="divider">
            <span>or</span>
        </div>

        <!-- Register button moved cleanly to the bottom -->
        <a href="register.php" class="register-btn">Create Admin Account</a>
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

        // --- Animated Shooting Lines Logic (Subtle Multi-Color) ---
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