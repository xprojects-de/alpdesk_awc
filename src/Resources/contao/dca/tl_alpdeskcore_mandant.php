<?php

$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['palettes']['default'] . ';awckey,awckey_overrite,awcapikey,awcusername,awcpassword';
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['awckey'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['awckey'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
    'sql' => "varchar(250) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['awckey_overrite'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['awckey_overrite'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
    'sql' => "varchar(250) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['awcapikey'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['awcapikey'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
    'sql' => "varchar(250) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['awcusername'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['awcusername'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
    'sql' => "varchar(250) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_alpdeskcore_mandant']['fields']['awcpassword'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_alpdeskcore_mandant']['awcpassword'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
    'sql' => "varchar(250) NOT NULL default ''"
);
