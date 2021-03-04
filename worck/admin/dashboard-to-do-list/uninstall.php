<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

delete_option('ardtdw-checkbox');
delete_option('ardtdw-textarea');
delete_option('ardtdw-position');
