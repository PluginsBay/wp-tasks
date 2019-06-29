<?php if ($this->user_can_post): ?>
<a id="task_new_task" href="<?php echo esc_url(add_query_arg('edit', '')) ?>"><?php _e('New Task', 'tasks') ?></a>
<?php endif; ?>
<ul id="task_subfilters">
<?php echo task_item_status::list_links($this->baselink, 'filter_task', $this->filters['filter_task'], ($this->data->count() ? $this->data->total() : null)); ?>
</ul>
<?php
if ($this->data->count()) {
?>
<div id="task_task_count">
	<?php echo $this->data->total().' '._n('task', 'tasks', $this->data->total(), 'tasks') ?>
</div>
<div id="task_sort_criteria">
	<label for="task_sort_criteria"><?php _e('Sort by:', 'tasks') ?></label>
	<ul>
	<?php foreach (array(
			array('proximity', __('Deadline proximity', 'tasks'), 'asc'),
			array('deadline_date', __('Deadline date', 'tasks'), 'desc'),
			array('priority', __('Priority', 'tasks'), 'asc'),
			array('title', __('Title', 'tasks'), 'asc'),
			array('display_name', __('Assignee', 'tasks'), 'asc'),
			array('log_date', __('Modification date', 'tasks'), 'desc'),
			array('item_status_action_code', __('Status', 'tasks'), 'desc'),
	) as $sort): ?>
		<li<?php echo (isset($_REQUEST['sort']) && $_REQUEST['sort'] == $sort[0]
						&& isset($_REQUEST['ord']) && $_REQUEST['ord'] == $sort[2]	? ' class="task_selected_order"' : '') ?>>
			<a href="<?php echo esc_url(remove_query_arg('pg', add_query_arg(array('sort' => $sort[0], 'ord' => $sort[2])))) ?>"><?php echo $sort[1] ?></a>
		</li>
	<?php endforeach; ?>
	</ul>
</div>
<ol id="task_tasksheet">
	<?php	
	while ($this->data->next()):
		$iid = $this->data->get_uid();
		$user_id = $this->data->get('user_id');
		$user =  get_userdata($user_id);
		$last_mod_user = get_userdata($this->data->get('item_status_user_id'));
		$deadline_date = $this->data->html('deadline_date');
		?>
		<li id="task_row-<?php echo $iid; ?>" class="task_pr<?php
			echo $this->data->get('priority').' ';
			if ($this->data->get('item_status')->get('action_code') != 60 && $deadline_date) {
		 		if ($this->data->get('deadline_date') < date("Y-m-d H:i:s", time()-24*60*60)) {
					echo "task_late";
				} elseif ($this->data->get('deadline_date') < date("Y-m-d H:i:s", time()+7*24*60*60)) { // TODO 7 days = urgent => setting ?
					echo "task_urg";
				}
			} else {
				echo "task_none";
			}
			?>">
			<ul>
				<li class="task_col1 task_avatar_<?php echo $this->options['avatar_size'] ?>"
					title="<?php echo $user_id ? 
										__('Assigned to', 'tasks').' '.($user ? $user->display_name : __('[deleted user]', 'tasks'))
										: __('Unassigned', 'tasks')?>">
					<?php echo get_avatar($user_id, $this->options['avatar_size']); ?>
				</li>
				<li class="task_col2">
					<ul>
						<?php 
						$proximity = $this->data->get('item_status')->get('action_code') == 60 ? _x('Closed', 'one task', 'tasks') : $this->data->get_deadline_proximity();
						if ($this->options['proximity']):
						?>
						<li class="task_prox" style="width: <?php echo $this->data->get_deadline_proximity_bar() ?>px" title="<?php echo $proximity ?>">
							<?php echo $proximity ?>
						</li>
						<?php endif; ?>
						<li class="task_desc<?php if ($this->prio_size) echo " task_size" ?>">
							<a	href="<?php echo esc_url(add_query_arg('view', $iid)).'#task_task_title'; ?>"
								title="<?php echo $this->data->get_description_extract(); ?>">
								<?php echo $this->data->html('title'); ?>
							</a>
						</li>
						<li class="task_prj"><?php echo $this->data->get('project')->html('name'); ?></li>
						<li class="task_pri"><span class="task_hid"><?php _e('Prio.:', 'tasks') ?> </span><?php echo $this->data->get('priority'); ?></li>
						<li class="task_usr<?php echo $user_id ? '' : ' unassigned' ?>"><?php echo $user_id ? ($user ? $user->display_name : __('[deleted user]', 'tasks')) : __('Unassigned', 'tasks') ; ?></li>
						<li class="task_upd"><?php 
							if ($this->data->get('item_status_info') == 'creation') {
								echo __('Add.: ', 'tasks')
										.$this->data->html('creation_date');
							} else {
								echo _e('Mod.: ', 'tasks')
										.$this->data->html('item_status_date');
							}
							echo __(' by ', 'tasks')	.($last_mod_user ? $last_mod_user->display_name : __('[deleted user]', 'tasks')); ?></li>
					</ul>
				</li>
				<li class="task_col3">
					<ul>
						<li class="task_com">
							<a title="<?php echo $this->data->get('comment_count').' '.($this->data->get('comment_count') ? _n('comment', 'comments', $this->data->get('comment_count'), 'tasks') : __('comment', 'tasks')) ?>" 
								href="<?php echo esc_url(add_query_arg('view', $iid)).'#task_comments' ?>">
								<?php echo $this->data->get('comment_count') ?>
							</a>
							<span class="task_hid">
								<?php echo $this->data->get('comment_count') ? _n('comment', 'comments', $this->data->get('comment_count'), 'tasks') : __('comment', 'tasks') ?>
							</span>
						</li>
						<?php if ($this->data->value('file_count')): ?>
						<li class="task_atch" title="<?php echo $this->data->get_attachments() ?>">
							<a title="<?php echo $this->data->get_attachments() ?>" href="<?php echo esc_url(add_query_arg('view', $iid)).'#task_uploaded_files' ?>">
								<?php echo $this->data->get('file_count') ?>
							</a>
							<span class="task_hid"><?php _e('Attached files', 'tasks') ?></span>
						</li>
						<?php endif; ?>
					</ul>
				</li>
				<li class="task_col4" id="task_col4-<?php echo $iid ?>">
					<ul>
						<li class="task_due" 
							title="<?php echo ($deadline_date ? $deadline_date.' — '.$proximity : __('Undefined Deadline', 'tasks')) ?>">
							<span class="task_hid"><?php _e('Deadline:', 'tasks') ?></span>
							<?php echo $deadline_date ? $deadline_date : '—'; ?>
						</li>
						<li class="task_sts">
							<span class="task_hid"><?php _e('Change Status:', 'tasks') ?></span>
							<?php $nonce = wp_create_nonce('status_changer') ?>
							<a id="task_sts1-<?php echo $iid ?>" 
								class="task_sts<?php echo $this->data->get('item_status')->get('action_code') > 0 ? 1 : 0 ?>" 
								href="<?php echo esc_url(add_query_arg(array('edit' => $iid, 'status' => 20, 'tasknonce' => $nonce))); ?>" 
								title="<?php _e('Click to mark as In Progress', 'tasks') ?>">
								<?php _ex('In Progress', 'one task', 'tasks') ?>
							</a><a id="task_sts2-<?php echo $iid ?>" 
								class="task_sts<?php echo $this->data->get('item_status')->get('action_code') > 20 ? 1 : 0 ?>" 
								href="<?php echo esc_url(add_query_arg(array('edit' => $iid, 'status' => 30, 'tasknonce' => $nonce))); ?>" 
								title="<?php _e('Click to mark as Suspended', 'tasks') ?>">
								<?php _ex('Suspended', 'one task', 'tasks') ?>
							</a><a id="task_sts3-<?php echo $iid ?>" 
								class="task_sts<?php echo $this->data->get('item_status')->get('action_code') > 30 ? 1 : 0 ?>" 
								href="<?php echo esc_url(add_query_arg(array('edit' => $iid, 'status' => 60, 'tasknonce' => $nonce))); ?>" 
								title="<?php _e('Click to mark as Closed', 'tasks') ?>">
								<?php _ex('Closed', 'one task', 'tasks') ?>
							</a>
							<span class="task_hid"><?php _e('Current Status: ', 'tasks') ?></span><span class="task_sts_lbl"><?php _ex($this->data->get('item_status')->get_status(), 'one task', 'tasks') ?></span>
						</li>
					</ul>
				</li>
			</ul>
		</li>
		<?php
	endwhile;
	?>
</ol>

<div id="task_pager">
	<label for="task_pager"><?php _e('Page:', 'tasks') ?></label>
	<ul>
	<?php for ($p = 1 ; $p <= $this->npages ; $p++): ?>
		<li<?php echo $this->page == $p ? ' class="task_current_page"' : '' ?>>
			<a href="<?php echo esc_url(add_query_arg('pg', $p)) ?>"><?php echo $p ?></a>
		</li>	
	<?php endfor; ?>
	</ul>
</div>

<div id="task_page_size">
	<label for="task_page_size"><?php _e('Results per page:', 'tasks') ?></label>
	<ul>
	<?php foreach (array(5, 10, 20, 50, 100) as $npg): ?>
		<li<?php echo $this->page_size == $npg ? ' class="task_selected_page_size"' : '' ?>>
			<a href="<?php echo esc_url(remove_query_arg('pg', add_query_arg('npg', $npg))) ?>"><?php echo $npg ?></a>
		</li>
	<?php endforeach; ?>
	</ul>
</div>

<?php
} else {
?>
<p>
	<?php _e('No item found','tasks') ?>
</p>
<?php
}
?>
