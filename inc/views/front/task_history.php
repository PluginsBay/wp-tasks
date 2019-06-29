<?php 
$iid = $this->data->get_uid();
?>
<a id="task_back_list" href="<?php echo esc_url(add_query_arg('view', $iid, remove_query_arg(array('hist', 'noheader')))) ?>"><?php _e('Back to task', 'tasks') ?></a>
<h2 id="task_task_title">
	<a id="task_task_link" href="<?php echo esc_url(add_query_arg('view', $iid, tzn_tools::baselink())) ?>">
		<?php echo $this->data->get('title') ?>
	</a>
</h2>
<h3 id="task_task_project_name"><?php echo __('In', 'tasks').' '.$this->data->get('project')->html('name'); ?></h3>

<h4><?php _e('Task history', 'tasks') ?></h4>

<?php if ($this->log->count()): ?>
	<table id="task_task_history">
		<tr>
			<th><?php _e('Date', 'tasks') ?></th>
			<th><?php _e('User', 'tasks') ?></th>
			<th><?php _e('Action', 'tasks') ?></th>
		</tr>
		<?php while ($this->log->next()): ?>
		<tr>
			<td><?php echo $this->log->html('log_date') ?></td>
			<td>
				<?php echo $this->log->get('user')->get('display_name') ? $this->log->get('user')->get('display_name') : __('[deleted user]', 'tasks') ?>
			 </td>
			<td>
				<?php
					if ($this->log->get('action_code') != '')
						echo _x($this->log->get_status(), 'one task', 'tasks');
					else
						echo __('Modified', 'tasks').' ('.$this->log->get_info().')';   
				?>
			</td>
		</tr>
		<?php endwhile; ?>
	</table>
<?php endif; ?>
