<?php

$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['palettes']['default'] . ';awckey';
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['awckey'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['awckey'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => true, 'maxlength' => 250, 'tl_class' => 'w50'),
    'sql' => "varchar(250) NOT NULL default ''"
);
