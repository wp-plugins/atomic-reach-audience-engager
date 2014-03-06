<?php 
/*
Plugin Name: Atomic Engager
Plugin URI: http://www.atomicreach.com
Description: Optimizing content for your target audience has never been easier.
Version: 1.6.50
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
//   define('API_HOST', 'http://api.probar.atomicreach.com');
//   define('AR_URL', 'http://probar.atomicreach.com'); 
  // define('API_HOST', 'http://api.arv3.local');
  // define('AR_URL', 'http://arv3.local'); 
  /* Staging */
//   define('API_HOST', 'https://api.dev.arv3.atomicreach.com'); // with SSL
//   define('AR_URL', 'http://dev.arv3.atomicreach.com');
  
  /* Production */
  define('API_HOST', 'https://api.score.atomicreach.com'); // with SSL
  define('AR_URL', 'http://score.atomicreach.com');    

  // if( !class_exists( 'WP_Http' ) )
    // require_once( ABSPATH . WPINC . '/class-http.php' );
  
  /*********************/
  /* Metabox functions */
  /*********************/

  function aranalyzer_metabox_init()
  {
    wp_enqueue_script('ar_meta_js', MY_PLUGIN_PATH . '/custom/meta.js', array( 'jquery'));
    wp_enqueue_style('ar_meta_css', MY_PLUGIN_PATH . '/custom/meta.css');
    wp_enqueue_script('ar_highlightRegex_js', MY_PLUGIN_PATH . '/highlightRegex/highlightRegex.js', array( 'jquery'));
    
    // thick box
    wp_enqueue_script('thickbox',null,array('jquery'));
              wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0');
        
    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/add_meta_box
 
    // add a meta box for each of the wordpress page types: posts and pages
    foreach (array('post','page') as $type) 
    {
      add_meta_box('aranalyzer_metabox', 'Atomic Engager', 'aranalyzer_metabox_setup', $type, 'side', 'high');
    }
  }
  
  add_action('admin_init','aranalyzer_metabox_init');


  /* oAuth check: this function check if the secret and consumer keys were set. 
   * This event will happen after doing a click on the Connect to AR button 
   * in the AR Optimizer section and the modal windows return those values
   * after login to Atomic Reach site
   * 
   * */
  function aranalyzer_oAuth_check() {    

    if ($_GET['mode'] == 'ar_callback' && isset($_GET['key']) && isset($_GET['secret'])) {
      
      update_option('aranalyzer_secretkey', '');
      update_option('aranalyzer_consumerkey', '');
      
      if (update_option('aranalyzer_secretkey', $_GET['secret']) && update_option('aranalyzer_consumerkey', $_GET['key']) ){
        update_option('aranalyzer_state_keys', 'TRUE');
        
        echo '<div style="background-color: #FFFFE0; border: 1px solid #E6DB55; padding: 0 0 0 6px;font-family:sans-serif; font-size:12px;">
                   <p id="aranalizerOk">The secret key and consumer key have being updated.</p>
                   <p>Close this window to continue</p>
              </div>';
      }else{
        echo '<div style="background-color:#FFEBE8;; border: 1px solid #CC0000; padding: 0 0 0 6px;font-family:sans-serif; font-size:12px;">
                <p>The secret key and consumer key have not being updated.</p>
                <p>close this window to continue</p>
              </div>';
      }
      exit(); 
    }
     
  }
  
  add_action('admin_init','aranalyzer_oAuth_check');
  /* End oAuth check */

  
  /* oAuth Callback response from modal windows. This function will prevent reload the WP site inside 
   * the modal windows.(see file modal/js/modal.windows.js)
   **/
  function aranalyzer_check_keys_callback() {   
      if ($_POST['modekeys']){
        echo true; 
        exit();
      }    
  }  
  
  add_action('admin_init','aranalyzer_check_keys_callback');  
  
  
  function aranalyzer_metabox_setup()
  {
    global $post;
    // using an underscore, prevents the meta variable
    // from showing up in the custom fields section
    $scoring = get_post_meta($post->ID, '_ar_scoring', TRUE);
    $ar_enabled = get_post_meta($post->ID, '_ar_meta_review_enabled', TRUE);
    $ar_audience = get_post_meta($post->ID, '_ar_meta_audience_list', TRUE);
    
    // will return TRUE if the keys have been set correctly in AR Optimizer (modal windows login to AR)
    $ar_state_keys = get_option('aranalyzer_state_keys');
    
    // if (!session_id()) {
      //  session_start();
    // }
    $error = $_SESSION['_ar_api_error'];

    $consumerKey = get_option('aranalyzer_consumerkey');
    $secretKey = get_option('aranalyzer_secretkey');   
    $audienceList = aranalyzer_api_getaudiencelist($consumerKey, $secretKey);

    // including the metabox php code
    require_once(MY_PLUGIN_FOLDER . '/custom/meta.php');
  }

  /*************************/
  /* Metabox Functions End */
  /*************************/

  /*************************/
  /*    API Interaction    */
  /*************************/

  function aranalyzer_api_getmetadata($consumerKey, $secretKey, $title, $content, $segmentId) {
    
    require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');
        
    $host = API_HOST;
    $apiClient = New AR_Client($host, $consumerKey, $secretKey);
    $apiClient->init();
    $result = $apiClient->analyzePost($content, $title, $segmentId);
    
    return $result;
  }

  function aranalyzer_api_getaudiencelist($consumerKey, $secretKey) {
    
    require_once(MY_PLUGIN_FOLDER . '/includes/ARClient.php');
        
    $host = API_HOST;
    $apiClient = New AR_Client($host, $consumerKey, $secretKey);
    $apiClient->init();

    $result = $apiClient->getAudienceList();
    
    return $result;
  }

  function aranalyzer_admin() {
    // this option will have the state of the keys in case of error will set to FALSE
    $ar_state_keys = get_option('ar_state_keys');
    require_once('ar_analyzer_admin.php');
    
  }

  function aranalyzer_admin_actions() {
    wp_enqueue_script('ar_simple.modal_js', MY_PLUGIN_PATH . 'modal/js/jquery.simplemodal.js', array( 'jquery'));
    wp_enqueue_script('ar_modal.windows_js', MY_PLUGIN_PATH . 'modal/js/modal.windows.js', array( 'jquery'));
    wp_enqueue_style('ar_modal_css', MY_PLUGIN_PATH . '/modal/css/modal-windows.css');
    add_menu_page("Atomic Engager Configuration", "Atomic Engager", 1, "ar-analyzer-admin", "aranalyzer_admin", plugin_dir_url( __FILE__ ) . "custom/ar-logo-icon.gif");
  }
  
  add_action('admin_menu', 'aranalyzer_admin_actions');

  function aranalyzer_review($post_ID) {
    
   // if (!session_id()) {
       // session_start();
   // }      
    // Get post information
    $post_info = get_post($post_ID); 
    $title = $post_info->post_title;
    $content = apply_filters('the_content', $post_info->post_content);
    $ar_api_status = true;
    $ar_api_error = "";

    // Save the analizer is active option
    $meta_key = '_ar_meta_review_enabled';
    $value = ( isset( $_POST[$meta_key] ) ? $_POST[$meta_key] : '' );
    delete_post_meta( $post_ID, $meta_key);
    add_post_meta( $post_ID, $meta_key, $value, true );
    if($value === "enabled") $analyzer_active = true;

    // Save the audience list option selected
    $meta_key = '_ar_meta_audience_list';
    $segmentId = $value = ( isset( $_POST[$meta_key] ) ? $_POST[$meta_key] : '' );
    delete_post_meta( $post_ID, $meta_key);
    add_post_meta( $post_ID, $meta_key, $value, true );

    if ($analyzer_active) {
      /* After a long time looking through formatting functions              *
       * I found this combination that left HTML code without encoding and   *
       * all the other text formatted with HTML entities encoding            */
      
      $title = htmlspecialchars_decode( htmlentities( $title, ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES );
      $content = htmlspecialchars_decode( htmlentities( $content, ENT_NOQUOTES, 'UTF-8', false ), ENT_NOQUOTES );
      
      // Call the API with the post contents.
      $consumerKey = get_option('aranalyzer_consumerkey');
      $secretKey = get_option('aranalyzer_secretkey');   
      $scoringObj = aranalyzer_api_getmetadata($consumerKey, $secretKey, $title, $content, $segmentId);
      
  

      // delete_post_meta($post_ID, '_ar_api_status');
      update_option('aranalyzer_state_keys', 'TRUE');
      
      if(isset($scoringObj->error)){ 
       // delete_post_meta($post_ID, '_ar_api_error');
       // add_post_meta($post_ID,'_ar_api_error', $scoringObj->error, TRUE);
       // add_post_meta($post_ID,'_ar_api_status', 'FALSE', TRUE);
        update_option('aranalyzer_state_keys', 'FALSE');
        
        $_SESSION['_ar_api_error'] = $scoringObj->error;
        
      }
      else
      {
          
        $_SESSION['_ar_api_error'] = false;
                
        // Fix the special case when only one suggestion comes in the spelling options
        foreach($scoringObj->data->analysis->sm->detail as $key => $value) {
          if(!is_array($value->suggestions->option)) {
            $value->suggestions->option = array($value->suggestions->option);
          }
        }

        // Fix the special case when only one suggestion comes in the grammar options
        foreach($scoringObj->data->analysis->gm->detail as $key => $value) {
          if(isset($value->suggestions->option)) {
            if(!is_array($value->suggestions->option)) {
              $value->suggestions->option = array($value->suggestions->option);
            }
          }
        }

        delete_post_meta($post_ID, '_ar_scoring');
        // Update metadata and recover it again to update the UI.
        $current_data = get_post_meta($post_ID, '_ar_scoring', TRUE);
        if ($current_data) 
        {
          update_post_meta($post_ID,'_ar_scoring',$scoringObj);
        }
        elseif (!is_null($scoringObj))
        {
          add_post_meta($post_ID,'_ar_scoring',$scoringObj,TRUE);
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
function aranalyzer_tracking_admin_init(){
    $opt_in = get_option('aranalyzer_tracking');
    
    if ( isset($opt_in) && $opt_in ) {
        require_once( MY_PLUGIN_FOLDER . '/includes/ARTracking.php');
        $ar_tracking = new AR_Tracking(API_HOST);
    }   
}


/**
 * checks if the Dashboard or the administration panel is attempting to be displayed
 * http://codex.wordpress.org/Function_Reference/is_admin
 */
if ( is_admin() ) {
   add_action( 'plugins_loaded', 'aranalyzer_tracking_admin_init' );
}

  /**
   * Used to get the state to be display in scoring list comparing recommended words against sentences 
   * 
   */ 
  function aranalyzer_get_state($dpObject) {  
   
   if(!isset($dpObject->scoring)) {
     $scoring= 40; // add text in case there is not a value
   }else{
     $scoring = $dpObject->scoring;
   }
   
   $dpObject = $dpObject->analysis;
     
   $aColorText = array();
   
   if($scoring <= 33){
     $aColorText = array(
                     'arBarColor' => 'red',
                     'arText'     => 'Content edits are needed',
                     'scoring' => $scoring,
                   );
     
   }elseif($scoring > 33 && $scoring<= 66){
     $aColorText = array(
                     'arBarColor' => 'yellow',
                     'arText'     => 'Content edits are recommended',
                     'scoring' => $scoring,
                   );     
   }else{
     $aColorText = array(
                     'arBarColor' => 'green',
                     'arText'     => 'Content refinements are suggested',
                     'scoring' => $scoring,
                   );       
   }
   
   return $aColorText;
   
  }
  /** plugin Tour **/
/**
 * Adds a simple WordPress pointer to Settings menu
 */
 
function ar_enqueue_pointer_script_style( $hook_suffix ) {
	
	// Assume pointer shouldn't be shown
	$enqueue_pointer_script_style = false;

	// Get array list of dismissed pointers for current user and convert it to array
	$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	// Check if our pointer is not among dismissed ones
	if( !in_array( 'ar_settings_pointer', $dismissed_pointers ) ) {
		$enqueue_pointer_script_style = true;
		
		// Add footer scripts using callback function
		add_action( 'admin_print_footer_scripts', 'ar_pointer_print_scripts' );
	}

	// Enqueue pointer CSS and JS files, if needed
	if( $enqueue_pointer_script_style ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
	
}
add_action( 'admin_enqueue_scripts', 'ar_enqueue_pointer_script_style' );

function ar_pointer_print_scripts() {

	$pointer_content  = "<h3>Atomic Engager!</h3>";
	$pointer_content .= "<p>Click here to connect your plugin to the Atomic Reach Scoring Engine.</p>";
	?>
	
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		$('#toplevel_page_ar-analyzer-admin').pointer({
			content:		'<?php echo $pointer_content; ?>',
			position:		{
								edge:	'left', // arrow direction
								align:	'center' // vertical alignment
							},
			pointerWidth:	350,
			close:			function() {
								$.post( ajaxurl, {
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
/** plugin Tour **/

/**
 * Add action links in Plugins table
 */
 
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'atomicreach_plugin_action_links' );
function atomicreach_plugin_action_links( $links ) {

	return array_merge(
		array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=ar-analyzer-admin' ) . '">' . __( 'Settings', 'atomicreach' ) . '</a>'
		),
		$links
	);

}

   
?>