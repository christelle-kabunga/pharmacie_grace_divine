<?php
// Redirect legacy personnel edit to user edit
header('Location: ?page=user&action=edit&id=' . ($_GET['id'] ?? ''));
exit;
?>
