<?php

@define('XOOPS_TRUST_PATH', '');
if (empty($mydirname)) {
    $mydirname = '';
}

define('_MD_WRAPS_BASEDIR', XOOPS_TRUST_PATH . '/wraps/' . @$mydirname);

define('_MD_WRAPS_NO_INDEX_FILE', 'トップページが存在しません');
