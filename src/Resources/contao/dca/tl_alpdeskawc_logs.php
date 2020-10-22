<?php

$GLOBALS['TL_DCA']['tl_alpdeskawc_logs'] = array
    (
    'config' => array
        (
        'dataContainer' => 'Table',
        'enableVersioning' => false,
        'sql' => array
            (
            'keys' => array
                (
                'id' => 'primary'
            )
        )
    ),
    'list' => array
        (
        'sorting' => array
            (
            'mode' => 2,
            'fields' => array('tstamp DESC'),
            'flag' => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label' => array
            (
            'fields' => array('tstamp', 'channel', 'key'),
            'label_callback' => array('tl_alpdeskawc_logs', 'showLabels'),
            'showColumns' => true
        ),
        'global_operations' => array
            (
            'all' => array
                (
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
            (
            'edit' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskawc_logs']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'delete' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskawc_logs']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            )
        )
    ),
    'palettes' => array
        (
        'default' => 'channel,key;request,response'
    ),
    'fields' => array
        (
        'id' => array
            (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'channel' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskawc_logs']['channel'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
            'sql' => "varchar(250) NOT NULL default ''"
        ),
        'key' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskawc_logs']['key'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 250, 'tl_class' => 'w50'),
            'sql' => "varchar(250) NOT NULL default ''"
        ),
        'request' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskawc_logs']['request'],
            'exclude' => true,
            'search' => false,
            'inputType' => 'textarea',
            'eval' => array('mandatory' => false, 'tl_class' => 'clr'),
            'sql' => "mediumtext NULL"
        ),
        'response' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskawc_logs']['response'],
            'exclude' => true,
            'search' => false,
            'inputType' => 'textarea',
            'eval' => array('mandatory' => false, 'tl_class' => 'clr'),
            'sql' => "mediumtext NULL"
        )
    )
);

class tl_alpdeskawc_logs extends Backend {

  public function showLabels($row, $label, DataContainer $dc, $args) {
    $args[0] = date('d.m.Y H:i:s', $args[0]);
    return $args;
  }

}
