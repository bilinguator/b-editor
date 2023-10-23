<?php
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>B-Editor</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Сниткин Дмитрий">
    <link rel="shortcut icon" href="img/icon.png" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script defer src="scripts/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="print-bilingual-pdf/scripts/print_bilingual_pdf.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"></link>
</head>
<body>
    <form action="" method="get">
    <header>
            <div class="book-file-address-container">
                <label class="book-file-address-label" for="book1-file-address">
                    Book 1
                </label>
                <input type="file" class="book-file-address book1-file-address" id="book1-file-address" name="BOOK1">
            </div>
            <div class="logo">
                <a href=".">
                    <img class="logo-img" src="img/logo.png" alt="B-Editor" />
                </a>
            </div>
            <div class="book-file-address-container">
                <label class="book-file-address-label" for="book2-file-address">
                    Book 2
                </label>
                <input type="file" class="book-file-address book2-file-address" id="book2-file-address" name="BOOK2">
            </div>
            <div>
                <button class="panel-button" type="submit" title="Запустить">
                    <img class="panel-button-img" src="img/play.png" />
                </button>
            </div>
    </form>
    </header>
    <div class="main-area">
        <?php
            // Check plugins
            // print-bilingual-pdf
            $printBilingualPDFExists = true;

            $pathsCheck = ['print-bilingual-pdf/scripts/print_bilingual_pdf.js',
                           'print-bilingual-pdf/scripts/create_element.js',
                           'print-bilingual-pdf/scripts/runnings.js',
                           'print-bilingual-pdf/css/printed_book.css'];

            foreach ($pathsCheck as $path)  {
                $printBilingualPDFExists = file_exists($path);
                if (!$printBilingualPDFExists) {
                    break;
                }
            }

            $printBilingualColsExists = file_exists('print-bilingual-pdf/scripts/print_bilingual_pdf_cols.js');
            $printBilingualRowsExists = file_exists('print-bilingual-pdf/scripts/print_bilingual_pdf_rows.js');
            
            // bilingual-formats
            $bilingualFormatsExists = is_dir('bilingual-formats');
            $saveBilingualTxtExists = file_exists('bilingual-formats/scripts/save_bilingual_txt.php');
            $saveBilingualFb2Exists = file_exists('bilingual-formats/scripts/save_bilingual_fb2.php');
            $saveBilingualEpubExists = file_exists('bilingual-formats/scripts/save_bilingual_epub.php');
            
            if (@$_GET['book1'] == "" and @$_GET['book2'] != "")
                echo "<p>Книга 1 не выбрана</p>";
            if (@$_GET['book1'] != "" and @$_GET['book2'] == "")
                echo "<p>Книга 2 не выбрана</p>";
            if (@$_GET['book1'] == "" and @$_GET['book2'] == "")
                echo "<p>Книги не выбраны</p>";
            if (@$_GET['book1'] == @$_GET['book2'] and @$_GET['book1'] != "" and @$_GET['book2'] != "")
                echo "<p>Вы выбрали одну и ту же книгу дважды</p>";
            
            // Beginning of the big condition "if both books are selected"
            if (@$_GET['book1'] != "" and @$_GET['book2'] != "" and @$_GET['book1'] != @$_GET['book2']):
                $BOOK1PATH = "books/" . iconv('UTF-8', 'cp1251', stripslashes($_GET['book1']));
                $BOOK2PATH = "books/" . iconv('UTF-8', 'cp1251', stripslashes($_GET['book2']));
                
                $BOOK1CONT = file_get_contents($BOOK1PATH);
                $BOOK2CONT = file_get_contents($BOOK2PATH);
                $BOOK1 = preg_split("/[\r]*[\n]+/", $BOOK1CONT);
                $BOOK2 = preg_split("/[\r]*[\n]+/", $BOOK2CONT);
                
                $arr1 = Array();
                $a = -1;
                for ($i = 0; $i < count($BOOK1); $i++) {
                    if (strlen(trim($BOOK1[$i])) != 0) {
                        $a++;
                        $arr1[$a] = $BOOK1[$i];
                    }
                }

                $arr2 = Array();
                $a = -1;
                for ($i = 0; $i < count($BOOK2); $i++) {
                    if (strlen(trim($BOOK2[$i])) != 0) {
                        $a++;
                        $arr2[$a] = $BOOK2[$i];
                    }
                }
                // Counting the longest book
                if (count($arr1) > count($arr2)) {
                    $MORE = count($arr1);
                } else {
                    $MORE = count($arr2);
                }
                    
                // Getting the bookmark number
                $bookmarkFileAddress = 'books/bookmark.txt';
                $bookmark = '0';
                if (file_exists($bookmarkFileAddress)) {
                    $bookmarkFileContents = file_get_contents($bookmarkFileAddress);
                    if (is_numeric($bookmarkFileContents)) {
                        $bookmark = file_get_contents($bookmarkFileAddress);
                    }
                }
                
                // Getting languages
                $lang1 = explode('.', $_GET['book1'])[0];
                $lang1 = explode(' ', $lang1)[0];
                $lang1 = explode('_', $lang1);
                $lang1 = end($lang1);
                
                $lang2 = explode('.', $_GET['book2'])[0];
                $lang2 = explode(' ', $lang2)[0];
                $lang2 = explode('_', $lang2);
                $lang2 = end($lang2);
                
                // Beginning of the loop displaying couples of paragraphs
                for ($i = 0; $i < $MORE; $i++):
                    $bookmarkClass = ($bookmark == $i) ? ' article-number-bookmark' : '';
        ?>
            <div class="article-couple">
                <div class="left">
                    <div class="article-panel panel-left">
                        <p class="article-number article-number-left<?=$bookmarkClass?>" onclick="setArticleIndex(this.innerText, 'left')"><?=$i?></p>
                        <div class="addDelete">
                            <img class="article-button add-article-button" src="img/add.png" onclick="addNewArticle(this)" />
                            <img class="article-button delete-article-button" src="img/delete.png" onclick="deleteArticle(this)" />
                        </div>
                    </div>
                    <textarea lang="<?=$lang1?>" class="article article-left" contenteditable="true"><?=@$arr1[$i]?></textarea>
                </div>
                    
                <div class="right">
                    <textarea lang="<?=$lang2?>" class="article article-right" contenteditable="true"><?=@$arr2[$i]?></textarea>
                    
                    <div class="article-panel article-panel-right">
                        <p class="article-number article-number-right<?=$bookmarkClass?>" onclick="setArticleIndex(this.innerText, 'right')"><?=$i?></p>
                        <div class="addDelete">
                            <img class="article-button add-article-button" src="img/add.png" onclick="addNewArticle(this)" />
                            <img class="article-button delete-article-button" src="img/delete.png" onclick="deleteArticle(this)" />
                        </div>
                    </div>
                </div>
            </div>
        <?php
            endfor;
            endif;
        ?>
    </div>
    
    <footer class="footer">
        <div class="file-panel panel">
            <div class="save-panel panel subpanel">
                <button class="panel-button save-sources-panel-button save-not-needed" name="save" title="Сохранить исходники">
                    <img class="panel-button-img" src="img/save.png" />
                </button>
                <?php
                    if ($printBilingualPDFExists || $bilingualFormatsExists):
                ?>
                <button class="panel-button save-all-formats-panel-button"  title="Сохранить все форматы">
                    <img class="panel-button-img" src="img/save_all_formats.png" />
                </button>
                <?php
                    endif;
                    if ($bilingualFormatsExists):
                        if ($saveBilingualTxtExists) :
                ?>
                <button class="panel-button save-txt-panel-button" title="Сохранить txt">
                    <img class="panel-button-img" src="img/save_txt.png" />
                </button>
                <?php
                        endif;
                        if ($saveBilingualFb2Exists) :
                ?>
                <button class="panel-button save-fb2-panel-button" title="Сохранить fb2">
                    <img class="panel-button-img" src="img/save_fb2.png" />
                </button>
                <?php
                        endif;
                        if ($saveBilingualEpubExists) :
                ?>
                <button class="panel-button save-epub-panel-button" title="Сохранить epub">
                    <img class="panel-button-img" src="img/save_epub.png" />
                </button>
                <?php
                        endif;
                    endif;
                ?>
            </div>
            <?php
            if ($printBilingualPDFExists):
            ?>
            <div class="print-book-panel panel subpanel">
                <?php
                if ($printBilingualColsExists):
                ?>
                    <button class="panel-button cols-panel-button" title="Столбцы">
                        <img class="panel-button-img" src="img/cols.png" />
                    </button>
                <?php
                endif;
                if ($printBilingualRowsExists):
                ?>
                <button class="panel-button rows-panel-button" title="Строки">
                    <img class="panel-button-img" src="img/rows.png" />
                </button>
                <?php
                endif;
                ?>
            </div>
            <?php
            endif;
            ?>
        </div>

        <div class="edition-panel panel">
            <div class="division-concatenation-edition-panel subpanel">
                <button class="panel-button division-panel-button" title="Разделить абзац" onmousedown="divideArticle()">
                    <img class="panel-button-img" src="img/division.png" />
                </button>
                <button class="panel-button concatenation-panel-button" title="Объединить абзацы" onmousedown="concatenateArticles('')">
                    <img class="panel-button-img" src="img/concatenation.png" />
                </button>
                <button class="panel-button concatenation-panel-button" title="Объединить абзацы через пробел" onmousedown="concatenateArticles(' ')">
                    <img class="panel-button-img" src="img/concatenation_space.png" />
                </button>
                <button class="panel-button concatenation-panel-button" title="Объединить абзацы через <delimiter>" onmousedown="concatenateArticles('<delimiter>')">
                    <img class="panel-button-img" src="img/concatenation_delimiter.png" />
                </button>
            </div>
            <div class="insertions-edition-panel subpanel">
                <button class="panel-button insertions-panel-button heading-panel-button" title="Заголовок">
                    <img class="panel-button-img" src="img/h.png" />
                </button>
                <button class="panel-button insertions-panel-button bold-panel-button" title="Полужирный">
                    <img class="panel-button-img" src="img/b.png" />
                </button>
                <button class="panel-button insertions-panel-button italic-panel-button" title="Курсив">
                    <img class="panel-button-img" src="img/i.png" />
                </button>
                <button class="panel-button insertions-panel-button delimiter-panel-button" title="Разделитель">
                    <img class="panel-button-img" src="img/delimiter.png" />
                </button>
                <button class="panel-button insertions-panel-button paste-img-panel-button" title="Вставить картинку">
                    <img class="panel-button-img" src="img/img.png" />
                </button>
                <button class="panel-button insertions-panel-button case-panel-button" title="Изменить регистр">
                    <img class="panel-button-img" src="img/case.png" />
                </button>
            </div>
        </div>
            
        <div class="manipulations-panel panel">
            <div class="shift-area subpanel">
                <div class="article-indexes-container">
                    <input type="text" class="article-index" rows="1" size="2" placeholder="№" value="<?=@$bookmark?>" />
                    <hr />
                    <input type="text" class="article-index" rows="1" size="2" placeholder="№"/>
                </div>
                <button class="panel-button shift-panel-button" title="Сдвинуть">
                    <img class="panel-button-img" src="img/shift.png" />
                </button>
                <button class="panel-button delete-by-index-panel-button" title="Удалить">
                    <img class="panel-button-img" src="img/delete_by_index.png" />
                </button>
            </div>
            
            <div class="score-panel panel subpanel">
                <button class="panel-button aim-button" onclick="aimAtArticle()" title="К текущей позиции">
                    <img class="panel-button-img" src="img/focus.png" />
                </button>
                <button class="panel-button bookmark-panel-button" title="Закладка">
                    <img class="panel-button-img" src="img/bookmark.png" />
                </button>
                <div class="stats">
                <div class="bookmark-score-container subpanel">
                    <p class="bookmark-number">
                        <?=@$bookmark?>
                    </p>
                    <p class="slash">
                        /
                    </p>
                    <p class="articles-count">
                        -
                    </p>
                </div>
                </div>
                <div class="scroll-panel subpanel">
                    <p class="percents">
                        0%
                    </p>
                    <div class="scroll-score"></div>
                </div>
            </div>
        </div>
    </footer>
    <script type="text/javascript">
        let book1FileAddress = document.querySelector('.book1-file-address');
        let book2FileAddress = document.querySelector('.book2-file-address');
        
        [book1FileAddress, book2FileAddress].forEach((item) => {
                item.addEventListener('change', () => {
                sessionStorage.setItem(item.classList.item(1), 'books/' + item.files[0].name);
            })
        });

        const bookID = getBookID();
        const lang1 = getBookLang(1);
        const lang2 = getBookLang(2);

        const illustrationsDir = 'books/illustrations';
        const coverPath = `covers/${lang1}.png`
        const saveDir = 'books/saved'
        const outputBase = `${saveDir}/${bookID}_${lang1}_${lang2}`
        
        // Get original array
        function getArticlesArray (side = 'left') {
            let articles = document.querySelectorAll('.article-' + side);
            let articlesArray = Array();
            
            for (let i = 0; i < articles.length; i++) {
                articlesArray[i] = articles[i].value;
            }
            
            return articlesArray;
        }

        // Get text, left or right
        function getText (side = 'left') {
            return getArticlesArray(side).join('\n');
        }
        
        // Adjusting textarea height
        function fixTextArea () {
            let textareaNeeded = document.querySelectorAll('.article');
            
            function fixTextareaSize(textarea) {
                textarea.style.height = '0px';
                textarea.style.height = textarea.scrollHeight + 2 + 'px';
            }
            
            ~function () {
                for (let i = 0; i < textareaNeeded.length; i++) {
                    textareaNeeded[i].addEventListener('input', function (e) {
                        fixTextareaSize(e.target)
                    });
                    fixTextareaSize(textareaNeeded[i]);
                }
            }()
        }
        
        fixTextArea();
        
        // Adjusting height of adjacent textareas
        function alignSiblingTextareas (index = 'all') {
            if (index === 'all') {
                let articleCouplesNumber = document.querySelectorAll('.article-couple').length;
                for (let i = 0; i < articleCouplesNumber; i++) {
                    alignSiblingTextareas(i);
                }
            } else {
                let textareaLeft = document.querySelectorAll('.article-left')[index];
                let textareaRight = document.querySelectorAll('.article-right')[index];
                
                let textareaLeftHeight = textareaLeft.offsetHeight;
                let textareaRightHeight = textareaRight.offsetHeight;
                
                if (textareaLeftHeight > textareaRightHeight) {
                    textareaRight.style.height = textareaLeft.offsetHeight + 'px';
                } else {
                    textareaLeft.style.height = textareaRight.offsetHeight + 'px';
                }
            }
        }
        
        alignSiblingTextareas();
        
        document.querySelectorAll('.article').forEach((article) => {
            article.addEventListener('input', () => {
                let articleIndex = getArticleIndex(article);
                
                alignSiblingTextareas(articleIndex);
                document.querySelectorAll('.panel-button').forEach((button) => {
                    button.classList.remove('save-not-needed');
                });
            });
        });
        
        // Add padding below .main-area
        function addMainAreaPaddingBottom () {
            document.querySelector('.main-area').style.paddingBottom = document.querySelector('.footer').offsetHeight + 20 + 'px';
        }
        
        addMainAreaPaddingBottom();
        
        window.addEventListener('resize', () => {
            fixTextArea();
            alignSiblingTextareas();
            addMainAreaPaddingBottom();
        });
        
        // Get author
        function getAuthor (side = 'left') {
            let authorArray = getArticlesArray(side)[0].split(",");
            let authorSplit = [];
            let author = "";
            for (let i = 0; i < authorArray.length; i++) {
                authorSplit = authorArray[i].split(" ");
                author = author + authorSplit[authorSplit.length - 1];
                for (let j = 0; j < authorSplit.length - 1; j++) {
                    author = author.trim() + ' ' + authorSplit[j];
                }
                if (i !== authorArray.length - 1) {
                    author = author +  ", ";
                }
            }
            
            return author;
        }
        
        // Get title
        function getTitle (side = 'left') {
            let titleArray = getArticlesArray(side)[1].split("<delimiter>");
            let title = titleArray[0].split("<h1>").join("").split("</h1>").join("");
            return title;
        }
        
        function getTitleRest (side = 'left') {
            let titleRest = getArticlesArray(side)[1].split("<delimiter>")[1];
            
            if (!titleRest) {
                titleRest = '';
            }
            
            return titleRest;
        }
        
        // Get file name
        function getFileName (type = '') {
            let space = type ? '_' : '';
            return bookID + '_' + getBookLang(1) + '_' + getBookLang(2) + space + type;
        }
        
        // Focus on a given paragraph
        function aimAtArticle() {
            let targetArticleNumber = document.querySelectorAll('.article-index')[0].value;
            let targetArticle = document.querySelectorAll('.article-left')[targetArticleNumber];
            if (targetArticle) {
                targetArticle.scrollIntoView();
            }
        }
        
        // Set bookmark
        function setBookmark () {
            let targetArticleNumber = document.querySelectorAll('.article-index')[0].value;
                if (Number.isInteger(Number(targetArticleNumber))) {
                    let bookmarkNumber = document.querySelector('.bookmark-number');
                    bookmarkNumber.innerHTML = targetArticleNumber;
                    
                    $.ajax({
                        url: "save_bookmark.php",
                        type: "POST",
                        data: ({bookmark_number: targetArticleNumber}),
                        dataType: "html"
                    });
                    
                    document.querySelectorAll('.article-number').forEach((item) => {
                        item.classList.remove('article-number-bookmark');
                    });
                    
                    let targetLeftNumber = document.querySelectorAll('.article-number-left')[targetArticleNumber];
                    let targetRightNumber = document.querySelectorAll('.article-number-right')[targetArticleNumber];
                    [targetLeftNumber, targetRightNumber].forEach((item) => {
                        item.classList.add('article-number-bookmark');
                    });
                }
        }
        
        document.querySelector('.save-all-formats-panel-button').addEventListener('click', () => {
            saveSources();
            saveTxt();
            saveFb2();
            saveEpub();
            printCols();
            printRows();
            document.querySelector('.save-all-formats-panel-button').classList.add('save-not-needed');
            document.querySelector('.save-txt-panel-button').classList.add('save-not-needed');
            document.querySelector('.save-fb2-panel-button').classList.add('save-not-needed');
            document.querySelector('.save-epub-panel-button').classList.add('save-not-needed');
        });
        
        document.querySelector('.bookmark-panel-button').addEventListener('click', () => {
            setBookmark();
        });
        
        function setArticleIndex (articleIndex, side = 'left') {
            if (side === 'left') {
                document.querySelectorAll('.article-index')[0].value = articleIndex;
            }
            
            if (side === 'right') {
                document.querySelectorAll('.article-index')[1].value = articleIndex;
            }
        }
        
        // Focus on bookmark
        function focusBookmark () {
            let bookmarkNumber = Number(document.querySelector('.bookmark-number').innerHTML);
            let targetArticleCouple = document.querySelectorAll('.article-couple')[bookmarkNumber];
            targetArticleCouple.scrollIntoView();
        }
        document.querySelector('.bookmark-number').addEventListener('click', focusBookmark);
        
        // Recalculation of paragraph numbers
        function recountArticleNumbers () {
            let leftArticleNumbers = document.querySelectorAll('.article-number-left');
            let rightArticleNumbers = document.querySelectorAll('.article-number-right');
            
            for (let i = 0; i < $(".article-couple").length; i++) {
                leftArticleNumbers[i].innerText = i;
                rightArticleNumbers[i].innerText = i;
            }
            
            showArticlesCount();
        }
        
        // Get language code
        function getBookLang (bookIndex = 1) {
            let fileAddress = sessionStorage.getItem('book' + bookIndex + '-file-address');
            let fileName = fileAddress.split('/')[1].split('.')[0];
            let semanticPart = fileName.split(' ')[0];
            let lang = semanticPart.split('_');
            lang = lang[lang.length - 1];
            return lang;
        }
        
        function getBookID (bookIndex = 1) {
            let fileAddress = sessionStorage.getItem('book' + bookIndex + '-file-address');
            let fileName = fileAddress.split('/')[1];
            let semanticPart = fileName.split('.')[0].split(' ')[0];
            let bookID = semanticPart.slice(0, semanticPart.lastIndexOf('_'));
            return bookID;
        }
        
        document.querySelector('.cols-panel-button').addEventListener('click', () => {
            printCols();
        });
        
        document.querySelector('.rows-panel-button').addEventListener('click', () => {
            printRows();
        });

        function printCols () {
            printBilingualPDF (getText('left'),
                               getText('right'),
                               getBookLang(1),
                               getBookLang(2),
                                 'cols',
                               coverPath,
                               `${bookID}_${getBookLang(1)}`,
                               illustrationsDir);
        }

        function printRows () {
            printBilingualPDF (getText('left'),
                               getText('right'),
                               getBookLang(1),
                               getBookLang(2),
                                 'rows',
                               coverPath,
                               `${bookID}_${getBookLang(1)}`,
                               illustrationsDir);
        }
        
        // Save sources
        function saveSources () {
            if (document.querySelectorAll('.article-couple').length === 0) {
                return false;
            }
            
            let articlesArrayLeft = getArticlesArray('left').join('\n');
            let articlesArrayRight = getArticlesArray('right').join('\n');
            
            $.ajax({
                url: "save_sources.php",
                type: "POST",
                data: ({address1: sessionStorage.getItem('book1-file-address'),
                        address2: sessionStorage.getItem('book2-file-address'),
                        text1: articlesArrayLeft,
                        text2: articlesArrayRight
                }),
                dataType: "html"
            });
            
            document.querySelector('.save-sources-panel-button').classList.add('save-not-needed');
        }
        
        function saveTxt () {
            $.ajax({
                url: "save_bilingual_formats.php",
                type: "POST",
                data: ({format: 'txt',
                        address1: sessionStorage.getItem('book1-file-address'),
                        address2: sessionStorage.getItem('book2-file-address'),
                        outputPath: `${outputBase}.txt`
                }),
                dataType: "html"
            });
            document.querySelector('.save-txt-panel-button').classList.add('save-not-needed');
        }

        function saveFb2 () {
            $.ajax({
                url: "save_bilingual_formats.php",
                type: "POST",
                data: ({format: 'fb2',
                        address1: sessionStorage.getItem('book1-file-address'),
                        address2: sessionStorage.getItem('book2-file-address'),
                        outputPath: `${outputBase}.fb2`,
                        coverPath: coverPath,
                        illustrationsDir: illustrationsDir,
                        lang1: lang1,
                        lang2: lang2,
                        bookID: bookID
                }),
                dataType: "html"
            });
            document.querySelector('.save-fb2-panel-button').classList.add('save-not-needed');
        }

        function saveEpub () {
            $.ajax({
                url: "save_bilingual_formats.php",
                type: "POST",
                data: ({format: 'epub',
                        address1: sessionStorage.getItem('book1-file-address'),
                        address2: sessionStorage.getItem('book2-file-address'),
                        outputPath: `${outputBase}.epub`,
                        coverPath: coverPath,
                        illustrationsDir: illustrationsDir,
                        lang1: lang1,
                        lang2: lang2,
                        bookID: bookID
                }),
                dataType: "html"
            });
            document.querySelector('.save-epub-panel-button').classList.add('save-not-needed');
        }
        
        document.querySelector('.save-sources-panel-button').addEventListener('click', () => {
            saveSources();
        });
        
        document.querySelector('.save-txt-panel-button').addEventListener('click', () => {
            saveSources();
            saveTxt();
        });

        document.querySelector('.save-fb2-panel-button').addEventListener('click', () => {
            saveSources();
            saveFb2();
        });
        
        document.querySelector('.save-epub-panel-button').addEventListener('click', () => {
            saveSources();
            saveEpub();
        });
        
        // Displaying number of paragraphs
        function showArticlesCount () {
            let articlesCountArea = document.querySelector('.articles-count');
            let articlesArrayLeft = getArticlesArray('left');
            let articlesArrayRight = getArticlesArray('right');
            let maxArticlesCount = Math.max(articlesArrayLeft.length, articlesArrayRight.length);
            
            if (maxArticlesCount === 0) {
                articlesCountArea.innerText = '-';
            } else {
                articlesCountArea.innerText = maxArticlesCount - 1;
            }
        }
        
        showArticlesCount ();

        // Class for the selected paragraph
        class focusedArticle {
            constructor(article, start = 0, end = 0) {
                this._article = article;
                this._start = start;
                this._end = end;
            }
            
            get article() {
                return this._article;
            }
            
            get start() {
                return this._start;
            }
            
            get end() {
                return this._end;
            }
            
            set article(value) {
                this._article = value;
            }
            
            set start(value) {
                this._start = value;
            }
            
            set end(value) {
                this._end = value;
            }
        }
        
        let activeArticle = new focusedArticle();
        
        // Get image number
        function getImgNumber () {
            let targetArticle = document.activeElement;
            let joinedText = '';
            
            if (targetArticle.classList.contains('article-left')) {
                joinedText = getArticlesArray('left').join('');
            } else if (targetArticle.classList.contains('article-right')) {
                joinedText = getArticlesArray('right').join('');
            }
            
            return joinedText.split('<img').length;
        }
        
        // Inserting text to paragraph instead of selected text 
        function insertTextToArticle (text, article, isInserted = true) {
            
            if (article.classList.contains('article') && text !== '') {
                let start = article.selectionStart;
                let end = isInserted ? article.selectionEnd : start;
                let articleValue = article.value;
                
                let part1 = articleValue.substring(0, start);
                let part3 = articleValue.substring(end);
                
                article.value = part1 + text + part3;
                
                activeArticle.article = article;
                activeArticle.start = isInserted ? start : start + text.length;
                activeArticle.end = start + text.length;
                
                fixTextArea();
                alignSiblingTextareas();
                document.querySelectorAll('.panel-button').forEach((button) => {
                    button.classList.remove('save-not-needed');
                });
            } else {
                return false;
            }
        }
        
        // Switch case
        function switchCase (text) {
            let textLowered = text.toLowerCase();
            let textUppered = text.toUpperCase();
            
            if (text === textUppered) {
                return textLowered;
            } else {
                return textUppered;
            }
        }
        
        // Get selected text
        function getSelectedText () {
            
            if (document.activeElement.classList.contains('article')) {
                let article = document.activeElement;
                let start = article.selectionStart;
                let end = article.selectionEnd;
                let articleContent = article.value;
                return articleContent.substring(start, end);
            } else {
                return false;
            }
        }
        
        document.querySelector('.delimiter-panel-button').addEventListener('mousedown', () => {
            insertTextToArticle('<delimiter>', document.activeElement, false);
        });
        
        document.querySelector('.paste-img-panel-button').addEventListener('mousedown', () => {
            let targetElement = document.activeElement;
            insertTextToArticle('<img' + getImgNumber(targetElement) + '>', document.activeElement, false);
        });
        
        document.querySelector('.case-panel-button').addEventListener('mousedown', () => {
            insertTextToArticle(switchCase(getSelectedText()), document.activeElement);
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        });
        
        // Add tags       
        function addTagsToArticle (tag1, tag2, article) {
            
            if (article.classList.contains('article')) {
                let start = article.selectionStart;
                let end = article.selectionEnd;
                
                if (start !== end) {
                    let articleValue = article.value;
                    let taggedArticleValue = articleValue.substring(0, start) + tag1;
                    taggedArticleValue += articleValue.substring(start, end) + tag2;
                    taggedArticleValue += articleValue.substring(end);
                    article.value = taggedArticleValue;
                    
                    activeArticle.article = article;
                    activeArticle.start = end + tag1.length + tag2.length;
                    activeArticle.end = end + tag1.length + tag2.length;
                } else {
                    return false;
                }
            }
            
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelector('.bold-panel-button').addEventListener('mousedown', () => {
            addTagsToArticle('<b>', '</b>', document.activeElement);
        });
        
        document.querySelector('.italic-panel-button').addEventListener('mousedown', () => {
            addTagsToArticle('<i>', '</i>', document.activeElement);
        });

        document.querySelector('.heading-panel-button').addEventListener('mousedown', () => {
            addTagsToArticle('<h1>', '</h1>', document.activeElement);
        });
        
        // For all insertions buttons
        document.querySelectorAll('.insertions-panel-button').forEach((button) => {
            button.addEventListener('mouseup', () => {
                activeArticle.article.focus();
                activeArticle.article.setSelectionRange(activeArticle.start, activeArticle.end);
            })
        });
        
        // Get scroll percent
        function getScrollPercent() {
            let doc = document.documentElement;
            let body = document.body;
            let top = 'scrollTop';
            let    height = 'scrollHeight';
            
            return Math.ceil((doc[top]||body[top]) / ((doc[height]||body[height]) - doc.clientHeight) * 100);
        }
        
        // Display scroll percent
        window.addEventListener('scroll', function() {
            let scrollPercent = getScrollPercent();
            
             document.querySelector('.percents').innerHTML = scrollPercent + '%';
            document.querySelector('.scroll-score').style.background = `linear-gradient(to right, rgba(250,143,56,1) 0%, rgba(250,143,56,1) ${scrollPercent}%, rgba(255,255,255,1) ${scrollPercent}%, rgba(255,255,255,1) 100%)`;
        });
        
        // Get side of paragraph
        function getArticleSide (article) {
            if (article.classList.contains('article-left')) {
                return 'left';
            }
            if (article.classList.contains('article-right')) {
                return 'right';
            }
            return false;
        }
        
        // Get paragraph index
        function getArticleIndex (article) {
            let articleSide = getArticleSide(article);
            let articlesArray = document.querySelectorAll('.article-' + articleSide);
            
            for (let i = 0; i < articlesArray.length; i++) {
                if (articlesArray[i] === article) {
                    return i;
                }
            }
            
            return false;
        }
        
        // Get element index
        function getElementIndex (element, selector) {
            let elementsArray = document.querySelectorAll(selector);
            
            for (let i = 0; i < elementsArray.length; i++) {
                if (elementsArray[i] === element) {
                    return i;
                }
            }
            
            return false;
        }
        
        // Find child by class
        function findChildByClass (element, childClass) {
            let elementChildren = element.childNodes;
            let result;

            elementChildren.forEach((item) => {
                if (item.classList) {
                    if (item.classList.contains(childClass)) {
                        result = item;
                    }
                }
            });

            return result;
        }
        
        // Add couple of empty paragraphs to the end
        function appendTerminalEmptyArticles () {
            let clonedArticleCouple = document.querySelectorAll('.article-couple')[0].cloneNode(true);
            let leftArticle = findChildByClass(findChildByClass(clonedArticleCouple, 'left'), 'article-left');
            let rightArticle = findChildByClass(findChildByClass(clonedArticleCouple, 'right'), 'article-right');
            leftArticle.value = '';
            rightArticle.value = '';
            
            document.querySelector('.main-area').append(clonedArticleCouple);
            document.querySelector('.main-area').append(clonedArticleCouple);
            recountArticleNumbers();
        }
        
        // Delete empty paragraphs in the end
        function deleteTerminalEmptyArticles () {
            let articleCouples = document.querySelectorAll('.article-couple');
            let leftArticles = document.querySelectorAll('.article-left');
            let rightArticles = document.querySelectorAll('.article-right');
            
            let index = articleCouples.length - 1;
            
            while (leftArticles[index].value.trim() === '' && rightArticles[index].value.trim() === '') {
                articleCouples[index].remove();
                index--;
            }
            
        }
        
        // Add paragraph
        function addNewArticle (addButton, deleteTerminal = true) {
            appendTerminalEmptyArticles ();
                    
            let buttonIndex = getElementIndex(addButton, '.add-article-button');
            let side = buttonIndex % 2 === 0 ? 'left' : 'right';
            let articleIndex = side === 'left' ? buttonIndex / 2 : (buttonIndex - 1) / 2;
            
            let targetArticlesArray = document.querySelectorAll('.article-' + side);
            
            for (let i = targetArticlesArray.length - 1; i > articleIndex; i--) {
                targetArticlesArray[i].value = targetArticlesArray[i - 1].value;
            }
            targetArticlesArray[articleIndex].value = '';
            
            fixTextArea();
            alignSiblingTextareas();
            
            if (deleteTerminal) {
                deleteTerminalEmptyArticles();
            }
            
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        // Delete paragraph
        function deleteArticle (deleteButton, deleteTerminal = true) {
            let buttonIndex = getElementIndex(deleteButton, '.delete-article-button');
            let side = buttonIndex % 2 === 0 ? 'left' : 'right';
            let articleIndex = side === 'left' ? buttonIndex / 2 : (buttonIndex - 1) / 2;
            
            let targetArticlesArray = document.querySelectorAll('.article-' + side);
            
            for (let i = articleIndex; i < targetArticlesArray.length - 1; i++) {
                targetArticlesArray[i].value = targetArticlesArray[i + 1].value;
            }
            
            targetArticlesArray[targetArticlesArray.length - 1].value = '';
            
            fixTextArea();
            alignSiblingTextareas();
            
            if (deleteTerminal) {
                deleteTerminalEmptyArticles();
            }
            
            showArticlesCount();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        // Concatenate paragraphs
        function concatenateArticles (glue='') {
            let targetArticle = document.activeElement;
            if (!targetArticle.classList.contains('article')) {
                return false;
            }
            
            let articleSide = getArticleSide(targetArticle);
            let articleIndex = getArticleIndex(targetArticle);
            
            let cancatenatedArticle = document.querySelectorAll('.article-' + articleSide)[articleIndex + 1]
            
            targetArticle.value += glue + cancatenatedArticle.value;
            
            let deleteButtonIndex;
            
            if (articleSide === 'left') {
                deleteButtonIndex = (articleIndex + 1) * 2;
            } else if (articleSide === 'right') {
                deleteButtonIndex = (articleIndex + 1) * 2 + 1;
            }
            let deleteButton = document.querySelectorAll('.delete-article-button')[deleteButtonIndex];
            
            deleteButton.click();
            activeArticle.article = targetArticle;
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelectorAll('.concatenation-panel-button').forEach((button) => {
            button.addEventListener('mouseup', () => {
                activeArticle.article.focus();
            });
        });
        
        // Divide paragraphs
        function divideArticle () {
            let targetArticle = document.activeElement;
            if (!targetArticle.classList.contains('article')) {
                return false;
            }
            
            let articleSide = getArticleSide(targetArticle);
            let articleIndex = getArticleIndex(targetArticle);
            
            let targetArticlesArray = document.querySelectorAll('.article-' + articleSide);
            if (articleIndex === targetArticlesArray.length - 1) {
                appendTerminalEmptyArticles();
            }
            
            let start = targetArticle.selectionStart;
            let end = targetArticle.selectionEnd;
            
            if (start != end) {
                return false;
            }
            
            substr1 = targetArticle.value.substring(0, start).trim();
            substr2 = targetArticle.value.substr(start).trim();
            
            let addButtonIndex;
            if (articleSide === 'left') {
                addButtonIndex = (articleIndex + 1) * 2;
            } else if (articleSide === 'right') {
                addButtonIndex = (articleIndex + 1) * 2 + 1;
            }
            let addButton = document.querySelectorAll('.add-article-button')[addButtonIndex];
            
            addNewArticle(addButton, false);
            targetArticle.value = substr1;
            document.querySelectorAll('.article-' + articleSide)[articleIndex + 1].value = substr2;
            deleteTerminalEmptyArticles();
            
            activeArticle.article = targetArticle;
            
            fixTextArea();
            alignSiblingTextareas();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        // Delete paragraphs by indexes
        function deleteArticlesByIndexes (index1, index2) {
            
            let minIndex = Math.min(index1, index2);
            let maxIndex = Math.max(index1, index2);
            
            for (let i = maxIndex; i >= minIndex; i--) {
                document.querySelectorAll('.article-couple')[i].remove();
            }
            
            recountArticleNumbers();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelector('.delete-by-index-panel-button').addEventListener('click', () => {
            let index1 = document.querySelectorAll('.article-index')[0].value;
            let index2 = document.querySelectorAll('.article-index')[1].value;
            deleteArticlesByIndexes (index1, index2);
        });
        
        // Shift paragraphs by indexes
        function shiftArticles (index1, index2) {
            if (!index1 || !index2 || index1 === index2) {
                return false;
            }
            
            let articleCouplesCount = document.querySelectorAll('.article-couple').length;
            if (index1 >= articleCouplesCount || index2 >= articleCouplesCount) {
                return false;
            }
            
            let minIndex = Math.min(index1, index2);
            let maxIndex = Math.max(index1, index2);
                    
            let side = minIndex == index1 ? 'left' : 'right';
            let difference = maxIndex - minIndex;
            for (let i = 0; i < difference; i++) {
                appendTerminalEmptyArticles();
            }
            
            let targetArticlesArray = document.querySelectorAll('.article-' + side);
            
            for (let i = targetArticlesArray.length - 1; i >= maxIndex; i--) {
                targetArticlesArray[i].value = targetArticlesArray[i - difference].value;
            }
            
            for (let i = maxIndex - 1; i >= minIndex; i--) {
                targetArticlesArray[i].value = '';
            }
            
            fixTextArea();
            alignSiblingTextareas();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelector('.shift-panel-button').addEventListener('click', () => {
            let index1 = document.querySelectorAll('.article-index')[0].value;
            let index2 = document.querySelectorAll('.article-index')[1].value;
            shiftArticles (index1, index2);
        });

        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 83) {
                event.preventDefault();
                saveSources();
            }
        });
    </script>
    
    </div>
</body>
</html>