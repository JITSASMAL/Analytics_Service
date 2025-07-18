<?php
session_start();
require 'dbconnect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM user_data WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $storedHash = $row['password'];

                if (password_verify($password, $storedHash)) {
                    // ✅ Login success
                    $_SESSION['user'] = $row;
                    echo "login-success"; // JS detects this string
                } else {
                    echo "Invalid password. Please try again.";
                }
            } else {
                echo "No user found with that email address.";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "System error. Please try again later.";
        }
    } else {
        echo "Please fill in both email and password fields.";
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quantum Login Portal</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Exo 2', sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
            cursor: none;
        }

        /* Custom Cursor - FIXED */
        .cursor {
            position: fixed;
            width: 20px;
            height: 20px;
            border: 2px solid #00ffff;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: transform 0.1s ease-out;
            backdrop-filter: blur(2px);
            mix-blend-mode: difference;
        }

        .cursor::after {
            content: '';
            position: absolute;
            width: 4px;
            height: 4px;
            background: #00ffff;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 0 10px #00ffff;
        }

        .cursor.hover {
            transform: translate(-50%, -50%) scale(1.5);
            border-color: #ff00ff;
        }

        .cursor.hover::after {
            background: #ff00ff;
            box-shadow: 0 0 15px #ff00ff;
        }

        /* Animated Background */
        .bg-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .neural-network {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                        radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            animation: neuraShift 20s ease-in-out infinite;
        }

        @keyframes neuraShift {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
        }

        /* Particle System */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: #00ffff;
            border-radius: 50%;
            animation: particleFloat 8s linear infinite;
            box-shadow: 0 0 6px #00ffff;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        /* Holographic Grid */
        .holo-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 10s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Main Login Portal */
        .login-portal {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .portal-container {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 60px;
            width: 100%;
            max-width: 500px;
            position: relative;
            border: 1px solid rgba(0, 255, 255, 0.3);
            box-shadow: 
                0 0 50px rgba(0, 255, 255, 0.2),
                inset 0 0 50px rgba(0, 255, 255, 0.1);
            animation: portalPulse 3s ease-in-out infinite;
            overflow: hidden;
        }

        @keyframes portalPulse {
            0%, 100% { 
                box-shadow: 
                    0 0 50px rgba(0, 255, 255, 0.2),
                    inset 0 0 50px rgba(0, 255, 255, 0.1);
            }
            50% { 
                box-shadow: 
                    0 0 80px rgba(0, 255, 255, 0.4),
                    inset 0 0 80px rgba(0, 255, 255, 0.2);
            }
        }

        .portal-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            animation: rotateBorder 4s linear infinite;
        }

        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Header */
        .portal-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }

        .portal-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: 42px;
            font-weight: 900;
            color: #00ffff;
            text-shadow: 
                0 0 10px #00ffff,
                0 0 20px #00ffff,
                0 0 30px #00ffff;
            animation: textGlow 2s ease-in-out infinite alternate;
            letter-spacing: 3px;
        }

        @keyframes textGlow {
            from { text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff, 0 0 30px #00ffff; }
            to { text-shadow: 0 0 20px #00ffff, 0 0 30px #00ffff, 0 0 40px #00ffff; }
        }

        .portal-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            margin-top: 10px;
            font-weight: 300;
            letter-spacing: 1px;
        }

        /* Scanning Line */
        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00ffff, transparent);
            animation: scanAnimation 3s ease-in-out infinite;
        }

        @keyframes scanAnimation {
            0% { transform: translateY(0); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(400px); opacity: 0; }
        }

        /* Error Message - UPDATED */
        .error-message {
            background: rgba(255, 0, 100, 0.2);
            border: 1px solid rgba(255, 0, 100, 0.5);
            color: #ff6b9d;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.5s ease;
        }

        .error-message.show {
            transform: translateY(0);
            opacity: 1;
            animation: errorGlitch 0.5s ease-in-out;
        }

        @keyframes errorGlitch {
            0%, 100% { transform: translateX(0); }
            10% { transform: translateX(-5px); }
            20% { transform: translateX(5px); }
            30% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            50% { transform: translateX(-2px); }
            60% { transform: translateX(2px); }
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 35px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #00ffff;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-container {
            position: relative;
            transform-style: preserve-3d;
        }

        .form-group input {
            width: 100%;
            padding: 18px 25px;
            background: rgba(0, 20, 40, 0.8);
            border: 2px solid rgba(0, 255, 255, 0.3);
            border-radius: 15px;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 2;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-group input:focus {
            outline: none;
            border-color: #00ffff;
            background: rgba(0, 40, 80, 0.9);
            box-shadow: 
                0 0 20px rgba(0, 255, 255, 0.5),
                inset 0 0 20px rgba(0, 255, 255, 0.1);
            transform: translateY(-2px) scale(1.02);
        }

        /* Input Hologram Effect */
        .input-hologram {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 15px;
            background: linear-gradient(45deg, 
                rgba(0, 255, 255, 0.1) 0%, 
                rgba(255, 0, 150, 0.1) 50%, 
                rgba(0, 255, 255, 0.1) 100%);
            opacity: 0;
            transition: all 0.4s ease;
            z-index: 1;
            animation: holoShimmer 3s ease-in-out infinite;
        }

        @keyframes holoShimmer {
            0%, 100% { opacity: 0; }
            50% { opacity: 0.3; }
        }

        .form-group input:focus + .input-hologram {
            opacity: 0.6;
            transform: translateZ(10px);
        }

        /* Submit Button */
        .quantum-btn {
            width: 100%;
            padding: 20px;
            background: linear-gradient(45deg, #00ffff, #ff00ff, #00ffff);
            background-size: 300% 300%;
            border: none;
            border-radius: 15px;
            color: #000;
            font-size: 18px;
            font-weight: 700;
            font-family: 'Orbitron', monospace;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 2px;
            animation: gradientShift 3s ease-in-out infinite;
            box-shadow: 0 10px 30px rgba(0, 255, 255, 0.3);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .quantum-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 255, 255, 0.5);
        }

        .quantum-btn:active {
            transform: translateY(0) scale(0.98);
        }

        /* Button Energy Effect */
        .quantum-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            transition: left 0.5s;
        }

        .quantum-btn:hover::before {
            left: 100%;
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .loading-spinner {
            width: 100px;
            height: 100px;
            border: 3px solid rgba(0, 255, 255, 0.3);
            border-top: 3px solid #00ffff;
            border-radius: 50%;
            animation: quantumSpin 1s linear infinite;
        }

        @keyframes quantumSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .portal-container {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .portal-header h1 {
                font-size: 32px;
            }
            
            .quantum-btn {
                padding: 18px;
                font-size: 16px;
            }
        }

        /* Additional Animations */
        @keyframes fadeInQuantum {
            from {
                opacity: 0;
                transform: translateY(30px) rotateX(20deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0deg);
            }
        }

        .form-group {
            animation: fadeInQuantum 0.8s ease-out forwards;
            animation-delay: calc(var(--delay) * 0.1s);
        }

        /* Quantum Ripple Effect */
        .quantum-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 255, 255, 0.3);
            transform: scale(0);
            animation: rippleExpand 0.6s linear;
            pointer-events: none;
        }

        @keyframes rippleExpand {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Success Animation */
        .success-checkmark {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            z-index: 1001;
        }

        .success-checkmark svg {
            width: 100%;
            height: 100%;
            fill: #00ffff;
            stroke: #00ffff;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            animation: checkmarkDraw 0.8s ease-in-out;
        }

        @keyframes checkmarkDraw {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }
    </style>
</head>
<body>
    <div class="cursor"></div>
    
    <div class="bg-container">
        <div class="neural-network"></div>
        <div class="holo-grid"></div>
        <div class="particles"></div>
    </div>

    <div class="login-portal">
        <div class="portal-container">
            <div class="scan-line"></div>
            
            <div class="portal-header">
                <h1>DATA</h1>
                <p>ACCESS PORTAL</p>
            </div>

            <form method="post" autocomplete="off" id="quantumForm">
                <div class="error-message" id="errorMessage">
                    Authentication failed. Please try again.
                </div>

                <div class="form-group" style="--delay: 1">
                    <label for="email">EMAIL</label>
                    <div class="input-container">
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        <div class="input-hologram"></div>
                    </div>
                </div>

                <div class="form-group" style="--delay: 2">
                    <label for="password">PASSWORD</label>
                    <div class="input-container">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <div class="input-hologram"></div>
                    </div>
                </div>

                <div class="form-group" style="--delay: 3">
                    <button type="submit" class="quantum-btn">
                        INITIATE LOGIN ACCESS
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="success-checkmark" id="successCheckmark">
        <svg viewBox="0 0 100 100">
            <path d="M20 50 L40 70 L80 30" stroke-dasharray="100" stroke-dashoffset="100"/>
        </svg>
    </div>

    <script>
        // Custom Cursor - FIXED
        const cursor = document.querySelector('.cursor');
        let mouseX = 0;
        let mouseY = 0;
        let cursorX = 0;
        let cursorY = 0;

        // Update mouse position
        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        // Smooth cursor animation
        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.1;
            cursorY += (mouseY - cursorY) * 0.1;
            
            cursor.style.left = cursorX + 'px';
            cursor.style.top = cursorY + 'px';
            
            requestAnimationFrame(animateCursor);
        }

        // Start cursor animation
        animateCursor();

        // Cursor hover effects
        const hoverElements = document.querySelectorAll('input, button, a');
        hoverElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                cursor.classList.add('hover');
            });
            
            element.addEventListener('mouseleave', () => {
                cursor.classList.remove('hover');
            });
        });

        // Particle System
        function createParticles() {
            const particlesContainer = document.querySelector('.particles');
            
            setInterval(() => {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.animationDelay = Math.random() * 2 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 5) + 's';
                
                particlesContainer.appendChild(particle);
                
                setTimeout(() => {
                    particle.remove();
                }, 8000);
            }, 300);
        }

        // Input Focus Effects
        document.querySelectorAll('input').forEach((input, index) => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-5px) scale(1.02)';
                this.parentElement.style.zIndex = '10';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0) scale(1)';
                this.parentElement.style.zIndex = '2';
            });

            // Typing effect
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.style.textShadow = '0 0 10px #00ffff';
                } else {
                    this.style.textShadow = 'none';
                }
            });
        });

        // Quantum Ripple Effect
        document.querySelector('.quantum-btn').addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const ripple = document.createElement('div');
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.className = 'quantum-ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Error Message Display Function - UPDATED
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.classList.add('show');
            
            // Hide after 5 seconds
            setTimeout(() => {
                errorDiv.classList.remove('show');
            }, 5000);
        }

        // Form Submission with Better Error Handling - UPDATED
        document.getElementById('quantumForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const loadingOverlay = document.getElementById('loadingOverlay');
            const successCheckmark = document.getElementById('successCheckmark');
            const errorMessage = document.getElementById('errorMessage');
            const formData = new FormData(this);
            
            // Hide any previous error messages
            errorMessage.classList.remove('show');
            
            // Show loading
            loadingOverlay.style.display = 'flex';
            
            // Submit form data
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                loadingOverlay.style.display = 'none';

                if (data.trim() === 'login-success') {
                    // Show success animation
                    successCheckmark.style.display = 'block';
                    
                    // Redirect after animation
                    setTimeout(() => {
                        window.location.href = 'data.php';
                    }, 1500);
                } else {
                    // Show error message with animation
                    showError(data.trim());
                    
                    // Clear form inputs
                    document.getElementById('email').value = '';
                    document.getElementById('password').value = '';
                    
                    // Focus on email input
                    document.getElementById('email').focus();
                }
            })
            .catch(error => {
                loadingOverlay.style.display = 'none';
                showError('Connection error. Please check your network and try again.');
                console.error('Error:', error);
            });
        });

        // Initialize Particle System
        createParticles();

        // Hologram Distortion on Hover
        document.querySelector('.portal-container').addEventListener('mouseenter', function() {
            this.style.transform = 'perspective(1000px) rotateY(5deg) rotateX(5deg)';
        });

        document.querySelector('.portal-container').addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
        });

        // Neural Network Animation
        function createNeuralConnections() {
            const network = document.querySelector('.neural-network');
            
            setInterval(() => {
                const connection = document.createElement('div');
                connection.style.position = 'absolute';
                connection.style.width = '2px';
                connection.style.height = Math.random() * 200 + 'px';
                connection.style.background = 'linear-gradient(180deg, transparent, #00ffff, transparent)';
                connection.style.left = Math.random() * 100 + '%';
                connection.style.top = Math.random() * 100 + '%';
                connection.style.opacity = '0.3';
                connection.style.animation = 'fadeInOut 3s ease-in-out';
                
                network.appendChild(connection);
                
                setTimeout(() => {
                    connection.remove();
                }, 3000);
            }, 1000);
        }

        // Initialize Neural Network
        createNeuralConnections();

        // Add fade animation
        const fadeStyle = document.createElement('style');
        fadeStyle.textContent = `
            @keyframes fadeInOut {
                0%, 100% { opacity: 0; transform: scale(0.8); }
                50% { opacity: 0.6; transform: scale(1); }
            }
        `;
        document.head.appendChild(fadeStyle);

        // Cyberpunk Audio Effects (optional)
        function playHoverSound() {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.1);
                
                gainNode.gain.setValueAtTime(0.05, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            } catch (e) {
                // Audio not supported or blocked
            }
        }

        // Add sound effects to interactive elements
        document.querySelectorAll('input, button').forEach(element => {
            element.addEventListener('mouseenter', playHoverSound);
        });

        // Initialize cursor position on page load
        window.addEventListener('load', () => {
            mouseX = window.innerWidth / 2;
            mouseY = window.innerHeight / 2;
        });
    </script>
</body>
</html>