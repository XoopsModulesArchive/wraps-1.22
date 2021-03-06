<?php

$mytrustdirname = basename(__DIR__);
$mytrustdirpath = __DIR__;

// environment
require_once XOOPS_ROOT_PATH . '/class/template.php';
$moduleHandler = xoops_getHandler('module');
$xoopsModule = $moduleHandler->getByDirname($mydirname);
$configHandler = xoops_getHandler('config');
$xoopsModuleConfig = $configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

// check permission of 'module_admin' of this module
$modulepermHandler = xoops_getHandler('groupperm');
if (!is_object(@$xoopsUser) || !$modulepermHandler->checkRight('module_admin', $xoopsModule->getVar('mid'), $xoopsUser->getGroups())) {
    die('only admin can access this area');
}

$xoopsOption['pagetype'] = 'admin';
require XOOPS_ROOT_PATH . '/include/cp_functions.php';

// initialize language manager
$langmanpath = XOOPS_TRUST_PATH . '/libs/altsys/class/D3LanguageManager.class.php';
if (!file_exists($langmanpath)) {
    die('install the latest altsys');
}
require_once($langmanpath);
$langman = D3LanguageManager::getInstance();

if (!empty($_GET['lib'])) {
    // common libs (eg. altsys)

    $lib = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['lib']);

    $page = preg_replace('/[^a-zA-Z0-9_-]/', '', @$_GET['page']);

    // check the page can be accessed (make controllers.php just under the lib)

    $controllers = [];

    if (file_exists(XOOPS_TRUST_PATH . '/libs/' . $lib . '/controllers.php')) {
        require XOOPS_TRUST_PATH . '/libs/' . $lib . '/controllers.php';

        if (!in_array($page, $controllers, true)) {
            $page = $controllers[0];
        }
    }

    if (file_exists(XOOPS_TRUST_PATH . '/libs/' . $lib . '/' . $page . '.php')) {
        include XOOPS_TRUST_PATH . '/libs/' . $lib . '/' . $page . '.php';
    } elseif (file_exists(XOOPS_TRUST_PATH . '/libs/' . $lib . '/index.php')) {
        include XOOPS_TRUST_PATH . '/libs/' . $lib . '/index.php';
    } else {
        die('wrong request');
    }
} else {
    // load language files (main.php & admin.php)

    $langman->read('admin.php', $mydirname, $mytrustdirname);

    $langman->read('main.php', $mydirname, $mytrustdirname);

    // fork each pages of this module

    $page = preg_replace('/[^a-zA-Z0-9_-]/', '', @$_GET['page']);

    if (file_exists("$mytrustdirpath/admin/$page.php")) {
        include "$mytrustdirpath/admin/$page.php";
    } elseif (file_exists("$mytrustdirpath/admin/index.php")) {
        include "$mytrustdirpath/admin/index.php";
    } else {
        die('wrong request');
    }
}
