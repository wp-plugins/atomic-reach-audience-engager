<?php
$admin_settings_url = '/wp-admin/admin.php?page=ar-analyzer-admin';
$light_blue = '#91c7f9';
$light_orange = '#FFA20C';

// this is to make test without calling to the API
$test = false;
    
      
    
      
      
      
if ($test) {
    include('metabox-harcoded.php');
} else {
    ?>
    <div class="ar_meta_control">

        <?php if ($ar_enabled === ''): ?>
            <p>Check the box below to analyze your content. Make sure you select your target audience. Hit save draft to get your results.</p>
            <!--<p>Analyze and view the current score of this post’s content. Atomic Reach will also suggest how to improve your content.</p>-->

            <div class="greyWhite">&nbsp;</div>
        <?php endif; ?>
        <p class="enabledArea">
            <?php if ($ar_enabled === 'enabled') $ar_enabled = "checked"; ?>
            <input name="_ar_meta_review_enabled" id="_ar_meta_review_enabled" type="checkbox" value="enabled" <?php echo $ar_enabled; ?> />
            <span>Analyze This Post’s Content</span>
        </p>

        <p class="contentSource">
            <strong>Target Audience Segment </strong><a href="http://www.atomicreach.com/wordpress-plugin-faqs/" target="_blank">?</a>
            <select name="_ar_meta_audience_list" id="_ar_meta_audience_list">
                <!-- Primary Sources List -->
                <?php
                // Primary Sources List
                foreach ($audienceList->sources as $sourceKey => $source) {
                    $sourceName = $source->name;
                    foreach ($source->segments as $segmentKey => $segment) {
                        $segmentName = $segment->name;
                        $segmentId = $segment->id;
                        $selected = ($ar_audience == $segment->id) ? 'selected' : '';
                        if ($segment->isPrimary)
                            echo "<option value='{$segmentId}' {$selected}>* {$sourceName} - {$segmentName}</option>";
                    }
                }

                // Secondary Sources List
                foreach ($audienceList->sources as $sourceKey => $source) {
                    $sourceName = $source->name;
                    foreach ($source->segments as $segmentKey => $segment) {
                        $segmentName = $segment->name;
                        $segmentId = $segment->id;
                        $selected = ($ar_audience == $segment->id) ? 'selected' : '';
                        if (!$segment->isPrimary)
                            echo "<option value='{$segmentId}' {$selected}>{$sourceName} - {$segmentName}</option>";
                    }
                }
                ?>
                <!--
                <option>* Testing Source - Adults</option>
                <option>Testing Source - Kids</option>
                <option>Testing Source - College</option>
                -->
            </select>
        </p>

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

        <?php if ($scoring->data->analysis): 
            
//            echo "<pre>";
//            print_r($scoring);
//        echo "</pre>";
            
            
            ?>

        <!-- <ul id="hl-class">
          <li><strong>Select a category to highlight areas to refine:</strong></li>
        <?php if ($scoring->data->analysis->sm->total > 0): ?>
                      <li><a href='#' id='highlight-sp'>Spelling Mistakes</a></li>
        <?php endif; ?>
        <?php if ($scoring->data->analysis->gm->total > 0): ?>
                      <li><a href='#' id='highlight-gm'>Grammar Mistakes</a></li>
        <?php endif; ?>
        <?php if ($scoring->data->analysis->lc->invalid > 0): ?>
                      <li><a href='#' id='highlight-il'>Underperforming Links</a></li>
        <?php endif; ?>
        </ul>  -->
        <?php $aBarColorText = aranalyzer_get_state($scoring->data); ?>
        <div class="bar-shadow box-score">
            <div class="bar circle-score">
                <div class="scoreText">
                    <!--Content Score:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
                    <span><?php echo round(number_format($aBarColorText['scoring'], 1)); ?></span>
                </div>
            </div>
            <div class="ar-message">Aim for an Atomic Score of 60+, but with the suggestions below you can gain even greater engagement.</div>
        </div>
        <?php if ($scoring->data->analysis->sm->total > 0 || $scoring->data->analysis->gm->total > 0 || $scoring->data->analysis->lc->invalid > 0 ||!empty($scoring->data->analysis->so->paragraphs)): ?>
        <ul id="hl-class">
            <li><strong>Select which area(s) you wish to highlight:</strong></li>
            <?php if ($scoring->data->analysis->sm->total > 0): ?>
            <li>
                <input type="checkbox" id="chksp" name="chk" value="all">
                <label for="chksp">Spelling Mistakes</label>
            </li>
            <?php endif; ?>
            <?php if ($scoring->data->analysis->gm->total > 0): ?>
            <li>
                <input type="checkbox" id="chkgm" name="chk"value="false">
                <label for="chkgm">Grammar Insights</label>
            </li>
            <?php endif; ?>
            <?php if ($scoring->data->analysis->lc->invalid > 0): ?>
            <li>
                <input type="checkbox" id="chkul" name="chk" value="true">
                <label for="chkul">Underperforming Links</label>
            </li>
            <?php endif; ?>
            <?php if (!empty($scoring->data->analysis->so->paragraphs)): ?>
            <li>
                <input type="checkbox" id="chkso" name="chk"value="false">
                <label for="chkso">Audience Mismatch</label>
            </li>
            <script>
                jQuery("#chkso").data('paragraphs', <?php echo json_encode($scoring->data->analysis->so->paragraphs); ?>);
                jQuery("#chkso").data('domExpression', <?php echo json_encode($scoring->data->analysis->so->paragraphDOM); ?>);
                jQuery("#chkso").data('tooSimpleColor', <?php echo json_encode($light_blue); ?>);
                jQuery("#chkso").data('tooComplexColor', <?php echo json_encode($light_orange); ?>);
            </script>
            <?php endif; ?>
        </ul>
        <?php endif; ?>

        <div class="greyWhite">&nbsp;</div>
        <div id="ar-content-results">
            <?php if (!$error): ?>
            <p>
                <span>Content Analysis &amp; Results</span>
            </p>

            <div class="resultsArea">
                <div class="resultsAreaInner">
                    <div id="ar_score">
                        <!-- x score place -->
                        <div class="bar-shadow">
                            <div class="bar">
                                <div class="score">
                                    <div class="redBar">&nbsp;</div>
                                    <div class="yellowBar">&nbsp;</div>
                                    <div class="greenBar">&nbsp;</div>
                                    <div class="indicator">
                                        <div class="marker" style="margin-left:<?php echo number_format($aBarColorText['scoring'], 1); ?>%;">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (number_format($aBarColorText['scoring'], 1) <= 33): ?>
                        <div class="pbMsg">Need Changes!</div>
                        <?php elseif (number_format($aBarColorText['scoring'], 1) > 33 && number_format($aBarColorText['scoring'], 1) <= 66): ?>
                        <div class="pbMsg">Almost There!</div>
                        <?php elseif (number_format($aBarColorText['scoring'], 1) > 66): ?>
                        <div class="pbMsg">On Your Way!</div>
                        <?php endif; ?>
                        <div class="label">
                            <?php
                            //echo $aBarColorText['arText'].
                            echo "To update this analysis, save as draft or update and check this page again.";
                            ?>                </div>
                    </div>
                </div><!--End resultsAreaInner-->
                <div id="ar_structure" class="arMeasureBlock">
                    <h3>Structure Elements</h3>
                    <ul class="ar_spritelist" id="accordion">
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
                        <!-- Title -->
                        <li class="<?= $class; ?>"><div>
                                <?php if (strtolower($scoring->data->analysis->tm->state) !== 'green'): ?>
                                <a href="#so"><?= $scoring->data->analysis->tm->message; ?></a></div>
                            <ul class="details title-measure">
                                <?php
                                foreach ($scoring->data->analysis->tm->recomendations as $recomendation)
                                echo '<li><span class="soText">' . $recomendation . '</span></li>';
                                ?>
                            </ul>
                            <?php else: ?>
                            <?= $scoring->data->analysis->tm->message; ?>
                            <?php endif; ?>
                        </li>

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
                            <div><a href="#tg">Here <?php echo ($total_topics == 1) ? 'is ' : 'are '; ?> <?php echo $total_topics; ?> Keyword<?php echo ($total_topics == 1) ? '' : 's'; ?> in your post</a></div>
                            <div>
                                <ul class="details">
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
                        $rs = $scoring->data->analysis->ln->recommended->sentences;
                        // $state = arnanlyzer_rsl_state($ms, $rs);
                        $state = strtolower($scoring->data->analysis->ln->state);
                        switch ($state) {
                        case 'green':
                        echo '<li class="ar_checkmarks_passed"><div>Length of post is ' . $ms . ' sentences, which meets the minimum recommended length of ' . $rs . '-75 sentences.</div></li>';
                        break;
                        case 'yellow':
                        echo '<li class="ar_checkmarks_warning"><div>Length of post is ' . $ms . ' sentences. We recommend a range of ' . $rs . '-75 sentences.</div></li>';
                        break;
                        case 'red':
                        echo '<li class="ar_checkmarks_error"><div>Length of post is ' . $ms . ' sentences. We recommend a range of ' . $rs . '-75 sentences.</div></li>';
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
                                <a href="#lc"><?php echo $scoring->data->analysis->lc->invalid . ' of the ' . $scoring->data->analysis->lc->total . ' link'; ?><?php echo ($scoring->data->analysis->lc->invalid == 1) ? '' : 's'; ?> included in the post<br /> <?php echo ($scoring->data->analysis->lc->invalid == 1) ? ' is' : ' are'; ?> underperforming.</a>
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

                    </ul>
                </div><!-- end of structure -->




                <div id="ar_linguistic" class="arMeasureBlock">
                    <h3>Linguistic Elements</h3>
                    <ul class="ar_spritelist" id="accordion">
                        <!-- Spelling Mistakes -->
                        <?php if (strtolower($scoring->data->analysis->sm->state) === 'green'): ?>
                        <li class="ar_checkmarks_passed">
                            <?php elseif (strtolower($scoring->data->analysis->sm->state) === 'yellow'): ?>
                        <li class="ar_checkmarks_warning">
                            <?php elseif (strtolower($scoring->data->analysis->sm->state) === 'red'): ?>
                        <li class="ar_checkmarks_error">
                            <?php endif; ?>
                            <?php if ($scoring->data->analysis->sm->total > 0): ?>
                            <div><a href="#sm">There are <?php echo $scoring->data->analysis->sm->total; ?> misspelled words.</a></div>
                            <div>
                                <ul class="details spelling-mistakes">
                                    <?php
                                    $ul_sm = '';
                                    foreach ($scoring->data->analysis->sm->detail as $key => $value) {
                                    $ul_sm .= '<li><span class="smText">' . $value->string . '</span>';

                                    // check if there are at least one suggestion
                                    if (isset($value->suggestions->option[0]) &&!empty($value->suggestions->option[0])) {
                                    $suggestions = '';
                                    foreach ($value->suggestions->option as $keys => $values) {
                                    $suggestions .= $values . ', ';
                                    }
                                    $suggestions = substr(trim($suggestions), 0, -1);
                                    $ul_sm .= ' - <b>Suggestions</b>: <i>' . $suggestions . '</i></li>';
                                    }
                                    // url
                                    if (isset($value->url) &&!empty($value->url)) {
                                    $ul_sm .= '<li><a class="toggle-link" href="' . $value->url . '" target="_blank">See explanation</a></li>';
                                    }
                                    }
                                    echo $ul_sm;
                                    ?>




                                </ul>
                            </div>
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
                            <div><a href="#gm">There is <?php echo $scoring->data->analysis->gm->total; ?> grammar insight.</a></div>
                            <?php else: ?>
                            <div><a href="#gm">There are <?php echo $scoring->data->analysis->gm->total; ?> grammar insights.</a></div>
                            <?php endif; ?>
                            <div>
                                <ul class="details grammar-mistakes">
                                    <?php
                                    $ul_gm = '';
                                    foreach ($scoring->data->analysis->gm->detail as $key => $value) {
                                    $ul_gm .= '<li><span class="gmText">' . $value->string . '</span>';

                                    // check if there are at least one suggestion
                                    if (isset($value->suggestions->option[0]) &&!empty($value->suggestions->option[0])) {
                                    $suggestions = '';
                                    foreach ($value->suggestions->option as $keys => $values) {
                                    $suggestions .= $values . ', ';
                                    }
                                    $suggestions = substr(trim($suggestions), 0, -1);
                                    $ul_gm .= ' - <b>Suggestions</b>: <i>' . $suggestions . '</i></li>';
                                    }
                                    // url
                                    if (isset($value->url) &&!empty($value->url)) {
                                    $ul_gm .= '<li><a class="toggle-link mw" href="' . $value->url . '" target="_blank">See explanation</a></li>';
                                    }
                          ?>          
                                    
                                    <div id="mw-modal-content">
  <div class="close"><a href="#" class="simplemodal-close">X</a></div>
  <div id="mw-modal-data">
   <iframe id="AtomicReachLogin" src="<?php echo $value->url ; ?>" width="800" height="415" scrolling="no"></iframe> 
  </div>
</div>
                            <?php        
                                    
                                    
                                    }
                                    echo $ul_gm;
                                    ?>
                                </ul>
                            </div>
                            <?php else: ?>
                            <div>There are no grammar errors.</div>
                            <?php endif; ?>
                        </li>



                        <!-- Level of repetition -->
                        <?php if (strtolower($scoring->data->analysis->lr->state) === 'green'): ?>
                        <li class="ar_checkmarks_passed">
                            <?php elseif (strtolower($scoring->data->analysis->lr->state) === 'yellow'): ?>
                        <li class="ar_checkmarks_warning">
                            <?php elseif (strtolower($scoring->data->analysis->lr->state) === 'red'): ?>
                        <li class="ar_checkmarks_error">
                            <?php endif; ?>
                            <div><?php echo $scoring->data->analysis->lr->detail; ?></div>
                        </li>


                    </ul>
                </div><!-- end linguistic -->

                <div id="ar_sophistication" class="arMeasureBlock">
                    <h3>Audience Elements</h3>
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
                        <li class="<?= $class; ?>"><div>
                                <?php if (strtolower($scoring->data->analysis->so->state) !== 'green'): ?>
                                <a href="#so">Your content is <?= $scoring->data->analysis->so->detail; ?> for your audience</a></div>
                            <ul class="details paragraph-sophistication">
                                <?php
                                $ul_so = '';
                                if ($scoring->data->analysis->so->detail === 'TOO SIMPLE'){
                                $ar_message = '<strong>TIPS:</strong> consider elaborating on short sentences and/ or use more sophisticated language.';
                                }elseif($scoring->data->analysis->so->detail === 'TOO COMPLEX'){
                                $ar_message = '<strong>TIPS:</strong> Write shorter sentences, use shorter words, break up paragraphs.';
                                }
                                $ar_message .= '<br /><br />Utilize the article highlighting (<strong>Audience Mismatch</strong>) button above to see where you should modify your content to match your audience. <span style="color:'. $light_blue.';">Blue highlights = Too simple.</span> <span style="color: '.$light_orange.';">Orange highlights = Too complex.</span>';
                                $ul_so .= '<li style="background: none;">
                                    <p class="ar-message">'.$ar_message.'</p>
						  		<br />
							<a href="https://www.slideshare.net/secret/ydYaIIq7pSJDxK" target="_blank" class="toggle-link ar-message-link">Refer to our tips on how to achieve a content sophistication match for your audience <span>here</span></a>.
                                  </li>';
//                                foreach ($scoring->data->analysis->so->paragraphs as $index => $match) {
//                                if ($match == 'HIT' || $match == 'UNAVAILABLE')
//                                continue;
//
//                                $teaser = is_array($scoring->data->analysis->so->paragraphTeasers) ? $scoring->data->analysis->so->paragraphTeasers[$index] : $scoring->data->analysis->so->paragraphTeasers->{$index};
//                                $color = ($match == 'TOO SIMPLE') ? $light_blue : $light_orange;
//                                $ul_so .= '<li><span class="soText" style = "color: ' . $color . '">"' . $teaser . '": ' . ucwords(strtolower($match)) . '</span></li>';
//                                }
                                echo $ul_so;
                                ?>
                            </ul>
                            <?php else: ?>
                            <?= $scoring->data->analysis->so->message; ?>
                            <?php endif; ?>
                        </li>
                        <?php endif; ?>


                        <!-- Emotion -->
                        <?php if (strtolower($scoring->data->analysis->em->state) === 'green'): ?>
                        <li class="ar_checkmarks_passed">
                            <?php elseif (strtolower($scoring->data->analysis->em->state) === 'yellow'): ?>
                        <li class="ar_checkmarks_warning">
                            <!--<div>Your content could evoke more emotion to excite and engage your readers. It should include more stimulating language, portrayed through positive and/or negative words.</div>-->
                            <?php elseif (strtolower($scoring->data->analysis->em->state) === 'red'): ?>
                        <li class="ar_checkmarks_warning">
                            <!--<div>Your content could evoke more emotion to excite and engage your readers. It should include more stimulating language, portrayed through positive and/or negative words.</div>-->
                            <?php endif; ?>
                            <div><?php echo $scoring->data->analysis->em->detail;   ?></div>
                        </li>
                    </ul>
                </div><!-- end ar_sophistication-->
                <hr class="ar-uniquenness">
                <div id="ar_unique" class="arMeasureBlock">
                    <h3>Uniqueness</h3>
                    <ul class="ar_spritelist ar_noIcon" id="accordion">
                        <!-- Surprise -->
                        <?php if (strtolower($scoring->data->analysis->su->state) === 'green'): ?>
                        <li class="ar_checkmarks_passed"><div><img src="<?php echo plugins_url('/', __FILE__); ?>thumbs_up.png" class="ar-thumbsUp"/><br />Your content is unique and will drive engagement, so share away!</div>
                            <?php elseif (strtolower($scoring->data->analysis->su->state) === 'yellow'): ?>
                        <li class="ar_checkmarks_warning"><div class="arThumbsUp">Original content drives more engagement. We will alert you when your content is unique.</div>
                            <?php elseif (strtolower($scoring->data->analysis->su->state) === 'red'): ?>
                        <li class="ar_checkmarks_error"><div class="arThumbsUp">Original content drives more engagement. We will alert you when your content is unique.</div>
                            <?php endif; ?>
                            <div><?php //echo $scoring->data->analysis->su->detail;   ?></div>
                        </li>

                        <?php
// Display similar articles
//                        if (is_array($scoring->data->analysis->su->similar)) {
//                        ?>
                        <!--<h4 style='color: #000000;'>Similar Articles:</h4>-->
                        <!--<ol>-->
                            <?php// foreach ($scoring->data->analysis->su->similar AS $article) { ?>
                            <!--<li><a href="<?php// echo $article->url; ?>" target="_blank"><?php //echo $article->title; ?></a></li>-->
                            <?php// } ?>
                        <!--</ol>-->
                        <?php
//                        } else {
//                        echo 'no similar article found!!';
//                        }
//                        ?>
                    </ul><!-- end .ar_spritelist ar_noIcon -->
                </div><!-- END  ar_unique -->
            </div>
        <?php endif; ?>
        </div>
    <?php endif; ?>
    </div>
    <?php
} // test ?>