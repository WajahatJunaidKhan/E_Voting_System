<?php
session_start();
session_destroy();
header("Location: thank_you.html");
exit();
?>
