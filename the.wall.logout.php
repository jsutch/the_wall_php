<?php

session_start();
session_destroy();
header('Location: the.wall.login-reg.php');
exit;
?>