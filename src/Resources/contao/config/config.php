<?php

use Alpdesk\AlpdeskAwcPlugin\Models\AlpdeskawcLogsModel;

$GLOBALS['TL_ADME']['awc'] = 'Alpdesk\\AlpdeskAwcPlugin\\Elements\\AlpdeskElementAwc';

$GLOBALS['BE_MOD']['alpdeskawc']['alpdeskawc_logs'] = array(
    'tables' => array(
        'tl_alpdeskawc_logs'
    )
);

$GLOBALS['TL_MODELS']['tl_alpdeskawc_logs'] = AlpdeskawcLogsModel::class;

