<?php

function wraps_update_indexes($mydirname, $base_path)
{
    global $xoopsModule;

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    // update config of 'index_last_updated'

    $db->queryF('UPDATE ' . $db->prefix('config') . " SET conf_value=UNIX_TIMESTAMP() WHERE conf_name='index_last_updated' AND conf_modid=" . (int)$xoopsModule->getVar('mid'));

    // delete indexes first

    $db->queryF('DELETE FROM ' . $db->prefix($mydirname . '_indexes'));

    // crawl directories recursively

    $GLOBALS['wraps_imported_count'] = 0;

    wraps_register_searchable_files_recursive($mydirname, $base_path, '');

    return $GLOBALS['wraps_imported_count'];
}

function wraps_register_searchable_files_recursive($mydirname, $base_path, $path)
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    if ($handler = @opendir($base_path . '/' . $path)) {
        while (false !== ($file = readdir($handler))) {
            if ('.' == mb_substr($file, 0, 1)) {
                continue;
            }

            $full_path = $base_path . '/' . $path . $file;

            if (is_dir($full_path)) {
                wraps_register_searchable_files_recursive($mydirname, $base_path, $path . $file . '/');
            } elseif (in_array(mb_strrchr($file, '.'), [ '.html', '.htm', '.txt' ], true)) {
                $mtime = (int)@filemtime($full_path);

                $body = file_get_contents($full_path);

                if (preg_match('/\<title\>([^<>]+)\<\/title\>/is', $body, $regs)) {
                    $title = $regs[1];
                } else {
                    $title = $file;
                }

                $result = $db->queryF('INSERT INTO ' . $db->prefix($mydirname . '_indexes') . " SET `filename`='" . addslashes($path . $file) . "', `title`='" . addslashes($title) . "', `mtime`='$mtime', `body`='" . addslashes(strip_tags($body)) . "'");

                if ($result) {
                    $GLOBALS['wraps_imported_count']++;
                }
            }
        }
    }
}
