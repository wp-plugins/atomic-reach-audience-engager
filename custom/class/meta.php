<?php

	/*
	 * Display AtomicReach assessment results.
	 * Following class takes the Json formatted result and display it in human friendly format.
	 * See: ../index.php From Line#90 to call each method.
	 * Please take backup before you edit this file.
	 *
	 */

	class meta {

		private $result;
		private $seg;


		// Contructor getting result from ARClient.php in a form of an object.
		Public function __construct( $obj, $seg ) {
			$this->result = $obj;
			$this->seg    = $seg;
		}

		/*     * *************************** */
		/* Content Sophistication    */
		/* find out more about this:   */
		/* http://www.slideshare.net/atomicreach/how-to-achieve-a-content-sophistication-match-for-your-audience */
		/*     * *************************** */

		public function summaryTab() {
			$out = '<h3>TOP 3 OPPORTUNITIES</h3>
					<ul class="threeOpp ar_spritelist">';

			$stopAtThree = 0;
			$measState   = $this->result->data->analysis;


			// 1 check audience match
			if ( $measState->so->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->so->state === "yellow" ) ? 'warning' : 'error' ) . '">
								Your post is not an audience match. Hit AUDIENCE to find out why.</li>';
				$stopAtThree ++;
			}
			// 2 check if title is not green.
			if ( $measState->tm->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->tm->state === "yellow" ) ? 'warning' : 'error' ) . '">
								Your title needs work. Hit TITLE to find out why.</li>';
				$stopAtThree ++;
			}
			// 3 check length
			if ( $measState->ln->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->ln->state === "yellow" ) ? 'warning' : 'error' ) . '">
								  Length of post is not optimal. Hit STRUCTURE to find out why.</li>';
				$stopAtThree ++;
			}
			// 4 Check Grammar
			if ( $measState->gm->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->gm->state === "yellow" ) ? 'warning' : 'error' ) . '">
								Improve your grammar. Hit LINGUISTICS to find out how.</li>';
				$stopAtThree ++;
			}
			// 5 check if emotion is not green
			if ( $measState->em->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->em->state === "yellow" ) ? 'warning' : 'error' ) . '">
								Your post lacks emotion. Hit AUDIENCE to fix it.</li>';
				$stopAtThree ++;
			}
			// 6 Check Spelling
			if ( $measState->sm->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->sm->state === "yellow" ) ? 'warning' : 'error' ) . '">
								Incorrect spelling detected. Hit LINGUISTICS to find out why.</li>';
				$stopAtThree ++;
			}
			// 7 Links
			if ( $measState->lc->state !== 'green' && $stopAtThree != 3 ) {
				$out .= '<li class="ar_checkmarks_' . ( ( $measState->lc->state === "yellow" ) ? 'warning' : 'error' ) . '">
								Some links aren\'t working. Hit LINGUISTICS to find out more.</li>';
				$stopAtThree ++;
			}
			// 8 Check Topics
			/*if($measState->tpp->state !== 'green' && $stopAtThree != 3){
					echo '';
					$stopAtThree ++;
				}*/

			$out .= '</ul>';

			return $out;


		}

		private function keywordsList() {
			$tg = $this->result->data->analysis->tg;
			if ( $tg->total == 0 ) {
				return "No keywords identified.";
			} else {
				$words = '';
				foreach ( $tg->detail as $val ) {
					$words .= $val . ", ";
				}
				$out = rtrim( $words, ", " );
				return $out;
			}
		}

		public function contentSophistication() {
			$num = 0;

			$out = '<p style="font-size: 24px;color: #64C1DD; margin-bottom: 0;margin-top: 0;">Format Results</p>' . PHP_EOL;
			$out .= '<p class="howto" style="margin-top: 0;">Hover over the elements to see more feedback.</p>' . PHP_EOL ;
			$out .= '<article class="ac-large soBox">' . PHP_EOL;

			$out .= '<div>';
			$out .= '<script> var lengthState = ' . json_encode( $this->result->data->analysis->ln->state ) . '; </script>';
			$out .= '<script> var lengthCount = ' . json_encode( $this->result->data->analysis->ln->measured->sentences ) . '; </script>';


//			$lengthState = $this->result->data->analysis->ln->state;
//			$lengthSen   = $this->result->data->analysis->measured->words;
			$ln = $this->result->data->analysis->ln;

			$out .= '<p id="ar-ln"><input id="ln" name="ln" type="checkbox" />' . PHP_EOL;
			$out .= '<label class="ar_info" style="font-size: 20px !important;" for="ln" id="ar-' . $ln->state . '">&nbsp;&nbsp;&nbsp;Length</label><p>' . PHP_EOL;

			$sm = $this->result->data->analysis->sm;
			$out .= '<script> var spellState = ' . json_encode( $this->result->data->analysis->sm->state ) . '; </script>';
			$out .= '<script> var spellHL = ' . json_encode( $this->result->data->analysis->sm ) . '; </script>';


			$spellState = $this->result->data->analysis->sm->state;
			$out .= '<p id="ar-sm"><input id="sm" name="sm" type="checkbox" />' . PHP_EOL;
			$out .= '<label class="ar_info" style="font-size: 20px !important;" for="sm" id="ar-' . $sm->state . '"> &nbsp;&nbsp;&nbsp;Spelling</label><p>' . PHP_EOL;


			$out .= '<script> var grammarState = ' . json_encode( $this->result->data->analysis->gm->state ) . '; </script>';
			$out .= '<script> var grammarHL = ' . json_encode( $this->result->data->analysis->gm ) . '; </script>';

			$gm = $this->result->data->analysis->gm;
			$out .= '<p id="ar-gm"><input id="gm" name="gm" type="checkbox" />' . PHP_EOL;
			$out .= '<label class="ar_info" style="font-size: 20px !important;" for="gm" id="ar-' . $gm->state . '"> &nbsp;&nbsp;&nbsp;Grammar</label><p>' . PHP_EOL;


			$linkState = $this->result->data->analysis->lc->state;
			$out .= '<script> var linkState = ' . json_encode( $this->result->data->analysis->lc->state ) . '; </script>';
			$out .= '<script> var linkHL = ' . json_encode( $this->result->data->analysis->lc ) . '; </script>';


			$lc = $this->result->data->analysis->lc;
			$out .= '<p id="ar-lc"><input id="lc" name="lc" type="checkbox" />' . PHP_EOL;
			$out .= '<label class="ar_info" style="font-size: 20px !important;" for="lc" id="ar-' . $lc->state . '"> &nbsp;&nbsp;&nbsp;Links</label><p>' . PHP_EOL;
			$out .= '</div>';
			$out .= '<hr/>';


			$out .= '<div style="margin-top: 10px;margin-left:25px">';

			$out .= '<p class="ar_count">Word Count:<span style="color:#464646 !important">&nbsp;&nbsp;' . $ln->measured->words . ' </span></p> ' . PHP_EOL;
			$out .= '<p class="ar_count">Sentence Count:<span style="color:#464646 !important">&nbsp;&nbsp;' . $ln->measured->sentences . ' </span></p> ' . PHP_EOL;
			$pc = count( $this->result->data->analysis->pwd->detail->paragraphs );
			$out .= '<p class="ar_count">Paragraph Count:<span style="color:#464646 !important">&nbsp;&nbsp;' . $pc . ' </span></p> ' . PHP_EOL;
			$out .= '<p class="ar_count">Keywords:<span style="color:#464646 !important; font-size: 12px;">&nbsp;&nbsp;' . $this->keywordsList() . ' </span></p> ' .
			        PHP_EOL;


			$out .= '</div>';
			$out .= '<hr/>';

			if ( strtolower( $spellState ) != "green" ) {
				$out .= '<div id="ar-spellingHighlightButton" style="margin-bottom: 7px !important;    margin-left: -19px;">';
				$out .= '<div class="onoffswitch">
                <input type="checkbox" id="writer_Spelling" style="visibility: hidden !important;display: none !important;" name="onoffswitch" class="onoffswitch-checkbox" >
                 <label class="onoffswitch-label" for="writer_Spelling">
                    <span class="onoffswitch-inner"></span>
                     <span class="onoffswitch-switch-spellings"></span>
                        </label>
                            </div>';
				$out .= '<p class="writer_fixes-labels">&nbsp;&nbsp;&nbsp;Spelling</p>';
				$out .= '</div>';
			}
			if ( strtolower( $gm->state ) != "green" ) {
				$out .= '<hr>';
				$out .= '<div id="ar-grammarHighlightButton" style="margin-bottom: 7px !important;margin-left: -19px;">';
				$out .= '<div class="onoffswitch">';
				$out .= '<input type="checkbox" style="visibility: hidden !important;display: none !important;" name="onoffswitch" class="onoffswitch-checkbox" id="writer_Grammar">';
				$out .= '<label class="onoffswitch-label" for="writer_Grammar">';
				$out .= '<span class="onoffswitch-inner"></span>';
				$out .= '<span class="onoffswitch-switch-grammar"></span>';
				$out .= '</label>';
				$out .= '</div>';
				$out .= '<p class="writer_fixes-labels">&nbsp;&nbsp;&nbsp;Grammar</p>';
				$out .= '</div>';
				$out .= '<hr>';
			}
			if ( strtolower( $lc->state ) != "green" ) {
				$out .= '<div id="ar-linksHighlightButton" style="margin-bottom: 7px !important;    margin-left: -19px;">';
				$out .= '<div class="onoffswitch">';
				$out .= '<input type="checkbox" style="visibility: hidden !important;display: none !important;" name="onoffswitch" class="onoffswitch-checkbox" id="writer_Links">';
				$out .= '<label class="onoffswitch-label" for="writer_Links">';
				$out .= '<span class="onoffswitch-inner"></span>';
				$out .= '<span class="onoffswitch-switch-links"></span>';
				$out .= '</label>';
				$out .= '</div>';
				$out .= '<p class="writer_fixes-labels">&nbsp;&nbsp;&nbsp;Links</p>';

				$out .= '</div>';
			}

			$out .= '</article>' . PHP_EOL;


			$out .= '<div class="writer-spelling_fix writer-hide">';
			$out .= '<p> Spelling Suggestions</p>';
			$out .= '<div>';
			$out .= '<ul class="spellings_list" style="text-align:center !important;background-color: #ffffff !important"></ul>';
			$out .= '<button id="add_dictionary">+ add to dictionary</button>';
			$out .= '</div>';
			$out .= '</div>';

			$out .= '<div class="writer-grammar_fix writer-hide">';
			$out .= '<p>Grammar</p>';
			$out .= '<p class="suggestions_content"></p>';
			$out .= '<p class="suggestions_link"><a class="grammar-suggestion-link" href="">See Explanation</a></p>';
			$out .= '</div>';

			return $out;
		}


		public function readability() {
			$num = 0;

			$out = '<p style="font-size: 24px;color: #64C1DD; margin-bottom: 0;margin-top: 0;">Readability Results</p>' . PHP_EOL;
			$out .= '<p class="howto" style="margin-top: 0;">Hover over the elements to see more feedback.</p>' . PHP_EOL ;
			$out .= '<article class="ac-large soBox">' . PHP_EOL;

			$out .= '<div>';
			$em = $this->result->data->analysis->em;
			$out .= '<script> var emState = ' . json_encode( $this->result->data->analysis->em->state ) . '; </script>';


			$out .= '<p id="ar-em"><input id="em" name="em" type="checkbox" />' . PHP_EOL;
			$out .= '<label style="font-size:20px !important;" class="ar_info" for="em" id="ar-' . $em->state . '">&nbsp;&nbsp;&nbsp;Emotion</label><p>' . PHP_EOL;

			$out .= '<script> var pwdState = ' . json_encode( $this->result->data->analysis->pwd->state ) . '; </script>';
			$out .= '<script> var pwdHL = ' . json_encode( $this->result->data->analysis->pwd ) . '; </script>';


			$pwd = $this->result->data->analysis->pwd;
			$out .= '<p id="ar-pwd"><input id="pwd" name="pwd" type="checkbox" />' . PHP_EOL;
			$out .= '<label style="font-size:20px !important;"  class="ar_info" for="pwd" id="ar-' . $pwd->state . '">&nbsp;&nbsp;&nbsp;Paragraph Density</label><p>' . PHP_EOL;

			$out .= '<script> var senState = ' . json_encode( $this->result->data->analysis->so->state ) . '; </script>';
			$out .= '<script> var soHL = ' . json_encode( $this->result->data->analysis->so ) . '; </script>';

			$so = $this->result->data->analysis->so;
			$out .= '<p id="ar-so"><input id="so" name="so" type="checkbox" />' . PHP_EOL;
			$out .= '<label style="font-size:19px !important;" class="ar_info" for="so" id="ar-' . $so->state . '">&nbsp;&nbsp;&nbsp;Sentence Complexity</label><p>' . PHP_EOL;

			$out .= '<p id="ar-wc"><input id="wc" name="wc" type="checkbox" />' . PHP_EOL;
			$out .= '<label style="font-size:19px !important;" class="ar_info" for="wc" id="ar-' . $so->state . '">&nbsp;&nbsp;&nbsp;Word
			Complexity</label><p>' . PHP_EOL;

			$out .= '</div>';


			if ( strtolower( $pwd->state ) != 'green' ) {
				$out .= '<hr>';
				$out .= '<div id="ar-PWDbtn" style="margin-bottom: 7px !important;    margin-left: -19px;">';
				$out .= '<div class="onoffswitch">';
				$out .= '<input type="checkbox" style="visibility: hidden !important;display: none !important;" name="onoffswitch" class="onoffswitch-checkbox" id="writer_ParaDensity">';
				$out .= '<label class="onoffswitch-label" for="writer_ParaDensity">';
				$out .= '<span class="onoffswitch-inner"></span>';
				$out .= '<span class="onoffswitch-switch-paragraph"></span>';
				$out .= '</label>';
				$out .= '</div>';
				$out .= '<p class="writer_fixes-labels">&nbsp;&nbsp;&nbsp;Paragraph Density</p>';
				$out .= '</div>';
			}
			if ( strtolower( $so->state ) != 'green' ) {
				$out .= '<hr>';
				$out .= '<div id="ar-SObtn" style="margin-bottom: 7px !important;margin-left: -19px;">';
				$out .= '<div class="onoffswitch">';
				$out .= '<input type="checkbox" style="visibility: hidden !important;display: none !important;" name="onoffswitch" class="onoffswitch-checkbox" id="writer_SenComp">';
				$out .= '<label class="onoffswitch-label" for="writer_SenComp">';
				$out .= '<span class="onoffswitch-inner"></span>';
				$out .= '<span class="onoffswitch-switch-sentence"></span>';
				$out .= '</label>';
				$out .= '</div>';
				$out .= '<p class="writer_fixes-labels"> &nbsp;&nbsp;&nbsp;Sentence Complexity</p>';
				$out .= '</div>';

				$out .= '<hr>';
				$out .= '<div id="ar-WCbtn" style="margin-bottom: 7px !important;margin-left: -19px;">';
				$out .= '<div class="onoffswitch">';
				$out .= '<input type="checkbox" style="visibility: hidden !important;display: none !important;" name="onoffswitch" class="onoffswitch-checkbox"
					id="writer_WordComp">';
				$out .= '<label class="onoffswitch-label" for="writer_WordComp">';
				$out .= '<span class="onoffswitch-inner"></span>';
				$out .= '<span class="onoffswitch-switch-word"></span>';
				$out .= '</label>';
				$out .= '</div>';
				$out .= '<p class="writer_fixes-labels"> &nbsp;&nbsp;&nbsp;Word Complexity <span>beta</span></p>';
				$out .= '</div>';
			}

			$out .= '</article>' . PHP_EOL;


			return $out;


		}


		public function titleOptimization() {
			$tm  = $this->result->data->analysis->tm;
			$out = '<div class="' . $this->DOMsort( $tm->state ) . '">' . PHP_EOL;
			$out .= '<p id="AWtitleHeading" class="AWtitleHeading'.$tm->state.'">Title Results</p>' . PHP_EOL;
			$out .= '<article class="ac-large tmBox">' . PHP_EOL;


			//	tags for keywords

			$out .= '<script> var tgKeywords = "' . $this->keywordsList() . '"; </script>';

			if ( $tm->detail >= 0 ) {
				foreach ( $tm->dimensions as $i => $v ) {
					$disabled = array( "TitleQuestion", "TitleContainsNumbers", "TitleContainsHowTo" );
					$code     = $v->name;
					if ( ! in_array( $code, $disabled, FALSE ) ) {
						switch ( $code ) {
							case "titleWordsCount":
								$out .= '<p id="ar_wc"><input id="wc" name="wc" type="checkbox" />' . PHP_EOL;
								$out .= '<label class="ar_info" style="font-size: 20px !important;" for="wc" id="ar-' . $v->state . '">&nbsp;&nbsp;&nbsp;Word Count</label><p>' . PHP_EOL;
								$out .= '<p style="margin-top: -15px;margin-bottom: 8px;"> Use 5-11 words</p>' . PHP_EOL;
								break;

							case "titleTopicsCount":
								$out .= '<script> var titleKeywordState = ' . json_encode( $v->state ) . '; </script>';
								$out .= '<p id="ar-kword"><input id="kword" name="kword" type="checkbox" />' . PHP_EOL;
								$out .= '<label class="ar_info" style="font-size: 20px !important;" for="kword" id="ar-' . $v->state . '">&nbsp;&nbsp;&nbsp;Keywords</label><p>' . PHP_EOL;
								$out .= '<p style="margin-top: -15px;margin-bottom: 8px;">' . $v->recommendations[0] . '</p>';
								break;

							case "titleSuperlatives":
								$out .= '<p id="ar-super"><input id="super" name="kword" type="checkbox" />' . PHP_EOL;
								$out .= '<label class="ar_info" style="font-size: 20px !important;"
                                                for="super" id="ar-' . $v->state . '">&nbsp;&nbsp;&nbsp;Superlatives</label><p>' . PHP_EOL;
								$out .= '<p style="margin-top: -15px;margin-bottom: 8px;">Use a superlative, they are super!</p>' . PHP_EOL;
								break;

							case "titlePronounPerson":
								$out .= '<p id="ar-pronoun"><input id="super" name="pronoun" type="checkbox" />' . PHP_EOL;
								$out .= '<label class="ar_info" style="font-size: 20px !important;"
                                                for="pronoun" id="ar-' . $v->state . '">&nbsp;&nbsp;&nbsp;Pronoun</label><p>' . PHP_EOL;
								$out .= '<p style="margin-top: -15px;margin-bottom: 8px;">Pump it with a 2nd person pronoun!</p>';
								break;

							case "titlePolarity":
								$out .= '<p id="ar-polarity"><input id="polarity" name="polarity" type="checkbox" />' . PHP_EOL;
								$out .= '<label class="ar_info" style="font-size: 20px !important;"
                                               for="polarity" id="ar-' . $v->state . '">&nbsp;&nbsp;&nbsp;Emotion</label><p>' . PHP_EOL;
								$out .= '<p style="margin-top: -15px;margin-bottom: 8px;">Use a word with a positive or negative emotion</p>';
								break;

							case "titleCapitalized":
								$out .= '<p id="ar-caps"><input id="caps" name="caps" type="checkbox" />' . PHP_EOL;
								$out .= '<label class="ar_info" style="font-size: 20px !important;"
                                               for="caps" id="ar-' . $v->state . '">&nbsp;&nbsp;&nbsp;Capitalization</label><p>' . PHP_EOL;
								$out .= '<p style="margin-top: -15px;margin-bottom: 8px;">' . $v->recommendations[0] . '</p>';
								break;
						} // switch
					} //in array
				}// foreach


			} //detail
			$out .= '</article>' . PHP_EOL;
			$out .= '<div id="aw_moretips_WP">' . PHP_EOL;
			$out .= '<p style="cursor: pointer !important;font-family: \'Roboto\', sans-serif"><i>+ More Title Tips</i></p>' . PHP_EOL;
			$out .= '<ul id="aw_titletips_WP" style="display: none;">' . PHP_EOL;
			$out .= '<li>"How to" articles can increase audience engagement</li>' . PHP_EOL;
			$out .= '<li>In listicles, numbers are easy to read than words</li>' . PHP_EOL;
			$out .= '<li>Headlines formed as a question provoke curiosity in a reader</li>' . PHP_EOL;
			$out .= '</ul>' . PHP_EOL;
			$out .= '<br>' . PHP_EOL;
			$out .= '</div>' . PHP_EOL;
			$out .= '</div>' . PHP_EOL;

			return $out;
		}

		public function displayHighlightsButtons() {
			if ( $this->result->data->analysis->gm->total > 0 || $this->result->data->analysis->lc->invalid > 0 || strtolower
				( $this->result->data->analysis->so->state !== 'green' ) || strtolower( $this->result->data->analysis->pwd->state !== 'green' )
			):
				$out = '';
				$out .= '<strong style="clear: both;display: table;padding: 0 25px;">Select a category to highlight areas to refine:</strong>';
				$out .= '<ul id="" class="hl-btns">';
//      if ($this->result->data->analysis->sm->total > 0):
//        $out .= '<li>';
//        $out .= '<input type="checkbox" id="chksp" name="chk" value="all">
//<label for="chksp">Spelling Mistakes</label>
//</li>';
//      endif;
				if ( $this->result->data->analysis->gm->total > 0 ):
					$out .= '<li>
<input type="checkbox" id="chkgm" name="chk"value="false">
<label for="chkgm">Grammar Mistakes</label>
</li>';
				endif;
				if ( $this->result->data->analysis->lc->invalid > 0 ):
					$out .= '<li>
<input type="checkbox" id="chkul" name="chk" value="true">
<label for="chkul">Underperforming Links</label>
</li>';
				endif;
				if ( strtolower( $this->result->data->analysis->so->state !== 'green' ) ):
					$out .= '<li>
<input type="checkbox" id="chkso" name="chk" value="false" class="' . strtolower( str_replace( " ", "-", $this->result->data->analysis->so->detail ) ) . '">
<label for="chkso">Audience Mismatch</label>
</li>';
					$light_blue   = '#91c7f9';
					$light_orange = '#FFA20C';
					$out .= '
<script>
jQuery("#chkso").data("paragraphs",' . json_encode( $this->result->data->analysis->so->paragraphs ) . ');
jQuery("#chkso").data("domExpression",' . json_encode( $this->result->data->analysis->so->paragraphDOM ) . ');
jQuery("#chkso").data("tooSimpleColor",' . json_encode( $light_blue ) . ');
jQuery("#chkso").data("tooComplexColor",' . json_encode( $light_orange ) . ');
</script>';
				endif;

				// PARAGRAPH DENSITY
				if ( strtolower( $this->result->data->analysis->pwd->state !== 'green' ) ):
					$out .= '<li>
<input type="checkbox" id="chkpwd" name="chk" value="true">
<label for="chkpwd">Paragraph Density</label>
</li>';
					$out .= '
        <script>
        var PWDtooShortColor = ' . json_encode( '#F6C9CB' ) . ';
        var PWDtooLongColor = ' . json_encode( '#C98BD1' ) . ';
        var PWDparagraphs = ' . json_encode( $this->result->data->analysis->pwd->detail->paragraphs ) . ';
        var PWDdomExpression = ' . json_encode( $this->result->data->analysis->pwd->detail->paragraphDOM ) . ';
</script>';
				endif; // end para density


				$out .= '</ul>   ';

				return $out;
			endif;
		}

		public function displayScore() {
			$score = $this->result->data->scoring;
			if ( $score <= 50 ) {
				$msg = '<span style="color: #F72210;">Need Changes!</span>';
			} elseif ( $score > 50 && $score < 75 ) {
				$msg = '<span style="color:  #F6D610;">Almost There!</span>';
			} elseif ( $score >= 75 ) {
				$msg = '<span style="color:  #2ecc71;">On Your Way!</span>';
			}


//    $out  = '<div class="ar-score-wrapper">';
//    $out .= '</div>';

			$out = '<div class="score-head"><h2
style="font-color:#ffffff !important;background-color:#CCC7C7 !important;font-size:24px !important;text-align:center">Your Editing Feedback</h2></div>';

			$out .= '<div style="    overflow: hidden;
    padding-top: 25px;
    border-top: 13px solid #64C1DD !important;
    border-right: 4px solid #64C1DD;
    border-left: 4px solid #64C1DD;
    border-bottom: 4px solid #64C1DD;">';
			$out .= '<div class="" style="float:left;">';
			$out .= '<div class="ar-score-container"><span>' . $score . '</span></div>';
			$out .= '<p style="margin-left: 43px;font-size: 18px;font-weight: bold;">Score </p></div>';

			$out .= '<div class="" style="float:right;">';
			$out .= '<div class="ar-aud-match">';
			if ( $this->result->data->analysis->so->actual == 'General' ) {
				if ( $this->result->data->analysis->so->detail == 'TOO SIMPLE' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_general_simple.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'TOO COMPLEX' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_general_complex.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'HIT' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_general_match.png" />';
				}
			}


			if ( $this->result->data->analysis->so->actual == 'Knowledgeable' ) {
				if ( $this->result->data->analysis->so->detail == 'TOO SIMPLE' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_knowledge_simple.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'TOO COMPLEX' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_knowledge_complex.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'HIT' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_knowledge_match.png" />';
				}
			}

			if ( $this->result->data->analysis->so->actual == 'Specialist' ) {
				if ( $this->result->data->analysis->so->detail == 'TOO SIMPLE' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_specialist_simple.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'TOO COMPLEX' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_specialist_complex.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'HIT' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_specialist_match.png" />';
				}
			}

			if ( $this->result->data->analysis->so->actual == 'Academic' ) {
				if ( $this->result->data->analysis->so->detail == 'TOO SIMPLE' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_academic_simple.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'TOO COMPLEX' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_academic_complex.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'HIT' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_academic_match.png" />';
				}
			}

			if ( $this->result->data->analysis->so->actual == 'Genius' ) {
				if ( $this->result->data->analysis->so->detail == 'TOO SIMPLE' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_genius_simple.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'TOO COMPLEX' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_genius_complex.png" />';
				} elseif ( $this->result->data->analysis->so->detail == 'HIT' ) {
					$out .= '<img src="' . MY_PLUGIN_PATH . 'custom/imgs/aw_audience_genius_match.png" />';
				}
			}


			$out .= '<span style="clear: both;display: table;"></span>';

			$out .= '</div>';
			$soActual = $this->result->data->analysis->so->actual;
			if ( $this->result->data->analysis->so->detail == 'HIT' ) {
				$out .= '<p style="font-size: 18px;font-weight: bold;margin-top: 0; text-align: center;">Audience Match</p></div>';
			} else {
				$out .= '<p style="font-size: 18px;font-weight: bold;margin-top: 0; text-align: center;">For ' . $soActual . '</p></div>';
			}

			$out .= '</div>';


//
//        $out .= '<div class="ar-progress">';
//        $out .= '<div class="ar-bar bar-danger" style="width: 50%;"></div>';
//        $out .= '<div class="ar-bar bar-warning" style="width: 30%;"></div>';
//        $out .= '<div class="ar-bar bar-success" style="width: 20%;"></div>';
//        $out .= '<div class="ar-score" style="left:' . $score . '%;"><i class="fa fa-long-arrow-up"></i></div>';
//        $out .= '</div>';
//        $out .= '<div class="ar-score-message">' . $msg . '</div>';
			return $out;
		}

		public function displayResult() {
			return $this->result;
		}

		public function DOMsort( $state ) {
			if ( strtolower( $state ) == "green" ) {
				return "elem3";
			} else if ( strtolower( $state ) == "yellow" ) {
				return "elem2";
			} else if ( strtolower( $state ) == "red" ) {
				return "elem1";
			}
		}

		public function atomicScore() {
			return $this->result->data->scoring;
		}

	}

?>
