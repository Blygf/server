<?php
session_start();
include("classes/autoload.php");

//checks if user is logged in and redirects them
$login = new Login();
if (isset($_SESSION['session_token'])) {
    header("Location: main.php");
    die;
}

//collecting results from form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['email'])) {
        unset($_SESSION['email']);
    }

    if (isset($_SESSION['password'])) {
        unset($_SESSION['password']);
    }

    $login = new Login();
    $result = $login->evaluate($_POST);

    if ($result != "") {
        echo "<script type='text/javascript'>alert('The following errors occurred:\\n" . str_replace("'", "\'", $result) . "');</script>";
    } else {
        header("Location: main.php");
        die;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist App Login</title>
    <style>
        @keyframes typing {
            0%, 10% { width: 0; }
            10%, 90% { width: 20ch; }
            100% { width: 0; }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #bb86fc; }
        }

        body {
            font-family: 'Roboto Mono', monospace;
            background: linear-gradient(135deg, #1f1c2c 0%, #928dab 100%);
            color: #e0e0e0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .title {
            font-size: 3rem;
            color: #bb86fc;
            white-space: nowrap;
            overflow: hidden;
            border-right: 0.15em solid #bb86fc;
            width: 20ch;
            animation: typing 6.5s steps(20, end) infinite, blink-caret 0.75s step-end infinite;
            margin-bottom: 60px; /* Increased space between title and box */
            margin-top: -100px;
        }

        .login-container {
            background-color: #1e1e1e;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            width: 400px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 1s ease-in-out, slideIn 1s ease-in-out;
            margin-top: 20px; /* Additional space above the box */
        }

        .login-container:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #bb86fc;
            font-size: 1.2rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            box-sizing: border-box;
            background-color: #333;
            border: 1px solid #bb86fc;
            border-radius: 5px;
            color: #e0e0e0;
            transition: border-color 0.3s ease;
            font-size: 1.1rem;
        }

        .form-group input:focus {
            border-color: #6200ea;
            outline: none;
        }

        button {
            background: linear-gradient(135deg, #bb86fc, #6200ea);
            color: white;
            border: none;
            padding: 20px 50px; /* Increased width */
            cursor: pointer;
            border-radius: 30px;
            transition: background 0.3s ease, transform 0.3s ease;
            font-size: 1.2rem;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background: linear-gradient(135deg, #9c27b0, #3700b3);
            transform: scale(1.1);
        }

        button:active {
            transform: scale(1.05);
        }

        .terms {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #bb86fc;
        }

        .terms a {
            color: #6200ea;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        footer {
            width: 100%;
            position: fixed;
            bottom: 0;
            background-color: #1e1e1e;
            color: #bb86fc;
            text-align: center;
            padding: 15px 0;
            font-size: 1rem;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 600px) {
            .title {
                font-size: 2.5rem;
                width: 20ch;
                animation: typing 6.5s steps(20, end) infinite, blink-caret 0.75s step-end infinite;
                margin-top: -120px;
            }

            .login-container {
                width: 320px;
                padding: 30px;
                margin-top: 30px; /* Additional space above the box */
            }

            .form-group label {
                font-size: 1.1rem;
            }

            .form-group input {
                padding: 12px;
                font-size: 1rem;
            }

            button {
                padding: 15px 40px; /* Adjusted width for mobile */
                font-size: 1.1rem;
            }

            .terms {
                font-size: 0.8rem;
            }

            footer {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="title">Checklist App Login</div>
    <div class="login-container">
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="terms">
                By logging in I accept the <a href="termsofservice.txt">Terms of Service</a>.
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    <footer>
        &copy; Hugo Industries
    </footer>
</body>
</html>
