<?php
session_start();
include("classes/autoload.php");

$login = new Login();

// Verify session token using check_login method
$login->check_login($_SESSION['session_token'] ?? null);

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
    <title>Edit Checklist</title>
    <style>
        body,
        html {
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
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .large-button {
            font-size: 36px;
            color: #fff;
            background: #03DAC6;
            border-radius: 50%;
            padding: 20px 30px;
            text-decoration: none;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            margin: 10px;
            cursor: pointer;
        }

        .large-button:hover {
            background: #02b8a5;
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

        .more-icon {
            width: 30px;
            height: 30px;
            cursor: pointer;
            margin-left: 10px;
        }

        .hidden-button {
            display: none;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            display: none;
        }

        .overlay-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .overlay textarea,
        .overlay input[type="text"] {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        .overlay textarea {
            height: auto;
            resize: none;
        }

        .overlay button {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            background: #6200EA;
            color: #fff;
            cursor: pointer;
        }

        .overlay button:hover {
            background: #5300d2;
        }
    </style>
</head>

<body>
    <header>Edit Checklist</header>
    <main>
        <ul class="checklist" id="checklist">
            <?php include('get-edit-items.php'); ?>
        </ul>
    </main>
    <footer>
        <div class="footer-buttons">
            <button id="deleteButton" class="large-button hidden-button" onclick="handleDelete()">Delete</button>
            <button id="exitButton" class="large-button" onclick="handleExit()">Exit</button>
        </div>
    </footer>

    <div id="overlay" class="overlay" onclick="closeOverlay()">
        <div class="overlay-content" onclick="event.stopPropagation()">
            <input type="text" id="overlayName" placeholder="Name" maxlength="30">
            <textarea id="overlayDescription" rows="4" placeholder="Description" maxlength="100"></textarea>
            <input type="hidden" id="overlayChallengeId">
            <button id="overlayButton" onclick="saveOrOk()">OK</button>
        </div>
    </div>

    <script>
        let originalName = '';
        let originalDescription = '';
        const sessionToken = "<?php echo $_SESSION['session_token']; ?>";

        function updateChecklistEventListeners() {
            document.querySelectorAll('.checklist-item input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateFooterButtons();
                });
            });

            document.querySelectorAll('.more-icon').forEach(icon => {
                icon.addEventListener('click', function () {
                    const item = this.parentElement;
                    const name = item.querySelector('label').innerText;
                    const description = item.querySelector('input').dataset.description;
                    const challengeid = item.dataset.challengeid;
                    showOverlay(0, name, description, challengeid);
                });
            });
        }

        function updateFooterButtons() {
            const deleteButton = document.getElementById('deleteButton');
            const exitButton = document.getElementById('exitButton');
            const isChecked = Array.from(document.querySelectorAll('.checklist-item input[type="checkbox"]')).some(cb => cb.checked);

            if (isChecked) {
                deleteButton.classList.remove('hidden-button');
                exitButton.classList.add('hidden-button');
            } else {
                deleteButton.classList.add('hidden-button');
                exitButton.classList.remove('hidden-button');
            }
        }

        function showOverlay(index, name, description, challengeid) {
            document.getElementById('overlay').style.display = 'flex';
            document.getElementById('overlayName').value = name;
            document.getElementById('overlayDescription').value = description;
            document.getElementById('overlayChallengeId').value = challengeid;
            originalName = name;
            originalDescription = description;
            updateOverlayButton();
        }

        function closeOverlay() {
            document.getElementById('overlay').style.display = 'none';
        }

        function saveOrOk() {
            const name = document.getElementById('overlayName').value;
            const description = document.getElementById('overlayDescription').value;
            const challengeId = document.getElementById('overlayChallengeId').value;
            const method = name !== originalName || description !== originalDescription ? 'EDIT' : '';

            if (method) {
                if (confirm("Do you really want to change these items (save)?")) {
                    fetch('change.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            method: method,
                            token: sessionToken,
                            name: name,
                            description: description,
                            challengeId: challengeId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'error') {
                            alert(`${data.status.toUpperCase()}: ${data.message}`);
                        }
                    })
                    .catch(error => {
                        alert('ERROR: ' + error.message);
                    })
                    .finally(() => {
                        setTimeout(reloadChecklist, 500);
                    });
                }
            }
            closeOverlay();
        }

        function updateOverlayButton() {
            const nameInput = document.getElementById('overlayName');
            const descriptionInput = document.getElementById('overlayDescription');
            const overlayButton = document.getElementById('overlayButton');

            if (nameInput.value !== originalName || descriptionInput.value !== originalDescription) {
                overlayButton.textContent = "Save";
            } else {
                overlayButton.textContent = "OK";
            }
        }

        function handleExit() {
            window.location.href = 'main.php';
        }

        function handleDelete() {
            if (confirm("Do you really want to permanently delete these items?")) {
                const checkedItems = document.querySelectorAll('.checklist-item input[type="checkbox"]:checked');
                const itemsToDelete = Array.from(checkedItems).map(item => item.parentElement.dataset.challengeid);
                
                fetch('change.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        method: 'DELETE',
                        token: sessionToken,
                        challengeId: JSON.stringify(itemsToDelete) // Ensure this is a JSON string
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        alert(`${data.status.toUpperCase()}: ${data.message}`);
                    }
                })
                .catch(error => {
                    alert('ERROR: ' + error.message);
                })
                .finally(() => {
                    setTimeout(reloadChecklist, 500);
                });
            }
        }


        function reloadChecklist() {
            fetch('get-edit-items.php')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('checklist').innerHTML = html;
                    updateChecklistEventListeners();
                    updateFooterButtons();
                })
                .catch(error => {
                    console.error('Error reloading checklist:', error);
                });
        }

        updateChecklistEventListeners();
        updateFooterButtons();

        document.getElementById('overlayName').addEventListener('input', updateOverlayButton);
        document.getElementById('overlayDescription').addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            updateOverlayButton();
        });
    </script>
</body>

</html>
