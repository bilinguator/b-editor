![Interface of B-Editor](img/for_readme/interface.png)
# B-Editor

**B-Editor** is the browser based tool for alignment texts in two different languages. 

Developed by [Bilinguator.com](https://bilinguator.com/).

|**Contents**|
|---|
|[Getting started](#getting-started)|
|[Input texts requirements](#input-texts-requirements)|
|[Loading files](#loading-files)|
|[Manipulation of paragraphs](#manipulation-of-paragraphs)|
|[Editing paragraphs contents](#editing-paragraphs-contents)|
|[Adding illustrations](#adding-illustrations)|
|[Saving files](#saving-files)|
|[Keyboard shortcuts](#keyboard-shortcuts)|
|[Precautions](#precautions)|

## Getting started

The **B-Editor** aligner has been developed and tested in Google Chrome, Yandex Browser, Edge, FireFox browsers. No third-party libraries or frameworks were used during development, except [JQuery](https://jquery.com/) 3.6.0. Our stack consists of HTML, CSS, JavaScript, PHP. We used XAMPP with PHP version 7.4.29 for development.

Put the repository contents to your web server and open [index.php](index.php) file in browser.

## Input texts requirements

Texts must meet the requirements of the [specification](https://github.com/dmitrii-snitkin/aligned-texts#aligned-text-files-specification).

The following scheme of naming your files is highly recommended:

`<BOOK_ID>_<LANG>.txt`, where

* `<BOOK_ID>` is a unique identifier of your book;
* `<LANG>` - ISO code of the language. This part of the file name determines the size and direction of the texts (left-to-right or right-to-left).  

## Loading files

Put your files to be aligned in the [`books`](books/) directory. Launch B-Editor, choose two files in the header and press the `Launch` button (“Play” button-like triangle <img src="img/play.png" alt="Launch button" height="25"/>) in the top right corner. Then, the paragraphs of the two chosen texts are uploaded.

![Header of B-Editor](img/for_readme/loading_files.png)

## Manipulation of paragraphs

### Adding new empty paragraph 

Each paragraph has the plus button (<img src="img/add.png" alt="Add button" height="15"/>) on its side. Clicking it, you can add a new empty paragraph to the current place. Current paragraph and all below will be shifted down.

### Deleting current paragraph

To delete a paragraph, click the diagonal cross (<img src="img/delete.png" alt="Delete button" height="15"/>) on its side. All the paragraphs below will be shifted up.

### Paragraphs’ indexes

Each paragraph is marked with an index. When clicking an index of a left paragraph, it appears in the upper textarea of toolbar; click a right paragraph’s index, it to appear in the lower textarea (<img src="img/for_readme/textareas.png" alt="Delete button" height="30"/>). It is also possible to change the textareas’ values manually. These indexes are useful in the following manipulations.

### Setting bookmark

The bookmark is one index corresponding to a paragraphs couple. To set a bookmark, click to the index of the left paragraph of the current couple or type it to the upper textarea in the toolbar. Click the Bookmark button (<img src="img/bookmark.png" alt="Bookmark button" height="25"/>) to set the bookmark. The bookmark index is saved to the [`books/bookmarks`](books/bookmarks) folder named as `bookmark_<BOOK_ID>_<LANG1>_<LANG2>.<BOOK1_EXTENSION>`. While the file exists, the bookmark is set all the time of using B-Editor, and it is displayed on the right side of the toolbar as `bookmark`/`total paragraphs count` ratio (<img src="img/for_readme/ratio.png" alt="Progress ratio" height="25"/>).

### Moving focus to a paragraph

Click the `bookmark` part of the `bookmark`/`total paragraphs count` ratio to move to the bookmarked paragraph.

To move to any other paragraph, type the index of the paragraph in the upper textarea and click the `To current position` button (<img src="img/focus.png" alt="To current position button" height="25px"/>). Look at the progress bar to orient (<img src="img/for_readme/progress.png" alt="Progress bar" height="25"/>).

### Shifting paragraphs

To shift two paragraphs of different sides to make them adjacent, choose them and click the `Shift` button (<img src="img/shift.png" alt="Shift button" height="25"/>).

### Deleting paragraph couples by indexes

To delete several paragraphs on both sides, enter the  ‘from’ and ‘to’ indexes to the textareas. Click the `Delete` button (<img src="img/delete_by_index.png" alt="Delete button" height="25"/>). Paragraph couples starting from the minimal entered index to the maximal index will be deleted. All the paragraphs below will be shifted up.

### Dividing paragraphs

To divide a paragraph into two, click the position in its content wherein the paragraph must be divided. Press the `Divide paragraph` button (<img src="img/division.png" alt="Divide paragraph button" height="25"/>).

To divide a paragraph into several paragraphs, add newline symbols, wherein the paragraph must be divided. Press the `Divide paragraph by newline` button (<img src="img/division_by_newline.png" alt="Divide paragraph button" height="25"/>).

### Concatenating paragraphs

To concatenate two following paragraphs of one side, click on the first of it in any place. Press the `Concatenate paragraphs` button (<img src="img/concatenation.png" alt="Concatenate paragraphs button" height="25"/>). The two paragraphs will be merged via empty string. To concatenate two paragraphs via space character or `<delimiter>` press `Concatenate paragraphs via space` (<img src="img/concatenation_space.png" alt="Concatenate paragraphs via space button" height="25"/>) or `Concatenate paragraphs via delimiter` (<img src="img/concatenation_delimiter.png" alt="Concatenate paragraphs via delimiter button" height="25"/>) button respectively.

## Editing paragraphs contents

### Adding tags

Select text in a paragraph. Press `Title` (<img src="img/h.png" alt="Title button" height="25"/>), `Bold` (<img src="img/b.png" alt="Bold button" height="25"/>) or `Italic` (<img src="img/i.png" alt="Italic button" height="25"/>) button to tag the selected text with `<h1></h1>`, `<b></b>` or `<i></i>` respectively.

### Adding delimiter

Click on the position in a paragraph. Press the `Add delimiter` button (<img src="img/delimiter.png" alt="Add delimiter button" height="25"/>).

### Switching case

Select a text and press the `Switch case` (<img src="img/case.png" alt="Switch case" height="25"/>) to change the case from upper to lower and vice versa.

## Adding illustrations

Put your PNG illustrations to the [`books/illustrations`](books/illustrations) folder. Name them as natural arabiс numbers starting from 1 like [here](https://github.com/bilinguator/bilingual-formats/tree/main/tests/img).

To add a new illustration to the book, create a new empty paragraph, click on it and press `Add illustration` button (<img src="img/img.png" alt="Add illustration button" height="25"/>). The `<imgℕ>` tag will be inserted, where ℕ is the natural Arabic number. The `<img1>` corresponds to the `books/illustrations/1.png` file.

## Saving files

### Saving source texts

To save two separate source files aligned, click the `Save source files` button (<img src="img/save.png" alt="Save source files" height="25"/>) or press `Ctrl`+`S` keys.

### Saving bilingual TXT, FB2 and EPUB books

Clone the git repository of [Bilingual formats](https://github.com/bilinguator/bilingual-formats/) to the root directory of B-Editor. After the next launch of B-Editor, new buttons will appear:

|Button|Action|
|--|---|
|<img src="img/save_txt.png" alt="Save txt" height="25"/>|Save TXT|
|<img src="img/save_fb2.png" alt="Save fb2" height="25"/>|Save FB2|
|<img src="img/save_epub.png" alt="Save epub" height="25"/>|Save EPUB|

All the books are saved to the [`books/saved`](books/saved) folder.

### Printing bilingual PDF

Clone the git repository of [Print Bilingual PDF](https://github.com/bilinguator/print-bilingual-pdf) to the root directory of B-Editor. After the next launch of B-Editor, new buttons will appear:

|Button|Action|
|--|---|
|<img src="img/cols.png" alt="Save txt" height="25"/>|Print columns PDF|
|<img src="img/rows.png" alt="Save fb2" height="25"/>|Print rows PDF|

If both [Bilingual formats](https://github.com/bilinguator/bilingual-formats/) and [Print Bilingual PDF](https://github.com/bilinguator/print-bilingual-pdf) are in the B-Editor root directory, the `Save in all formats` button (<img src="img/save_all_formats.png" alt="Save in all formats" height="25"/>) appears. Press it to save your bilingual book in TXT, FB2, EPUB formats and print the PDF in columns and rows.

## Keyboard shortcuts

|Keys|Button|Action|
|--|--|--|
|`Ctrl`+`S`|<img src="img/save.png" alt="Save source files" height="25"/>|Save source files|
|`Ctrl`+`P`|<img src="img/cols.png" alt="Save txt" height="25"/>|Print columns PDF|
|`Ctrl`+`Shift`+`P`|<img src="img/rows.png" alt="Save fb2" height="25"/>|Print rows PDF|
|`Ctrl`+`H`|<img src="img/h.png" alt="Title button" height="25"/>|Title|
|`Ctrl`+`B`|<img src="img/b.png" alt="Bold button" height="25"/>|Bold (when text in paragraph selected)|
|`Ctrl`+`I`|<img src="img/i.png" alt="Italic button" height="25"/>|Italic|
|`Ctrl`+`,`|<img src="img/delimiter.png" alt="Add delimiter button" height="25"/>|Add delimiter|
|`Ctrl`+`L`|<img src="img/img.png" alt="Add illustration button" height="25"/>|Add illustration|
|`Ctrl`+`D`|<img src="img/division.png" alt="Divide paragraph button" height="25"/>|Divide paragraph|
|`Ctrl`+ `Shift` + `D`|<img src="img/division_by_newline.png" alt="Divide paragraph button" height="25"/>|Divide paragraph by newlines|
|`Ctrl`+`M`|<img src="img/concatenation.png" alt="Concatenate paragraphs button" height="25"/>|Concatenate paragraphs|
|`Ctrl`+`U`|<img src="img/concatenation_space.png" alt="Concatenate paragraphs via space button" height="25"/>|Concatenate paragraphs via space|
|`Ctrl`+`Y`|<img src="img/concatenation_delimiter.png" alt="Concatenate paragraphs via delimiter button" height="25"/>|Concatenate paragraphs via delimiter|
|`Ctrl`+`O`|<img src="img/focus.png" alt="To current position button" height="25px"/>|To current position|
|`Ctrl`+`B`|<img src="img/bookmark.png" alt="Bookmark button" height="25"/>|Set bookmark (when no text in paragraph selected)|

## Precautions

B-Editor does not suit for working with big texts. When working with texts with more than a thousand paragraphs, the waiting time for some operations can be quite long.