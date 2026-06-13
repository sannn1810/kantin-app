<?php
require 'config.php';
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);
header('Location: index.php');
exit;
