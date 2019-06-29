<?php

/*
@package Tasks
@since 0.1
@version 1.0

*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
 exit;
}

// remove options
delete_option('task_options');

// delete database
global $wpdb;
foreach (array(
		"item",
		"item_comment",
		"item_comment_like",
		"item_file",
		"item_like",
		"item_status",
		"log",
		"project",
		"project_status",
		"project_user",
		) as $table_name) {
	$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix.'task_'.$table_name);
}