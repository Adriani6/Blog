<?php
function polish($var) {
    return trim(stripslashes(htmlspecialchars($var)));
}
?>