<?php
	$this->view_inc('front/nav.php');
?>
<ul id="task_subfilters">
<?php echo task_log::list_links($_SERVER['REQUEST_URI'], 'filter_recent', $this->filters['filter_recent']); ?>
</ul>
<div id="task_updates">
<?php $current_date = '' ?>
<?php if ($this->log->count()): ?>
	<?php while($this->log->next()): ?>
		<?php if ($current_date != substr($this->log->get('log_date'),0,10)): ?>
			<?php if ($current_date): ?>
				</ul>
			<?php endif; ?>
			<?php $current_date = substr($this->log->get('log_date'),0,10) ?>
			<h4><?php echo $current_date ?></h4>
			<ul>
		<?php endif; ?>
		<li class="task_<?php 
			if ($this->log->get('comment_id')) {
				echo "com";
			} elseif ($this->log->get('info') == 'creation') {
				echo "cre";
			} elseif ($this->log->get('action_code') != '') {
				echo "set";
			} else {
				echo "mod";
			}
			?>"><span class="task_user">
			<?php echo ($this->log->get('user')->get('display_name') ? $this->log->get('user')->get('display_name') : __('[deleted user]', 'tasks')).' ' ?>
			</span>
			<?php
			if ($this->log->get('type') == "task") {
				$task_link = esc_url(remove_query_arg('mode', add_query_arg('view', $this->log->get('item_id'), $this->baselink)));
				if ($this->log->get('comment_id'))
					echo __('commented task', 'tasks').' <a href="'.$task_link.'#task_comment_'.$this->log->get('comment_id').'">'.$this->log->get('title_or_name').'</a>';
				elseif ($this->log->get('info') == 'creation')
					echo __('created task', 'tasks').' <a href="'.$task_link.'">'.$this->log->get('title_or_name').'</a>';
				elseif ($this->log->get('action_code') != '')
					echo __('set task', 'tasks').' <a href="'.$task_link.'">'.$this->log->get('title_or_name').'</a> '.__('to', 'tasks').' '._x($this->log->get_status(), 'one task', 'tasks');
				else
					echo __('modified task', 'tasks').' <a href="'.$task_link.'">'.$this->log->get('title_or_name').'</a> <small>('.$this->log->get_info().')</small>';
			} else {
				$project_link = esc_url(remove_query_arg('mode', add_query_arg('proj', $this->log->get('project_id'), $this->baselink)));
				if ($this->log->get('info') == 'creation')
					echo __('created project', 'tasks').' <a href="'.$project_link.'">'.$this->log->get('title_or_name').'</a>';
				elseif ($this->log->get('action_code') != '')
					echo __('set project', 'tasks').' <a href="'.$project_link.'">'.$this->log->get('title_or_name').'</a> '.__('to', 'tasks').' '._x($this->log->get_status(), 'one project', 'tasks');
				else
					echo __('modified project', 'tasks').' <a href="'.$project_link.'">'.$this->log->get('title_or_name').'</a>';
			}
			?>
		</li>
	<?php endwhile; ?>
<?php else: ?>
	<?php _e('Nothing, for the moment', 'tasks') ?>
<?php endif; ?>
</div><!-- .task_updates -->