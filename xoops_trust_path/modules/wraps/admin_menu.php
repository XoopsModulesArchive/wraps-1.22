<?php

$constpref = '_MI_' . mb_strtoupper($mydirname);

$adminmenu = [
    [
        'title' => constant($constpref . '_UPDATE_SEARCH_INDEX'),
        'link' => 'admin/index.php?page=index' ,
    ] ,
];

$adminmenu4altsys = [
    [
        'title' => constant($constpref . '_ADMENU_MYLANGADMIN'),
        'link' => 'admin/index.php?mode=admin&lib=altsys&page=mylangadmin' ,
    ] ,
    [
        'title' => constant($constpref . '_ADMENU_MYTPLSADMIN'),
        'link' => 'admin/index.php?mode=admin&lib=altsys&page=mytplsadmin' ,
    ] ,
    [
        'title' => constant($constpref . '_ADMENU_MYBLOCKSADMIN'),
        'link' => 'admin/index.php?mode=admin&lib=altsys&page=myblocksadmin' ,
    ] ,
    [
        'title' => constant($constpref . '_ADMENU_MYPREFERENCES'),
        'link' => 'admin/index.php?mode=admin&lib=altsys&page=mypreferences' ,
    ] ,
];
