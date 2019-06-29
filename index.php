<?php
/*
Plugin Name: Tasks
Plugin URI: http://www.tasks.com
Description: Task Management made easy
Version: 1.0.0
Author: PluginsBay
Author URI: http://www.pluginsbay.com
License: GPL2
*/

define('TASK_ROOT_FILE', __FILE__);
define('TASK_ROOT_PATH', plugin_dir_path(__FILE__));
define('TZN_PROJECT_PREFIX', 'task');

load_plugin_textdomain('tasks', false, basename(dirname( __FILE__ )).'/languages');

include TASK_ROOT_PATH.'inc/install.php';

include TASK_ROOT_PATH.'inc/classes/models.php';
include TASK_ROOT_PATH.'inc/classes/tools.php';
include TASK_ROOT_PATH.'inc/classes/controller.php';

include TASK_ROOT_PATH.'inc/models/log.php';
include TASK_ROOT_PATH.'inc/models/user.php';
include TASK_ROOT_PATH.'inc/models/project.php';
include TASK_ROOT_PATH.'inc/models/item.php';

// add_action('plugins_loaded', 'task_loaded_init');

if (is_admin()) {
	include TASK_ROOT_PATH.'inc/controllers/admin.php';
} else {
	include TASK_ROOT_PATH.'inc/controllers/front.php';
}
