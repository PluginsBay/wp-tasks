<?php echo $this->data->errors['global_errors'] ?>
<?php $iid = $this->data->value('item_id') ?>
<form name="my_form" action="<?php echo esc_url(add_query_arg('noheader', 1)); ?>" enctype="multipart/form-data" method="post" id="task_edit_task_form">
	<input type="hidden" name="edit" value="<?php echo $iid; ?>">
	
	<fieldset>
		
		<a id="task_back_list" href="<?php echo esc_url(remove_query_arg(array('edit', 'noheader'))).'#task_row-'.$iid ?>"><?php _e('Back to list', 'tasks') ?></a>
		<h3><?php echo $this->pid ? __('Edit Task', 'tasks') : __('New Task', 'tasks') ?></h3>
		
		<ol>
			<li>
				<label for="task_title"><?php _e('Title', 'tasks') ?></label>
				<input type="text" name="title" id="task_title" value="<?php echo $this->data->value('title'); ?>">
				<?php echo $this->data->errors['title'] ?>
			</li>
			<li>
				<label for="task_project"><?php _e('Project', 'tasks') ?></label>
				<?php if ($this->projects->count()): ?>
					<select name="project_id" id="task_project" data-ajax="<?php echo esc_url(add_query_arg('js', 1, tzn_tools::baselink())) ?>">
						<?php while ($obj = $this->projects->next(true)):?>
							<option value="<?php echo $obj->get_uid(); ?>"<?php 
								if ($this->pid && $this->data->get('project')->value('project_id') == $obj->get_uid()
									|| !$this->pid && $this->current_project == $obj->get_uid()) { 
									echo " selected"; 
								}
								?>><?php echo (strlen($obj->get('name')) > 60 ? substr($obj->get('name'), 0, 60).'…' : $obj->get('name')) ?></option>
						<?php endwhile; ?>
					</select>
				<?php else: ?>
					<?php _e('Create a project first, please', 'tasks'); ?>
				<?php endif; ?>
				<?php echo $this->data->errors['project'] ?>
			</li>
			<li>
				<label for="task_priority"><?php _e('Priority', 'tasks') ?></label>
				<div id="task_select_prio_color">&nbsp;</div>
				<select id="task_select_prio" name="priority" id="task_priority">
					<?php for ($p = 1; $p < 4; $p++): ?>
					<option value="<?php echo $p ?>"<?php if ($this->data->value('priority') == $p) { echo " selected"; } ?>><?php echo $p ?></option>
					<?php endfor; ?>
				</select>
				<?php echo $this->data->errors['priority'] ?>
			</li>
			<li>
				<label for="task_deadline_date"><?php _e('Deadline', 'tasks') ?></label>
				<input type="text" name="deadline_date" id="task_deadline_date" 
					value="<?php echo ($this->pid && $this->data->value('deadline_date')) ? tzn_tools::format_date($this->data->value('deadline_date'), $this->options['format_date']) : '' ?>">
				<img id="task_cal_btn" 
					src="<?php echo plugins_url('tasks') ?>/img/calendar.png" 
					alt="<?php _e('Calendar', 'tasks') ?>" 
					title="<?php _e('Click to show calendar', 'tasks') ?>"
					width="28"
					height="28">
				<!-- span id="task_cal_btn" type="button"><?php _e('Calendar', 'tasks') ?></span-->
				<?php echo $this->data->errors['deadline_date'] ?>
			</li>
			<li>
				<label for="task_user_id"><?php _e('Assigned to', 'tasks') ?></label>
				<select name="user_id" id="task_user_id">
					<option value="">&mdash;</option>
					<?php foreach ($this->users as $user): ?>
					<option value="<?php echo $user->ID ?>"<?php if ($this->pid && $this->data->get('user_id') == $user->ID) { echo " selected"; } ?>><?php echo $user->display_name ?></option>
					<?php endforeach; ?>
				</select>
				<?php echo $this->data->errors['user_id'] ?>
			</li>
			<li>
				<label for="task_status"><?php _e('Status', 'tasks') ?></label>
				<select name="status" id="task_status">
					<?php foreach ($this->status->get_status_list(true, 'one task') as $status => $status_lbl): ?>
					<option value="<?php echo $status ?>"
					<?php if ((isset($_GET['status']) && $_GET['status'] == $status) 
								|| ($this->pid && !isset($_GET['status']) && $this->data->get_status()->get('action_code') == $status)) { 
									echo " selected"; } ?>>
						<?php echo $status_lbl ?>
					</option>
					<?php endforeach; ?>
				</select>
				<?php echo $this->data->errors['status'] ?>
			</li>
		</ol>
	
		<?php
		wp_editor($this->data->value('description'), 'description', array(
			'media_buttons'=> false, // no media button
	        'wpautop'	=> false, // no <p>
	        'teeny'	=> true, // minimal editor
	        'dfw' => false,
	        // 'tabfocus_elements' => 'sample-permalink,post-preview',
	        'editor_height' => 360
	    ));
		?>
		<?php echo $this->data->errors['description'] ?>
		<p id="task_add_file"><?php _e('Add a File:', 'tasks') ?></p>
		<ul>
			<li id="task_file_1"><input type="file" name="task_file_1"><a href="#" class="task_file_more"><?php _e('more', 'tasks') ?></a></li>
			<li id="task_file_2"><input type="file" name="task_file_2"><a href="#" class="task_file_more"><?php _e('more', 'tasks') ?></a></li>
			<li id="task_file_3"><input type="file" name="task_file_3"><a href="#" class="task_file_more"><?php _e('more', 'tasks') ?></a></li>
			<li id="task_file_4"><input type="file" name="task_file_4"><a href="#" class="task_file_more"><?php _e('more', 'tasks') ?></a></li>
			<li id="task_file_5"><input type="file" name="task_file_5"></li>
		</ul>
		<?php echo $this->data->errors['file'] ?>
	
		<?php if ($this->file->count()):
				$wp_upload_dir = wp_upload_dir(); ?>
			<p><?php _e('Attached Files:', 'tasks') ?></p>
			<ol id="task_uploaded_files">
			<?php while ($this->file->next()) : ?>
				<li>
					<a href="<?php echo $wp_upload_dir['url'].'/'.$this->file->get('file_name') ?>"
						rel="external">
						<?php echo $this->file->get('file_title') ?>
					</a>
				</li>
			<?php endwhile; ?>
			</ol>
		<?php endif; ?>
		</fieldset>
	<button type="submit"><?php _e('Save Task', 'tasks') ?></button>

</form>