<?php
	/*
	  Plugin Name: Atomic Engager
	  Plugin URI: http://www.atomicreach.com
	  Description: Optimizing content for your target audience has never been easier.
	  Version: 2.0.19
	  Author URI: http://www.atomicreach.com
	  Author: atomicreach
	 */
	if (!session_id()) {
		session_start();
	}

	define('MY_WORDPRESS_FOLDER', $_SERVER['DOCUMENT_ROOT']);
	define('MY_PLUGIN_FOLDER', str_replace("\\", '/', dirname(__FILE__)));
	define('MY_PLUGIN_PATH', plugins_url('/', __FILE__));

	/* Development */
	// define('API_HOST', 'http://api.probar.atomicreach.com');
	// define('AR_URL', 'http://probar.atomicreach.com');
	//define('AR_URL', 'http://arv3.local');
	/* Staging */
	//	define('API_HOST', 'https://api.dev.arv3.atomicreach.com'); // with SSL
	//	define('AR_URL', 'http://dev.arv3.atomicreach.com');

	/* Production */
	define('API_HOST', 'https://api.score.atomicreach.com'); // with SSL
	define('AR_URL', '//score.atomicreach.com');

	// if( !class_exists( 'WP_Http' ) )
	// require_once( ABSPATH . WPINC . '/class-http.php' );

	/*********************/
	/* Metabox functions */
	/*********************/

	function aranalyzer_metabox_init()
	{
		wp_enqueue_script('ar_meta_js', MY_PLUGIN_PATH . '/custom/meta.js', array('jquery'));
		wp_enqueue_style('ar_meta_css', MY_PLUGIN_PATH . '/custom/meta.css');
		wp_enqueue_script('ar_highlightRegex_js', MY_PLUGIN_PATH . '/highlightRegex/highlightRegex.js', array('jquery'));
		//wp_enqueue_script('ar_customDictionaryContextMenu_js', MY_PLUGIN_PATH . '/customDictionaryContextMenu/editor_plugin.js', array('jquery'));

		// review the function reference for parameter details
		// http://codex.wordpress.org/Function_Reference/add_meta_box
		// add a meta box for each of the wordpress page types: posts and pages and all custom ones
		// get all the post types excluding default ones
		$arg_post_types = array(
			'public'   => TRUE,
			'_builtin' => FALSE
		);
		$post_types     = get_post_types($arg_post_types, 'names');
		// add  post and pages as well.
		array_push($post_types, 'post', 'page');
		foreach ($post_types as $type) {
			add_meta_box('aranalyzer_metabox', 'Atomic Engager', 'aranalyzer_metabox_setup', $type, 'side', 'high');
		}
	}

	add_action('admin_init', 'aranalyzer_metabox_init');


	/* oAuth check: this function check if the secret and consumer keys were set.
	 * This event will happen after doing a click on the Connect to AR button
	 * in the AR Optimizer section and the modal windows return those values
	 * after login to Atomic Reach site
	 *
	 * */
	function aranalyzer_oAuth_check()
	{

	if (isset($_GET['mode']) && isset($_GET['key']) && isset($_GET['secret'])) {
		if ($_GET['mode'] == 'ar_callback' ) {
			update_option('aranalyzer_secretkey', '');
			update_option('aranalyzer_consumerkey', '');
			if (update_option('aranalyzer_secretkey', $_GET['secret']) && update_option('aranalyzer_consumerkey', $_GET['key'])) {
				update_option('aranalyzer_state_keys', 'TRUE');
				echo '<div style="background-color: #FFFFE0; border: 1px solid #E6DB55; padding: 0 0 0 6px;font-family:sans-serif; font-size:12px;">
                   <p id="aranalizerOk">The secret key and consumer key have being updated.</p>
					<p>Close this window to continue</p>
              </div>';
			} else {
				echo '<div style="background-color:#FFEBE8;; border: 1px solid #CC0000; padding: 0 0 0 6px;font-family:sans-serif; font-size:12px;">
                <p>The secret key and consumer key have not being updated.</p>
				<p>Close this window to continue</p>
              </div>';
			}
			exit();
		}
		}
	}

	add_action('admin_init', 'aranalyzer_oAuth_check');
	/* End oAuth check */


	/* oAuth Callback response from modal windows. This function will prevent reload the WP site inside
	 * the modal windows.(see file modal/js/modal.windows.js)
	 **/
	function aranalyzer_check_keys_callback()
	{

		if ($_POST['modekeys']) {
			echo TRUE;
			exit();
		}

	}

	add_action('admin_init', 'aranalyzer_check_keys_callback');


	function aranalyzer_metabox_setup()
	{
		global $post;
		// using an underscore, prevents the meta variable
		// from showing up in the custom fields section
		$scoring     = get_post_meta($post->ID, '_ar_scoring', TRUE);
		$ar_enabled  = get_post_meta($post->ID, '_ar_meta_review_enabled', TRUE);
		$ar_audience = get_post_meta($post->ID, '_ar_meta_audience_list', TRUE);


//      $custom_fields = get_post_custom($post->ID);
//  $my_custom_field = $custom_fields['_ar_meta_audience_list'];
//  foreach ( $my_custom_field as $key => $value ) {
//    echo $key . " => " . $value . "<br />";
//  }

		// will return TRUE if the keys have been set correctly in AR Optimizer (modal windows login to AR)
		$ar_state_keys = get_option('aranalyzer_state_keys');

		// if (!session_id()) {
		//  session_start();
		// }
		$error = $_SESSION['_ar_api_error'];

		$consumerKey  = get_option('aranalyzer_consumerkey');
		$secretKey    = get_option('aranalyzer_secretkey');
		$audienceList = aranalyzer_api_getsophisticationbandlist($consumerKey, $secretKey);


//    Store user selected targetAudience to show the pie image. HZ
		/*foreach ($audienceList->sources AS $source) {
			foreach ($source->segments AS $v) {
				if ($v->id == $ar_audience) {
					$targetAud = $v->targetAudience;
				}
			}
		}*/

		foreach ($audienceList->sophisticatonBands AS $SophBand) {
			if ($SophBand->id == $ar_audience) {
				$targetAud = strtoupper(str_replace('* ', '', $SophBand->name));
			}
		}


		$arView = get_option('aranalyzer_view');

		// including the metabox php code
		if ($arView == 1) {
			require_once(MY_PLUGIN_FOLDER . '/custom/new-meta.php');
		} else {
			require_once(MY_PLUGIN_FOLDER . '/custom/meta.php');
		}

	}

	/*************************/
	/* Metabox Functions End */
	/*************************/

	/*************************/
	/*    API Interaction    */
	/*************************/

	function aranalyzer_api_getmetadata($consumerKey, $secretKey, $title, $content, $segmentId)
	{

		require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');

		$host      = API_HOST;
		$apiClient = New AR_Client($host, $consumerKey, $secretKey);
		$apiClient->init();
		$result = $apiClient->analyzePost($content, $title, $segmentId);

		return $result;
	}

	// get 5 bands.
	function aranalyzer_api_getsophisticationbandlist($consumerKey, $secretKey)
	{
		require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');
		$host      = API_HOST;
		$apiClient = New AR_Client($host, $consumerKey, $secretKey);
		$apiClient->init();
		$result = $apiClient->getSophisticationBandList();

		return $result;
	}

	function aranalyzer_api_getaudiencelist($consumerKey, $secretKey)
	{

		require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');

		$host      = API_HOST;
		$apiClient = New AR_Client($host, $consumerKey, $secretKey);
		$apiClient->init();

		$result = $apiClient->getAudienceList();

		return $result;
	}

	function ar_analyzer_custom_dictionary()
	{
		$success = FALSE;
		$word    = $_GET['word'];
		if ($word) {
			require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');

			$host        = API_HOST;
			$consumerKey = get_option('aranalyzer_consumerkey');
			$secretKey   = get_option('aranalyzer_secretkey');
			$apiClient   = New AR_Client($host, $consumerKey, $secretKey);
			$apiClient->init();

			$result = $apiClient->addDictionary($word);

			if ($result->status == AR_Client::STATUS_OK) {
				$success = TRUE;
			}
		}
		echo $success ? 'OK' : 'ERROR';
		exit();
	}

	add_action('wp_ajax_ar_analyzer_custom_dictionary', 'ar_analyzer_custom_dictionary');

	function aranalyzer_admin()
	{
		// this option will have the state of the keys in case of error will set to FALSE
		$ar_state_keys = get_option('ar_state_keys');
		require_once('ar_analyzer_admin.php');

	}

	function aranalyzer_admin_actions()
	{
		wp_enqueue_script('ar_simple.modal_js', MY_PLUGIN_PATH . 'modal/js/jquery.simplemodal.js', array('jquery'));
		wp_enqueue_script('ar_modal.windows_js', MY_PLUGIN_PATH . 'modal/js/modal.windows.js', array('jquery'));
		wp_enqueue_style('ar_modal_css', MY_PLUGIN_PATH . '/modal/css/modal-windows.css');
		add_menu_page("Atomic Engager Configuration", "Atomic Engager", "manage_options", "ar-analyzer-admin", "aranalyzer_admin",
			plugin_dir_url(__FILE__) . "custom/ar-logo-icon.png");
	}

	add_action('admin_menu', 'aranalyzer_admin_actions');

	function aranalyzer_review($post_ID = 0)
	{

		if ($post_ID == 0) {
			$post_ID = intval($_POST['postID']);
			$arajax  = 1;
		}

		// code by Sergio
		global $wp_version;
		if (version_compare($wp_version, '3.8', '<')) {
			global $flag;
			if ($flag != 1) {
				$flag = 1;

				return;
			}
		}
		// End Sergio Code
		// if (!session_id()) {
		// session_start();
		// }
		// Get post information
		$post_info = get_post($post_ID);
		$title     = $post_info->post_title;

		/**
		 * We are removing the wptexturize before creating the content value to be send to api because it is changing single quotes
		 * with #8217 char and if makes the analysis fail.
		 *
		 * check
		 * http://codex.wordpress.org/Function_Reference/wptexturize
		 */
		remove_filter('the_content', 'wptexturize');
		$content = apply_filters('the_content', $post_info->post_content);
		add_filter('the_content', 'wptexturize');

		$ar_api_status = TRUE;
		$ar_api_error  = "";

		// Save the analizer is active option
		$meta_key = '_ar_meta_review_enabled';
		$value    = (isset($_POST[$meta_key]) ? $_POST[$meta_key] : '');
		delete_post_meta($post_ID, $meta_key);
		add_post_meta($post_ID, $meta_key, $value, TRUE);
		if ($value === "enabled") $analyzer_active = TRUE;

		// Save the audience list option selected
		$meta_key  = '_ar_meta_audience_list';
		$segmentId = $value = (isset($_POST[$meta_key]) ? $_POST[$meta_key] : '');
		delete_post_meta($post_ID, $meta_key);
		add_post_meta($post_ID, $meta_key, $value, TRUE);

		if ($analyzer_active) {
			/* After a long time looking through formatting functions              *
			 * I found this combination that left HTML code without encoding and   *
			 * all the other text formatted with HTML entities encoding            */

			$title   = htmlspecialchars_decode(htmlentities($title, ENT_NOQUOTES, 'UTF-8', FALSE), ENT_NOQUOTES);
			$content = htmlspecialchars_decode(htmlentities($content, ENT_NOQUOTES, 'UTF-8', FALSE), ENT_NOQUOTES);
			// Call the API with the post contents.
			$consumerKey = get_option('aranalyzer_consumerkey');
			$secretKey   = get_option('aranalyzer_secretkey');
			$scoringObj  = aranalyzer_api_getmetadata($consumerKey, $secretKey, $title, $content, $segmentId);

			if ($arajax == 1) {
				echo $scoringObj;
			}

			// delete_post_meta($post_ID, '_ar_api_status');
			update_option('aranalyzer_state_keys', 'TRUE');

			if (isset($scoringObj->error)) {
				// delete_post_meta($post_ID, '_ar_api_error');
				// add_post_meta($post_ID,'_ar_api_error', $scoringObj->error, TRUE);
				// add_post_meta($post_ID,'_ar_api_status', 'FALSE', TRUE);
				update_option('aranalyzer_state_keys', 'FALSE');

				$_SESSION['_ar_api_error'] = $scoringObj->error;
			} else {

				$_SESSION['_ar_api_error'] = FALSE;

				// Fix the special case when only one suggestion comes in the spelling options
				foreach ($scoringObj->data->analysis->sm->detail as $key => $value) {
					if (isset($value->suggestions->option)) {
						if (!is_array($value->suggestions->option)) {
							$value->suggestions->option = array($value->suggestions->option);
						}
					}
				}

				// Fix the special case when only one suggestion comes in the grammar options
				foreach ($scoringObj->data->analysis->gm->detail as $key => $value) {
					if (isset($value->suggestions->option)) {
						if (!is_array($value->suggestions->option)) {
							$value->suggestions->option = array($value->suggestions->option);
						}
					}
				}

				delete_post_meta($post_ID, '_ar_scoring');
				// Update metadata and recover it again to update the UI.
				$current_data = get_post_meta($post_ID, '_ar_scoring', TRUE);
				if ($current_data) {
					update_post_meta($post_ID, '_ar_scoring', $scoringObj);
				} elseif (!is_null($scoringObj)) {
					add_post_meta($post_ID, '_ar_scoring', $scoringObj, TRUE);
				}
			}
		} else {
			delete_post_meta($post_ID, '_ar_scoring');
		}
	}

	/* hook when click on update */
	add_action('publish_post', 'aranalyzer_review');

	/* hook when save draft post */
	add_action('save_post', 'aranalyzer_review');

	/**
	 * Used to load the required files on the plugins_loaded hook, instead of immediately.
	 *
	 */
	function aranalyzer_tracking_admin_init()
	{
		$opt_in = get_option('aranalyzer_tracking');

		if (isset($opt_in) && $opt_in) {
			require_once(MY_PLUGIN_FOLDER . '/includes/ARTracking.php');
			$ar_tracking = new AR_Tracking(API_HOST);
		}
	}


	/**
	 * checks if the Dashboard or the administration panel is attempting to be displayed
	 * http://codex.wordpress.org/Function_Reference/is_admin
	 */
	if (is_admin()) {
		add_action('plugins_loaded', 'aranalyzer_tracking_admin_init');
	}

	/**
	 * Used to get the state to be display in scoring list comparing recommended words against sentences
	 *
	 */
	function aranalyzer_get_state($dpObject)
	{

		if (!isset($dpObject->scoring)) {
			$scoring = 40; // add text in case there is not a value
		} else {
			$scoring = $dpObject->scoring;
		}

		$dpObject = $dpObject->analysis;

		$aColorText = array();

		if ($scoring <= 33) {
			$aColorText = array(
				'arBarColor' => 'red',
				'arText'     => 'Content edits are needed',
				'scoring'    => $scoring,
			);

		} elseif ($scoring > 33 && $scoring <= 66) {
			$aColorText = array(
				'arBarColor' => 'yellow',
				'arText'     => 'Content edits are recommended',
				'scoring'    => $scoring,
			);
		} else {
			$aColorText = array(
				'arBarColor' => 'green',
				'arText'     => 'Content refinements are suggested',
				'scoring'    => $scoring,
			);
		}

		return $aColorText;

	}

	/*********************/
	/* TinyMCE custom functions */
	/*********************/

	function tiny_mce_custom_plugins()
	{
		global $wp_version;
		if (version_compare($wp_version, '3.9', '<')) {
			return array('customdictionarycontextmenu' => MY_PLUGIN_PATH . '/customDictionaryContextMenu/editor_plugin.js');
		} else {
			return array('customdictionarycontextmenu' => MY_PLUGIN_PATH . '/customDictionaryContextMenu/plugin.js');
		}
	}

	add_filter('mce_external_plugins', 'tiny_mce_custom_plugins');

	/** plugin Tour * */

	/**
	 * Adds a simple WordPress pointer to Settings menu
	 */
	function ar_enqueue_pointer_script_style($hook_suffix)
	{

		// Assume pointer shouldn't be shown
		$enqueue_pointer_script_style = FALSE;

		// Get array list of dismissed pointers for current user and convert it to array
		$dismissed_pointers = explode(',', get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', TRUE));

		// Check if our pointer is not among dismissed ones
		if (!in_array('ar_settings_pointer', $dismissed_pointers)) {
			$enqueue_pointer_script_style = TRUE;

			// Add footer scripts using callback function
			add_action('admin_print_footer_scripts', 'ar_pointer_print_scripts');
		}

		// Enqueue pointer CSS and JS files, if needed
		if ($enqueue_pointer_script_style) {
			wp_enqueue_style('wp-pointer');
			wp_enqueue_script('wp-pointer');
		}
	}

	add_action('admin_enqueue_scripts', 'ar_enqueue_pointer_script_style');

	function ar_pointer_print_scripts()
	{

		$pointer_content = "<h3>Atomic Engager!</h3>";
		$pointer_content .= "<p>Click here to connect your plugin to the Atomic Reach Scoring Engine.</p>";
		?>

		<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready(function ($) {
				$('#toplevel_page_ar-analyzer-admin').pointer({
					content: '<?php echo $pointer_content; ?>',
					position: {
						edge: 'left', // arrow direction
						align: 'center' // vertical alignment
					},
					pointerWidth: 350,
					close: function () {
						$.post(ajaxurl, {
							pointer: 'ar_settings_pointer', // pointer ID
							action: 'dismiss-wp-pointer'
						});
					}
				}).pointer('open');
			});
			//]]>
		</script>

	<?php
	}

	/** plugin Tour * */
	/**
	 * Add action links in Plugins table
	 */
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'atomicreach_plugin_action_links');

	function atomicreach_plugin_action_links($links)
	{

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url('admin.php?page=ar-analyzer-admin') . '">' . __('Settings', 'atomicreach') . '</a>'
			), $links
		);
	}


	add_action('wp_ajax_aranalyzer_ajax', 'aranalyzer_ajax_callback');

	function aranalyzer_ajax_callback()
	{
		/*$postID = intval($_POST['postID']);

		$post_info = get_post($postID);
		$title     = $post_info->post_title;*/

		$_title   = $_POST['arTitle'];
		$_content = $_POST['arContent'];

		/**
		 * We are removing the wptexturize before creating the content value to be send to api because it is changing single quotes
		 * with #8217 char and if makes the analysis fail.
		 *
		 * check
		 * http://codex.wordpress.org/Function_Reference/wptexturize
		 */
		/*remove_filter('the_content', 'wptexturize');
		$content = apply_filters('the_content', $post_info->post_content);
		add_filter('the_content', 'wptexturize');*/

//		$ar_audience = get_post_meta($postID, '_ar_meta_audience_list', TRUE);

		$ar_api_status = TRUE;
		$ar_api_error  = "";


//    if ($analyzer_active) {
		/* After a long time looking through formatting functions              *
		 * I found this combination that left HTML code without encoding and   *
		 * all the other text formatted with HTML entities encoding            */

		$title   = htmlspecialchars_decode(htmlentities($_title, ENT_NOQUOTES, 'UTF-8', FALSE), ENT_NOQUOTES);
		$content = htmlspecialchars_decode(htmlentities($_content, ENT_NOQUOTES, 'UTF-8', FALSE), ENT_NOQUOTES);
		// Call the API with the post contents.
		$consumerKey = get_option('aranalyzer_consumerkey');
		$secretKey   = get_option('aranalyzer_secretkey');
		$segmentId   = intval($_POST['segmentId']);

		$audienceList = aranalyzer_api_getsophisticationbandlist($consumerKey, $secretKey);

//    Store user selected targetAudience to show the pie image. HZ
		foreach ($audienceList->sophisticatonBands AS $SophBand) {
			if ($SophBand->id == $segmentId) {
				$targetAud = strtoupper(str_replace('* ', '', $SophBand->name));
			}
		}


		$host = API_HOST;

		require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');
		$apiClient = New AR_Client($host, $consumerKey, $secretKey);
		$apiClient->init();

		$scoring = $apiClient->analyzePost($content, $title, $segmentId);

		$isAjax = 1;

		$audienceList = aranalyzer_api_getsophisticationbandlist($consumerKey, $secretKey);

		ob_start();
		require_once(MY_PLUGIN_FOLDER . '/custom/new-meta.php');
		$data = ob_get_clean();

		echo $data;


		die(); // this is required to return a proper result
	}


	// Add admin bar menu
	add_action('wp_before_admin_bar_render', 'aranalyzer_admin_bar_render');

	function aranalyzer_admin_bar_render()
	{
		global $wp_admin_bar;
		// we can add a submenu item too
		$wp_admin_bar->add_menu(array(
			'parent' => '',
			'id'     => 'atomicreach',
			'title'  => __('<img src="' . plugin_dir_url(__FILE__) . 'custom/ar-logo-icon.png"  style="vertical-align:middle;margin-right:5px"
			alt="Atomic Reach" title="Atomic Reach" />Atomic Engager'),
			'href'   => 'http://www.atomicreach.com?utm_source=WP%20Plugin&utm_medium=' . get_option('home') . '&utm_campaign=WP%20PLUGIN%20ADMINBAR'
		));

		$wp_admin_bar->add_menu(array(
			'parent' => 'atomicreach',
			'id'     => 'atomicreach1',
			'title'  => __('Signup'),
			'href'   => 'http://score.atomicreach.com?utm_source=WP%20Plugin&utm_medium=' . get_option('home') .
				'&utm_campaign=WP%20PLUGIN%20ADMINBAR',
			'meta'   => array(
				'title'  => __('Signup'),
				'target' => '_blank',
				'class'  => 'ar_score'
			),
		));

		$wp_admin_bar->add_menu(array(
			'parent' => 'atomicreach',
			'id'     => 'atomicreach3',
			'title'  => __('Contact us'),
			'href'   => 'http://www.atomicreach.com/about-us/#contact?utm_source=WP%20Plugin&utm_medium=' . get_option('home') . '&utm_campaign=WP%20PLUGIN%20ADMINBAR',
			'meta'   => array(
				'title'  => __('Contact us'),
				'target' => '_blank',
				'class'  => 'ar_contact_us'
			),
		));


	}


	/*** EXTRA FEED ***/
	add_action('init', 'arfeed');

	function arfeed()
	{
		$arRSS = get_option('aranalyzer_RSS');
		if ($arRSS == 1)
			add_feed('arfeed', 'arcustomRSS');
	}

	function arcustomRSS()
	{
		$arRSS = get_option('aranalyzer_RSS');
//		get_template_part('feed', 'two');

//		if ($arRSS == 1) {
		global $more;
		$more      = -1;
		$postCount = 150; // The number of posts to show in the feed
		$posts     = query_posts('showposts=' . $postCount);
		header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), TRUE);
		echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>' . PHP_EOL;
		echo '<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/"' . PHP_EOL;
		do_action('rss2_ns');
		echo '>' . PHP_EOL;
		echo '<channel>' . PHP_EOL;
		echo '<title>';
		bloginfo_rss('name');
		echo ' - Feed</title>' . PHP_EOL;
		echo '<atom:link href="';
		self_link();
		echo '" rel="self" type="application/rss+xml" />' . PHP_EOL;
		echo '<link>';
		bloginfo_rss('url');
		echo '</link>' . PHP_EOL;
		echo '<description>';
		bloginfo_rss('description');
		echo '</description>' . PHP_EOL;
		echo '<lastBuildDate>' . mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), FALSE) . '</lastBuildDate>' . PHP_EOL;
		echo '<language>' . get_option('rss_language') . '</language>' . PHP_EOL;
		echo '<sy:updatePeriod>' . apply_filters('rss_update_period', 'hourly') . '</sy:updatePeriod>' . PHP_EOL;
		echo '<sy:updateFrequency>' . apply_filters('rss_update_frequency', '1') . '</sy:updateFrequency>' . PHP_EOL;
		do_action('rss2_head');
		while (have_posts()) : the_post();
			echo '<item>';
			echo '<title>';
			the_title_rss();
			echo '</title>' . PHP_EOL;
			echo '<link>';
			the_permalink_rss();
			echo '</link>' . PHP_EOL;
			echo '<pubDate>' . mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', TRUE), FALSE) . '</pubDate>' . PHP_EOL;
			echo '<dc:creator>';
			the_author();
			echo '</dc:creator>' . PHP_EOL;
			echo '<guid isPermaLink="false">';
			the_guid();
			echo '</guid>' . PHP_EOL;
			echo '<description><![CDATA[';
			the_content(); //the_excerpt_rss();
			echo ']]></description>' . PHP_EOL;
			echo '<content:encoded><![CDATA[';
			the_content();
			echo ']]></content:encoded>' . PHP_EOL;
			rss_enclosure();
			do_action('rss2_item');
			echo '</item>' . PHP_EOL;
		endwhile;
		echo '</channel>' . PHP_EOL;
		echo '</rss>' . PHP_EOL;


