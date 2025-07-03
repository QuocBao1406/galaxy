<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Tải mảng chứa tất cả các bản dịch
    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/translations.php';

    $default_lang = 'vi';
    $available_langs = ['vi', 'en'];

    if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
        $_SESSION['lang'] = $_GET['lang'];
    }

    $current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : $default_lang;

    /**
     * Hàm dịch thuật ngắn gọn
     * t('key_name') sẽ trả về chuỗi ngôn ngữ tương ứng
     */
    function t($key) {
        global $translations, $current_lang;
        if (isset($translations[$key][$current_lang])) {
            return $translations[$key][$current_lang];
        } else {
            // Trả về key nếu không tìm thấy bản dịch, để dễ debug
            return $key;
        }
    }
?>