<?php
require_once("classes/autoload.php");
$DB = new Database();
$token = $_SESSION['session_token'];
$query = "SELECT userid FROM sessions WHERE token = '$token' LIMIT 1";
$result = $DB->read($query);

if ($result) 
{
    $userid = $result[0]['userid'];
} else
{
    die;
}

$query = "SELECT * FROM challenges WHERE userid = $userid ORDER BY date_added DESC";
$result = $DB->read($query);

if ($result && count($result) > 0) {
    $checklist_items = $result;
    foreach ($checklist_items as $index => $item): ?>
<li class="checklist-item" data-challengeid="<?php echo htmlspecialchars($item['challengeid']); ?>">            
    <input type="checkbox" id="item-<?php echo $index; ?>" data-name="<?php echo htmlspecialchars($item['name']); ?>" data-description="<?php echo htmlspecialchars($item['description']); ?>">
    <label for="item-<?php echo $index; ?>"><?php echo htmlspecialchars($item['name']); ?></label>
    <img src="more.png" class="more-icon" onclick="showOverlay(0, '<?php echo htmlspecialchars($item['name']); ?>', '<?php echo htmlspecialchars($item['description']); ?>')" alt="More">
</li>
    <?php endforeach;
} else {
    echo "<div style='text-align: center; font-size: 24px;'>There are no items</div>";
}
?>           



