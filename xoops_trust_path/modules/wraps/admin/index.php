<?php

require_once dirname(__DIR__) . '/include/transact_functions.php';

//
// TRANSACTION STAGE
//

if (!empty($_POST['submit'])) {
    $imported_count = wraps_update_indexes($mydirname, _MD_WRAPS_BASEDIR);

    redirect_header(XOOPS_URL . '/modules/' . $mydirname . '/admin/index.php?page=index', 3, sprintf(_MD_A_WRAPS_FMT_UPDATED_INDEXES, $imported_count));

    exit;
}

//
// FORM STAGE
//

xoops_cp_header();
$mymenu_fake_uri = 'admin/index.php?page=index';
include __DIR__ . '/mymenu.php';

echo '
<h3>' . $xoopsModule->getVar('name') . "</h3>
<form action='?page=index' method='post'>
	<input type='submit' name='submit' value='" . _MD_A_WRAPS_BTN_UPDATE_INDEXES . "'>
</form>
" . _MD_A_WRAPS_LABEL_INDEXLASTUPDATED . ': ' . formatTimestamp(@$xoopsModuleConfig['index_last_updated']);

xoops_cp_footer();
