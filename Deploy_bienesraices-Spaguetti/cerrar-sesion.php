<?php
session_start();
$_SESSION=[];//tambien existe session_detroy o session_unset
header('location: /');
?>