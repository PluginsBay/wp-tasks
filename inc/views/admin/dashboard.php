<div class="wrap">
	<h2><?php _e('Dashboard', 'tasks'); ?></h2>
	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">
			<h3>WP Tasks v<?php echo $this->options['version']; ?></h3>
			<p class="about-description">
				<?php printf(__( 'Manage Tasks and Projects with ease. <a href="%1$s" target="_blank">Get Support</a>.', 'tasks' ), 'https://pluginsbay.com/plugin/tasks/'); ?>
			</p>
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column task-dash-col1">
					<?php
					if ($this->options['page_id']) {
					?>
					<a class="button button-primary button-hero" href="<?php echo $this->linktsk; ?>"><?php _e('Start working!', 'tasks'); ?></a>
					<ul>
						<li><a href="<?php echo $this->linkupd; ?>" class="welcome-icon welcome-view-site"><?php _e('Recent updates', 'tasks'); ?></a></li>
						<?php
						if (task_user::check_role('editor')) {
						?>
						<li><a href="<?php echo $this->linkprj; ?>" class="welcome-icon welcome-widgets-menus"><?php _e('Manage projects', 'tasks'); ?></a></li>
						<?php
						}
						?>
						<li><a href="https://pluginsbay.com/plugin/tasks/" class="welcome-icon welcome-learn-more" target="_blank"><?php _e('Learn more about WP Tasks', 'tasks'); ?></a></li>
					</ul>
					<?php
					} else {
					?>
					<p><?php printf(__('To start using Tasks plugin for Wordpress, you first need to add the shortcode <code>[task_all]</code> in one of your <a href="%s">pages</a>.','tasks'), 'edit.php?post_type=page'); ?></p>
					<p><?php printf(__('You can also start by <a href="%s">creating projects</a>', 'tasks'), $this->linkprj); ?></p>
					<?php
					}
					?>
				</div>
				<div class="welcome-panel-column task-dash-col2">
					<table class="task_stats">
						<thead>
							<tr>
								<td></td>
								<th><?php _ex('In Progress', 'many projects', 'tasks'); ?></th>
								<th><?php _ex('Suspended', 'many projects', 'tasks'); ?></th>
								<th><?php _ex('Closed', 'many projects', 'tasks'); ?></th>
						</thead>
						<tbody>
							<tr>
								<th><?php _e('My Tasks', 'tasks'); ?></th>
								<td><?php echo $this->tskusr[20]; ?></td>
								<td><?php echo $this->tskusr[30]; ?></td>
								<td><?php echo $this->tskusr[60]; ?></td>
							</tr>
							<tr>
								<th><?php _e('All Tasks', 'tasks'); ?></th>
								<td><?php echo $this->tskall[20]; ?></td>
								<td><?php echo $this->tskall[30]; ?></td>
								<td><?php echo $this->tskall[60]; ?></td>
							</tr>
							<tr class="sep">
								<th><?php _e('My Projects', 'tasks'); ?></th>
								<td><?php echo $this->prjusr[20]; ?></td>
								<td><?php echo $this->prjusr[30]; ?></td>
								<td><?php echo $this->prjusr[60]; ?></td>
							</tr>
							<tr>
								<th><?php _e('All Projects', 'tasks'); ?></th>
								<td><?php echo $this->prjall[20]; ?></td>
								<td><?php echo $this->prjall[30]; ?></td>
								<td><?php echo $this->prjall[60]; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<p class="about-description">
				<?php printf(__('If you enjoy using this free plugin, pleace consider <a href="%s" target="_blank">leaving a review on WordPress.org</a>', 'tasks'), 'https://pluginsbay.com/plugin/tasks/'); ?>
			</p>
		</div>
	</div>
	
	<div>
		<div class="welcome-panel-content">
			<h3 align="center">
			<br>
			<a href="https://pluginsbay.com/testimonials">★★★★★ Leave a Review</a> | <a href="https://pluginsbay.com/support">Get Support</a> | <a href="https://pluginsbay.com/request">Request a Feature</a>
			</h3>
		</div>
	</div>
	
	
</div>
