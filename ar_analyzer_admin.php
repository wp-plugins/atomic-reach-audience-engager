<?php
	//Normal page display
	$consumerkey = get_option('aranalyzer_consumerkey');
	$secretkey = get_option('aranalyzer_secretkey');
	$aranalyzer_state_keys = get_option('aranalyzer_state_keys');

	if ($_POST['aranalyzer_saved'] == 'Y') {

		if (empty($consumerkey) && empty($secretkey)) {

			$tracking = $_POST['aranalyzer_tracking'];
			$arView   = $_POST['aranalyzer_view'];
			$arRSS    = $_POST['aranalyzer_RSS'];


			update_option('aranalyzer_view', $arView);
			update_option('aranalyzer_RSS', $arRSS);
			update_option('aranalyzer_tracking', $tracking);

			$date = current_time('mysql');
			update_option('aranalyzer_tracking_date', $date);

			?>
			<div class="error"><p><strong><?php _e('The consumer and secret keys are wrong, please get them first before continue.'); ?></strong></p>
			</div>
		<?php

		} else {
			$tracking = $_POST['aranalyzer_tracking'];
			$arView   = $_POST['aranalyzer_view'];
			$arRSS    = $_POST['aranalyzer_RSS'];


			update_option('aranalyzer_view', $arView);
			update_option('aranalyzer_RSS', $arRSS);
			update_option('aranalyzer_tracking', $tracking);

			$date = current_time('mysql');
			update_option('aranalyzer_tracking_date', $date);

			?>
			<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
		<?php
		}
		if ($_POST['aranalyzer_RSS'] == 1)
			flush_rewrite_rules();
	} else {
		$tracking = get_option('aranalyzer_tracking');
		$arView   = get_option('aranalyzer_view');
		$arRSS    = get_option('aranalyzer_RSS');
	}
?>
<?php
	global $current_user;
	get_currentuserinfo();
?>

