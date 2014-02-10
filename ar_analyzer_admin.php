<?php 
    //Normal page display
    $consumerkey = get_option('aranalyzer_consumerkey'); 
    $secretkey = get_option('aranalyzer_secretkey');
    $aranalyzer_state_keys = get_option('aranalyzer_state_keys');
    
    if($_POST['aranalyzer_saved'] == 'Y') {
  
      if(empty($consumerkey) && empty($secretkey)) {
        
        ?>
        <div class="error"><p><strong><?php _e('The consumer and secret keys are wrong, please get them first before continue.'); ?></strong></p></div>
        <?php
                
      } else {
        $tracking = $_POST['aranalyzer_tracking'];
        update_option('aranalyzer_tracking', $tracking);
        
        $date = current_time( 'mysql' ); 
        update_option('aranalyzer_tracking_date', $date);
                        
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
        <?php
      }
        
    } else {
        $tracking = get_option('aranalyzer_tracking');
    }
?>

<div class="wrap">
  <?php echo "<h2>" . __( 'Atomic Engager Configuration', 'aranalyzer_trdom' ) . "</h2>"; ?>
  
  <form name="aranalyzer_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="aranalyzer_saved" value="Y">
    <?php echo "<h4>" . __( 'Atomic Engager Settings', 'aranalyzer_trdom' ) . "</h4>"; ?>
    
    <div id="ar-btns-state">
<?php if( (empty($consumerkey) || empty($secretkey)) || ($aranalyzer_state_keys === 'FALSE' || empty($aranalyzer_state_keys)) ): ?>
        
        <div id="arInfoMsg"><p>This plugin works with the Atomic Reach Scoring Engine. Paired together, creators are able to analyze their historical content with our Scoring Engine and realize their Atomic Score, through a series of proprietary measures based on their target audience.</p>
                            <p>The Atomic Engager™ allows content creators to optimize their work before it’s published in real-time so that it is best suited for their target audience and delivers maximized engagement everywhere that content is published.</p>
                            <p>Make sure you create an account at <a href="http://score.atomicreach.com/" target="_blank">score.atomicreach.com</a>.</p></div>
        
    <p><a href="#" class="ar_btn-login mw">Connect to AR</a></p>
    <p>To get the secret and consumer keys click on the Connect to AR button</p>  
<?php else:?>
    <p><span class="ar_btn-connected">Connected</span></p>
<?php endif;?>   
    </div>  
    
    <h3>Tracking.</h3>
    <p><input name="aranalyzer_tracking" type="checkbox" value="1" <?php checked( $tracking, 1 ); ?> />
  
       <?php _e("Allow tracking of this Wordpress installs anonymous data." ); ?></p>
    <p class="submit">
    <input type="submit" name="Submit" value="<?php _e('Update Options', 'aranalyzer_trdom' ) ?>" />
    </p>
  </form>
</div>
<?php 
   $qry = get_site_url();
?>
<!-- modal content -->
<div id="mw-modal-content">
  <div class="close"><a href="<?php echo $qry;?>" class="simplemodal-close">x</a></div>
  <div id="mw-modal-data">
<?php
    
    $qry .= '/wp-admin/admin.php?page=ar-analyzer-admin&' . 'mode=ar_callback';
    $qry = urlencode($qry);
    $cbUrl = AR_URL . '/account/remote-login?callback=' . $qry;
?>   
   <iframe id="AtomicReachLogin" src="<?php echo $cbUrl;?>" width="800" height="415" scrolling="no"></iframe> 
  </div>
</div>