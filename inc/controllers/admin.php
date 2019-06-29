<?php

/*
@package Tasks
@since 0.1
@version 1.0

*/

class task_admin extends tzn_controller {
	
	public function __constuct() {
		include TASK_ROOT_PATH.'inc/controllers/admin/project_ajax.php';
	}
	
	public static function init() {
		$c = __CLASS__;
		$obj = new $c();
		add_action('admin_menu', array($obj, 'init_menu'));
		add_filter('plugin_action_links', array($obj,'init_plugin_links'), 10, 2);
	}

	/**
	 * Create admin menu
	 */
	public function init_menu() {
		// menu section
		add_menu_page('Tasks', 'Tasks', 'read', 'tasks', array($this,'page_dashboard'), plugins_url('/img/logo.16.png', TASK_ROOT_FILE), 55);
		// submenu
		add_submenu_page('tasks', __('Administrator Dashboard', 'tasks'), __('Dashboard', 'tasks'), 'read', 'tasks', array($this, 'page_dashboard')); // all WP users
		add_submenu_page('tasks', __('Manage projects', 'tasks'), __('Projects', 'tasks'), 'publish_posts', 'tasks_projects', array($this, 'page_projects')); // author
		add_submenu_page('tasks', __('Settings & Options', 'tasks'), __('Settings', 'tasks'), 'manage_options', 'tasks_settings', array($this, 'page_settings')); // administrator
	}
	
	/**
	 * Dashboard page
	 */
	public function page_dashboard() {
		$this->call('admin/dashboard.php');
	}
	
	/**
	 * Projects page
	 * list, create, edit or delete project
	 */
	public function page_projects() {
		$this->options = get_option('task_options');
		$this->linkadmin = '?page=tasks_projects';
		$this->linkfront = add_query_arg('mode', 'projects', $this->options['page_url']);
		$this->is_manager = task_user::check_role('editor');
		if (empty($_REQUEST['id'])) {
			// list projects by default
			// author, editor and admin
			$this->call('admin/project_list.php');
		} else if ($this->is_manager) {
			// only editor and admin can add/edit projects (any projects)
			$this->call('admin/project_edit.php');
		}
	}
	
	/**
	 * Global settings page
	 */
	public function page_settings() {
		$this->baselink = '?page=tasks_settings';
		$this->call('admin/settings.php');
	}
	
	/**
	 * Add "settings" link to plugin
	 */
	public function init_plugin_links($links, $file) {
		static $this_plugin;
	    if (!$this_plugin) {
	        $this_plugin = plugin_basename(TASK_ROOT_PATH);
	    }
	    if (preg_match('/^'.$this_plugin.'/', $file)) {
	        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=tasks_settings">Settings</a>';
	        array_unshift($links, $settings_link);
	    }	
	    return $links;
	}
	
}

task_admin::init();