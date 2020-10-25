<?php

if (defined('FOR_XOOPS_LANG_CHECKER')) {
    $mydirname = 'wraps';
}
$constpref = '_MI_' . mb_strtoupper($mydirname);

if (defined('FOR_XOOPS_LANG_CHECKER') || !defined($constpref . '_LOADED')) {
    // a flag for this language file has already been read or not.

    define($constpref . '_LOADED', 1);

    define($constpref . '_MODULE_DESCRIPTION', 'A little module for wrapping');

    // admin menu

    define($constpref . '_UPDATE_SEARCH_INDEX', 'Update indexes for searching');

    define($constpref . '_ADMENU_MYLANGADMIN', 'Languages');

    define($constpref . '_ADMENU_MYTPLSADMIN', 'Templates');

    define($constpref . '_ADMENU_MYBLOCKSADMIN', 'Permissions');

    define($constpref . '_ADMENU_MYPREFERENCES', 'Preferences');

    // configs

    define($constpref . '_INDEX_FILE', 'Top page');

    define($constpref . '_INDEX_FILEDSC', 'specify the file should be wrapped when the top of this module is accessed');

    define($constpref . '_INDEXAUTOUPD', 'Update indexes automatically (touch the base directory)');

    define($constpref . '_INDEXLASTUPD', 'Indexes last updated (timestamp)');

    define($constpref . '_BRCACHE', 'Cache life time for image files');

    define($constpref . '_BRCACHEDSC', 'Files other than HTML will be cached by web browser in this second (0 means disabled)');
}
