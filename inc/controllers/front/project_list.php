<?php

/*
@package Tasks
@since 0.1
@version 1.0

List user's project

*/

$this->baselink = add_query_arg('mode', 'projects', tzn_tools::baselink());

$this->data = new task_project_info();

if ($this->filters['filter_project'] == 'all') {
	$sql = array('where' => task_user::get_roles_sql('who_read').' AND trashed=0');
	if (!current_user_can('manage_options')) {
		$sql['having'] = 'project_status_action_code <> 0';
	}
	$this->data->load_list($sql);
} else {
	$this->data->load_list(array(
			'where' => task_user::get_roles_sql('who_read').' AND trashed=0',
			'having' => 'project_status_action_code='.intval($this->filters['filter_project'])
	));	
}

$this->view('front/project_list.php');