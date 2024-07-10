<?php
session_start();
include("classes/autoload.php");

$login = new Login();

// Verify session token using check_login method
$login->check_login($_SESSION['session_token']?? null);

$DB = new Database();
$token = $_SESSION['session_token'];
$query = "SELECT userid FROM sessions WHERE token = '$token' LIMIT 1";
$result = $DB->read($query);

if ($result) {
    $userid = $result[0]['userid'];
    $query = "SELECT * FROM users WHERE userid = '$userid' LIMIT 1";
    $userinfo = $DB->read($query);
    if ($userinfo) {
        $real_name = $userinfo[0]['name'];
        $userinfo = $userinfo[0];
    } else {
        $real_name = 'User';
    }
} else {
    $real_name = 'User';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist App</title>
    <?php include("downloadable.php"); ?>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background: #6200EA;
            padding: 20px;
            text-align: center;
            font-size: 48px;
            font-weight: bold;
            color: #fff;
            border-bottom: 2px solid #ffffff;
            flex: 0 0 80px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        main {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f9f9f9;
            display: flex;
            justify-content: center;
        }
        footer {
            background: #6200EA;
            color: #fff;
            text-align: center;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70px;
            position: relative;
        }
        .footer-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .footer-link {
            color: #fff;
            text-decoration: none;
            font-size: 24px;
            text-align: center;
            padding: 0px 20px; 
            z-index: 1;
            margin-left: 11px;
        }
        .footer-link:hover {
            background: #5b00d4;
        }
        .checklist {
            list-style: none;
            padding: 0;
            margin: 0 auto;
            width: 80%;
        }
        .checklist-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            font-size: 24px;
        }
        .checklist-item:last-child {
            border-bottom: none; 
        }
        .checklist-item input {
            margin-right: 20px;
            transform: scale(1.5);
        }
        .checklist-item label {
            font-size: 24px;
            flex: 1;
        }
        .add-button {
            font-size: 36px;
            color: #fff;
            background: #03DAC6;
            border-radius: 50%;
            padding: 30px 40px;
            text-decoration: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            position: absolute;
            left: 50%;
            bottom: 10px;
            transform: translateX(-50%);
            z-index: 2;
        }
        .submit-button {
            font-size: 36px;
            color: #fff;
            background: #03DAC6;
            border-radius: 50%;
            padding: 30px 40px;
            text-decoration: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            position: absolute;
            left: 50%;
            bottom: 10px;
            transform: translateX(-50%);
            z-index: 3; /* Ensure it's above the overlay */
        }
        .menu {
            position: fixed;
            top: 0;
            right: 0;
            width: 0;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            overflow-x: hidden;
            transition: 0.5s;
            z-index: 1;
            overflow-y: hidden; 
        }
        .menu-content {
            background-color: #6200EA;
            height: 100%;
            width: 60%;
            position: absolute;
            top: 0;
            right: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-top: 80px;
        }
        .menu a {
            padding: 8px 32px;
            text-decoration: none;
            font-size: 40px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }
        .menu a:hover {
            color: #03DAC6;
        }
        .menu.close-btn {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
            cursor: pointer; 
        }
        .menu.menu-item {
            margin-top: 20px;
            padding-bottom: 75vh;
        }
        .logout-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            bottom: 15vh; 
            right: 4vh;
            background-color: #03DAC6; 
            padding: 15px;
            border-radius: 10px; 
        }
        .logout-icon:hover {
            background-color: #02b8a5; 
        }
        .logout-icon a {
            color: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .logout-icon a img {
            width: 40px; 
            height: 40px; 
        }
        .hidden-button {
            display: none;
        }
        /* New styles for the purple overlay */
        .overlay {
            position: fixed;
            bottom: -100%;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #6200EA;
            transition: bottom 0.5s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 36px;
            z-index: 3;
            flex-direction: column; /* Allow for vertical alignment */
        }
        .overlay.show {
            bottom: 0;
        }
        .overlay .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #fff;
        }
        .overlay form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
        }
        .overlay textarea {
            font-size: 24px;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
            resize: none;
            line-height: 1.2em;
            height: auto;
            min-height: 50px;
        }
        #nameInput {
            min-height: 1.2em;
            overflow: hidden;
        }
        .overlay textarea:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <header>Hello, <?php echo htmlspecialchars($real_name);?></header>
    <main>
        <ul class="checklist" id="checklist">
            <?php include("get-items.php"); ?> <!-- Include without query string -->
        </ul>
    </main>
    <a href="#" class="add-button" id="addButton">+</a>
    <footer>
        <div class="footer-buttons">
            <a href="edit.php" class="footer-link">Edit</a>
            <a href="#" class="footer-link" onclick="openMenu()">Menu</a>
        </div>
    </footer>
    <div id="menu" class="menu" onclick="closeMenu(event)">
        <div class="menu-content" onclick="event.stopPropagation()">
            <div class="menu-item">
                <a href="#">Account</a>
                <a href="#">History</a>
            </div>
            <div class="logout-icon">
                <a href="logout.php"><img src="logout.png" alt="Logout"></a>
            </div>
        </div>
    </div>
    <div id="overlay" class="overlay">
        <form id="overlayForm">
            <input type="hidden" name="session_token" value="<?php echo $_SESSION['session_token']; ?>">
            <textarea id="nameInput" name="name" placeholder="Name" maxlength="30" required></textarea>
            <textarea id="descriptionInput" name="description" placeholder="Description" maxlength="100"></textarea>
            <a href="#" class="submit-button" id="submitButton">Cancel</a>
        </form>
    </div>
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div class="modal-name" id="modalName"></div>
            <div class="modal-description" id="modalDescription"></div>
        </div>
    </div>
