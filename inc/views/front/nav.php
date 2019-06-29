<ul id="task_filters">
<?php
// these are the links used for main navigation
$arr = array(
	'projects'	=> __('Projects','tasks'),
	'tasks'		=> __('Tasks','tasks'),
	'recent'	=> __('Updates','tasks'),
);

foreach ($arr as $lnk => $label) {
	if ($this->mode == $lnk) {
		echo '<li class="task_selected_filter">'.$label.'</li>';
	} else {
		echo '<li><a href="'.esc_url(add_query_arg('mode', $lnk, tzn_tools::baselink())).'">'.$label.'</a></li>';
	}
}
?>
</ul>