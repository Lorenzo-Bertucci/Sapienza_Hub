<?php
session_start(); 

session_unset();

session_destroy();

$current_page = '/src/client/html/index.html';

header("Location: $current_page");
exit;
?>