<?php

if (defined('FOR_XOOPS_LANG_CHECKER')) {
    $mydirname = 'wraps';
}
$constpref = '_MI_' . mb_strtoupper($mydirname);

if (defined('FOR_XOOPS_LANG_CHECKER') || !defined($constpref . '_LOADED')) {
    // a flag for this language file has already been read or not.

    define($constpref . '_LOADED', 1);

    define($constpref . '_MODULE_DESCRIPTION', 'ページラップ専用モジュール');

    // admin menu

    define($constpref . '_UPDATE_SEARCH_INDEX', '検索用インデックスの更新');

    define($constpref . '_ADMENU_MYLANGADMIN', '言語定数管理');

    define($constpref . '_ADMENU_MYTPLSADMIN', 'テンプレート管理');

    define($constpref . '_ADMENU_MYBLOCKSADMIN', 'アクセス権限');

    define($constpref . '_ADMENU_MYPREFERENCES', '一般設定');

    // configs

    define($constpref . '_INDEX_FILE', 'トップページ');

    define($constpref . '_INDEX_FILEDSC', 'モジュールトップにアクセスされた時にラップするファイルを指定します');

    define($constpref . '_INDEXAUTOUPD', '検索インデックスの自動更新');

    define($constpref . '_INDEXLASTUPD', '検索インデックスの最終更新日時');

    define($constpref . '_BRCACHE', '画像ファイルのブラウザキャッシュ');

    define($constpref . '_BRCACHEDSC', 'HTML以外のファイルをブラウザにキャッシュする時間を秒で指定（0で無効化）');
}
