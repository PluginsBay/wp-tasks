<?php
	$this->view_inc('front/nav.php');
?>
<ul id="task_subfilters">
<?php echo task_project_status::list_links($this->baselink, 'filter_project', $this->filters['filter_project']); ?>
</ul>
<table class="task_projects widefat">
	<thead>
		<tr>
			<th style="width:6%"><?php _e('ID', 'tasks') ?></th>
			<th style="width:64%"><?php _e('Name', 'tasks'); ?></th>
			<th style="width:30%"><?php _e('Status', 'tasks'); ?></th>
		</tr>
	</thead>
	<!-- tfoot>
		<tr>
			<th><?php _e('ID', 'tasks') ?></th>
			<th><?php _e('Name', 'tasks'); ?></th>
			<th><?php _e('Status', 'tasks'); ?></th>
		</tr>
	</tfoot -->
	<tbody>
	<?php
	if ($this->data->count()) {
		while ($this->data->next()) {
			$pid = $this->data->get_uid();
			echo '<tr>';
			echo '<td>'.$pid.'</td>';
			echo '<td>';
			echo '<a href="'.esc_url(add_query_arg('proj', $pid)).'"><strong>'.$this->data->get('name').'</strong></a>';
			echo '</td>';
			echo '<td>'._x($this->data->get('project_status')->get_status(), 'one project', 'tasks').'</td>';
			echo '</tr>';
		}
	} else {
		echo '<tr><td colspan="3">'.__('No project found', 'tasks').'</td></tr>';
	}
	?>
	</tbody>
</table>
