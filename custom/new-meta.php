<?php
	$admin_settings_url = '/wp-admin/admin.php?page=ar-analyzer-admin';
	$light_blue = '#91c7f9';
	$light_orange = '#FFA20C';
	$cKey = get_option('aranalyzer_consumerkey');
	$sKey = get_option('aranalyzer_secretkey');

	// this is to make test without calling to the API
	$test = FALSE;
	if ($test) {
		include('metabox-harcoded.php');
	} else {?>
	<?php
		if (empty($cKey) || empty($sKey)) {
			?>
			<a href="<?php echo bloginfo('url'); ?>/wp-admin/admin.php?page=ar-analyzer-admin">
				<img src="<?php echo plugins_url('/', __FILE__); ?>plugin-post-cta.png" alt="atomic engager cta" width="240"/></a>
		<?php
		} else {
			?>
			<div class="ar_meta_control">
			<?php if ($isAjax != 1): ?>
				<?php if ($ar_enabled === ''): ?>
					<p>Check the box below to analyze your content. Make sure you select your target audience. Hit save
						draft to get your results.</p>

					<div class="greyWhite">&nbsp;</div>
				<?php endif; ?>
				<p class="enabledArea">
					<?php
						if ($ar_enabled === 'enabled') $ar_enabled = "checked"; ?>
					<a target="_blank" href="http://score.atomicreach.com?utm_source=WP%20Plugin&utm_medium=' <?php echo get_option('home') ; ?>
				'&utm_campaign=WP%20PLUGIN%20CTA-editphp">
						<img style=" display: block; margin: 0 auto;" src="<?php echo plugins_url('/', __FILE__); ?>ViewMyDashboard.png" alt="atomic
				insights cta"/></a>
					<input name="_ar_meta_review_enabled" id="_ar_meta_review_enabled" type="checkbox"
					       value="enabled" <?php echo $ar_enabled; ?> />
					<span>Analyze This Post’s Content</span>
				</p>



				<p class="contentSource">
					<strong>Target Audience Segment </strong><a href="http://help.atomicreach.com/question/who-is-my-target-audience/"
					                                            target="_blank">?</a>
					<select name="_ar_meta_audience_list" id="_ar_meta_audience_list">
						<?php foreach ($audienceList->sophisticatonBands as $sb) { ?>
							<?php $selected = ($ar_audience == $sb->id) ? 'selected' : ''; ?>
							<option data-audience="<?php echo strtoupper(str_replace('* ', '', $sb->name)); ?>" value="<?php echo $sb->id; ?>" <?php
								echo $selected; ?>><?php echo $sb->name;
								?></option>
						<?php } ?>
					</select>
					<a class="ARinfoToolTip" href=""><img style="vertical-align: middle;"
					                                      src="<?php echo plugins_url('/', __FILE__); ?>infoIcon.png"/>
						<span><img src="<?php echo plugins_url('/', __FILE__); ?>AR-KnowledgeWheel-Words-0914-400.png"
						           alt="atomic reach pie of sophistication"/></span>
					</a>


				</p>
			<?php endif; //isAjax ?>

			<img id="AR-Loading" src="<?php echo plugins_url('/', __FILE__); ?>AR-Loading.gif" class="AR-hide"
			     style="margin: 0 auto;"/>

			<div id="ARajaxData">
			<?php if ($error): ?>
				<?php if ($error === 'invalid access token'): ?>

					<?php if ($ar_state_keys === 'FALSE'): ?>
						<?php echo '<span style="color:red;">The secret and consumer keys are wrong. Please </span><a href="' . $admin_settings_url . '">click here</a>
                <span style="color:red;">and then click on Connect to AR button to get new ones.</span>'; ?>
					<?php endif; ?>

				<?php else: ?>
					<span style="color:red;"><?php echo "Error: " . $error; ?></span>
				<?php endif; ?>
			<?php endif; ?>

			<?php
				if ($scoring->data->analysis): ?>

					<?php $aBarColorText = aranalyzer_get_state($scoring->data); ?>
					<div class="bar-shadow box-score">
						<div class="bar circle-score">
							<div class="scoreText">
								<!--Content Score:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
								<span><?php echo round(number_format($aBarColorText['scoring'], 1)); ?></span>
							</div>
						</div>
						<div class="aud-pie">

							<?php
								if ($scoring->data->analysis->so->detail == 'TOO COMPLEX') {
									$pieImg = 'AR-TooComplicated.png';
								} elseif ($scoring->data->analysis->so->detail == 'TOO SIMPLE') {
									$pieImg = 'AR-TooSimple.png';
								} elseif ($scoring->data->analysis->so->detail == 'HIT') {
									if ($targetAud == 'GENERAL') {
										$pieImg = 'AR-Match-General.png';
									} elseif ($targetAud == 'KNOWLEDGEABLE') {
										$pieImg = 'AR-Match-Knowledgable.png';
									} elseif ($targetAud == 'ACADEMIC') {
										$pieImg = 'AR-Match-Academic.png';
									} elseif ($targetAud == 'SPECIALIST') {
										$pieImg = 'AR-Match-Specialist.png';
									} elseif ($targetAud == 'GENIUS') {
										$pieImg = 'AR-Match-Genius.png';
									}
								}
							?>
							<img src="<?php echo plugins_url('/', __FILE__) . $pieImg; ?>"
							     alt="atomic reach pie of sophistication"/>
						</div>
					</div>
					<!-- End Score and Aud box -->

					<!--     Tabs starts here       -->
					<div id="AR-newUI">
					<p style="color: #888888;font-size: 12px;line-height: 12px;margin: 0">Choose from the buttons below to
						learn how to optimize your article to best suit your audience</p>
					<a class="ARinfoToolTip" href=""><img style="vertical-align: bottom;"
					                                      src="<?php echo plugins_url('/', __FILE__); ?>infoIcon.png"/>
						<span>The analysis of your content is grouped into 4 buckets: title, audience match,
							structure and linguistics. Click on each bar to reveal insights about your content. As you make changes,
							hit rescore and re-evaluate your content.</span>
					</a>
					<ul class="AR-tabs">
						<li><a class="AR-tabsButton" id="AR-tabsSummary" href="#arTabContent">Summary</a></li>
						<li><a class="AR-tabsButton" href="#arTabTitle">Title</a></li>
						<li><a class="AR-tabsButton" href="#arTab1Audience">Audience</a></li>
						<li><a class="AR-tabsButton" href="#arTabStructure">Structure</a></li>
						<li><a class="AR-tabsButton" href="#arTabLinguistics">Linguistics</a></li>
					</ul>
					<span style="clear: both"></span>
					<!--    arTabContent        -->
					<div id="arTabContent" class="AR-tabBlock">


						<h3>TOP 3 QUICK FIXES</h3>
						<ul class="threeOpp ar_spritelist">
							<?php
								$stopAtThree = 0;
								$measState = $scoring->data->analysis;


								// 1 check audience match
								if ($measState->so->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->so->state === "yellow") ? 'warning' : 'error') . '">
								Your post is not an audience match. Hit AUDIENCE to find out why.</li>';
									$stopAtThree++;
								}
								// 2 check if title is not green.
								if ($measState->tm->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->tm->state === "yellow") ? 'warning' : 'error') . '">
								Your title needs work. Hit TITLE to find out why.</li>';
									$stopAtThree++;
								}
								// 3 check length
								if ($measState->ln->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->ln->state === "yellow") ? 'warning' : 'error') . '">
								  Length of post is not optimal. Hit STRUCTURE to find out why.</li>';
									$stopAtThree++;
								}
								// 4 Check Grammar
								if ($measState->gm->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->gm->state === "yellow") ? 'warning' : 'error') . '">
								Improve your grammar. Hit LINGUISTICS to find out how.</li>';
									$stopAtThree++;
								}

								// 4.5 Check Paragraph Word Density
								if ($measState->pwd->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->pwd->state === "yellow") ? 'warning' : 'error') . '">
								Some of your paragraphs are too long for your target audience. Hit STRUCTURE to find out how.</li>';
									$stopAtThree++;
								}

								// 5 check if emotion is not green
								if ($measState->em->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->em->state === "yellow") ? 'warning' : 'error') . '">
								Your post lacks emotion. Hit AUDIENCE to fix it.</li>';
									$stopAtThree++;
								}
								// 6 Check Spelling
								if ($measState->sm->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->sm->state === "yellow") ? 'warning' : 'error') . '">
								Incorrect spelling detected. Hit LINGUISTICS to find out why.</li>';
									$stopAtThree++;
								}
								// 7 Links
								if ($measState->lc->state !== 'green' && $stopAtThree != 3) {
									echo '<li class="ar_checkmarks_' . (($measState->lc->state === "yellow") ? 'warning' : 'error') . '">
								Some links aren\'t working. Hit LINGUISTICS to find out more.</li>';
									$stopAtThree++;
								}
								// 8 Check Topics
								/*if($measState->tpp->state !== 'green' && $stopAtThree != 3){
										echo '';
										$stopAtThree ++;
									}*/
							?>
						</ul>

						<?php if ($scoring->data->analysis->sm->total > 0 || $scoring->data->analysis->gm->total > 0 ||
							$scoring->data->analysis->lc->invalid > 0 || !empty($scoring->data->analysis->so->paragraphs) ||
							!empty($scoring->data->analysis->pwd->detail->paragraphs)
						): ?>

							<p><strong>Select which area(s) you wish to highlight:</strong></p>

							<ul class="hl-btns">
								<?php if ($scoring->data->analysis->sm->total > 0): ?>
									<li>
										<input type="checkbox" id="chksp" name="chk" value="all">
										<label for="chksp">Spelling Mistakes</label>
									</li>
									<script>
										jQuery("#chksp").data('words', <?php
                                $words = array();
                                foreach($scoring->data->analysis->sm->detail as $key => $value)
                                    $words[] = $value->string;
                                echo json_encode($words); ?>);
									</script>
								<?php endif; ?>
								<?php if ($scoring->data->analysis->gm->total > 0): ?>
									<li>
										<input type="checkbox" id="chkgm" name="chk" value="false">
										<label for="chkgm">Grammar Insights</label>
									</li>
									<script>
										jQuery("#chkgm").data('words', <?php
                                    $words = array();
                                    foreach($scoring->data->analysis->gm->detail as $key => $value)
                                        $words[] = $value->string;
                                    echo json_encode($words); ?>);
									</script>
								<?php endif; ?>
								<?php if ($scoring->data->analysis->lc->invalid > 0): ?>
									<li>
										<input type="checkbox" id="chkul" name="chk" value="true">
										<label for="chkul">Underperforming Links</label>
									</li>
								<?php endif; ?>
								<?php if (!empty($scoring->data->analysis->so->paragraphs)): ?>
									<li>
										<input
											class="<?php echo strtolower(str_replace(" ", "-", $scoring->data->analysis->so->detail)); ?>"
											type="checkbox" id="chkso" name="chk" value="false">
										<label for="chkso">Audience Mismatch</label>

									</li>
									<script>
										jQuery("#chkso").data('paragraphs', <?php echo json_encode($scoring->data->analysis->so->paragraphs); ?>);
										jQuery("#chkso").data('domExpression', <?php echo json_encode($scoring->data->analysis->so->paragraphDOM); ?>);
										jQuery("#chkso").data('tooSimpleColor', <?php echo json_encode($light_blue); ?>);
										jQuery("#chkso").data('tooComplexColor', <?php echo json_encode($light_orange); ?>);
									</script>
								<?php endif; ?>
									<!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\ Paragraph density \\\\\\\\\\\\\\\\\\\\\\\\ -->
								<?php if ($scoring->data->analysis->pwd->state !== 'green'): ?>
									<li>
										<input type="checkbox" id="chkpwd" name="chk" value="false">
										<label for="chkpwd">Paragraph Density</label>
									</li>
									<script>
										jQuery("#chkpwd").data('paragraphs', <?php echo json_encode($scoring->data->analysis->pwd->detail->paragraphs); ?>);
										jQuery("#chkpwd").data('domExpression', <?php echo json_encode($scoring->data->analysis->pwd->detail->paragraphDOM); ?>);
										jQuery("#chkpwd").data('tooShortColor', <?php echo json_encode('#F6C9CB'); ?>);
										jQuery("#chkpwd").data('tooLongColor', <?php echo json_encode('#C98BD1'); ?>);
									</script>
								<?php endif; ?>
							</ul>
						<?php endif; ?>
					</div>

					<!--       arTab2Title         -->
					<div id="arTabTitle" class="AR-tabBlock">
						<ul class="ar_spritelist" id="accordion">
							<!-- Title -->
							<?php
								$class = 'ar_checkmarks_error';
								switch (strtolower($scoring->data->analysis->tm->state)) {
									case 'green':
										$class = 'ar_checkmarks_passed';
										break;
									case 'yellow':
										$class = 'ar_checkmarks_warning';
										break;
									case 'red':
										$class = 'ar_checkmarks_error';
										break;
								}
							?>
							<li class="<?php echo $class; ?>">
								<div>
									<?php if (strtolower($scoring->data->analysis->tm->state) !== 'green'): ?>
									<a href="#so"><?php echo $scoring->data->analysis->tm->message; ?></a>
								</div>
								<ul class="details title-measure">
									<?php
										foreach ($scoring->data->analysis->tm->recomendations as $recomendation)
											echo '<li><span class="soText">' . $recomendation . '</span></li>';
									?>
									<p class="AR-guide">Refer to our guide on how to create engaging titles <a
											href="http://hub.atomicreach.com/i/341645/" target="_blank"
											class="toggle-link ar-message-link"><span>here</span></a>.</p>
								</ul>
								<?php else: ?>
									<?php echo $scoring->data->analysis->tm->message; ?>
								<?php
									endif;
								?>
							</li>
						</ul>
					</div>

					<!--        3 arTabAudience           -->
					<div id="arTab1Audience" class="AR-tabBlock">

						<?php if (!empty($scoring->data->analysis->so->paragraphs)): ?>

						<p><strong>Select which area(s) you wish to highlight:</strong></p>

						<ul class="hl-btns">
							<?php if (!empty($scoring->data->analysis->so->paragraphs)): ?>
								<li>
									<input
										class="<?php echo strtolower(str_replace(" ", "-", $scoring->data->analysis->so->detail)); ?>"
										type="checkbox" id="chkso2" name="chk" value="false">
									<label for="chkso2">Audience Mismatch</label>
								</li>
							<?php endif; ?><?php endif; ?>
						</ul>


						<ul class="ar_spritelist" id="accordion">
							<!-- Audience Match -->
							<?php if ($scoring->data->analysis->so === 'UNAVAILABLE'): ?>
							<?php else: ?>
								<?php
								switch (strtolower($scoring->data->analysis->so->state)) {
									case 'green':
										$class = 'ar_checkmarks_passed';
										break;
									case 'yellow':
										$class = 'ar_checkmarks_warning';
										break;
									case 'red':
										$class = 'ar_checkmarks_error';
										break;
								}
								?>
								<li class="<?php echo $class; ?>">
									<div>
										<?php if (strtolower($scoring->data->analysis->so->state) !== 'green'): ?>
										<a href="#so">Your content is <?php $scoring->data->analysis->so->detail; ?> for your
											audience</a></div>
									<ul class="details paragraph-sophistication">
										<?php
											$ul_so = '';
											if ($scoring->data->analysis->so->detail === 'TOO SIMPLE') {
												$ar_message = 'Your article is too simple for your audience. To get the most of your content, 85% of your paragraphs need to be a match. Consider revising some of your highlighted paragraphs by writing longer sentences and using sophisticated language.';
											} elseif ($scoring->data->analysis->so->detail === 'TOO COMPLEX') {
												$ar_message = 'Your article is too complex for your audience. To get the most of your content, 85% of your paragraphs need to be a match. Consider revising some of your highlighted paragraphs by writing shorter sentences and simplifying your language.';
											}

											$ul_so .= '<li style="background: none;">
                                    <p class="ar-message">' . $ar_message . '</p>
						  		<br />
							<p class="AR-guide"><a href="http://hub.atomicreach.com/i/303975/" target="_blank" class="toggle-link ar-message-link">Refer to our tips on how to achieve a content sophistication match for your audience <span>here</span></a>.</p>
                                  </li>';
											echo $ul_so;
										?>
									</ul>
									<?php else: ?>
										<?php echo $scoring->data->analysis->so->message; ?>
									<?php
										endif;
									?>
								</li>
							<?php endif; ?>

						</ul>


					</div>

					<!--    arTab4Structure    -->
					<div id="arTabStructure" class="AR-tabBlock">

						<!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\ Paragraph density \\\\\\\\\\\\\\\\\\\\\\\\ -->
						<?php if ($scoring->data->analysis->pwd->state !== 'green'){ ?>
						<p><strong>Select which area(s) you wish to highlight:</strong></p>
						<ul class="hl-btns">
							<li>
								<input type="checkbox" id="chkpwd2" name="chk" value="false">
								<label for="chkpwd2">Paragraph Density</label>
							</li>
							<?php } ?>
						</ul>


						<ul class="ar_spritelist" id="accordion">
							<!-- Topics List -->
							<?php $total_topics = $scoring->data->analysis->tg->total; ?>
							<?php if (strtolower($scoring->data->analysis->tg->state) === 'green'): ?>
						<li class="ar_checkmarks_passed">
						<?php elseif (strtolower($scoring->data->analysis->tg->state) === 'yellow'): ?>
						<li class="ar_checkmarks_warning">
						<?php elseif (strtolower($scoring->data->analysis->tg->state) === 'red'): ?>
							<li class="ar_checkmarks_error">
								<?php endif; ?>
								<?php if ($total_topics > 0): ?>
									<div>
										<a href="#tg">There <?php echo ($total_topics == 1) ? 'is ' : 'are '; ?> <?php echo $total_topics; ?>
											Topic<?php echo ($total_topics == 1) ? '' : 's'; ?> in your post</a></div>
									<div>
										<ul class="details">
											<p style="font-weight: 600;">Are these the topics you intended to represent your
												article? If not, revise your post to better express your ideas. To optimize
												your
												title, use 1-4 topics below.</p>
											<?php
												$ul_tg = '';
												foreach ($scoring->data->analysis->tg->detail as $key => $value) {
													$ul_tg .= '<li>' . $value . '</li>';
												}
												echo $ul_tg;
											?>
										</ul>
									</div>
								<?php else: ?>
									<div>There are no identified topics.</div>
								<?php endif; ?>
							</li>

							<!-- Length vs. Recommended Length -->
							<?php
								// states: passed (green) - warning (yellow) - error (red)
								$ms = $scoring->data->analysis->ln->measured->sentences;
								$rsMin = $scoring->data->analysis->ln->recommended->sentencesMin;
								$rsMax = $scoring->data->analysis->ln->recommended->sentencesMax;
								// $state = arnanlyzer_rsl_state($ms, $rs);
								$state = strtolower($scoring->data->analysis->ln->state);
								switch ($state) {
									case 'green':
										echo '<li class="ar_checkmarks_passed"><div>Length of post is ' . $ms . ' sentences, which meets the minimum recommended length of ' . $rsMin . '-' . $rsMax . ' sentences.</div></li>';
										break;
									case 'yellow':
										echo '<li class="ar_checkmarks_warning"><div>Length of post is ' . $ms . ' sentences. We recommend a range of ' . $rsMin . '-' . $rsMax . ' sentences.</div></li>';
										break;
									case 'red':
										echo '<li class="ar_checkmarks_error"><div>Length of post is ' . $ms . ' sentences. We recommend a range of ' . $rsMin . '-' . $rsMax . ' sentences.</div></li>';
										break;
								}
							?>
							<!-- Link Checking -->
							<?php if (strtolower($scoring->data->analysis->lc->state) === 'green'): ?>
						<li class="ar_checkmarks_passed">
						<?php elseif (strtolower($scoring->data->analysis->lc->state) === 'yellow'): ?>
						<li class="ar_checkmarks_warning">
						<?php elseif (strtolower($scoring->data->analysis->lc->state) === 'red'): ?>
							<li class="ar_checkmarks_error">
								<?php endif; ?>
								<?php if ($scoring->data->analysis->lc->invalid > 0): ?>
									<div>
										<a href="#lc"><?php echo $scoring->data->analysis->lc->invalid . ' of the ' . $scoring->data->analysis->lc->total . ' link'; ?><?php echo ($scoring->data->analysis->lc->invalid == 1) ? '' : 's'; ?>
											included in the
											post<br/> <?php echo ($scoring->data->analysis->lc->invalid == 1) ? ' is' : ' are'; ?>
											underperforming.</a>
									</div>
									<div>
										<ul class="details invalid-links">
											<?php
												$ul_lc = '';
												foreach ($scoring->data->analysis->lc->detail as $key => $value) {
													$ul_lc .= '<li><span class="ilText">' . $value . '</span>';
													$ul_lc .= '<li>- These links are either slow to load or not working</li>';
												}
												echo $ul_lc;
											?>
										</ul>
									</div>
								<?php else: ?>
									<div>All links detected are valid.</div>
								<?php endif; ?>
							</li>

							<!-- Paragraph Word Density vs. Recommended Paragraph Word Density -->
							<?php

								// states: passed (green) - warning (yellow) - error (red)
								$ms = 100 - $scoring->data->analysis->pwd->measured->percentageParagraphsIdealWordCountMetadata;
								//$pwdMin = $scoring->data->analysis->pwd->recommended->paragraphWordDensityMin;
								$pwdMax = $scoring->data->analysis->pwd->recommended->paragraphWordDensityMax;
								// $state = arnanlyzer_rsl_state($ms, $rs);
								$state = strtolower($scoring->data->analysis->pwd->state);
								switch ($state) {
									case 'green':
										echo '<li class="ar_checkmarks_passed"><div>Your paragraphs are an ideal length for your target audience.</div></li>';
										break;
									case 'yellow':
										echo '<li class="ar_checkmarks_warning"><div>' . $ms . '% of your paragraphs are too long for your target
										audience. Write paragraphs with no greater than ' . $pwdMax . ' words.</div></li>';
										break;
									case 'red':
										echo '<li class="ar_checkmarks_error"><div>' . $ms . '% of your paragraphs are too long for your target
										audience. Write paragraphs with no greater than ' . $pwdMax . ' words.</div></li>';
										break;
								}
							?>

						</ul>
					</div>
					<!--   4 arTabLinguistics    -->
					<div id="arTabLinguistics" class="AR-tabBlock">
						<?php if ($scoring->data->analysis->sm->total > 0 || $scoring->data->analysis->gm->total > 0): ?>

						<p><strong>Select which area(s) you wish to highlight:</strong></p>

						<ul class="hl-btns">
							<?php if ($scoring->data->analysis->sm->total > 0): ?>
								<li>
									<input type="checkbox" id="chksp2" name="chk" value="all">
									<label for="chksp2">Spelling Mistakes</label>
								</li>

							<?php endif; ?>
							<?php if ($scoring->data->analysis->gm->total > 0): ?>
								<li>
									<input type="checkbox" id="chkgm2" name="chk" value="false">
									<label for="chkgm2">Grammar Insights</label>
								</li>

							<?php endif; ?><?php endif; ?>
						</ul>
						<ul class="ar_spritelist" id="accordion">
							<!--		Emotions				-->
							<?php if (strtolower($scoring->data->analysis->em->state) === 'green'): ?>
						<li class="ar_checkmarks_passed">
						<?php elseif (strtolower($scoring->data->analysis->em->state) === 'yellow'): ?>
						<li class="ar_checkmarks_warning">
						<?php elseif (strtolower($scoring->data->analysis->em->state) === 'red'): ?>
							<li class="ar_checkmarks_error">
								<?php endif; ?>

								<div><a href="#em">Emotions</a></div>
								<div>
									<ul class="details emotion-details">
										<?php
											$ul_tg = '';
											foreach ($scoring->data->analysis->em->dimensions as $key => $value) {
												$ul_tg .= '<li class="emotion-' . strtolower($value->state) . '"><strong>' . $value->name . '</strong>: ' . $value->detail . '</li>';
											}
											echo $ul_tg;
										?>
										<p class="AR-guide">Refer to our tips on how to write with emotion <a
												href="//hub.atomicreach.com/i/303948/" target="_blank"
												class="toggle-link ar-message-link"><span>here</span></a>.</p>
									</ul>
								</div>

							</li>
							<!-- Spelling Mistakes -->
							<?php if (strtolower($scoring->data->analysis->sm->state) === 'green'): ?>
						<li class="ar_checkmarks_passed">
						<?php elseif (strtolower($scoring->data->analysis->sm->state) === 'yellow'): ?>
						<li class="ar_checkmarks_warning">
						<?php elseif (strtolower($scoring->data->analysis->sm->state) === 'red'): ?>
							<li class="ar_checkmarks_error">
								<?php endif; ?>
								<?php if (count($scoring->data->analysis->sm->detail) > 0): ?>
									<div>
										<a href="#sm">There <?php echo (count($scoring->data->analysis->sm->detail) == 1) ? 'is ' : 'are '; ?> <?php echo $scoring->data->analysis->sm->total; ?>
											misspelled word(s).</a></div>
									<div>
										<ul class="details spelling-mistakes">
											<li><p>Hit the Spelling Mistakes button above to highlight these words in your
													article.
													Fix the ones that are incorrect or right click a word to add it to your
													custom
													dictionary.</p></li>
											<?php
												$ul_sm = '';
												foreach ($scoring->data->analysis->sm->detail as $key => $value) {
													$ul_sm .= '<li><span class="smText">' . $value->string . '</span>';

													/*echo '<pre>';
													 print_r($value);
													echo '</pre>';*/
													// check if there are at least one suggestion
													if (isset($value->suggestions->option[0]) && !empty($value->suggestions->option[0])) {
														$suggestions = '';
														$sOptions    = is_array($value->suggestions->option) ? $value->suggestions->option : array($value->suggestions->option);
														foreach ($sOptions as $values) {
															$suggestions .= $values . ', ';
														}
														$suggestions = substr(trim($suggestions), 0, -1); // remove last comma
														$ul_sm .= ' - <b>Suggestions</b>: <i>' . $suggestions . '</i></li>';
													}
													// url
													if (isset($value->url) && !empty($value->url)) {
														$ul_sm .= '<li><a class="toggle-link" href="' . $value->url . '" target="_blank">See explanation</a></li>';
													}
												}
												echo $ul_sm;
											?>
										</ul>
									</div>
									<script>
									</script>
								<?php else: ?>
									<div>There are no spelling mistakes.</div>
								<?php endif; ?>
							</li>

							<!-- Grammar Mistakes -->
							<?php if (strtolower($scoring->data->analysis->gm->state) === 'green'): ?>
							<li class="ar_checkmarks_passed">
								<?php elseif (strtolower($scoring->data->analysis->gm->state) === 'yellow'): ?>
							<li class="ar_checkmarks_warning">
								<?php elseif (strtolower($scoring->data->analysis->gm->state) === 'red'): ?>
							<li class="ar_checkmarks_error">
								<?php endif; ?>

								<?php if ($scoring->data->analysis->gm->total > 0): ?>
									<?php if ($scoring->data->analysis->gm->total == 1): ?>
										<div><a href="#gm">There is <?php echo $scoring->data->analysis->gm->total; ?>
												grammar
												insight.</a></div>
									<?php else: ?>
										<div><a href="#gm">There are <?php echo $scoring->data->analysis->gm->total; ?>
												grammar
												insights.</a></div>
									<?php endif; ?>
									<div>
										<ul class="details grammar-mistakes">
											<li><p>These grammatical recommendations will improve your post.</p></li>
											<?php
												$ul_gm = '';
												foreach ($scoring->data->analysis->gm->detail as $key => $value) {
													$ul_gm .= '<li><span class="gmText">' . $value->string . '</span>';

													// check if there are at least one suggestion
													if (isset($value->suggestions->option[0]) && !empty($value->suggestions->option[0])) {
														$suggestions = '';
														foreach ($value->suggestions->option as $keys => $values) {
															$suggestions .= $values . ', ';
														}
														$suggestions = substr(trim($suggestions), 0, -1);
														$ul_gm .= ' - <b>Suggestions</b>: <i>' . $suggestions . '</i></li>';
													}
													// url
													if (isset($value->url) && !empty($value->url)) {
														$ul_gm .= '<li><a class="toggle-link" href="' . $value->url . '" target="_blank">See explanation</a></li>';
													}
													?>
												<?php
												}
												echo $ul_gm;
											?>
											<p class="AR-guide">Refer to our guide on how to write with an active voice <a
													href="//hub.atomicreach.com/i/304278/" target="_blank"
													class="toggle-link ar-message-link"><span>here</span></a>.</p>
										</ul>

									</div>
								<?php else: ?>
									<div>There are no grammar errors.</div>
								<?php endif; ?>
							</li>
						</ul>

					</div>
					<div class="AR-TabsFooter">
						<p>To see your current results, press the rescore button below.</p>
						<a href="//help.atomicreach.com" target="_blank" class="ar-help">Help</a>
						<a id="AR-scoreBtn" class="arScoreBtn" href="#">RESCORE</a>
						<span style=" color: #666565; font-size: 11px;">Click “Save Draft” or "Update" to store all your edits for this article.</span>
					</div>

					</div>

					<!--     Tabs ends here         -->

				<?php endif; ?>
			</div>
			<!-- ARajaxData -->
			</div>
		<?php } // endif consumer and secret key check ?>
	<?php } // test ?>