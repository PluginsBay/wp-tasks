<?php 
$iid = $this->data->get_uid();
$assignee_id = $this->data->get('user_id');
$assignee =  get_userdata($assignee_id);
$author_id = $this->data->get('author_id');
$author = get_userdata($this->data->get('author_id'));
$last_mod_user = get_userdata($this->data->get('item_status_user_id'));
$status_action_code = $this->data->get_status()->get('action_code');
?>
<a id="task_back_list" href="<?php echo esc_url(remove_query_arg(array('view', 'noheader'))).'#task_row-'.$iid ?>"><?php _e('Back to list', 'tasks') ?></a>
<h2 id="task_task_title">
	<a id="task_task_link" href="<?php echo esc_url(add_query_arg('view', $iid, tzn_tools::baselink())) ?>">
		<?php echo $this->data->get('title') ?>
	</a>
</h2>
<h3 id="task_task_project_name"><?php echo __('In', 'tasks').' '.$this->data->get('project')->html('name') ?></h3>
<?php echo $this->comment->errors['global_errors'] ?>
<div id="task_task_edit_comment">
	<?php if ($this->user_can_edit_task): ?>
	<a id="task_task_edit" href="<?php echo esc_url(remove_query_arg(array('view','noheader'), add_query_arg('edit', $iid))) ?>#task_edit_task_form">
		<?php _e('Edit', 'tasks') ?>
	</a>
	<?php endif; ?>
	<?php if ($this->user_can_comment_project): ?>
	<a href="#task_comment_form"><?php _e('Comment', 'tasks') ?></a>
	<?php endif; ?>
</div>
<span id="task_task_author_pic" 
		class="task_avatar_<?php echo $this->options['avatar_size'] ?>" 
		title="<?php echo __('Task created by', 'tasks').' '.($author ? $author->display_name : __('[deleted user]', 'tasks')) ?>">
	<?php echo get_avatar(($author_id ? $author_id : 0), $this->options['avatar_size']); ?>
</span>
<p class="task_task_head"><?php echo __('By', 'tasks').' '.($author ? $author->display_name : __('[deleted user]', 'tasks')) ?></p>
<p class="task_task_head"><?php echo __('On', 'tasks').' '.$this->data->html('creation_date') ?></p>
<div id="task_task_details" class="task_pr<?php echo $this->data->get('priority') ?>">
	<p><span><?php _e('Priority:', 'tasks') ?></span> <span class="task_pri task_pr<?php echo $this->data->get('priority') ?>"><?php echo $this->data->get('priority') ?></span></p>
	<p><span><?php _e('Deadline:', 'tasks') ?></span> <span><?php echo $this->data->html('deadline_date') ? $this->data->html('deadline_date') : '&mdash;' ?></span></p>
	<p><span><?php _e('Assigned To:', 'tasks') ?></span> <span><?php echo $assignee_id ? ($assignee ? $assignee->display_name : __('[deleted user]', 'tasks')) : __('Unassigned', 'tasks'); ?></span></p>
	<p class="task_sts">
		<span class="task_hid"><?php _e('Change Status:', 'tasks') ?></span>
		<?php $nonce = wp_create_nonce('status_changer') ?>
		<a id="task_sts1-<?php echo $iid ?>" 
			class="task_sts<?php echo $status_action_code > 0 ? 1 : 0 ?>" 
			href="<?php echo esc_url(remove_query_arg('view', add_query_arg(array('edit' => $iid, 'status' => 20, 'tasknonce' => $nonce)))) ?>" 
			title="<?php _e('Click to mark as In Progress', 'tasks') ?>">
			<?php _ex('In Progress', 'one task', 'tasks') ?>
		</a><a id="task_sts2-<?php echo $iid ?>" 
			class="task_sts<?php echo $status_action_code > 20 ? 1 : 0 ?>" 
			href="<?php echo esc_url(remove_query_arg('view', add_query_arg(array('edit' => $iid, 'status' => 30, 'tasknonce' => $nonce)))) ?>" 
			title="<?php _e('Click to mark as Suspended', 'tasks') ?>">
			<?php _ex('Suspended', 'one task', 'tasks') ?>
		</a><a id="task_sts3-<?php echo $iid ?>" 
			class="task_sts<?php echo $status_action_code > 30 ? 1 : 0 ?>" 
			href="<?php echo esc_url(remove_query_arg('view', add_query_arg(array('edit' => $iid, 'status' => 60, 'tasknonce' => $nonce)))) ?>" 
			title="<?php _e('Click to mark as Closed', 'tasks') ?>">
			<?php _ex('Closed', 'one task', 'tasks') ?>
		</a>
		<span class="task_hid"><?php _e('Current Status: ', 'tasks') ?></span><span class="task_sts_lbl"><?php _ex($this->data->get_status()->get_status(), 'one task', 'tasks') ?></span>
	</p>
	<p id="task_task_hist_cntr">
		<a id="task_task_history_toggle" 
            class="task_task_history_hidden"
            href="<?php echo esc_url(add_query_arg('hist', $iid, tzn_tools::baselink())) ?>" 
            title="<?php _e('Task history', 'tasks') ?>"><?php _e('Task history', 'tasks') ?></a>
	</p>
