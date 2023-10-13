<?php
	header('Content-Type: text/html; charset=utf-8');
	file_put_contents('books/bookmark.txt', $_POST['bookmark_number']);
?>