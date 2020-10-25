<?php

// language file (modinfo.php)
$langmanpath = XOOPS_TRUST_PATH . '/libs/altsys/class/D3LanguageManager.class.php';
if (!file_exists($langmanpath)) {
    die('install the latest altsys');
}
require_once($langmanpath);
$langman = D3LanguageManager::getInstance();
$langman->read('modinfo.php', $mydirname, $mytrustdirname, false);

$constpref = '_MI_' . mb_strtoupper($mydirname);

$modversion['name'] = $mydirname;
$modversion['version'] = 1.22;
$modversion['description'] = constant($constpref . '_MODULE_DESCRIPTION');
$modversion['credits'] = 'PEAK Corp.';
$modversion['author'] = 'GIJ=CHECKMATE<br>PEAK Corp.(http://www.peak.ne.jp/)';
$modversion['help'] = '';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = file_exists($mydirpath . '/module_icon.png') ? 'module_icon.png' : 'module_icon.php';
$modversion['dirname'] = $mydirname;

// Any tables can't be touched by modulesadmin.
$modversion['sqlfile'] = false;
$modversion['tables'] = [];

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/admin_menu.php';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'search.php';
$modversion['search']['func'] = $mydirname . '_global_search';

// Menu
$modversion['hasMain'] = 1;

// There are no submenu (use menu moudle instead of mainmenu)
$modversion['sub'] = [];

// All Templates can't be touched by modulesadmin.
$modversion['templates'] = [];

// Blocks
$modversion['blocks'] = [];

// Comments
$modversion['hasComments'] = 0;

// Configs
$modversion['config'][1] = [
    'name' => 'index_file' ,
    'title' => $constpref . '_INDEX_FILE' ,
    'description' => $constpref . '_INDEX_FILEDSC' ,
    'formtype' => 'textbox' ,
    'valuetype' => 'text' ,
    'default' => 'index.html' ,
    'options' => [],
];
$modversion['config'][] = [
    'name' => 'index_auto_updated' ,
    'title' => $constpref . '_INDEXAUTOUPD' ,
    'description' => '' ,
    'formtype' => 'yesno' ,
    'valuetype' => 'int' ,
    'default' => 1 ,
    'options' => [],
];
$modversion['config'][] = [
    'name' => 'index_last_updated' ,
    'title' => $constpref . '_INDEXLASTUPD' ,
    'description' => '' ,
    'formtype' => 'textbox' ,
    'valuetype' => 'int' ,
    'default' => 0 ,
    'options' => [],
];
$modversion['config'][] = [
    'name' => 'browser_cache' ,
    'title' => $constpref . '_BRCACHE' ,
    'description' => $constpref . '_BRCACHEDSC' ,
    'formtype' => 'textbox' ,
    'valuetype' => 'int' ,
    'default' => 3600 ,
    'options' => [],
];

// Notification
$modversion['hasNotification'] = 0;

$modversion['onInstall'] = 'oninstall.php';
$modversion['onUpdate'] = 'onupdate.php';
$modversion['onUninstall'] = 'onuninstall.php';
