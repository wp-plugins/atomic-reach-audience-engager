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

<div class="wrap">
	<?php echo "<h2>" . __('Atomic Engager Configuration', 'aranalyzer_trdom') . "</h2>"; ?>

	<form name="aranalyzer_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="aranalyzer_saved" value="Y">
		<?php echo "<h4>" . __('Atomic Engager Settings', 'aranalyzer_trdom') . "</h4>"; ?>

		<div id="ar-btns-state">

			<?php if ((empty($consumerkey) || empty($secretkey)) || ($aranalyzer_state_keys === 'FALSE' || empty($aranalyzer_state_keys))): ?>

				<a target="_blank" href="http://www.atomicreach.com/how-to-connect-wp-plugin/?utm_source=WP%20Plugin&utm_medium=<?php echo get_option('home'); ?>&utm_campaign=WP%20PLUGIN%20CTA"><img src="<?php echo plugins_url('/',
						__FILE__); ?>custom/AR-WP-CTA.png" alt="atomic engager cta"/></a>

				<p><a href="#" class="ar_btn-login mw">Connect to <img src="<?php echo plugins_url('/', __FILE__); ?>custom/ar-logo.gif"/></a></p>
				<!--				<p>To get the secret and consumer keys click on the Connect to AR button</p>-->
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
			<?php
				global $current_user;
				get_currentuserinfo();
			?>
			<!-- Begin MailChimp Signup Form -->
			<link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
			<style type="text/css">
				#mc_embed_signup {
					clear: left;
					width: 300px;
				}

				/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
				   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
				#mc_embed_signup .button {
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
				<form action="//atomicreach.us4.list-manage.com/subscribe/post?u=2a8048c104efa30d1b06df2a0&amp;id=dd2e84675a" method="post"
				      id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<h2>Get one-on-one help!</h2>

					<div class="mc-field-group">
						<label for="mce-EMAIL">Email Address </label>
						<input type="email" value="<?php echo $current_user->user_email; ?>" name="EMAIL" class="required email" id="mce-EMAIL">
					</div>
					<div id="mce-responses" class="clear">
						<div class="response" id="mce-error-response" style="display:none"></div>
						<div class="response" id="mce-success-response" style="display:none"></div>
					</div>
					<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
					<div style="position: absolute; left: -5000px;"><input type="text" name="b_2a8048c104efa30d1b06df2a0_dd2e84675a" tabindex="-1"
					                                                       value=""></div>
					<div class="clear"><input type="submit" value="Submit" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				</form>
			</div>
			<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
			<script type='text/javascript'>(function ($) {
					window.fnames = new Array();
					window.ftypes = new Array();
					fnames[0] = 'EMAIL';
					ftypes[0] = 'email';
					fnames[1] = 'FNAME';
					ftypes[1] = 'text';
					fnames[2] = 'LNAME';
					ftypes[2] = 'text';
				}(jQuery));
				var $mcj = jQuery.noConflict(true);</script>
			<!--End mc_embed_signup-->
		</div>

	</div>


</div><!-- end wrap -->

<!--<div class="aranalyzerCTA">
	<a target="_blank"
	   href="http://www.atomicreach.com/insights-beta/?utm_source=WP%20Plugin&utm_medium=<?php /*echo get_option('home'); */ ?>&utm_campaign=WP%20PLUGIN%20CTA"><img
			src="<?php /*echo plugins_url('/', __FILE__); */ ?>custom/greatContent.png"></a><br>

	<a target="_blank" href="http://atomicreach.us4.list-manage.com/subscribe?u=2a8048c104efa30d1b06df2a0&id=e8ff369a7f"><img
			src="<?php /*echo plugins_url('/', __FILE__); */ ?>custom/getItNow.png"></a>&nbsp;&nbsp;&nbsp;<a target="_blank"
	                                                                                                    href="http://www.atomicreach.com/#beintouch?utm_source=WP%20Plugin&utm_medium=<?php /*echo get_option('home'); */ ?>&utm_campaign=WP%20PLUGIN%20CTA"><img
			src="<?php /*echo plugins_url('/', __FILE__); */ ?>custom/needHelp.png"></a>

	<br><a target="_blank"
	       href="http://blog.atomicreach.com?utm_source=WP%20Plugin&utm_medium=<?php /*echo get_option('home'); */ ?>&utm_campaign=WP%20PLUGIN%20CTA"><img
			src="<?php /*echo plugins_url('/', __FILE__); */ ?>custom/readOurBlog.png"></a>
</div>-->
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