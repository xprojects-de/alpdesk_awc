<?php

$GLOBALS['TL_DCA']['tl_settings']['fields']['awc_baseurl'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['awc_baseurl'],
    'exclude' => true,
    'default' => 'https://oats-test-wcs.wilken.de',
    'inputType' => 'text',
    'eval' => array('tl_class' => 'w50', 'mandatory' => true)
);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{awc_legend},awc_baseurl;';