</div>
<table id="task_task_history"></table>
<div id="task_task_description"><?php echo $this->data->get('description') ?></div>
<?php if ($this->file->count()): 
		$wp_upload_dir = wp_upload_dir(); ?>
	<h3><?php _e('Attached files', 'tasks') ?></h3>
	<ol id="task_uploaded_files">
	<?php while ($this->file->next()): ?>
		<li>
			<a href="<?php echo $wp_upload_dir['url'].'/'.$this->file->get('file_name') ?>" rel="external">
				<?php echo $this->file->get('file_title') ?>
			</a>
		</li>
	<?php endwhile; ?>
	</ol>
<?php endif; ?>
<h3 id="task_comments"><?php echo $this->comment->count().' '._n('comment', 'comments', $this->comment->count() > 1 ? 2 : 1, 'tasks') ?></h3>
<ul>
<?php while ($this->comment->next()): ?>
	<li class="task_comment" id="task_comment_<?php echo $this->comment->get_uid() ?>">
		<div class="task_comm_user task_avatar_<?php echo $this->options['avatar_size'] ?>">
			<?php 
				$comment_user_id = $this->comment->get('user_id');
				$comment_user = get_userdata($comment_user_id);
				echo get_avatar($comment_user_id ? $comment_user_id : 0, $this->options['avatar_size']);
			?>
		</div>
		<div>
			<a class="task_comm_lnk" href="<?php echo esc_url(add_query_arg('view', $iid, tzn_tools::baselink())) ?>#task_comment_<?php echo $this->comment->get_uid() ?>">#</a>
			<?php echo $comment_user ? $comment_user->display_name : _e('[deleted user]', 'tasks') ?>
			<div class="task_comm_date"><?php echo $this->comment->html('post_date') ?></div>
			<div class="task_comm_body"><?php echo $this->comment->get('body') ?></div>
		</div>
	</li>
<?php endwhile; ?>
</ul>
<?php if ($this->user_can_comment_project): ?>
<form id="task_comment_form" action="<?php echo esc_url(add_query_arg('noheader', 1)) ?>#task_task_title" enctype="multipart/form-data" method="post">
	<input type="hidden" name="edit" value="<?php echo $this->data->value('item_id'); ?>">
	<div>
		<p id="task_add_comment"><?php _e('Add a Comment:', 'tasks') ?></p>
		<?php
		wp_editor('', 'body', array(
			'media_buttons'=> false, // no media button
	        'wpautop'	=> false, // no <p>
	        'teeny'	=> true, // minimal editor
	        'dfw' => false,
	        // 'tabfocus_elements' => 'sample-permalink,post-preview',
	        'editor_height' => 360
	    ));
		?>
		<?php echo $this->comment->errors['body'] ?>
	</div>
	<?php if ($this->options['comment_upload']): ?>
	<div>
		<p id="task_add_file"><?php _e('Add a File:', 'tasks') ?></p>
		<ul>
			<li id="task_file_1"><input type="file" name="task_file_1"><a href="#" class="task_file_more"><?php _e('more', 'tasks') ?></a></li>
			<li id="task_file_2"><input type="file" name="task_file_2"><a href="#" class="task_file_more"><?php _e('more', 'tasks') ?></a></li>
			<li id="task_file_3"><input type="file" name="task_file_3"></li>
		</ul>
		<?php echo $this->comment->errors['file'] ?>
	</div>
	<?php endif; ?>
	<button type="submit"><?php _e('Save Comment', 'tasks') ?></button>
</form>
<?php endif; ?>
