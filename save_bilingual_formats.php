<?php
if ($_POST['format'] == 'txt') {
    require_once('bilingual-formats/scripts/save_bilingual_txt.php');
    saveBilingualTxt($_POST['address1'],
                    $_POST['address2'],
                    $_POST['outputPath']);
} else if ($_POST['format'] == 'fb2') {
    require_once('bilingual-formats/scripts/save_bilingual_fb2.php');
    saveBilingualFb2($_POST['address1'],
                    $_POST['address2'],
                    $_POST['outputPath'],
                    $_POST['coverPath'],
                    $_POST['illustrationsDir'],
                    $_POST['lang1'],
                    $_POST['lang2'],
                    $_POST['coverPath'],
                    $_POST['bookID']);
} else if ($_POST['format'] == 'epub') {
    require_once('bilingual-formats/scripts/save_bilingual_epub.php');
    saveBilingualEpub($_POST['address1'],
                      $_POST['address2'],
                      $_POST['outputPath'],
                      $_POST['coverPath'],
                      $_POST['illustrationsDir'],
                      $_POST['lang1'],
                      $_POST['lang2'],
                      $_POST['coverPath'],
                      $_POST['bookID']);
}