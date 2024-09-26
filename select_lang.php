<?php
session_start();

if (isset($_GET['lang'])) {
    if ($_GET['lang'] == 'en') {
        $_SESSION['lang'] = 'en';
    } else if ($_GET['lang'] == 'th') {
        $_SESSION['lang'] = 'th';
    }
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'th'; // ภาษาเริ่มต้น
}