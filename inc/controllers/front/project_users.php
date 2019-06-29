<?php

/*
@package Tasks
@since 0.1
@version 1.0

Update task status in Ajax

*/

$pid = intval($_REQUEST['proj']);
$selected = intval($_REQUEST['user']);

$users = get_users();

$project = new task_project();
$project->set_uid($pid);
if ($pid && $project->load()) {
	if (!$project->check_access('post')) {
		echo '<p class="task_err">'.__("Sorry, you can't post in this project. Please contact an admin.", 'tasks').'</p>';
	} else {
		echo '<div id="task_corresponding_users">';
		echo '<option value="">&mdash;</option>';
		foreach ($users as $user) {
			if ($project->check_access('read', $user->ID)) {
				echo '<option value="'.$user->ID.'"'.($user->ID == $selected ? ' selected' : '').'>'.$user->display_name.'</option>';
			}
		}
		echo '</div>';
	}
}