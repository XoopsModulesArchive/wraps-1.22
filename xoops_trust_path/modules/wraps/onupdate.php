<?php

eval(' function xoops_module_update_' . $mydirname . '( $module ) { return wraps_onupdate_base( $module , \'' . $mydirname . '\' ) ; } ');

if (!function_exists('wraps_onupdate_base')) {
    function wraps_onupdate_base($module, $mydirname)
    {
        // transations on module update

        global $msgs; // TODO :-D

        // for Cube 2.1

        if (defined('XOOPS_CUBE_LEGACY')) {
            $root = &XCube_Root::getSingleton();

            $root->mDelegateManager->add('Legacy.Admin.Event.ModuleUpdate.' . ucfirst($mydirname) . '.Success', 'wraps_message_append_onupdate');

            $msgs = [];
        } else {
            if (!is_array($msgs)) {
                $msgs = [];
            }
        }

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $mid = $module->getVar('mid');

        // TABLES (write here ALTER TABLE etc. if necessary)

        // configs (Though I know it is not a recommended way...)

        $check_sql = 'SHOW COLUMNS FROM ' . $db->prefix('config') . " LIKE 'conf_title'";

        if (($result = $db->query($check_sql)) && ($myrow = $db->fetchArray($result)) && 'varchar(30)' == @$myrow['Type']) {
            $db->queryF('ALTER TABLE ' . $db->prefix('config') . " MODIFY `conf_title` varchar(255) NOT NULL default '', MODIFY `conf_desc` varchar(255) NOT NULL default ''");
        }

        // configs (Though I know it is not a recommended way...)

        $check_sql = 'SHOW COLUMNS FROM ' . $db->prefix('config') . " LIKE 'conf_title'";

        if (($result = $db->query($check_sql)) && ($myrow = $db->fetchArray($result)) && 'varchar(30)' == @$myrow['Type']) {
            $db->queryF('ALTER TABLE ' . $db->prefix('config') . " MODIFY `conf_title` varchar(255) NOT NULL default '', MODIFY `conf_desc` varchar(255) NOT NULL default ''");
        }

        // TEMPLATES (all templates have been already removed by modulesadmin)

        $tplfileHandler = xoops_getHandler('tplfile');

        $tpl_path = __DIR__ . '/templates';

        if ($handler = @opendir($tpl_path . '/')) {
            while (false !== ($file = readdir($handler))) {
                if ('.' == mb_substr($file, 0, 1)) {
                    continue;
                }

                $file_path = $tpl_path . '/' . $file;

                if (is_file($file_path)) {
                    $mtime = (int)@filemtime($file_path);

                    $tplfile = $tplfileHandler->create();

                    $tplfile->setVar('tpl_source', file_get_contents($file_path), true);

                    $tplfile->setVar('tpl_refid', $mid);

                    $tplfile->setVar('tpl_tplset', 'default');

                    $tplfile->setVar('tpl_file', $mydirname . '_' . $file);

                    $tplfile->setVar('tpl_desc', '', true);

                    $tplfile->setVar('tpl_module', $mydirname);

                    $tplfile->setVar('tpl_lastmodified', $mtime);

                    $tplfile->setVar('tpl_lastimported', 0);

                    $tplfile->setVar('tpl_type', 'module');

                    if (!$tplfileHandler->insert($tplfile)) {
                        $msgs[] = '<span style="color:#ff0000;">ERROR: Could not insert template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b> to the database.</span>';
                    } else {
                        $tplid = $tplfile->getVar('tpl_id');

                        $msgs[] = 'Template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b> added to the database. (ID: <b>' . $tplid . '</b>)';

                        // generate compiled file

                        require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

                        require_once XOOPS_ROOT_PATH . '/class/template.php';

                        if (!xoops_template_touch($tplid)) {
                            $msgs[] = '<span style="color:#ff0000;">ERROR: Failed compiling template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b>.</span>';
                        } else {
                            $msgs[] = 'Template <b>' . htmlspecialchars($mydirname . '_' . $file, ENT_QUOTES | ENT_HTML5) . '</b> compiled.</span>';
                        }
                    }
                }
            }

            closedir($handler);
        }

        require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';

        require_once XOOPS_ROOT_PATH . '/class/template.php';

        xoops_template_clear_module_cache($mid);

        return true;
    }

    function wraps_message_append_onupdate(&$module_obj, $log)
    {
        if (is_array(@$GLOBALS['msgs'])) {
            foreach ($GLOBALS['msgs'] as $message) {
                $log->add(strip_tags($message));
            }
        }

        // use mLog->addWarning() or mLog->addError() if necessary
    }
}
