<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Homepage</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(45deg, #0a0a0a, #1a1a2e, #16213e, #0f3460);
            background-size: 400% 400%;
            animation: cosmicFlow 20s ease-in-out infinite;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        @keyframes cosmicFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Celestial particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(2px 2px at 20px 30px, #fff, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255,255,255,0.6), transparent),
                radial-gradient(1px 1px at 130px 80px, rgba(255,255,255,0.4), transparent),
                radial-gradient(2px 2px at 160px 30px, rgba(255,255,255,0.7), transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            animation: stardust 50s linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes stardust {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-100vh) rotate(360deg); }
        }

        /* Aurora effect */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at top, rgba(0,255,255,0.1) 0%, transparent 70%),
                radial-gradient(ellipse at bottom right, rgba(255,0,255,0.1) 0%, transparent 70%),
                radial-gradient(ellipse at bottom left, rgba(255,255,0,0.1) 0%, transparent 70%);
            animation: aurora 15s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 2;
        }

        @keyframes aurora {
            0% { opacity: 0.3; transform: scale(1) rotate(0deg); }
            100% { opacity: 0.8; transform: scale(1.1) rotate(2deg); }
        }

        form {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .company {
            font-size: 4rem;
            font-weight: 900;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4, #ffd93d);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 2rem;
            animation: textShimmer 3s ease-in-out infinite, levitate 6s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(255,255,255,0.5);
            letter-spacing: 0.5rem;
            position: relative;
        }

        .company::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: scanline 2s linear infinite;
        }

        @keyframes textShimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes levitate {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes scanline {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        h1 {
            font-size: 2.5rem;
            color: #fff;
            text-align: center;
            margin: 3rem 0;
            text-shadow: 0 0 20px rgba(255,255,255,0.8);
            animation: pulseglow 4s ease-in-out infinite;
            letter-spacing: 0.3rem;
        }

        @keyframes pulseglow {
            0%, 100% { text-shadow: 0 0 20px rgba(255,255,255,0.8); }
            50% { text-shadow: 0 0 40px rgba(255,255,255,1), 0 0 60px rgba(0,255,255,0.8); }
        }

        .nav-links {
            display: flex;
            gap: 3rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .entry, .dashboard, .record {
            position: relative;
            padding: 2rem;
            border-radius: 20px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
            min-width: 200px;
            animation: float 8s ease-in-out infinite;
            overflow: hidden;
        }

        .entry {
            animation-delay: 0s;
            background: linear-gradient(135deg, rgba(255,107,107,0.1), rgba(255,107,107,0.05));
            box-shadow: 0 20px 40px rgba(255,107,107,0.3);
        }

        .dashboard {
            animation-delay: 0.5s;
            background: linear-gradient(135deg, rgba(78,205,196,0.1), rgba(78,205,196,0.05));
            box-shadow: 0 20px 40px rgba(78,205,196,0.3);
        }

        .record {
            animation-delay: 1s;
            background: linear-gradient(135deg, rgba(255,217,61,0.1), rgba(255,217,61,0.05));
            box-shadow: 0 20px 40px rgba(255,217,61,0.3);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotateZ(0deg); }
            33% { transform: translateY(-20px) rotateZ(1deg); }
            66% { transform: translateY(-10px) rotateZ(-1deg); }
        }

        .entry::before, .dashboard::before, .record::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        button {
            margin-top: 1rem;
            padding: 1rem 2rem;
            border: none;
            border-radius: 50px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #764ba2, #667eea);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        button:hover::before {
            opacity: 1;
        }

        button:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 20px 40px rgba(102,126,234,0.4);
        }

        button:active {
            transform: translateY(-2px) scale(0.98);
        }

        /* Black Hole Effect */
        .black-hole {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            transform: translate(-50%, -50%);
            z-index: 2;
            pointer-events: none;
        }

        .black-hole-core {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80px;
            height: 80px;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, 
                rgba(0,0,0,1) 0%, 
                rgba(0,0,0,0.9) 20%, 
                rgba(50,0,100,0.3) 50%, 
                transparent 100%);
            border-radius: 50%;
            animation: blackHolePulse 4s ease-in-out infinite;
            box-shadow: 
                0 0 100px rgba(0,0,0,0.8),
                inset 0 0 50px rgba(100,0,200,0.3);
        }

        .accretion-disk {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            pointer-events: none;
        }

        .disk-1 {
            width: 200px;
            height: 200px;
            background: conic-gradient(
                from 0deg,
                transparent 0deg,
                rgba(255,100,0,0.4) 90deg,
                rgba(255,200,0,0.6) 180deg,
                rgba(255,0,100,0.4) 270deg,
                transparent 360deg
            );
            animation: accretionSpin 8s linear infinite;
        }

        .disk-2 {
            width: 160px;
            height: 160px;
            background: conic-gradient(
                from 180deg,
                transparent 0deg,
                rgba(100,0,255,0.3) 90deg,
                rgba(0,255,255,0.5) 180deg,
                rgba(255,0,200,0.3) 270deg,
                transparent 360deg
            );
            animation: accretionSpin 6s linear infinite reverse;
        }

        .disk-3 {
            width: 120px;
            height: 120px;
            background: conic-gradient(
                from 90deg,
                transparent 0deg,
                rgba(255,255,0,0.4) 90deg,
                rgba(0,255,100,0.6) 180deg,
                rgba(255,0,0,0.4) 270deg,
                transparent 360deg
            );
            animation: accretionSpin 4s linear infinite;
        }

        @keyframes blackHolePulse {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1);
                box-shadow: 
                    0 0 100px rgba(0,0,0,0.8),
                    inset 0 0 50px rgba(100,0,200,0.3);
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.1);
                box-shadow: 
                    0 0 150px rgba(0,0,0,1),
                    inset 0 0 80px rgba(100,0,200,0.5);
            }
        }

        @keyframes accretionSpin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        /* Gravitational Lensing Effect */
        .lensing-ring {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.1);
            pointer-events: none;
            z-index: 2;
        }

        .lensing-1 {
            width: 350px;
            height: 350px;
            animation: lensingPulse 6s ease-in-out infinite;
        }

        .lensing-2 {
            width: 420px;
            height: 420px;
            animation: lensingPulse 8s ease-in-out infinite reverse;
        }

        .lensing-3 {
            width: 500px;
            height: 500px;
            animation: lensingPulse 10s ease-in-out infinite;
        }

        @keyframes lensingPulse {
            0%, 100% { 
                opacity: 0.1;
                transform: translate(-50%, -50%) scale(1);
            }
            50% { 
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(1.05);
            }
        }

        /* Magical orbs with gravitational attraction */
        .orb {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 3;
        }

        .orb1 {
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255,107,107,0.3), rgba(255,107,107,0.1));
            top: 20%;
            left: 10%;
            animation: gravitationalOrbit1 25s linear infinite;
        }

        .orb2 {
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(78,205,196,0.3), rgba(78,205,196,0.1));
            top: 60%;
            right: 10%;
            animation: gravitationalOrbit2 20s linear infinite;
        }

        .orb3 {
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, rgba(255,217,61,0.3), rgba(255,217,61,0.1));
            bottom: 20%;
            left: 50%;
            animation: gravitationalOrbit3 15s linear infinite;
        }

        @keyframes gravitationalOrbit1 {
            0% { transform: rotate(0deg) translateX(250px) rotate(0deg) scale(1); }
            25% { transform: rotate(90deg) translateX(200px) rotate(-90deg) scale(0.8); }
            50% { transform: rotate(180deg) translateX(250px) rotate(-180deg) scale(1); }
            75% { transform: rotate(270deg) translateX(300px) rotate(-270deg) scale(1.2); }
            100% { transform: rotate(360deg) translateX(250px) rotate(-360deg) scale(1); }
        }

        @keyframes gravitationalOrbit2 {
            0% { transform: rotate(0deg) translateX(-280px) rotate(0deg) scale(1); }
            25% { transform: rotate(-90deg) translateX(-220px) rotate(90deg) scale(0.7); }
            50% { transform: rotate(-180deg) translateX(-280px) rotate(180deg) scale(1); }
            75% { transform: rotate(-270deg) translateX(-340px) rotate(270deg) scale(1.3); }
            100% { transform: rotate(-360deg) translateX(-280px) rotate(360deg) scale(1); }
        }

        @keyframes gravitationalOrbit3 {
            0% { transform: rotate(0deg) translateY(180px) rotate(0deg) scale(1); }
            25% { transform: rotate(90deg) translateY(150px) rotate(-90deg) scale(0.6); }
            50% { transform: rotate(180deg) translateY(180px) rotate(-180deg) scale(1); }
            75% { transform: rotate(270deg) translateY(220px) rotate(-270deg) scale(1.4); }
            100% { transform: rotate(360deg) translateY(180px) rotate(-360deg) scale(1); }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .company {
                font-size: 2.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 2rem;
            }
            
            .entry, .dashboard, .record {
                min-width: 250px;
            }
        }
    </style>
