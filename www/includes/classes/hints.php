<?php
if (isset($_SESSION['Hints']) && isset($_SESSION['Hints']['ttl'])) {
    $_SESSION['Hints']['ttl'] -= 1;
}
if (isset($_SESSION['Hints']) && $_SESSION['Hints']['ttl'] < 0) {
    unset($_SESSION['Hints']);
}
?>