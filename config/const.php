<?php

define("__CATEGORY_CODE", "Books");

//書籍タイプ
define("__BOOK_ITEM_TYPE_DEFAULT", 1);
define("__BOOK_ITEM_TYPE_EBOOK", 2);
define("__LABEL_BOOK_ITEM_TYPE_DEFAULT", "一般");
define("__LABEL_BOOK_ITEM_TYPE_EBOOK", "電子書籍");

//ステータス
define("__STATUS_NONE", 0);
define("__STATUS_WANT", 1);
define("__STATUS_NOW", 2);
define("__STATUS_FINISH", 3);
define("__STATUS_WAIT", 4);
define("__LABEL_STATUS_NONE", "未設定");
define("__LABEL_STATUS_WANT", "読みたい");
define("__LABEL_STATUS_NOW", "いま読んでる");
define("__LABEL_STATUS_FINISH", "読み終わった");
define("__LABEL_STATUS_WAIT", "積読");

//タスクステータス
define("__TASK_STATUS_DEFAULT", 1);
define("__TASK_STATUS_COMPLETE", 2);
define("__TASK_STATUS_RENTAL", 3);
define("__TASK_STATUS_WAITING", 4);
define("__LABEL_TASK_STATUS_DEFAULT", "未購入");
define("__LABEL_TASK_STATUS_COMPLETE", "購入");
define("__LABEL_TASK_STATUS_RENTAL", "レンタル");
define("__LABEL_TASK_STATUS_WAITING", "配送待ち");

//リクエストステータス
define("__REQUEST_STATUS_WAITING", 1);
define("__REQUEST_STATUS_UNDONE", 2);
define("__REQUEST_STATUS_DONE", 3);
define("__LABEL_REQUEST_STATUS_WAITING", "蔵書待ち");
define("__LABEL_REQUEST_STATUS_UNDONE", "リクエストOK");
define("__LABEL_REQUEST_STATUS_DONE", "リクエスト済");

define("__IMPORT_FLAG_DONE", 1);
define("__IMPORT_FLAG_UNDONE", 2);
define("__LABEL_IMPORT_FLAG_DONE", "取込済");
define("__LABEL_IMPORT_FLAG_UNDONE", "未取込");