<script>
    function openMenu() {
        document.getElementById("menu").style.width = "100%";
        document.getElementById("addButton").classList.add("hidden-button");
    }

    function closeMenu(event) {
        if (event.target === document.getElementById("menu") || event.target.classList.contains('close-btn')) {
            document.getElementById("menu").style.width = "0";
            document.getElementById("addButton").classList.remove("hidden-button");
        }
    }

    document.getElementById('addButton').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('overlay').classList.add('show');
        document.getElementById('addButton').classList.add('hidden-button'); // Hide the add button
        document.getElementById('submitButton').style.display = 'block'; // Ensure submit button is visible
        document.getElementById('submitButton').textContent = "Cancel"; // Default to Cancel when opening
    });

    document.getElementById('nameInput').addEventListener('input', function(event) {
        const submitButton = document.getElementById('submitButton');
        if (event.target.value.trim() === "") {
            submitButton.textContent = "Cancel";
        } else {
            submitButton.textContent = "Add";
        }
    });

    document.getElementById('submitButton').addEventListener('click', function(event) {
        event.preventDefault();
        const nameInput = document.getElementById('nameInput');
        if (nameInput.value.trim() === "") {
            closeOverlay();
        } else {
            submitForm();
        }
    });

    function submitForm() {
        const form = document.getElementById('overlayForm');
        const formData = new FormData(form);

        fetch('add.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                closeOverlay();
                // Clear the form fields
                document.getElementById('nameInput').value = '';
                document.getElementById('descriptionInput').value = '';
                setTimeout(fetchItems, 500); // Fetch new items 0.5s after adding
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function closeOverlay() {
        document.getElementById('overlay').classList.remove('show');
        document.getElementById('addButton').classList.remove('hidden-button'); // Show the add button again
        const submitButton = document.getElementById('submitButton');
        submitButton.textContent = "Cancel";
    }

    function fetchItems() {
        fetch('get-items.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('checklist').innerHTML = data;
                addCheckboxListeners(); // Add this line
            })
            .catch(error => console.error('Error fetching items:', error));
    }

    function toggleDescription(index) {
        var modal = document.getElementById('modal');
        var name = document.querySelectorAll('.checklist-item label')[index].textContent;
        var description = document.querySelectorAll('.description')[index].textContent;

        document.getElementById('modalName').textContent = name;
        document.getElementById('modalDescription').textContent = description;
        modal.style.display = 'flex';
    }

    function closeModal() {
        var modal = document.getElementById('modal');
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        var modal = document.getElementById('modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

    // Auto-expand textarea based on content
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        // Ensure the textarea starts with the correct height
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    });

    // Fetch items every 5 seconds
    // setInterval(fetchItems, 5000);

    window.onload = function() {
        closeModal(); // Ensure modal is hidden when the page loads
        addCheckboxListeners(); // Add this line
    };

    function addCheckboxListeners() {
        document.querySelectorAll('.checklist-item input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function(event) {
                const challengeId = this.closest('.checklist-item').getAttribute('data-challengeid');
                const status = this.checked ? 'CHECKED' : 'UNCHECKED';

                const formData = new FormData();
                formData.append('session_token', '<?php echo $_SESSION['session_token']; ?>');
                formData.append('challenge_id', challengeId);
                formData.append('status', status);

                fetch('save.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    window.onclick = function(event) {
        var modal = document.getElementById('modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>


</body>
</html>
