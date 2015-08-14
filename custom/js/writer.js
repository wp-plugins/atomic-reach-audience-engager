/**
 * Created by Atomic 1 on 8/7/2015.
 */
jQuery(document).ready(function ($) {
    $("#aw-goTosignUpForm").click(function (e) {
        e.preventDefault();
        $("#aw-signIn").fadeOut(function () {
            // Animation complete.
            $("#aw-signUp").fadeIn();
        });
    });
    $("#aw-goTosignInForm").click(function (e) {
        e.preventDefault();
        $("#aw-signUp").fadeOut(function () {
            // Animation complete.
            $("#aw-signIn").fadeIn();
        });
    });
    $("#aw-signUpForm").submit(function (e) {
        e.preventDefault();
        var email = $("#aw-signUpEmail").val();
        var pass = $("#aw-signUpPassword").val();
        var pass2 = $("#aw-signUpPasswordReType").val();

        if (pass != pass2) {
            $("#aw-API-Error").html("Password do not match!").show().delay(5000).fadeOut("slow", function () {
                $(this).empty()
            });
            return;
        }
        if (!jQuery("#tos").is(":checked")) {
            $("#aw-API-Error").html("Unfortunately you cannot set up an account without accepting our Terms of Use. Please feel free to use our" +
                " <a href='https://www.atomicreach.com/' target='_blank'>Web App</a>.").show().delay(9000).fadeOut("slow", function () {
                $(this).empty()
            });
            return;

        }

        var data = {
            'action': 'awSignUpEmail_ajax',
            'email': email,
            'test': 'test',
            'pass': pass
        };
        $.post(ajaxurl, data, function (response) {
            if (response != 'ok') {
                var value = JSON.parse(response);

                if (typeof value.data.email !== 'undefined') {
                    emailMessages = Object.keys(value.data.email);
                    $.map(emailMessages, function (val, i) {
                        $("#aw-API-Error").append("<p class='text-danger'><strong>Create Account</strong> - Email Error: " + value.data.email[val] + "</p>");
                    });
                }
                if (typeof value.data.password !== 'undefined') {
                    passwordMessages = Object.keys(value.data.password);
                    $.map(passwordMessages, function (val, i) {
                        $("#aw-API-Error").append("<p class='text-danger'> <strong>Create Account</strong> - Password Error: " + value.data.password[val] + "</p>");
                    });
                }

                $("#aw-API-Error").show().delay(5000).fadeOut("slow", function () {
                    $(this).empty()
                });
            } else if (response == 'ok') {
                $("#aw-atomicAdminNotice").fadeOut();
                $("#AW-StandBy").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/audSlider-score.html", function () {
                    $("#AW-notLoggedIn").slideUp("slow", function () {
                        $("#AW-StandBy").slideDown("slow");
                        createTooltipsyForTheI();
                    });
                });

            }
        }).done(function () {
        }).always(function () {
        });
    });
    $("#aw-signInFormSubmit").click(function (e) {
        e.preventDefault();
        var email = $("#aw-signInEmail").val();
        var pass = $("#aw-signInPassword").val();
        var data = {
            'action': 'awSignInEmail_ajax',
            'email': email,
            'test': 'test',
            'pass': pass
        };
        $.post(ajaxurl, data, function (response) {
            if (response != 'ok') {
                $("#aw-API-Error").html(response).fadeIn().delay(5000).fadeOut("slow");
            } else if (response == 'ok') {
                $("#aw-atomicAdminNotice").fadeOut();
                $("#AW-StandBy").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/audSlider-score.html", function () {
                    $("#AW-notLoggedIn").slideUp("slow", function () {
                        $("#AW-StandBy").slideDown("slow");

              createTooltipsyForTheI();
                    });
                });

            }

        }).done(function () {
        }).always(function () {
        });
    });
    var audBand = 5;
    // change audience slider
    $("#aranalyzer_metabox").on("change", '.ar-aud-slider_WP', function (e) {

        var audnum = $(this).val();
        var audText = '';


        if (audnum == 1) {
            audText = 'General';
            audBand = 5;

            $('#ar_desc-aud_WP, #ar_desc-aud_WP2').html("Beginner Novice Basic")
        } else if (audnum == 2) {

            audText = 'Knowledgeable';
            audBand = 4;
            $('#ar_desc-aud_WP, #ar_desc-aud_WP2').html("Aware Familiar Informed");

        } else if (audnum == 3) {
            audText = 'Specialist';
            audBand = 3;
            $('#ar_desc-aud_WP, #ar_desc-aud_WP2').html("Advanced Trained Well-versed")
        } else if (audnum == 4) {
            audText = 'Academic';
            audBand = 2;
            $('#ar_desc-aud_WP, #ar_desc-aud_WP2').html("Scholarly Collegiate Masterful")
        } else if (audnum == 5) {
            audText = 'Genius';
            audBand = 1;
            $('#ar_desc-aud_WP, #ar_desc-aud_WP2').html("Expert Brilliant Intellectual")
        }


        $('#arSelectedAud_WP, #arSelectedAud2').text(audText).removeClass().addClass(audText + "ColorText");
        $('#ar_desc-aud_WP, #ar_desc-aud_WP2').removeClass().addClass(audText + "ColorText");

        targetAud = audText.toUpperCase();
    });
    // Click Score button
    $("#aranalyzer_metabox").on("click", "#arScore_WP", function (e) {
        e.preventDefault();


        var title = $("#title").val();
        var content = tinyMCE.activeEditor.getContent();

        if (title == ""){
            $("#right-score").html("<span style='color: #ff0000;'>ERROR! : Title is missing. Please write your title and try again.</span>").fadeIn().delay(5000).fadeOut();
            return;
        }if (content == ""){
            $("#right-score").html("<span style='color: #ff0000;'>ERROR! : Content is missing. Please write some content and try again.</span>").fadeIn().delay(5000).fadeOut();
            return;
        }


        var data = {
            'action': 'aranalyzer_ajax',
            'arTitle': title,
            'arContent': content,
            'segmentId': audBand
        };

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                if ($("#right-score").is(":visible")) {
                    $("#right-score").slideUp(function () {
                        $("#AW-staticBlock").fadeOut(function () {
                            $("#awloadingBlock").fadeIn();
                        });
                    });
                } else {
                    $("#AW-staticBlock").fadeOut(function () {
                        $("#awloadingBlock").fadeIn();
                    });
                }
                clearAllhighlighting();
            },
            success: function (response) {
                $("#right-score").html(response).hide();
                ARTabs();
                ar_tips();
                ar_tipsread();

            },
            error: function (response) {

            },
            complete: function () {
                $("#awloadingBlock").fadeOut(function () {
                    $("#AW-staticBlock").fadeIn(function () {
                        $("#right-score").slideDown("slow");
                    })
                });
                //$("#arScore_WP").text("Rescore")
            }
        });
    });


    /********************************/
    /**** Score Button & new-meta.php Tabs ui ****/
    /********************************/
    $("#right-score").on("load", "ul.AR-tabs", ARTabs());
    function ARTabs() {


        $('ul.AR-tabs').each(function () {
            // For each set of tabs, we want to keep track of
            // which tab is active and it's associated content
            var $active, $content, $links = $(this).find('a');

            // If the location.hash matches one of the links, use that as the active tab.
            // If no match is found, use the first link as the initial active tab.
            $active = $($links.filter('[href="' + location.hash + '"]')[0] || $links[0]);
            $active.addClass('active');
            $content = $($active[0].hash);

            // Hide the remaining content
            $links.not($active).each(function () {
                $(this.hash).hide();
            });

            // Bind the click event handler
            $(this).on('click', 'a', function (e) {
                // Make the old tab inactive.
                $active.removeClass('active');
                $content.hide();

                // Update the variables with the new link and content
                $active = $(this);
                $content = $(this.hash);

                // Make the tab active.
                $active.addClass('active');
                $content.show();

                // Prevent the anchor's default click action
                e.preventDefault();
            });
        });
    }

    function ar_tips() {

        if (titleKeywordState.toLowerCase() != 'green') {

            $("#ar-kword").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'width': 'auto',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'


                },
                content: '<b>Your Keywords:</b> <br><p>' + tgKeywords + '</p>'
            });

        }

        if (lengthState == 'red' || 'yellow') {

            $("#ar-ln").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b> <br>You have <span>' + lengthCount + '</span> sentences. Hit the ideal mark of 26-75 sentences to increase readability.'
            });
        } else if (lengthState == 'green') {

            $("#ar-ln").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'


                },
                content: '<b>Tip:</b> <br>Length of article is <span>' + lengthCount + ' </span>sentences. You are good to go!'
            });
        }

        if (grammarState == 'green') {

            $("#ar-gm").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'


                },
                content: '<b>Tip:</b><br>' +
                'Whoa, you are good!'
            });
        } else if (grammarState == 'red' || 'yellow') {

            $("#ar-gm").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'


                },
                content: '<b>Tip:</b><br>' +
                ' Turn on the highlight feature to identify grammar issues.'
            });
        }

        if (spellState == 'green') {

            $("#ar-sm").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'


                },
                content: '<b>Tip:</b><br>' +
                'You are a great speller!'
            });
        } else if (spellState == 'red' || 'yellow') {

            $("#ar-sm").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'


                },
                content: '<b>Tip:</b><br>' +
                'Turn on the hightlight feature to fix or add words to dictionary.'
            });
        }
        ;


        if (linkState == 'green') {

            $("#ar-lc").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br>' +
                'Your links are valid. Phew!'
            });
        }
        else if (linkState == 'red' || 'yellow') {


            $("#ar-lc").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br> Link is broken or slow-to-load.'
            });
        }
    }

    function ar_tipsread() {
        if (emState == 'green') {

            $("#ar-em").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br> Connect with your readers using a positive or negative emotion.'
            });
        }
        else if (emState == 'red' || 'yellow') {


            $("#ar-em").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br> Connect with your readers using a positive or negative emotion.'
            })
        }


        if (pwdState == 'green') {
            $('#ar-PWDbtn').remove();

            $("#ar-pwd").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br> Paragraph density is great, making reading a breeze!'
            });
        }
        else if (pwdState == 'red' || 'yellow') {


            $("#ar-pwd").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br>Turn on the highlight feature and revise those sections.'
            })
        }

        if (senState == 'green') {
            $('#ar-SObtn').remove();
            $("#ar-so").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br> Sentence density matches audience readability.'
            });
            $("#ar-wc").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br> Word complexity matches audience readability.'
            });
        }
        else if (senState == 'red' || 'yellow') {

            if (soHL.detail == "TOO COMPLEX") {
                var contentWC = "Turn on this feature to find words that are too complicated for your audience.";
                var contentSO = "";
            }
            if (soHL.detail == "TOO SIMPLE") {
                var contentWC = "Turn on this feature to find words that are too simple for your audience.   ";
                var contentSO = "";
            }

            $("#ar-so").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br>  Turn on the highlight feature and revise those sections.'
            });

            $("#ar-wc").tooltipsy({
                offset: [-10, 0],
                css: {
                    'padding': '10px',
                    'max-width': '200px',
                    'color': 'white',
                    'background-color': '#64C1DD',
                    'border': '1px solid #E6E6E6',
                    '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
                    'text-shadow': 'none',
                    'text-align': 'left'
                },
                content: '<b>Tip:</b><br>'+contentWC
            })
        }


    }

