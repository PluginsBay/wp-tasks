<?php

/*
@package Tasks
@since 0.1
@version 1.0

*/

class task_front extends tzn_controller {
	
	public function __constuct() {
		parent::__construct();
	}
	
	public static function init() {
		$c = __CLASS__;
		$obj = new $c();
		add_shortcode('task_all', array($obj, 'task_all'));
                add_action('wp_enqueue_scripts', array($obj, 'tzn_scripts_and_styles'));
	}

        public function tzn_scripts_and_styles() {
                wp_register_style('tznfrontcss', plugins_url('css/front.css', TASK_ROOT_FILE));
		wp_enqueue_style('tznfrontcss');
                
                $options = get_option('task_options');
                wp_enqueue_script('tznfrontjs', plugins_url('/js/front.js', TASK_ROOT_FILE), array('jquery'));
                wp_localize_script('tznfrontjs', 'tznfrontjs_vars', array(
                                'plugins_url' 	    => plugins_url(),
                                'error_message'	    => __('An error has occured.', 'tasks'),
                                'datepicker_format' => tzn_tools::date_format_convert_php_jquery($options['format_date']),
                                'task_hist_show'    => __('Show History', 'tasks'),
                                'task_hist_hide'    => __('Hide History', 'tasks'),
                        )
                );
                wp_enqueue_script('jquery-ui-datepicker');
                
                $wp_lang = get_locale();
                $wp_lang = preg_replace('/_..$/', '', $wp_lang);
                if ($wp_lang != 'en') {
                        wp_register_script('jquery-ui-datepicker-l10n',
                                            '//raw.githubusercontent.com/jquery/jquery-ui/master/ui/i18n/datepicker-'.$wp_lang.'.js',
                                            array('jquery-ui-datepicker'));
                        wp_enqueue_script('jquery-ui-datepicker-l10n');
                }
                wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        }
        
	public function task_all() {
		
		$this->_mode = 'return';
		
		// Load options
		$this->options = get_option('task_options');
		
		// update page url in options for widgets
		$baselink = tzn_tools::baselink();
		if ($this->options['page_url'] != $baselink || $this->options['page_id'] != get_the_ID()) {
			$this->options['page_url'] = $baselink;
			$this->options['page_id'] = get_the_ID();
			update_option('task_options', $this->options);
		}
		
		$this->mode = '';
		
		// List filters
		// TODO keep in session
		$this->filters = array(
			'filter_project'	=> '20',
			'filter_task'		=> '20',
			'filter_recent'		=> 'all'
		); 
		foreach (array('filter_project', 'filter_task') as $key) {
			if (isset($_GET[$key]) && ($_GET[$key] == 'all' || preg_match('/^\d+$/', $_GET[$key]))) {
				$this->filters[$key] = $_GET[$key];
			}
		}
		if (isset($_GET['filter_recent']) && in_array($_GET['filter_recent'], array('all', 'tasks', 'projects', 'comments'))) {
			$this->filters['filter_recent'] = $_GET['filter_recent'];
		}
		
		// ACTION
		
		if (isset($_GET['hist'])) {
			
			// task history (called by Ajax if JS is enabled)
			$this->call('front/task_history.php');
			
		} else if (isset($_GET['view'])) {
			
			// view task
			$this->call('front/task_view.php');
			
		} else if (isset($_GET['edit']) && isset($_GET['js'])) {
			
			// update task status
			$this->call('front/task_status.php');
			
		} else if (isset($_REQUEST['edit'])) {
			
			// edit or create task
			$this->call('front/task_edit.php');
			
		} else if (isset($_GET['proj']) && isset($_GET['js'])) {
			
			// get users list for the project (ajax call)
			$this->call('front/project_users.php');
			
		} else if (isset($_REQUEST['proj'])) {
			
			$this->mode = 'projects'; // tell nav view it's a project so we can add tasks filter
			
			// view project
			$this->call('front/project_view.php');
			
		} else {
		
			$this->mode = $this->options['task_all'];
			if (isset($_REQUEST['mode'])) {
				$this->mode = $_REQUEST['mode'];
			}
		
			switch ($this->mode) {
			case 'recent':
				// list recent updates
				$this->call('front/task_recent.php');
				break;
			case 'tasks':
				// list tasks
				$this->call('front/task_list.php'); 
				break;
			case 'projects':
			default:
				// list projects
				$this->mode = 'projects';
				$this->call('front/project_list.php');
				break;	
			}
		}

		// Return view
		return $this->view_front();
	}
}

task_front::init();
