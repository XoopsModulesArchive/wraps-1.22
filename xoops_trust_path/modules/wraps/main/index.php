<?php

define('WRAPS_DISALLOW_CHARS', '?[^a-zA-Z0-9_./+-]?');

if (!empty($_GET['path_info'])) {
    // path_info=($path_info) by mod_rewrite

    @list(, $path_info) = explode('/' . $mydirname . '/', $_SERVER['REQUEST_URI'], 2);

    if (empty($path_info)) {
        $path_info = $_GET['path_info'];
    }

    $path_info = str_replace('..', '', preg_replace(WRAPS_DISALLOW_CHARS, '', $path_info));
} elseif (!empty($_SERVER['PATH_INFO'])) {
    // try PATH_INFO first

    $path_info = str_replace('..', '', preg_replace(WRAPS_DISALLOW_CHARS, '', mb_substr(@$_SERVER['PATH_INFO'], 1)));
} elseif (mb_stristr($_SERVER['REQUEST_URI'], $mydirname . '/index.php/')) {
    // try REQUEST_URI second

    [, $path_info_query] = explode($mydirname . '/index.php', $_SERVER['REQUEST_URI'], 2);

    [$path_info_tmp] = explode('?', $path_info_query, 2);

    $path_info = str_replace('..', '', preg_replace(WRAPS_DISALLOW_CHARS, '', mb_substr($path_info_tmp, 1)));
} elseif (mb_strlen($_SERVER['PHP_SELF']) > mb_strlen($_SERVER['SCRIPT_NAME'])) {
    // try PHP_SELF & SCRIPT_NAME third

    $path_info = str_replace('..', '', preg_replace(WRAPS_DISALLOW_CHARS, '', mb_substr($_SERVER['PHP_SELF'], mb_strlen($_SERVER['SCRIPT_NAME']) + 1)));
} else {
    // module top

    $path_info = empty($xoopsModuleConfig['index_file']) ? 'index.html' : $xoopsModuleConfig['index_file'];

    $wrap_full_path = _MD_WRAPS_BASEDIR . '/' . $path_info;

    if (!file_exists($wrap_full_path)) {
        die(_MD_WRAPS_NO_INDEX_FILE);
    }  

    header('Location: ' . XOOPS_URL . '/modules/' . $mydirname . '/index.php/' . $path_info);

    exit;
}

// auto update indexes
if (!empty($xoopsModuleConfig['index_auto_updated']) && @filemtime(_MD_WRAPS_BASEDIR) > @$xoopsModuleConfig['index_last_updated']) {
    require_once dirname(__DIR__) . '/include/transact_functions.php';

    $imported_count = wraps_update_indexes($mydirname, _MD_WRAPS_BASEDIR);
}

$wrap_full_path = _MD_WRAPS_BASEDIR . '/' . $path_info;
if (!file_exists($wrap_full_path)) {
    header('HTTP/1.0 404 Not Found');

    exit;
}

require dirname(__DIR__) . '/include/mimes.php';
$ext = mb_strtolower(mb_substr(mb_strrchr($path_info, '.'), 1));

switch ($ext) {
    case 'htm':
    case 'html':
        // page wrapping
        $GLOBALS['xoopsOption']['template_main'] = $mydirname . '_index.html';
        require XOOPS_ROOT_PATH . '/header.php';
        [$mytitle] = $xoopsDB->fetchRow($xoopsDB->query('SELECT title FROM ' . $xoopsDB->prefix($mydirname . '_indexes') . " WHERE filename='" . addslashes($path_info) . "'"));
        if (empty($mytitle)) {
            $mytitle = $path_info;
        }
        $xoopsTpl->assign([
            'mydirname' => $mydirname ,
            'mod_url' => XOOPS_URL . '/modules/' . $mydirname ,
            'xoops_config' => $xoopsConfig ,
            'mod_config' => $xoopsModuleConfig ,
            'main_contents' => file_get_contents($wrap_full_path) ,
            'xoops_pagetitle' => htmlspecialchars($mytitle, ENT_QUOTES),
        ]);
        require XOOPS_ROOT_PATH . '/footer.php';
        exit;

    default:
        // remove output bufferings
        while (ob_get_level()) {
            ob_end_clean();
        }

        // can headers be sent?
        if (headers_sent()) {
            restore_error_handler();

            die("Can't send headers. check language files etc.");
        }

        // headers for browser cache
        $cache_limit = (int)@$xoopsModuleConfig['browser_cache'];
        if ($cache_limit > 0) {
            session_cache_limiter('public');

            header('Expires: ' . date('r', (int)(time() / $cache_limit) * $cache_limit + $cache_limit));

            header("Cache-Control: public, max-age=$cache_limit");

            header('Last-Modified: ' . date('r', (int)(time() / $cache_limit) * $cache_limit));
        }

        // Content-Type header
        if (!empty($mimes[$ext])) {
            header('Content-Type: ' . $mimes[$ext]);
        } else {
            header('Content-Type: application/octet-stream');
        }

        // Transfer
        set_time_limit(0);
//		readfile( $wrap_full_path ) ;
        $fp = fopen($wrap_full_path, 'rb');
        while (!feof($fp)) {
            echo fread($fp, 65536);
        }
        exit;
}