// clear highlight before submiting form, this way will clean the html added to the iframe
    $('form#post').submit(function () {
        clearAllhighlighting();
        return true;
    });

    function unwrapAllHighlighting() {
        var element = $('#content_ifr').contents().find('body');

        $(element).find("span.arSMhighlight").contents().unwrap();
        $(element).find("span.arGMhighlight").contents().unwrap();
        $(element).find("span.SenCompHighlight").contents().unwrap();
        $(element).find("span.WordCompHighlight").contents().unwrap();
        $(element).find("span.arWChighlight").contents().unwrap();
        $(element).find("span.pwdHighlight").contents().unwrap();
        $(element).find("span.arLNhighlight").contents().unwrap();


    }

    function clearAllhighlighting() {
        var element = $('#content_ifr').contents().find('body');

        $(element).find("span.arSMhighlight").removeAttr('style');
        $(element).find("span.arGMhighlight").removeAttr('style');
        $(element).find("span.SenCompHighlight").removeAttr('style');
        $(element).find("span.WordCompHighlight").contents().unwrap();
        $(element).find("span.arWChighlight").contents().unwrap();
        $(element).find("span.pwdHighlight").removeAttr('style');
        $(element).find("span.arLNhighlight").removeAttr('style');
        $(element).parent().css('border', 'none');

        unwrapAllHighlighting();
    }





    $("#aw_moretips_WP p").click(function (e) {
        e.preventDefault();

        $("ul#aw_titletips_WP").slideToggle();
    });

createTooltipsyForTheI();


});
function createTooltipsyForTheI(){


    jQuery("#aud_info_WP").tooltipsy({
        offset: [-10, 0],
        css: {
            'padding': '10px',
            'width': 'auto',
            'color': 'white',
            'background-color': '#64C1DD',
            'border': '1px solid #E6E6E6',
            '-moz-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            '-webkit-box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            'box-shadow': '0 0 10px rgba(0, 0, 0, .5)',
            'text-shadow': 'none',
            'text-align': 'left'
        },
        content: "<p><strong>The knowledge levels to choose from are:</strong><br>" +
        "<strong>General</strong> - your audience has a basic understanding of the content topic or theme.<br>" +
        "<strong>Knowledgeable</strong> - your audience has an advanced understanding of content or theme.<br>" +
        "<strong>Specialist</strong> - your audience has a superior understanding &nbsp;of content or theme.<br>" +
        "<strong>Academic</strong> - your audience has an proficient&nbsp;understanding of content or theme.<br>" +
        "<strong>Genius</strong> - your audience has a expert&nbsp;understanding of content or theme.</p>"
    });
}