<?php
require_once("classes/autoload.php");



$userid = $_SESSION['userid'] ?? 0;
$query = "SELECT * FROM challenges WHERE userid = $userid ORDER BY date_added DESC";
$DB = new Database();
$result = $DB->read($query);

if ($result && count($result) > 0) {
    $checklist_items = $result;
    foreach ($checklist_items as $index => $item): ?>
        <li class="checklist-item">
            <input type="checkbox" id="item-<?php echo $index; ?>">
            <label for="item-<?php echo $index; ?>"><?php echo htmlspecialchars($item['name']); ?></label>
            <img src="more.png" class="more-icon" onclick="toggleDescription(<?php echo $index; ?>)" alt="More">
            <div class="description" style="display: none;"><?php echo htmlspecialchars($item['description']); ?></div>
        </li>
    <?php endforeach;
} else {
    echo "<div style='text-align: center; font-size: 24px;'>There are no items</div>";
}
?>

<script>
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
</script>

<style>
.more-icon {
    width: 30px;
    height: 30px;
    cursor: pointer;
    margin-left: 10px;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 4;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #333;
    color: white;
    margin: auto;
    padding: 40px;
    border: 1px solid #888;
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
    text-align: center;
}

.modal-name {
    font-size: 36px;
    margin-bottom: 20px;
}

.modal-description {
    font-size: 24px;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
}
</style>