//		}
	}


	// TOP banner
	function aranalyzer_admin_user_area_notice()
	{
		$screen = get_current_screen();

		if (current_user_can('manage_options')) {
			$consumerkey           = get_option('aranalyzer_consumerkey');
			$secretkey             = get_option('aranalyzer_secretkey');
			$aranalyzer_state_keys = get_option('aranalyzer_state_keys');
			if ($screen->id !== 'toplevel_page_ar-analyzer-admin') {
				if ((empty($consumerkey) || empty($secretkey)) || ($aranalyzer_state_keys === 'FALSE' || empty($aranalyzer_state_keys))) {
					echo ' <div class="update-nag">
                 <img src="' . plugin_dir_url(__FILE__) . 'custom/ar-logo.gif" /><p>&nbsp;&nbsp;&nbsp;<strong>Almost Done!<strong>
                 <a href="' . admin_url('admin.php?page=ar-analyzer-admin') . '">Click Here</a> to setup the Atomic Engager plugin.</p>
          </div>';
				}
			}
		}
	}

	add_action('admin_notices', 'aranalyzer_admin_user_area_notice');


	function atomic_engager_activate()
	{
// custom feed
		update_option('aranalyzer_RSS', 1);
		update_option('aranalyzer_view', 1);
		arfeed();
		flush_rewrite_rules();
	}

	register_activation_hook(__FILE__, 'atomic_engager_activate');

	function atomic_engager_deactivation()
	{
		flush_rewrite_rules();

		delete_option('aranalyzer_tracking');
		delete_option('aranalyzer_view');
		delete_option('aranalyzer_RSS');
	}

	register_deactivation_hook(__FILE__, 'atomic_engager_deactivation');
?>