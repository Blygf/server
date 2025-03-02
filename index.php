<?php
session_start();

// Check if session token is set
if (isset($_SESSION['session_token'])) {
    header('Location: /checklist');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultimate Checklist App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f06, #4a90e2);
            color: #333;
            overflow-x: hidden;
        }
        header {
            color: white;
            padding: 60px 0;
            text-align: center;
            position: relative;
            background: linear-gradient(135deg, #6a0dad, #ff6f61);
            animation: slideInDown 1s;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .hero {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 50px 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            border-radius: 10px;
            animation: slideInLeft 1s;
        }
        .hero h1 {
            margin-top: 20px;
            font-size: 2.5em;
            animation: bounceInDown 1s;
        }
        .hero p {
            font-size: 1.2em;
            margin: 20px 0;
            animation: fadeIn 2s;
        }
        .signup {
            background-color: #ff6f61;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 40px 0;
            border-radius: 5px;
            animation: slideInRight 1s;
        }
        .signup a {
            color: white;
            text-decoration: none;
            background-color: #4CAF50;
            padding: 15px 30px;
            border-radius: 50px;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        .signup a:hover {
            background-color: #45a049;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            margin-top: 40px;
            animation: fadeInUp 1s;
        }
        .feature {
            background-color: rgba(255, 255, 255, 0.9);
            flex: 1;
            padding: 30px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s;
            animation: slideInUp 1s;
        }
        .feature h3 {
            margin-top: 10px;
            font-size: 1.5em;
            animation: bounceInUp 1.5s;
        }
        .feature p {
            font-size: 1.1em;
            animation: fadeIn 1.5s;
        }
        .feature:hover {
            transform: translateY(-10px);
            background-color: #ffcccb;
        }
        .testimonial {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 50px 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 40px 0;
            border-radius: 10px;
            animation: slideInLeft 1s;
        }
        .testimonial h3 {
            margin-top: 10px;
            font-size: 1.5em;
            animation: bounceInUp 1.5s;
        }
        .testimonial p {
            font-size: 1.1em;
            color: #ff6f61;
            animation: fadeIn 1.5s;
        }
        .testimonial img {
            border-radius: 50%;
            margin-bottom: 20px;
            animation: bounceInDown 1s;
        }
        .benefit {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 50px 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 40px 0;
            border-radius: 10px;
            animation: slideInRight 1s;
        }
        .benefit h3 {
            margin-top: 10px;
            font-size: 1.5em;
            animation: bounceInUp 1.5s;
        }
        .benefit p {
            font-size: 1.1em;
            animation: fadeIn 1.5s;
        }
        .center-image {
            text-align: center;
            margin: 40px 0;
            animation: fadeIn 2s;
        }
        .center-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #6a0dad;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
            animation: slideInUp 1s;
        }
        @media (max-width: 768px) {
            .features {
                flex-direction: column;
            }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes bounceInDown {
            from {
                opacity: 0;
                transform: translateY(-2000px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes bounceInUp {
            from {
                opacity: 0;
                transform: translateY(2000px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <header>
        <h1 class="animate__animated animate__bounceInDown">Ultimate Checklist App</h1>
        <p>Your Productivity Partner</p>
    </header>
    <div class="container">
        <div class="hero animate__animated animate__fadeInUp">
            <h1>Welcome to the Ultimate Checklist App</h1>
            <p>Stay organized and productive with our simple and effective checklist app. Manage your tasks effortlessly and never miss a deadline again.</p>
            <div class="signup animate__animated animate__fadeInUp">
                <h2>Sign up today and boost your productivity!</h2>
                <a href="/checklist">Get Started</a>
            </div>
        </div>
        <div class="center-image animate__animated animate__fadeIn">
            <img src="picture.png" alt="Checklist App">
        </div>
        <div class="features">
            <div class="feature animate__animated animate__slideInUp">
                <h3>Easy to Use</h3>
                <p>Our app is designed with simplicity in mind. Create, edit, and manage your tasks with ease.</p>
            </div>
            <div class="feature animate__animated animate__slideInUp">
                <h3>Cross-Platform</h3>
                <p>Whether you're on your phone, tablet, or desktop, our app works seamlessly across all your devices.</p>
            </div>
        </div>
        <div class="testimonial animate__animated animate__slideInLeft">
            <img src="https://via.placeholder.com/100" alt="User Testimonial" class="animate__animated animate__bounceInDown">
            <h3>What Our Users Say</h3>
            <p>"The Ultimate Checklist App has transformed the way I organize my tasks. It's user-friendly and has helped me stay on top of my work effortlessly." - Jane Doe</p>
        </div>
        <div class="benefit animate__animated animate__slideInRight">
            <h3>Why Choose Us?</h3>
            <p>Our app is not just another task manager. It's a comprehensive tool designed to boost your productivity and simplify your life. With advanced features and a user-friendly interface, you'll wonder how you ever managed without it.</p>
        </div>
        <div class="signup animate__animated animate__fadeInUp">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of users who have boosted their productivity with the Ultimate Checklist App.</p>
            <a href="/checklist">Sign Up Now</a>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Ultimate Checklist App. All rights reserved.</p>
    </footer>
</body>
</html>