</head>
<body>

<?php
require "dbconnect.php";

if(mysqli_connect_errno()){
    echo "Failed to connect: " . mysqli_connect_error();
    exit;
}

$name = "";
$designation = "";

$query = "SELECT name, designation FROM user_data LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $designation = $row['designation'];
}
?>

<div class="black-hole">
    <div class="black-hole-core"></div>
    <div class="accretion-disk disk-1"></div>
    <div class="accretion-disk disk-2"></div>
    <div class="accretion-disk disk-3"></div>
</div>

<div class="lensing-ring lensing-1"></div>
<div class="lensing-ring lensing-2"></div>
<div class="lensing-ring lensing-3"></div>

<div class="orb orb1"></div>
<div class="orb orb2"></div>
<div class="orb orb3"></div>

<form>
    <div class="company">
        ANALYTICS SERVICES
    </div>

    <div class="u_name">
    </div>

    <h1>WELCOME TO ANALYSIS PAGE</h1>

    <div class="nav-links">
        <div class="entry">Entry
            <button type="button" onclick="window.location.href='login.php'">Click</button>
        </div>
        <div class="dashboard">Dashboard
            <button type="button" onclick="window.location.href='https://app.powerbi.com/groups/me/reports/e726bb42-3e89-486a-ae77-6c2f4ce9539d/75ebbe25d06e765b04e4?experience=power-bi'">Click</button>
        </div>
        <div class="record">Record
            <button type="button" onclick="window.location.href='record.php'">Click</button>
        </div>
    </div>
</form>

</body>
</html>