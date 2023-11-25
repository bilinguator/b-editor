<?php
    header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>B-Editor</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="Bilinguator.com">
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
                <input type="file" class="book-file-address book1-file-address" id="book1-file-address" name="book1">
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
                <input type="file" class="book-file-address book2-file-address" id="book2-file-address" name="book2">
            </div>
            <div>
                <button class="panel-button" type="submit" title="Launch">
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
                echo "<p>Book 1 is not selected.</p>";
            if (@$_GET['book1'] != "" and @$_GET['book2'] == "")
                echo "<p>Book 2 is not selected.</p>";
            if (@$_GET['book1'] == "" and @$_GET['book2'] == "")
                echo "<p>Books are not selected.</p>";
            if (@$_GET['book1'] == @$_GET['book2'] and @$_GET['book1'] != "" and @$_GET['book2'] != "")
                echo "<p>The same book is selected twice!</p>";
            
            // Beginning of the big condition "if both books are selected"
            if (@$_GET['book1'] != "" and @$_GET['book2'] != "" and @$_GET['book1'] != @$_GET['book2']):
                $bookPath1 = "books/" . iconv('UTF-8', 'cp1251', stripslashes($_GET['book1']));
                $bookPath2 = "books/" . iconv('UTF-8', 'cp1251', stripslashes($_GET['book2']));
                
                $bookContent1 = file_get_contents($bookPath1);
                $bookContent2 = file_get_contents($bookPath2);
                $book1 = preg_split("/[\r]*[\n]+/", $bookContent1);
                $book2 = preg_split("/[\r]*[\n]+/", $bookContent2);
                
                $arr1 = Array();
                $a = -1;
                for ($i = 0; $i < count($book1); $i++) {
                    if (strlen(trim($book1[$i])) != 0) {
                        $a++;
                        $arr1[$a] = $book1[$i];
                    }
                }

                $arr2 = Array();
                $a = -1;
                for ($i = 0; $i < count($book2); $i++) {
                    if (strlen(trim($book2[$i])) != 0) {
                        $a++;
                        $arr2[$a] = $book2[$i];
                    }
                }
                // Counting the longest book
                if (count($arr1) > count($arr2)) {
                    $longestBookLength = count($arr1);
                } else {
                    $longestBookLength = count($arr2);
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

                $br = '&#013;';
                
                // Beginning of the loop displaying couples of paragraphs
                for ($i = 0; $i < $longestBookLength; $i++):
                    $bookmarkClass = ($bookmark == $i) ? ' paragraph-number-bookmark' : '';
        ?>
            <div class="paragraph-couple">
                <div class="left">
                    <div class="paragraph-panel panel-left">
                        <p class="paragraph-number paragraph-number-left<?=$bookmarkClass?>" onclick="setParagraphIndex(this.innerText, 'left')"><?=$i?></p>
                        <div class="addDelete">
                            <img class="paragraph-button add-paragraph-button" src="img/add.png" onclick="addNewParagraph(this)" />
                            <img class="paragraph-button delete-paragraph-button" src="img/delete.png" onclick="deleteParagraph(this)" />
                        </div>
                    </div>
                    <textarea lang="<?=$lang1?>" class="paragraph paragraph-left" contenteditable="true"><?=@$arr1[$i]?></textarea>
                </div>
                    
                <div class="right">
                    <textarea lang="<?=$lang2?>" class="paragraph paragraph-right" contenteditable="true"><?=@$arr2[$i]?></textarea>
                    
                    <div class="paragraph-panel paragraph-panel-right">
                        <p class="paragraph-number paragraph-number-right<?=$bookmarkClass?>" onclick="setParagraphIndex(this.innerText, 'right')"><?=$i?></p>
                        <div class="addDelete">
                            <img class="paragraph-button add-paragraph-button" src="img/add.png" onclick="addNewParagraph(this)" />
                            <img class="paragraph-button delete-paragraph-button" src="img/delete.png" onclick="deleteParagraph(this)" />
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
                <button class="panel-button save-sources-panel-button save-not-needed" name="save" title="Save source files<?=$br?>(Ctrl+S)">
                    <img class="panel-button-img" src="img/save.png" />
                </button>
                <?php
                    if ($printBilingualPDFExists || $bilingualFormatsExists):
                ?>
                <button class="panel-button save-all-formats-panel-button"  title="Save in all formats">
                    <img class="panel-button-img" src="img/save_all_formats.png" />
                </button>
                <?php
                    endif;
                    if ($bilingualFormatsExists):
                        if ($saveBilingualTxtExists) :
                ?>
                <button class="panel-button save-txt-panel-button" title="Save txt">
                    <img class="panel-button-img" src="img/save_txt.png" />
                </button>
                <?php
                        endif;
                        if ($saveBilingualFb2Exists) :
                ?>
                <button class="panel-button save-fb2-panel-button" title="Save fb2">
                    <img class="panel-button-img" src="img/save_fb2.png" />
                </button>
                <?php
                        endif;
                        if ($saveBilingualEpubExists) :
                ?>
                <button class="panel-button save-epub-panel-button" title="Save epub">
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
                    <button class="panel-button cols-panel-button" title="Print columns PDF<?=$br?>(Ctrl+P)">
                        <img class="panel-button-img" src="img/cols.png" />
                    </button>
                <?php
                endif;
                if ($printBilingualRowsExists):
                ?>
                <button class="panel-button rows-panel-button" title="Print rows PDF<?=$br?>(Ctrl+Shift+P)">
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
            <div class="insertions-edition-panel subpanel">
                <button class="panel-button insertions-panel-button heading-panel-button" title="Title<?=$br?>(Ctrl+H)">
                    <img class="panel-button-img" src="img/h.png" />
                </button>
                <button class="panel-button insertions-panel-button bold-panel-button" title="Bold<?=$br?>(Ctrl+B)">
                    <img class="panel-button-img" src="img/b.png" />
                </button>
                <button class="panel-button insertions-panel-button italic-panel-button" title="Italic<?=$br?>(Ctrl+I)">
                    <img class="panel-button-img" src="img/i.png" />
                </button>
                <button class="panel-button insertions-panel-button delimiter-panel-button" title="Add delimiter<?=$br?>(Ctrl+,)">
                    <img class="panel-button-img" src="img/delimiter.png" />
                </button>
                <button class="panel-button insertions-panel-button case-panel-button" title="Switch case">
                    <img class="panel-button-img" src="img/case.png" />
                </button>
                <button class="panel-button insertions-panel-button paste-img-panel-button" title="Add illustration<?=$br?>(Ctrl+L)">
                    <img class="panel-button-img" src="img/img.png" />
                </button>
            </div>
        </div>
            
        <div class="manipulations-panel panel">
            <div class="division-concatenation-edition-panel subpanel">
                <button class="panel-button division-panel-button" title="Divide paragraph<?=$br?>(Ctrl+D)" onmousedown="divideParagraph()">
                    <img class="panel-button-img" src="img/division.png" />
                </button>
                <button class="panel-button concatenation-panel-button" title="Concatenate paragraphs<?=$br?>(Ctrl+M)" onmousedown="concatenateParagraphs('')">
                    <img class="panel-button-img" src="img/concatenation.png" />
                </button>
                <button class="panel-button concatenation-panel-button" title="Concatenate paragraphs via space<?=$br?>(Ctrl+U)" onmousedown="concatenateParagraphs(' ')">
                    <img class="panel-button-img" src="img/concatenation_space.png" />
                </button>
                <button class="panel-button concatenation-panel-button" title="Concatenate paragraphs via delimiter<?=$br?>(Ctrl+Y)" onmousedown="concatenateParagraphs('<delimiter>')">
                    <img class="panel-button-img" src="img/concatenation_delimiter.png" />
                </button>
            </div>

            <div class="shift-area subpanel">
                <div class="paragraph-indexes-container">
                    <input type="text" class="paragraph-index" rows="1" size="2" placeholder="№" value="<?=@$bookmark?>" />
                    <hr />
                    <input type="text" class="paragraph-index" rows="1" size="2" placeholder="№"/>
                </div>
                <button class="panel-button shift-panel-button" title="Shift">
                    <img class="panel-button-img" src="img/shift.png" />
                </button>
                <button class="panel-button delete-by-index-panel-button" title="Delete">
                    <img class="panel-button-img" src="img/delete_by_index.png" />
                </button>
            </div>
            
            <div class="score-panel panel subpanel">
                <button class="panel-button aim-button" onclick="focusOnParagraph()" title="To current position<?=$br?>(Ctrl+O)">
                    <img class="panel-button-img" src="img/focus.png" />
                </button>
                <button class="panel-button bookmark-panel-button" title="Set bookmark<?=$br?>(Ctrl+B)">
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
                    <p class="paragraphs-count">
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
        function getParagraphsArray (side = 'left') {
            let paragraphs = document.querySelectorAll('.paragraph-' + side);
            let paragraphsArray = Array();
            
            for (let i = 0; i < paragraphs.length; i++) {
                paragraphsArray[i] = paragraphs[i].value;
            }
            
            return paragraphsArray;
        }

        // Get text, left or right
        function getText (side = 'left') {
            return getParagraphsArray(side).join('\n');
        }
        
        // Adjusting textarea height
        function fixTextArea () {
            let textareaNeeded = document.querySelectorAll('.paragraph');
            
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
                let paragraphCouplesNumber = document.querySelectorAll('.paragraph-couple').length;
                for (let i = 0; i < paragraphCouplesNumber; i++) {
                    alignSiblingTextareas(i);
                }
            } else {
                let textareaLeft = document.querySelectorAll('.paragraph-left')[index];
                let textareaRight = document.querySelectorAll('.paragraph-right')[index];
                
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
        
        document.querySelectorAll('.paragraph').forEach((paragraph) => {
            paragraph.addEventListener('input', () => {
                let paragraphIndex = getParagraphIndex(paragraph);
                
                alignSiblingTextareas(paragraphIndex);
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
        
        // Focus on a given paragraph
        function focusOnParagraph () {
            let targetParagraphNumber = document.querySelectorAll('.paragraph-index')[0].value;
            let targetParagraph = document.querySelectorAll('.paragraph-left')[targetParagraphNumber];
            if (targetParagraph) {
                targetParagraph.scrollIntoView();
            }
        }
        
        // Set bookmark
        function setBookmark () {
            let targetParagraphNumber = document.querySelectorAll('.paragraph-index')[0].value;
                if (Number.isInteger(Number(targetParagraphNumber))) {
                    let bookmarkNumber = document.querySelector('.bookmark-number');
                    bookmarkNumber.innerHTML = targetParagraphNumber;
                    
                    $.ajax({
                        url: "save_bookmark.php",
                        type: "POST",
                        data: ({bookmark_number: targetParagraphNumber}),
                        dataType: "html"
                    });
                    
                    document.querySelectorAll('.paragraph-number').forEach((item) => {
                        item.classList.remove('paragraph-number-bookmark');
                    });
                    
                    let targetLeftNumber = document.querySelectorAll('.paragraph-number-left')[targetParagraphNumber];
                    let targetRightNumber = document.querySelectorAll('.paragraph-number-right')[targetParagraphNumber];
                    [targetLeftNumber, targetRightNumber].forEach((item) => {
                        item.classList.add('paragraph-number-bookmark');
                    });
                }
        }

        function saveAllFormats () {
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
        }
        
        document.querySelector('.save-all-formats-panel-button').addEventListener('click', () => {
            saveAllFormats();
        });
        
        document.querySelector('.bookmark-panel-button').addEventListener('click', () => {
            setBookmark();
        });
        
        function setParagraphIndex (paragraphIndex, side = 'left') {
            if (side === 'left') {
                document.querySelectorAll('.paragraph-index')[0].value = paragraphIndex;
            }
            
            if (side === 'right') {
                document.querySelectorAll('.paragraph-index')[1].value = paragraphIndex;
            }
        }
        
        // Focus on bookmark
        function focusBookmark () {
            let bookmarkNumber = Number(document.querySelector('.bookmark-number').innerHTML);
            let targetParagraphCouple = document.querySelectorAll('.paragraph-couple')[bookmarkNumber];
            targetParagraphCouple.scrollIntoView();
        }
        document.querySelector('.bookmark-number').addEventListener('click', focusBookmark);
        
        // Recalculation of paragraph numbers
        function recountParagraphNumbers () {
            let leftParagraphNumbers = document.querySelectorAll('.paragraph-number-left');
            let rightParagraphNumbers = document.querySelectorAll('.paragraph-number-right');
            
            for (let i = 0; i < $('.paragraph-couple').length; i++) {
                leftParagraphNumbers[i].innerText = i;
                rightParagraphNumbers[i].innerText = i;
            }
            
            showParagraphsCount();
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
            if (document.querySelectorAll('.paragraph-couple').length === 0) {
                return false;
            }
            
            let paragraphsArrayLeft = getParagraphsArray('left').join('\n');
            let paragraphsArrayRight = getParagraphsArray('right').join('\n');
            
            $.ajax({
                url: "save_sources.php",
                type: "POST",
                data: ({address1: sessionStorage.getItem('book1-file-address'),
                        address2: sessionStorage.getItem('book2-file-address'),
                        text1: paragraphsArrayLeft,
                        text2: paragraphsArrayRight
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
        function showParagraphsCount () {
            let paragraphsCountArea = document.querySelector('.paragraphs-count');
            let paragraphsArrayLeft = getParagraphsArray('left');
            let paragraphsArrayRight = getParagraphsArray('right');
            let maxParagraphsCount = Math.max(paragraphsArrayLeft.length, paragraphsArrayRight.length);
            
            if (maxParagraphsCount === 0) {
                paragraphsCountArea.innerText = '-';
            } else {
                paragraphsCountArea.innerText = maxParagraphsCount - 1;
            }
        }
        
        showParagraphsCount();

        // Class for the selected paragraph
        class focusedParagraph {
            constructor(paragraph, start = 0, end = 0) {
                this._paragraph = paragraph;
                this._start = start;
                this._end = end;
            }
            
            get paragraph() {
                return this._paragraph;
            }
            
            get start() {
                return this._start;
            }
            
            get end() {
                return this._end;
            }
            
            set paragraph(value) {
                this._paragraph = value;
            }
            
            set start(value) {
                this._start = value;
            }
            
            set end(value) {
                this._end = value;
            }
        }
        
        let activeParagraph = new focusedParagraph();
        
        // Get image number
        function getImgNumber () {
            let targetParagraph = document.activeElement;
            let joinedText = '';
            
            if (targetParagraph.classList.contains('paragraph-left')) {
                joinedText = getParagraphsArray('left').join('');
            } else if (targetParagraph.classList.contains('paragraph-right')) {
                joinedText = getParagraphsArray('right').join('');
            }
            
            return joinedText.split('<img').length;
        }
        
        // Inserting text to paragraph instead of selected text 
        function insertTextToParagraph (text, paragraph, isInserted = true) {
            
            if (paragraph.classList.contains('paragraph') && text !== '') {
                let start = paragraph.selectionStart;
                let end = isInserted ? paragraph.selectionEnd : start;
                let paragraphValue = paragraph.value;
                
                let part1 = paragraphValue.substring(0, start);
                let part3 = paragraphValue.substring(end);
                
                paragraph.value = part1 + text + part3;
                
                activeParagraph.paragraph = paragraph;
                activeParagraph.start = isInserted ? start : start + text.length;
                activeParagraph.end = start + text.length;
                
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
            
            if (document.activeElement.classList.contains('paragraph')) {
                let paragraph = document.activeElement;
                let start = paragraph.selectionStart;
                let end = paragraph.selectionEnd;
                let paragraphContent = paragraph.value;
                return paragraphContent.substring(start, end);
            } else {
                return false;
            }
        }
        
        document.querySelector('.delimiter-panel-button').addEventListener('mousedown', () => {
            insertTextToParagraph('<delimiter>', document.activeElement, false);
        });
        
        document.querySelector('.paste-img-panel-button').addEventListener('mousedown', () => {
            insertTextToParagraph('<img' + getImgNumber(document.activeElement) + '>', document.activeElement, false);
        });
        
        document.querySelector('.case-panel-button').addEventListener('mousedown', () => {
            insertTextToParagraph(switchCase(getSelectedText()), document.activeElement);
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        });
        
        // Add tags       
        function addTagsToParagraph (tag1, tag2, paragraph) {
            
            if (paragraph.classList.contains('paragraph')) {
                let start = paragraph.selectionStart;
                let end = paragraph.selectionEnd;
                
                if (start !== end) {
                    let paragraphValue = paragraph.value;
                    let taggedParagraphValue = paragraphValue.substring(0, start) + tag1;
                    taggedParagraphValue += paragraphValue.substring(start, end) + tag2;
                    taggedParagraphValue += paragraphValue.substring(end);
                    paragraph.value = taggedParagraphValue;
                    
                    activeParagraph.paragraph = paragraph;
                    activeParagraph.start = end + tag1.length + tag2.length;
                    activeParagraph.end = end + tag1.length + tag2.length;
                } else {
                    return false;
                }
            }
            
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelector('.bold-panel-button').addEventListener('mousedown', () => {
            addTagsToParagraph('<b>', '</b>', document.activeElement);
        });
        
        document.querySelector('.italic-panel-button').addEventListener('mousedown', () => {
            addTagsToParagraph('<i>', '</i>', document.activeElement);
        });

        document.querySelector('.heading-panel-button').addEventListener('mousedown', () => {
            addTagsToParagraph('<h1>', '</h1>', document.activeElement);
        });
        
        // For all insertions buttons
        document.querySelectorAll('.insertions-panel-button').forEach((button) => {
            button.addEventListener('mouseup', () => {
                activeParagraph.paragraph.focus();
                activeParagraph.paragraph.setSelectionRange(activeParagraph.start, activeParagraph.end);
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
        function getParagraphSide (paragraph) {
            if (paragraph.classList.contains('paragraph-left')) {
                return 'left';
            }
            if (paragraph.classList.contains('paragraph-right')) {
                return 'right';
            }
            return false;
        }
        
        // Get paragraph index
        function getParagraphIndex (paragraph) {
            let paragraphSide = getParagraphSide(paragraph);
            let paragraphsArray = document.querySelectorAll('.paragraph-' + paragraphSide);
            
            for (let i = 0; i < paragraphsArray.length; i++) {
                if (paragraphsArray[i] === paragraph) {
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
        function appendTerminalEmptyParagraphs () {
            let clonedParagraphCouple = document.querySelectorAll('.paragraph-couple')[0].cloneNode(true);
            let leftParagraph = findChildByClass(findChildByClass(clonedParagraphCouple, 'left'), 'paragraph-left');
            let rightParagraph = findChildByClass(findChildByClass(clonedParagraphCouple, 'right'), 'paragraph-right');
            leftParagraph.value = '';
            rightParagraph.value = '';
            
            document.querySelector('.main-area').append(clonedParagraphCouple);
            document.querySelector('.main-area').append(clonedParagraphCouple);
            recountParagraphNumbers();
        }
        
        // Delete empty paragraphs in the end
        function deleteTerminalEmptyParagraphs () {
            let paragraphCouples = document.querySelectorAll('.paragraph-couple');
            let leftParagraphs = document.querySelectorAll('.paragraph-left');
            let rightParagraphs = document.querySelectorAll('.paragraph-right');
            
            let index = paragraphCouples.length - 1;
            
            while (leftParagraphs[index].value.trim() === '' && rightParagraphs[index].value.trim() === '') {
                paragraphCouples[index].remove();
                index--;
            }
            
        }
        
        // Add paragraph
        function addNewParagraph (addButton, deleteTerminal = true) {
            appendTerminalEmptyParagraphs();
                    
            let buttonIndex = getElementIndex(addButton, '.add-paragraph-button');
            let side = buttonIndex % 2 === 0 ? 'left' : 'right';
            let paragraphIndex = side === 'left' ? buttonIndex / 2 : (buttonIndex - 1) / 2;
            
            let targetParagraphsArray = document.querySelectorAll('.paragraph-' + side);
            
            for (let i = targetParagraphsArray.length - 1; i > paragraphIndex; i--) {
                targetParagraphsArray[i].value = targetParagraphsArray[i - 1].value;
            }
            targetParagraphsArray[paragraphIndex].value = '';
            
            fixTextArea();
            alignSiblingTextareas();
            
            if (deleteTerminal) {
                deleteTerminalEmptyParagraphs();
            }
            
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        // Delete paragraph
        function deleteParagraph (deleteButton, deleteTerminal = true) {
            let buttonIndex = getElementIndex(deleteButton, '.delete-paragraph-button');
            let side = buttonIndex % 2 === 0 ? 'left' : 'right';
            let paragraphIndex = side === 'left' ? buttonIndex / 2 : (buttonIndex - 1) / 2;
            
            let targetParagraphsArray = document.querySelectorAll('.paragraph-' + side);
            
            for (let i = paragraphIndex; i < targetParagraphsArray.length - 1; i++) {
                targetParagraphsArray[i].value = targetParagraphsArray[i + 1].value;
            }
            
            targetParagraphsArray[targetParagraphsArray.length - 1].value = '';
            
            fixTextArea();
            alignSiblingTextareas();
            
            if (deleteTerminal) {
                deleteTerminalEmptyParagraphs();
            }
            
            showParagraphsCount();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        // Concatenate paragraphs
        function concatenateParagraphs (glue='') {
            let targetParagraph = document.activeElement;
            if (!targetParagraph.classList.contains('paragraph')) {
                return false;
            }
            
            let paragraphSide = getParagraphSide(targetParagraph);
            let paragraphIndex = getParagraphIndex(targetParagraph);
            
            let cancatenatedParagraph = document.querySelectorAll('.paragraph-' + paragraphSide)[paragraphIndex + 1]
            
            targetParagraph.value += glue + cancatenatedParagraph.value;
            
            let deleteButtonIndex;
            
            if (paragraphSide === 'left') {
                deleteButtonIndex = (paragraphIndex + 1) * 2;
            } else if (paragraphSide === 'right') {
                deleteButtonIndex = (paragraphIndex + 1) * 2 + 1;
            }
            let deleteButton = document.querySelectorAll('.delete-paragraph-button')[deleteButtonIndex];
            
            deleteButton.click();
            activeParagraph.paragraph = targetParagraph;
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelectorAll('.concatenation-panel-button').forEach((button) => {
            button.addEventListener('mouseup', () => {
                activeParagraph.paragraph.focus();
            });
        });
        
        // Divide paragraphs
        function divideParagraph () {
            let targetParagraph = document.activeElement;
            if (!targetParagraph.classList.contains('paragraph')) {
                return false;
            }
            
            let paragraphSide = getParagraphSide(targetParagraph);
            let paragraphIndex = getParagraphIndex(targetParagraph);
            
            let targetParagraphsArray = document.querySelectorAll('.paragraph-' + paragraphSide);
            if (paragraphIndex === targetParagraphsArray.length - 1) {
                appendTerminalEmptyParagraphs();
            }
            
            let start = targetParagraph.selectionStart;
            let end = targetParagraph.selectionEnd;
            
            if (start != end) {
                return false;
            }
            
            substr1 = targetParagraph.value.substring(0, start).trim();
            substr2 = targetParagraph.value.substr(start).trim();
            
            let addButtonIndex;
            if (paragraphSide === 'left') {
                addButtonIndex = (paragraphIndex + 1) * 2;
            } else if (paragraphSide === 'right') {
                addButtonIndex = (paragraphIndex + 1) * 2 + 1;
            }
            let addButton = document.querySelectorAll('.add-paragraph-button')[addButtonIndex];
            
            addNewParagraph(addButton, false);
            targetParagraph.value = substr1;
            document.querySelectorAll('.paragraph-' + paragraphSide)[paragraphIndex + 1].value = substr2;
            deleteTerminalEmptyParagraphs();
            
            activeParagraph.paragraph = targetParagraph;
            
            fixTextArea();
            alignSiblingTextareas();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        // Delete paragraphs by indexes
        function deleteParagraphsByIndexes (index1, index2) {
            
            let minIndex = Math.min(index1, index2);
            let maxIndex = Math.max(index1, index2);
            
            for (let i = maxIndex; i >= minIndex; i--) {
                document.querySelectorAll('.paragraph-couple')[i].remove();
            }
            
            recountParagraphNumbers();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelector('.delete-by-index-panel-button').addEventListener('click', () => {
            let index1 = document.querySelectorAll('.paragraph-index')[0].value;
            let index2 = document.querySelectorAll('.paragraph-index')[1].value;
            deleteParagraphsByIndexes(index1, index2);
        });
        
        // Shift paragraphs by indexes
        function shiftParagraphs (index1, index2) {
            if (!index1 || !index2 || index1 === index2) {
                return false;
            }
            
            let paragraphCouplesCount = document.querySelectorAll('.paragraph-couple').length;
            if (index1 >= paragraphCouplesCount || index2 >= paragraphCouplesCount) {
                return false;
            }
            
            let minIndex = Math.min(index1, index2);
            let maxIndex = Math.max(index1, index2);
                    
            let side = minIndex == index1 ? 'left' : 'right';
            let difference = maxIndex - minIndex;
            for (let i = 0; i < difference; i++) {
                appendTerminalEmptyParagraphs();
            }
            
            let targetParagraphsArray = document.querySelectorAll('.paragraph-' + side);
            
            for (let i = targetParagraphsArray.length - 1; i >= maxIndex; i--) {
                targetParagraphsArray[i].value = targetParagraphsArray[i - difference].value;
            }
            
            for (let i = maxIndex - 1; i >= minIndex; i--) {
                targetParagraphsArray[i].value = '';
            }
            
            fixTextArea();
            alignSiblingTextareas();
            document.querySelectorAll('.panel-button').forEach((button) => {
                button.classList.remove('save-not-needed');
            });
        }
        
        document.querySelector('.shift-panel-button').addEventListener('click', () => {
            let index1 = document.querySelectorAll('.paragraph-index')[0].value;
            let index2 = document.querySelectorAll('.paragraph-index')[1].value;
            shiftParagraphs(index1, index2);
        });

        // Keyboard shortcuts
        // Save source texts (Ctrl+S)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 83) {
                event.preventDefault();
                saveSources();
            }
        });

        // Divide paragraph (Ctrl+D)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 68) {
                event.preventDefault();
                divideParagraph();
            }
        });
        
        // Set bookmark (Ctrl+B)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 66) {
                if (document.activeElement.classList.contains('paragraph')) {
                    addTagsToParagraph('<b>', '</b>', document.activeElement);
                } else {
                    event.preventDefault();
                    setBookmark();
                }
            }
        });

        // Title (Ctrl+H)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 72) {
                if (document.activeElement.classList.contains('paragraph')) {
                    event.preventDefault();
                    addTagsToParagraph('<h1>', '</h1>', document.activeElement);
                }
            }
        });

        // Italic (Ctrl+I)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 73) {
                if (document.activeElement.classList.contains('paragraph')) {
                    event.preventDefault();
                    addTagsToParagraph('<i>', '</i>', document.activeElement);
                }
            }
        });
        
        // Concatenate paragraphs (Ctrl+M)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 77) {
                event.preventDefault();
                concatenateParagraphs('');
            }
        });
        
        // Concatenate paragraphs via delimiter (Ctrl+Y)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 89) {
                event.preventDefault();
                concatenateParagraphs('<delimiter>');
            }
        });
        
        // Concatenate paragraphs via space(Ctrl+U)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 85) {
                event.preventDefault();
                concatenateParagraphs(' ');
            }
        });
        
        // Add delimiter (Ctrl+,)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 188) {
                event.preventDefault();
                insertTextToParagraph('<delimiter>', document.activeElement, false);
            }
        });
        
        // Add illustration (Ctrl+L)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 76) {
                event.preventDefault();
                insertTextToParagraph('<img' + getImgNumber(document.activeElement) + '>', document.activeElement, false);
            }
        });
        
        // To current position (Enter)
        document.addEventListener('keydown', function(event) {
            if (event.keyCode === 13 && document.activeElement.classList.contains('paragraph-index')) {
                event.preventDefault();
                focusOnParagraph();
            }
        });

        // To current position (Ctrl+O)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 79) {
                event.preventDefault();
                focusOnParagraph();
            }
        });
        
        // Print PDF columns (Ctrl+P) and rows (Ctrl+Shift+P)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.keyCode === 80) {
                event.preventDefault();
                if (event.shiftKey) {
                    printRows();
                } else {
                    printCols();
                }
            }
        });
    </script>
</body>
</html>