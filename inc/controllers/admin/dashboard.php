<?php

/*
@package Tasks
@since 0.1
@version 1.0

TFWP dashboard
	
*/

$this->options = get_option('task_options');
$this->linktsk = esc_url(add_query_arg('mode', 'tasks', $this->options['page_url']));
$this->linkprj = '?page=tasks_projects';
$this->linkupd = esc_url(add_query_arg('mode', 'recent', $this->options['page_url']));

// User tasks

$this->tskusr = array(
	20	=> 0,
	30	=> 0,
	60	=> 0
);

foreach ($this->tskusr as $k => $v) {
	$lst = new task_item_info();
	if ($c = $lst->load_list(
		array(
			'where'		=> 'item.user_id = '.get_current_user_id().' AND trashed=0',
			'having'	=> 'item_status_action_code = '.$k
		)
	)) {
		$this->tskusr[$k] = $c;
	}
}

// All tasks

$this->tskall = array(
	20	=> 0,
	30	=> 0,
	60	=> 0
);

foreach ($this->tskall as $k => $v) {
	$lst = new task_item_info();
	if ($c = $lst->load_list(
		array(
			'where'		=> 'trashed=0',
			'having'	=> 'item_status_action_code = '.$k
		)
	)) {
		$this->tskall[$k] = $c;
	}
}

// User projects

$this->prjusr = array(
	20	=> 0,
	30	=> 0,
	60	=> 0
);

foreach ($this->prjusr as $k => $v) {
	$lst = new task_project_info();
	if ($c = $lst->load_list(
		array(
			'where'		=> task_user::get_roles_sql('who_read').' AND trashed=0',
			'having'	=> 'project_status_action_code='.$k
		)
	)) {
		$this->prjusr[$k] = $c;
	}
}

// All projects

$this->prjall = array(
	20	=> 0,
	30	=> 0,
	60	=> 0
);

foreach ($this->prjall as $k => $v) {
	$lst = new task_project_info();
	if ($c = $lst->load_list(
		array(
			'where'		=> 'trashed=0',
			'having'	=> 'project_status_action_code='.$k
		)
	)) {
		$this->prjall[$k] = $c;
	}
}

// View

wp_register_style( 'tznadmincss', plugins_url('css/admin.css', TASK_ROOT_FILE));
wp_enqueue_style( 'tznadmincss' );
$this->view('admin/dashboard.php');	