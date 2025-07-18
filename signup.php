<?php
require 'dbconnect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $password    = trim($_POST['password'] ?? '');
    $contact     = trim($_POST['contact'] ?? '');
    $company     = trim($_POST['company'] ?? '');
    $designation = trim($_POST['designation'] ?? '');

    // Validation rules
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors[] = "Name must contain only letters and spaces.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/\.com$/', $email)) {
        $errors[] = "Enter a valid email ending with .com";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (!preg_match("/^[0-9]{10}$/", $contact)) {
        $errors[] = "Contact must be exactly 10 digits.";
    }

    if (strlen($company) < 2) {
        $errors[] = "Company name must have at least 2 characters.";
    }

    if (strlen($designation) < 2) {
        $errors[] = "Designation must have at least 2 characters.";
    }

    // Check for duplicate email only if no errors so far
    if (empty($errors)) {
        $checkStmt = $conn->prepare("SELECT id FROM user_data WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();
        if ($checkStmt->num_rows > 0) {
            $errors[] = "Email already exists.";
        }
        $checkStmt->close();
    }

    // Insert into database only if no errors after checks
    if (empty($errors)) {
        $sql = "INSERT INTO user_data (name, email, password, contact, company, designation) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            $errors[] = "Prepare failed: " . mysqli_error($conn);
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            mysqli_stmt_bind_param(
                $stmt,
                "ssssss",
                $name,
                $email,
                $hashed_password,
                $contact,
                $company,
                $designation
            );

            if (mysqli_stmt_execute($stmt)) {
                // ✅ Redirect only after successful submission
                header("Location: login_home.php?signup=success");
                exit();
            } else {
                $errors[] = "Execution failed: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Enhanced Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(20, 20, 20, 0.8);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ff006e, #8338ec, #06ffa5);
            border-radius: 10px;
            box-shadow: 
                inset 0 0 6px rgba(0, 0, 0, 0.6),
                0 0 20px rgba(255, 0, 150, 0.5);
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #ff5ec1, #9b5cf6, #39e5ac);
            box-shadow: 
                inset 0 0 6px rgba(0, 0, 0, 0.6),
                0 0 30px rgba(255, 0, 150, 0.8);
            transform: scale(1.1);
        }

        ::-webkit-scrollbar-thumb:active {
            background: linear-gradient(180deg, #ff006e, #8338ec, #06ffa5);
            box-shadow: 
                inset 0 0 6px rgba(0, 0, 0, 0.8),
                0 0 40px rgba(255, 0, 150, 1);
        }

        ::-webkit-scrollbar-corner {
            background: rgba(20, 20, 20, 0.8);
        }

        /* Firefox support */
        * {
            scrollbar-width: thin;
            scrollbar-color: #8338ec rgba(20, 20, 20, 0.8);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Animated background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255, 0, 150, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(0, 255, 255, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 100, 0, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 90% 10%, rgba(150, 0, 255, 0.3) 0%, transparent 50%);
            animation: morphBackground 8s ease-in-out infinite;
            z-index: -3;
        }

        @keyframes morphBackground {
            0%, 100% { 
                transform: scale(1) rotate(0deg);
                filter: hue-rotate(0deg);
            }
            25% { 
                transform: scale(1.1) rotate(90deg);
                filter: hue-rotate(90deg);
            }
            50% { 
                transform: scale(1.2) rotate(180deg);
                filter: hue-rotate(180deg);
            }
            75% { 
                transform: scale(1.1) rotate(270deg);
                filter: hue-rotate(270deg);
            }
        }

        /* Geometric shapes */
        .geometric-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -2;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ff006e, #8338ec);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 70%;
            right: 10%;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #06ffa5, #00d4ff);
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            bottom: 20%;
            left: 20%;
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #ffbe0b, #fb5607);
            border-radius: 20px;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            top: 20%;
            right: 30%;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, #ff006e, #3a86ff);
            transform: rotate(45deg);
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg);
                opacity: 0.1;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.3;
            }
        }

        /* Matrix rain effect */
        .matrix-rain {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }

        .matrix-column {
            position: absolute;
            top: -100%;
            color: rgba(0, 255, 255, 0.3);
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.2;
            animation: matrixFall linear infinite;
        }

        @keyframes matrixFall {
            to { transform: translateY(100vh); }
        }

        /* Main form container */
        .form-container {
            background: rgba(20, 20, 20, 0.9);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px;
            width: 100%;
            max-width: 520px;
            position: relative;
            z-index: 10;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.4),
                0 0 80px rgba(255, 0, 150, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: containerPulse 3s ease-in-out infinite alternate;
            margin: 40px auto;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes containerPulse {
            0% { 
                box-shadow: 
                    0 20px 40px rgba(0, 0, 0, 0.4),
                    0 0 80px rgba(255, 0, 150, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }
            100% { 
                box-shadow: 
                    0 25px 50px rgba(0, 0, 0, 0.5),
                    0 0 120px rgba(0, 255, 255, 0.2),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
            }
        }

        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 0, 150, 0.1) 0%, 
                transparent 50%, 
                rgba(0, 255, 255, 0.1) 100%);
            border-radius: 24px;
            z-index: -1;
        }

        /* Form container scrollbar */
        .form-container::-webkit-scrollbar {
            width: 8px;
        }

        .form-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            margin: 10px 0;
        }

        .form-container::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ff006e, #8338ec);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 0, 150, 0.5);
        }

        .form-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #ff5ec1, #9b5cf6);
            box-shadow: 0 0 15px rgba(255, 0, 150, 0.8);
        }

        /* Animated title */
        h2 {
            background: linear-gradient(45deg, #ff006e, #8338ec, #06ffa5);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 40px;
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            animation: gradientShift 3s ease-in-out infinite;
            position: relative;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, #ff006e, #8338ec, #06ffa5);
            border-radius: 2px;
            animation: lineExpand 2s ease-in-out infinite alternate;
        }

        @keyframes lineExpand {
            0% { width: 80px; }
            100% { width: 160px; }
        }

        /* Form groups with enhanced animations */
        .form-group {
            margin-bottom: 30px;
            position: relative;
        }

        .form-group::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 0, 150, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 16px;
            z-index: -1;
        }

        .form-group:hover::before {
            opacity: 1;
        }

        .form-group label {
            display: block;
            color: #ffffff;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .form-group input {
            width: 100%;
            padding: 18px 24px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ff006e;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 0 30px rgba(255, 0, 150, 0.3),
                0 0 60px rgba(255, 0, 150, 0.1);
            transform: translateY(-2px) scale(1.02);
        }

        .form-group input:focus::placeholder {
            color: rgba(255, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        .form-group input:focus + label {
            color: #ff006e;
            text-shadow: 0 0 20px rgba(255, 0, 150, 0.8);
            transform: translateY(-2px) scale(1.05);
        }

        /* Animated submit button */
        .submit-btn {
            width: 100%;
            padding: 20px;
            background: linear-gradient(45deg, #ff006e, #8338ec, #06ffa5);
            background-size: 300% 300%;
            border: none;
            border-radius: 16px;
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: buttonGradient 3s ease-in-out infinite;
        }

        @keyframes buttonGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.8s ease;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 
                0 15px 40px rgba(255, 0, 150, 0.4),
                0 0 80px rgba(255, 0, 150, 0.3);
        }

        .submit-btn:active {
            transform: translateY(-2px) scale(1.02);
        }

        /* Enhanced error styling */
        .error-container {
            background: rgba(255, 0, 0, 0.1);
            border: 2px solid rgba(255, 0, 150, 0.3);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
            animation: errorPulse 1s ease-in-out;
        }

        @keyframes errorPulse {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }

        .error-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 0, 150, 0.1), transparent);
            animation: errorSweep 2s linear infinite;
        }

        @keyframes errorSweep {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-container ul {
            list-style: none;
            margin: 0;
            padding: 0;
            position: relative;
            z-index: 1;
        }

        .error-container li {
            color: #ff6b9d;
            margin-bottom: 12px;
            padding-left: 30px;
            position: relative;
            font-weight: 500;
            animation: errorSlide 0.5s ease-out;
        }

        @keyframes errorSlide {
            0% { transform: translateX(-20px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }

        .error-container li::before {
            content: '⚡';
            position: absolute;
            left: 0;
            color: #ff006e;
            font-size: 1.2em;
            animation: errorIcon 1s ease-in-out infinite;
        }

        @keyframes errorIcon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Floating orbs */
        .floating-orbs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 0, 150, 0.3), rgba(0, 255, 255, 0.1));
            animation: orbFloat 8s ease-in-out infinite;
        }

        @keyframes orbFloat {
            0%, 100% { 
                transform: translateY(0px) translateX(0px) scale(1);
                opacity: 0.3;
            }
            25% { 
                transform: translateY(-20px) translateX(10px) scale(1.1);
                opacity: 0.6;
            }
            50% { 
                transform: translateY(-40px) translateX(-10px) scale(1.2);
                opacity: 0.8;
            }
            75% { 
                transform: translateY(-20px) translateX(-20px) scale(1.1);
                opacity: 0.6;
            }
        }

        /* Ripple effect */
        .ripple {
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.8s linear;
            z-index: 1;
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Success message */
        .success-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(45deg, #06ffa5, #00d4ff);
            color: white;
            padding: 20px 40px;
            border-radius: 16px;
            font-size: 18px;
            font-weight: 600;
            box-shadow: 0 20px 40px rgba(0, 255, 255, 0.3);
            animation: successPop 0.5s ease-out;
            z-index: 1000;
        }

        @keyframes successPop {
            0% { transform: translate(-50%, -50%) scale(0.5); opacity: 0; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .form-container {
                padding: 30px 25px;
                margin: 10px;
                max-height: 95vh;
            }
            
            h2 {
                font-size: 2.2rem;
                letter-spacing: 2px;
            }
            
            .form-group input {
                padding: 16px 20px;
            }
            
            .submit-btn {
                padding: 18px;
                font-size: 16px;
            }
        }

        /* Scroll indicator */
        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 1000;
        }

        .scroll-progress {
            height: 100%;
            background: linear-gradient(90deg, #ff006e, #8338ec, #06ffa5);
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Scroll indicator -->
    <div class="scroll-indicator">
        <div class="scroll-progress" id="scrollProgress"></div>
    </div>

    <!-- Animated background -->
    <div class="bg-animation"></div>
    
    <!-- Geometric shapes -->
    <div class="geometric-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <!-- Matrix rain effect -->
    <div class="matrix-rain" id="matrixRain"></div>
    
    <!-- Floating orbs -->
    <div class="floating-orbs" id="floatingOrbs"></div>

    <div class="form-container">
        <h2>Sign Up</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <div class="form-group">
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                <label for="name">Full Name</label>
            </div>

            <div class="form-group">
                <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                <label for="email">Email Address</label>
            </div>

            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Create a secure password" required>
                <label for="password">Password</label>
            </div>

            <div class="form-group">
                <input type="text" id="contact" name="contact" placeholder="Enter your contact number" required>
                <label for="contact">Contact Number</label>
            </div>

            <div class="form-group">
                <input type="text" id="company" name="company" placeholder="Enter your company name" required>
                <label for="company">Company</label>
            </div>

            <div class="form-group">
                <input type="text" id="designation" name="designation" placeholder="Enter your job title" required>
                <label for="designation">Job Title</label>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>
    </div>

    <script>
        document.addEventListener("visibilitychange", function () {
            if (document.visibilityState === "visible") {
                location.reload();
            }
        });

        // Scroll progress indicator
        function updateScrollProgress() {
            const scrollProgress = document.getElementById('scrollProgress');
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = (scrollTop / scrollHeight) * 100;
            scrollProgress.style.width = progress + '%';
        }

        window.addEventListener('scroll', updateScrollProgress);
        updateScrollProgress(); // Initial call

        // Matrix rain effect
        function createMatrixRain() {
            const matrixContainer = document.getElementById('matrixRain');
            const characters = '01アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン';
            
            for (let i = 0; i < 50; i++) {
                const column = document.createElement('div');
                column.className = 'matrix-column';
                column.style.left = Math.random() * 100 + '%';
                column.style.animationDuration = (Math.random() * 3 + 2) + 's';
                column.style.animationDelay = Math.random() * 5 + 's';
                
                let columnText = '';
                for (let j = 0; j < 20; j++) {
                    columnText += characters.charAt(Math.floor(Math.random() * characters.length)) + '<br>';
                }
                column.innerHTML = columnText;
                
                matrixContainer.appendChild(column);
            }
        }

        // Create floating orbs
        function createFloatingOrbs() {
            const orbsContainer = document.getElementById('floatingOrbs');
            
            for (let i = 0; i < 8; i++) {
                const orb = document.createElement('div');
                orb.className = 'orb';
                
                const size = Math.random() * 60 + 20;
                orb.style.width = size + 'px';
                orb.style.height = size + 'px';
                orb.style.left = Math.random() * 100 + '%';
                orb.style.top = Math.random() * 100 + '%';
                orb.style.animationDelay = Math.random() * 8 + 's';
                orb.style.animationDuration = (Math.random() * 4 + 6) + 's';
                
                orbsContainer.appendChild(orb);
            }
        }

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            createMatrixRain();
            createFloatingOrbs();
            
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                // Add typing effect
                input.addEventListener('input', function() {
                    this.style.boxShadow = '0 0 20px rgba(255, 0, 150, 0.5)';
                    setTimeout(() => {
                        this.style.boxShadow = '';
                    }, 200);
                });

                             // Add focus wave effect
                input.addEventListener('focus', function() {
                    const parent = this.parentElement;
                    parent.style.transform = 'scale(1.02)';
                    parent.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                    parent.style.boxShadow = '0 0 20px rgba(255, 0, 150, 0.3)';
                });

                input.addEventListener('blur', function() {
                    const parent = this.parentElement;
                    parent.style.transform = 'scale(1)';
                    parent.style.boxShadow = 'none';
                });
            });
        });
    </script>
</body>
</html>