<div class="wrap">
	<?php echo "<h2>" . __('Atomic Engager Configuration', 'aranalyzer_trdom') . "</h2>"; ?>

	<form id='hb-form' class='jotform-form' name='form_43305729254' id='43305729254' method="post" action="<?php echo str_replace('%7E', '~',
		$_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="aranalyzer_saved" value="Y">
		<?php echo "<h4>" . __('Atomic Engager Settings', 'aranalyzer_trdom') . "</h4>"; ?>

		<div id="ar-btns-state">

			<?php if ((empty($consumerkey) || empty($secretkey)) || ($aranalyzer_state_keys === 'FALSE' || empty($aranalyzer_state_keys))): ?>



				<a target="_blank" href="http://www.atomicreach.com/how-to-connect-wp-plugin/?utm_source=WP%20Plugin&utm_medium=<?php echo get_option('home'); ?>&utm_campaign=WP%20PLUGIN%20CTA"><img src="<?php echo plugins_url('/',
						__FILE__); ?>custom/AR-WP-CTA.png" alt="atomic engager cta"/></a>

				<!--	Hatchbuck Form	-->
				<script src='//app.hatchbuck.com/OnlineForm/js/cdn/jotform.js' type='text/javascript'></script>
				<script type='text/javascript'>
					var jsTime = setInterval(function(){try{JotForm.jsForm = true;
						JotForm.init(function(){$('input_4').hint('ex: myname@example.com');JotForm.highlightInputs = false;});
						clearInterval(jsTime); }catch(e){}}, 1000);

					function submitHb() {
						console.log('one');
							var email_add = jQuery('#input_4').val();
						if (email_add != '') {

							console.log('here now');
							document.form_43305729254.action = "https://app.hatchbuck.com/onlineForm/submit.php"
							document.form_43305729254.target = "_blank";    // Open in a new window
							document.form_43305729254.submit();             // Submit the page
						return false;
						} else {

							console.log('here');
							jQuery('.ar_errorMsg').show().delay(5000).fadeOut();
//							return false;

						}
					}
				</script>

			<style type='text/css'>
				.AR-loginBox {
				border: 1px solid;
				border-radius: 3px;
				box-shadow: 0 0 10px #acacac;
				margin: 45px 0;
				max-width: 600px;
				padding: 20px;
				}
				.AR-loginBox input#input_4{
					border: 1px solid;
				}
			</style>
				<input type='hidden' name='formID' value='43305729254' />

				<div class="AR-loginBox">
				<h2>Connect To Atomic Reach:</h2>
					<p>In order to connect your account, and make sure you're not a robot, we need to know who you are</p>
					<p class="ar_errorMsg" style="display: none; color: #ff0000;">Please provide an email address.</p>
					<div>
					<label id='label_4' for='input_4'>E-mail<span class='form-required'>*</span></label>
					<input type='email' class='validate[required, Email]' id='input_4' name='q4_email' size='50' required />
				</div>

				<p><a href="#"  onclick="submitHb();" class="ar_btn-login mw">Connect to <img src="<?php echo plugins_url('/',
							__FILE__); ?>custom/ar-logo.gif"/></a></p>

					</div>
				<input type='hidden' id='simple_spc' name='simple_spc' value='43305729254-43305729254'/>
				<input type='hidden' id='enableServerValidation' name='enableServerValidation' value='1'/>
				 <input type='hidden' id='enable303Redirect' name='enable303Redirect' value='0'/>
			<?php else: ?>
				<p><span class="ar_btn-connected">Connected</span></p>
			<?php endif; ?>
		</div>

		<h3>Tracking.</h3>

		<p><input name="aranalyzer_tracking" type="checkbox" value="1" <?php checked($tracking, 1); ?> />

			<?php _e("Allow tracking of this Wordpress installs anonymous data."); ?></p>

		<h3>View:</h3>

		<p><input name="aranalyzer_view" type="checkbox" value="1" <?php checked($arView, 1); ?> />
			<?php _e("Use modern view."); ?>
		</p>

		<h3>RSS:</h3>

		<p><input name="aranalyzer_RSS" type="checkbox" value="1" <?php checked($arRSS, 1); ?> />
			<?php _e("Enable the special RSS link with full content."); ?>
			<br>
			<small>Copy and paste this RSS link in your Atomic Insights account so that the platform can collect data about your audience(s).</small>
			<code><?php bloginfo('url'); ?>/feed/arfeed/</code>
		</p>

		<p class="submit">
			<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'aranalyzer_trdom') ?>"/>
		</p>

	</form>

	<div class="aranalyzer-rightpanel">

		<div id="aranalyzer-rightForm">

			<!-- Begin 1on1 Signup Form -->

			<style type="text/css">
				#mc_embed_signup {
					clear: left;
					width: 300px;
					padding: 5px;
				}
				#mc_embed_signup input{
					max-width: 250px;
				}
				#mc_embed_signup .button {
					margin-top: 5px ;
					-moz-box-shadow: inset 0px 1px 0px 0px #f5978e;
					-webkit-box-shadow: inset 0px 1px 0px 0px #f5978e;
					box-shadow: inset 0px 1px 0px 0px #f5978e;
					background-color: #db2537;
					-webkit-border-top-left-radius: 7px;
					-moz-border-radius-topleft: 7px;
					border-top-left-radius: 7px;
					-webkit-border-top-right-radius: 7px;
					-moz-border-radius-topright: 7px;
					border-top-right-radius: 7px;
					-webkit-border-bottom-right-radius: 7px;
					-moz-border-radius-bottomright: 7px;
					border-bottom-right-radius: 7px;
					-webkit-border-bottom-left-radius: 7px;
					-moz-border-radius-bottomleft: 7px;
					border-bottom-left-radius: 7px;
					text-indent: 0px;
					border: 1px solid #c41d2e;
					display: inline-block;
					color: #ffffff;
					font-size: 20px;
					font-weight: bold;
					font-style: normal;
					height: 38px;
					line-height: 38px;
					width: 100px;
					text-decoration: none;
					text-align: center;
					text-shadow: 1px 1px 0px #810e05;
				}

				#mc_embed_signup .button:hover {
					background-color: #d9505e;
				}

				#mc_embed_signup .button:active {
					position: relative;
					top: 1px;
				}

				#mc-embedded-subscribe-form > h2:before {
					content: url("<?php echo plugins_url('/', __FILE__); ?>custom/ar-logo.gif");
					margin-right: 5px;
					vertical-align: top;
				}
			</style>
			<div id="mc_embed_signup">

				<form id='hb-form' class='jotform-form' action='https://app.hatchbuck.com/onlineForm/submit.php' method='post' name='form_43345948285' id='43345948285' accept-charset='utf-8'>
					<h2>Get one-on-one help!</h2>
					<input type='hidden' name='formID' value='43345948285' />
					<div>
						<label id='label_4' for='input_4'>E-mail<span class='form-required'>*</span></label>
						<input type='email' class='validate[required, Email]' id='input_4' value="<?php echo $current_user; ?>" name='q4_email'
						       size='50' />
					</div>
					<button id='input_2' type='submit' class='form-submit-button button'>Submit</button>
					<input type='hidden' id='simple_spc' name='simple_spc' value='43345948285-43345948285'/>
					<input type='hidden' id='enableServerValidation' name='enableServerValidation' value='1'/>
					<input type='hidden' id='enable303Redirect' name='enable303Redirect' value='0'/>
				</form>


			</div>

		</div>

	</div>


</div><!-- end wrap -->


<?php
	$qry = get_site_url();
?>
<!-- modal content -->
<div id="mw-modal-content">
	<div class="close"><a href="<?php echo $qry; ?>" class="simplemodal-close">x</a></div>
	<div id="mw-modal-data">
		<?php

			$qry .= '/wp-admin/admin.php?page=ar-analyzer-admin&' . 'mode=ar_callback';
			$qry = urlencode($qry);
			$cbUrl = AR_URL . '/account/remote-login?callback=' . $qry;
		?>
		<iframe id="AtomicReachLogin" src="<?php echo $cbUrl; ?>" width="800" height="415" scrolling="no"></iframe>
	</div>
</div>