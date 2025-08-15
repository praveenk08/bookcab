<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// Enable hooks
$hook['display_override'][] = array(
    'class'    => 'Seo_hook',
    'function' => 'add_seo_tags',
    'filename' => 'Seo_hook.php',
    'filepath' => 'hooks',
    'params'   => array()
);
