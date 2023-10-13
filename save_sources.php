<?php
	header('Content-Type: text/html; charset=utf-8');
	$CODING1 = mb_detect_encoding($_POST['text1'], array('utf-8', 'cp1251'));
	$CODING2 = mb_detect_encoding($_POST['text2'], array('utf-8', 'cp1251'));
	$CODINGADDRESS1 = mb_detect_encoding($_POST['address1'], array('utf-8', 'cp1251'));
	$CODINGADDRESS2 = mb_detect_encoding($_POST['address2'], array('utf-8', 'cp1251'));
	
	$TEXT1 = iconv($CODING1, 'UTF-8', stripslashes($_POST['text1']));
	$TEXT2 = iconv($CODING1, 'UTF-8', stripslashes($_POST['text2']));
	$ADDRESS1 = iconv($CODINGADDRESS1, 'UTF-8', $_POST['address1']);
	$ADDRESS2 = iconv($CODINGADDRESS2, 'UTF-8', $_POST['address2']);
	
	file_put_contents($ADDRESS1, $TEXT1);
	file_put_contents($ADDRESS2, $TEXT2);
?>