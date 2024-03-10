<?php
    header('Content-Type: text/html; charset=utf-8');
    $bookMarkPath = "books/bookmarks/bookmark_{$_POST['file_name_base']}.{$_POST['file1_extension']}";
    file_put_contents($bookMarkPath, $_POST['bookmark_number']);
?>