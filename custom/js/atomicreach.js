jQuery(document).ready(function ($) {
    $("#aranalyzer_metabox").on("change", ".onoffswitch > input[type=checkbox]", function(e){
        jQuery(".onoffswitch > input[type=checkbox]").not($(this)).prop('checked', false);
    })


    function clearAllhighlighting() {
        var element = $('#content_ifr').contents().find('body');

        if ($(element).find("span.arSMhighlight").length > 0) {
            $(element).find("span.arSMhighlight").removeAttr('style');
            $(element).find("span.arSMhighlight").contents().unwrap();
        }
        if ($(element).find("span.arEMhighlight").length > 0) {
            $(element).find("span.arEMhighlight").removeAttr('style');
            $(element).find("span.arEMhighlight").contents().unwrap();
        }


        if ($(element).find("span.arGMhighlight").length > 0) {
            $(element).find("span.arGMhighlight").removeAttr('style');
            $(element).find("span.arGMhighlight").contents().unwrap();
        }
        if ($(element).find("span.SenCompHighlight").length > 0) {
            $(element).find("span.SenCompHighlight").removeAttr('style');
            $(element).find("span.SenCompHighlight").contents().unwrap();
        }

        if ($(element).find("span.pwdHighlight").length > 0) {
            $(element).find("span.pwdHighlight").removeAttr('style');
            $(element).find("span.pwdHighlight").contents().unwrap();
        }
        if ($(element).find("span.arLNhighlight").length > 0) {
            $(element).find("span.arLNhighlight").removeAttr('style');
            $(element).find("span.arLNhighlight").contents().unwrap();
        }

        if ($(element).find(".writer_wordComplexity").length > 0)
            $(element).find(".writer_wordComplexity").addClass("writer-hide");

        if ($(element).find("span.arWChighlight").length > 0) {
            $(element).find("span.arWChighlight").removeAttr('style');
            $(element).removeHighlight('.arWChighlight');
            if ($(element).find("span.WordCompHighlight").length > 0)
                $(element).removeHighlight('.WordCompHighlight');
        }



    }

    $(document).ajaxComplete(function (event, xhr, settings) {
        var settingsURL = settings.url.match("admin-ajax.php");
        if (settingsURL) {


            if (typeof spellHL != "undefined")
                if (spellHL.length == 0) {
                    $('#ar-spellingHighlightButton').remove();
                } else {
                    $("#writer_Spelling").change(function () {
                        clearAllhighlighting();
                        var thisSMword;
                        var contentWrapper = $('#content_ifr').contents().find('body');

                        if ($("#writer_Spelling").is(":checked")) {

                            for (var i = 0; i < spellHL.detail.length; i++) {

                                if ($('span.arSMhighlight:contains(' + spellHL.detail[i].string + ')').length == 0) {

                                    contentWrapper.highlightRegex('\\b'+spellHL.detail[i].string+'\\b', {'className': 'arSMhighlight'});

                                    thisSMword = $(contentWrapper).find('span.arSMhighlight');
                                    thisSMword.css({'background-color': 'rgba(234, 129, 142, 0.7)', 'position': 'relative', 'display': 'inline'});
                                    $(contentWrapper).find('span.arSMhighlight:contains(' + spellHL.detail[i].string + ')').data('suggestions', spellHL.detail[i].suggestions.option);
                                }
                            }
                            var beforeHover;
                            $(thisSMword).hover(function (e) {
                                beforeHover = $(this).prop("textContent");
                                if (e.pageX > $(contentWrapper).width() - 200) {
                                    var left = -180;
                                } else {
                                    var left = 0;
                                }
                                if (e.pageY > $(contentWrapper).height() - 180) {
                                    var top = -115;
                                } else {
                                    var bottom = 0;
                                }

                                var p;
                                if (typeof p === 'undefined')
                                    p = $(contentWrapper).find('span.arSMhighlight:contains(' + $(this).prop("textContent") + ')').data('suggestions');

                                // sice hover function was being called multiple time, it now checks if #load_spelling_popup exists. If not it
                                // creates it otherwise nothing.
                                if ($(contentWrapper).find("#load_spelling-popup").length < 1) {
                                    $(this).append('<div id="load_spelling-popup"></div>');
                                    _this = $(this);


                                    $(contentWrapper).find("#load_spelling-popup").css({
                                        "position": "absolute",
                                        "bottom": "0px",
                                        "left": left + "px",
                                        "top": top + "px",
                                        "bottom": bottom + "px",
                                        "display": "inline"
                                    });
                                    // bind CSS before load html
                                    $(contentWrapper).find("#load_spelling-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html  .writer-spelling_fix", {}, function () {


                                        $(contentWrapper).find(".writer-spelling_fix").removeClass("writer-hide").css({
                                            "position": "absolute",
                                            "display": "inline",
                                            "z-index": "999999",
                                            "width": "260px",
                                            "text-align": "center",
                                            "border": "2px solid #666666",
                                            "background-color": "#EA818E",
                                            "border-radius": "7px"
                                        });

                                        //var p =  $(contentWrapper).find(_this.data('suggestions'));
                                        if ($.isArray(p)) {
                                            for (var key in p) {
                                                if (p.hasOwnProperty(key)) {

                                                    //$(contentWrapper).find('.writer-spelling_fix .spellings_list').prepend('<li' +
                                                    //    ' style="display:inline; padding: 0 3px">' + p[key] + '</li>');

                                                    $(contentWrapper).find('.writer-spelling_fix .spellings_list').prepend('<li' +
                                                        ' style="display:block; padding-left:75px;text-align:left;"><span class="dashicons dashicons-update r" style="vertical-align: middle;cursor: pointer;"></span><strong>'  +
                                                        p[key] + '</strong></li>');
                                                }
                                            }
                                        } else {
                                            $(contentWrapper).find('.writer-spelling_fix .spellings_list').prepend('<li style="display:inline"><span class="dashicons dashicons-update r" style="vertical-align:' +
                                                ' middle;cursor: pointer;"></span><strong>' + p + '</strong></li>');
                                        }

                                        $(contentWrapper).find('.writer-spelling_fix .spellings_list > li .r').unbind('click').bind('click', function (e) {

                                            var x = $(this).next('strong').text();
                                            var r = this.closest("span.arSMhighlight").childNodes[0].textContent;
                                            this.closest("span.arSMhighlight").childNodes[0].textContent = x;
                                            $(this).next('strong').text(r);

                                        });


                                        $(contentWrapper).find('.writer-spelling_fix #add_dictionary').click(function (e) {
                                            e.preventDefault();

                                            var word = _this.not($('#load_spelling-popup'))[0].childNodes[0].data.toLowerCase();

                                            var response = addToDictionary(word);

                                            if (response) {
                                                alert('Word added to the Dictionary');
                                                //TODO: remove highlighting right here
                                            } else {
                                                alert('An error occurred');
                                            }

                                        });


                                    });
                                }
                            }, function () {

                                var _this = $(this);
                                var x = $(this).prop("textContent").split(" ");
                                var afterHover = x[0].trim();


                                setTimeout(function () {
                                    if ($(contentWrapper).find("#load_spelling-popup").length > 0)
                                        if (!$(contentWrapper).find("#load_spelling-popup").is(":hover")) {
                                            $(contentWrapper).find("#load_spelling-popup").remove();

                                            if(beforeHover != afterHover){
                                                _this.removeAttr('style').removeClass('.arSMhighlight').contents().unwrap();

                                            }

                                        }
                                }, 400);
                            });
                        } else {
                            var contentWrapper = $('#content_ifr').contents().find('body');

                            contentWrapper.removeClass("noun").removeHighlight('.arSMhighlight');
                            $(".writer-spelling_fix").addClass("writer-hide");

                        }

                    });
                }

            if (typeof grammarHL != "undefined")
                if (grammarHL.total == 0) {
                    $('#ar-grammarHighlightButton').remove()
                } else {

                    $("#writer_Grammar").change(function () {
                        clearAllhighlighting();
                        var contentWrapper = $('#content_ifr').contents().find('body');
                        var gmWord;
                        if ($("#writer_Grammar").is(":checked")) {
                            for (var i = 0; i < grammarHL.detail.length; i++) {
                                if ($('span.arGMhighlight:contains(' + grammarHL.detail[i].string + ')').length == 0) {
                                    contentWrapper.highlightRegex(grammarHL.detail[i].string, {className: 'arGMhighlight'});
                                    gmWord = $(contentWrapper).find('span.arGMhighlight');
                                    $(gmWord).css({'background-color': 'rgba(150, 237, 164, 0.7)', 'position': 'relative', 'display': 'inline'});
                                    $(contentWrapper).find('span.arGMhighlight:contains("' + grammarHL.detail[i].string + '")')
                                        .data('suggestions', grammarHL.detail[i].suggestions.option)
                                        .data('description', grammarHL.detail[i].description)
                                        .data('url', grammarHL.detail[i].url);
                                }
                            }

                            //$('span[data-color="green"]').css({'background-color': '#5FFF6B'});

                            $(gmWord).hover(function (e) {

                                if (e.pageX > $(contentWrapper).width() - 200) {
                                    var left = -180;
                                } else {
                                    var left = 0;
                                }
                                if (e.pageY > $(contentWrapper).height() - 180) {
                                    var top = -155;
                                } else {
                                    var bottom = 0;
                                }

                                var p, l, d;
                                if (typeof p === 'undefined')
                                    p = $(contentWrapper).find('span.arGMhighlight:contains(' + $(this).prop("textContent") + ')').data('suggestions');
                                if (typeof l === 'undefined')
                                    l = $(contentWrapper).find('span.arGMhighlight:contains(' + $(this).prop("textContent") + ')').data('url');
                                if (typeof d === 'undefined')
                                    d = $(contentWrapper).find('span.arGMhighlight:contains(' + $(this).prop("textContent") + ')').data('description');


                                if ($(contentWrapper).find("#load_grammar-popup").length == 0) {
                                    $(this).append('<div id="load_grammar-popup"></div>');
                                    _this = $(this);
                                    $(contentWrapper).find("#load_grammar-popup").css({
                                        "position": "absolute",
                                        "bottom": bottom + "px",
                                        "left": left + "px",
                                        "top": top + "px",
                                        "display": "inline"
                                    });
                                    $(contentWrapper).find("#load_grammar-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html  .writer-grammar_fix",
                                        p, function () {
                                            $(contentWrapper).find(".writer-grammar_fix").removeClass("writer-hide").css({
                                                "position": "absolute",
                                                "display": "inline",
                                                "z-index": "99999",
                                                "width": "260px",
                                                "text-align": "center",
                                                "border": "2px solid #666666",
                                                "background-color": "#96EDA4",
                                                "border-radius": "7px"
                                            });


                                            //"see explanation"
                                            $(contentWrapper).find('.writer-grammar_fix .suggestions_content').html(
                                                '<p class=" " style="margin-bottom: 0px !important;">Revise: ' + d + '</p>');
                                            //.append("<hr/>");
                                            $(contentWrapper).find('.writer-grammar_fix .suggestions_link .grammar-suggestion-link').attr('href', l);
                                            for (var key in p) {
                                                if (p.hasOwnProperty(key)) {
                                                    $(contentWrapper).find('.writer-grammar_fix div').append('<p class="suggestions_content">' + p[key] + '</p>');
                                                }
                                            }
                                            $(contentWrapper).find('.writer-grammar_fix a.grammar-suggestion-link').click(function (e) {
                                                e.preventDefault();


                                                newwindow = window.open(l, 'name', 'height=400,width=450');
                                                if (window.focus) {
                                                    newwindow.focus()
                                                }
                                                return false;
                                            })
                                        });
                                }
                            }, function () {
                                setTimeout(function () {
                                    if ($(contentWrapper).find("#load_grammar-popup").length > 0)
                                        if (!$(contentWrapper).find("#load_grammar-popup").is(":hover")) {
                                            $(contentWrapper).find("#load_grammar-popup").remove();
                                        }
                                }, 500);
                            });
                        }
                        else {
                            var contentWrapper = $('#content_ifr').contents().find('body');
                            $(contentWrapper).removeClass("noun").removeHighlight('.arGMhighlight');
                            $(contentWrapper).find(".writer-grammar_fix").addClass("writer-hide");
                        }
                    });
                }

            if (typeof linkHL != "undefined")
                if (linkHL.invalid == 0) {
                    $('#ar-linksHighlightButton').remove();
                } else {

                    $("#writer_Links").change(function () {
                        clearAllhighlighting();
                        var contentWrapper = $('#content_ifr').contents().find('body');

                        if ($("#writer_Links").is(":checked")) {

                            for (var i = 0; i < linkHL.detail.length; i++) {
                                link = contentWrapper.find('a[href="' + linkHL.detail[i] + '"]');
                                if (link.parent('span').length != 1 || !link.parent('span').hasClass('highlight')) {
                                    link.wrap('<span class="arLNhighlight" style="background-color: rgba(137, 227, 232, 0.7); position: relative; display: inline;">' +
                                        ' </span>');
                                }
                            }

                            $(contentWrapper).find("span.arLNhighlight").hover(function (e) {
                                if ($(contentWrapper).find("#load_links-popup").length == 0) {
                                    $(this).append('<div id="load_links-popup"></div>');

                                    _this = $(this);
                                    $(contentWrapper).find("#load_links-popup").css({
                                        'left': '0',
                                        'bottom': '0',
                                        'position': 'absolute',
                                        'display': 'inline'
                                    });
                                    $(contentWrapper).find("#load_links-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer-links_fix", function () {
                                        $(contentWrapper).find(".writer-links_fix").removeClass("writer-hide");
                                        var p = _this.data('suggestions');
                                        for (var key in p) {
                                            if (p.hasOwnProperty(key)) {
                                                $(contentWrapper).find('.writer-links_fix div').prepend('<p class="suggestions_content">' + p[key] + '</p>');
                                            }
                                        }
                                    });
                                }
                            }, function () {

                                setTimeout(function () {
                                    if (!$(contentWrapper).find("#load_links-popup").is(":hover")) {
                                        $(contentWrapper).find("#load_links-popup").remove();
                                    }
                                }, 500);
                            });
                        } else {
                            var contentWrapper = $('#content_ifr').contents().find('body');
                            $(contentWrapper).removeClass("noun").find('span.arLNhighlight > a').unwrap();
                            $(contentWrapper).find(".writer-links_fix").addClass("writer-hide");
                        }
                    });
                }

            $("#writer_EM").change(function() {
                clearAllhighlighting();
                var contentWrapper = $('#content_ifr').contents().find('body');

                var thisEMword;
                if ($("#writer_EM").is(":checked")) {
                    var em = emWords;

                    $.each(em, function(i,v){
                        if ($('span.arEMhighlight:contains(' + i + ')').length == 0) {
                            contentWrapper.highlightRegex('\\b'+i+'\\b', {'className': 'arEMhighlight'});
                            thisEMword = $(contentWrapper).find('span.arEMhighlight');

                            if(v == 'red'){

                                $(contentWrapper).find('span.arEMhighlight:contains('+i+')').css({'background-color': 'rgba(125, 208, 255, 0.7)', 'position': 'relative', 'display': 'inline'});
                            }else if(v == 'green'){

                                $(contentWrapper).find('span.arEMhighlight:contains('+i+')').css({'background-color': 'rgba(255, 243, 128, 0.7)', 'position': 'relative', 'display': 'inline'});
                            }


                            $(contentWrapper).find('span.arEMhighlight:contains(' + i + ')').data('val', v);
                        }
                    });

                    $(thisEMword).hover(function (e) {
                        if (e.pageX > $(contentWrapper).width() - 200) {
                            var left = -180;
                        } else {
                            var left = 0;
                        }
                        if (e.pageY > $(contentWrapper).height() - 180) {
                            var top = -115;
                        } else {
                            var bottom = 0;
                        }

                        var p;
                        if (typeof p === 'undefined')
                            p = $(contentWrapper).find('span.arEMhighlight:contains(' + $(this).prop("textContent") + ')').data('val');
                        var thisWord = $(this).prop("textContent");
                        if ($(contentWrapper).find("#load_emotion-popup").length < 1) {
                            $(this).append('<div id="load_emotion-popup"></div>');
                            _this = $(this);

                            $(contentWrapper).find("#load_emotion-popup").css({
                                "position": "absolute",
                                "bottom": "0px",
                                "left": left + "px",
                                "top": top + "px",
                                "bottom": bottom + "px",
                                "display": "inline"
                            });

                            $(contentWrapper).find("#load_emotion-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer-emotion_fix", {}, function () {
                                $(contentWrapper).find('.writer-emotion_fix .suggestions_header-em').text( thisWord );
                                if (p == "red") {
                                    $(contentWrapper).find('.writer-emotion_fix .ar-emRED').show();
                                } else if (p == "green") {
                                    $(contentWrapper).find('.writer-emotion_fix .ar-emGREEN').show();
                                }

                                $(contentWrapper).find(".writer-emotion_fix").removeClass("writer-hide").css({
                                    "position": "absolute",
                                    "display": "inline",
                                    "z-index": "999",
                                    "width": "260px",
                                    "text-align": "center",
                                    "border": "2px solid #666666",
                                    "background-color": "#EA818E",
                                    "border-radius": "7px"
                                });
                            });//end load

                        }

                    },function(){
                        setTimeout(function () {
                            if ($(contentWrapper).find("#load_emotion-popup").length > 0)
                                if (!$(contentWrapper).find("#load_emotion-popup").is(":hover")) {
                                    $(contentWrapper).find("#load_emotion-popup").remove();
                                }
                        }, 400);
                    });

                }else{
                    var contentWrapper = $('#content_ifr').contents().find('body');
                    contentWrapper.removeClass("noun").removeHighlight('.arEMhighlight');
                    $(".writer-emotion_fix").addClass("writer-hide");
                }

            });//end change

            $("#writer_ParaDensity").change(function () {
                clearAllhighlighting();
                var contentWrapper = $('#content_ifr').contents().find('body');

                if ($("#writer_ParaDensity").is(":checked")) {
                    var p = pwdHL.detail.paragraphs;
                    $.each($(contentWrapper).find('p'), function(ind, val){
                       if (p[ind] != "length_hit" &&  p[ind] != "" && typeof p[ind] != "undefined"){
                           $(this).wrapInner( "<span class='pwdHighlight'></span>");
                           $(this).find('span.pwdHighlight').css({
                               'background-color': 'rgba(220, 220, 244, 0.7)',
                               'position': 'relative',
                               'display': 'inline'
                           });
                           $(this).data("value", p[ind]);
                       }
                    });

                    var contentWrapper = $('#content_ifr').contents().find('body');
                    $(contentWrapper).find("span.pwdHighlight").hover(function (e) {

                        $(this).click(function () {
                            $(this).children("#load_ParaComp-popup").remove();
                            $(this).unbind('hover');
                        })


                        if (e.pageY > $(contentWrapper).height() - 180) {
                            var top = -155;
                        } else {
                            var bottom = 0;
                        }

                        var responseData = $(this).parents('p').data();

                        if ($(contentWrapper).find("#load_ParaComp-popup").length == 0) {
                            $(this).append('<div id="load_ParaComp-popup"></div>');
                            $(contentWrapper).find("#load_ParaComp-popup").css({
                                'left': "0",
                                'bottom': bottom + 'px',
                                'top': top + 'px',
                                'position': 'absolute',
                                'display': 'inline'
                            });
                            $(contentWrapper).find("#load_ParaComp-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer-paragraph_Complexity", function () {
                                titleText = responseData.value.matchResult;
                                maxWords = pwdHL.recommended.paragraphWordDensityMax;
                                switch (responseData.value.matchResult) {
                                    case "toolong":
                                        titleText = "Too Long";
                                        break;
                                    case "tooshort":
                                        titleText = "Too Short";
                                        break;
                                }
                                $($(contentWrapper).find(".writer-paragraph_Complexity p")[0]).text(titleText);
                                $(contentWrapper).find("#numberOfWords").text(maxWords);
                                $($(contentWrapper).find(".writer-paragraph_Complexity p")[0]).text();
                                $(contentWrapper).find(".writer-paragraph_Complexity").removeClass("writer-hide");


                            })
                        }
                    }, function () {
                        $(contentWrapper).find("#load_ParaComp-popup").remove();
                    })
                    ;
                }

                else {
                    var contentWrapper = $('#content_ifr').contents().find('body');
                    $(contentWrapper).find("span.pwdHighlight").contents().unwrap();
                    $(contentWrapper).find(".writer-paragraph_Complexity").addClass("writer-hide");

                }

            });


            $("#writer_SenComp").change(function () {
                clearAllhighlighting();
                if ($("#writer_SenComp").is(":checked")) {



                    var contentWrapper = $('#content_ifr').contents().find('body');


                    $.each($(contentWrapper).find('p'), function(i,v) {
                        if (typeof soHL.sentences[i] != "undefined"){
                            var sent = $(v).prop('innerText').split(/[\?\.\!]\n|[\?\.\!]\s/);

                            for (var x = 0; x < sent.length; x++) {

                                if (soHL.sentences[i][x] != "UNAVAILABLE") {
                                    $(this).highlight(sent[x].trim(), "SenCompHighlight");
                                    //$(this).wrapInner('<span class="SenCompHighlight"></span>');
                                     $(this).find("span.SenCompHighlight").css({
                                         "background-color": 'rgba(246, 197, 164, 0.70)',
                                         "position": "relative",
                                         "display": "inline"});
                                    $(this).data("value", soHL.sentences[i][x])

                                }

                            }
                        }

                    });

                    var contentWrapper = $('#content_ifr').contents().find('body');


                    $(contentWrapper).find("span.SenCompHighlight").hover(function (e) {
                        console.log($(this));
                        $(this).click(function () {
                            $(this).children("#load_SenComp-popup").remove();
                            $(this).unbind('hover');
                        });

                        if (e.pageY > $(contentWrapper).height() - 180) {
                            var top = -155;
                        } else {
                            var bottom = 0;
                        }

                        var responseData = $(this).parents('p').data();

                        if ($(contentWrapper).find("#load_SenComp-popup").length == 0)
                            $(this).append('<div id="load_SenComp-popup"></div>');
                        $(contentWrapper).find("#load_SenComp-popup").css({
                            'left': '0',
                            'bottom': bottom + 'px',
                            'top': top + 'px',
                            'z-index': '99999',
                            'font-weight': "normal",
                            'text-decoration': "none",
                            'position': 'absolute',
                            'display': 'inline'
                        });

                        $(contentWrapper).find("#load_SenComp-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer-sentence_Complexity", function () {
                            //$( $(".writer-word_Complexity")[0] ).text(responseData.value.matchResult);
                            console.log(responseData.value);
                            if (responseData.value == "TOO COMPLEX") {

                                $(contentWrapper).find('.writer-sentence_Complexity .ar-tooComplex').show();
                            } else if (responseData.value == "TOO SIMPLE") {
                                $(contentWrapper).find('.writer-sentence_Complexity .ar-tooSimple').show();
                            }
                            $(contentWrapper).find('.writer-sentence_Complexity').removeClass("writer-hide");
                        });
                    }, function () {
                            $(contentWrapper).find("#load_SenComp-popup").remove();
                    })

                } else {
                    var contentWrapper = $('#content_ifr').contents().find('body');
                    if ($(contentWrapper).find("span.SenCompHighlight").length > 0) {
                        contentWrapper.removeHighlight(".SenCompHighlight");
                        $(contentWrapper).find(".writer-sentence_Complexity").addClass("writer-hide");
                    }
                }
            });


            // +++++++++++++ WORD COMPLEXITY +++++++++++++++++++
            $("#writer_WordComp").change(function () {
                if ($("#writer_WordComp").is(":checked")) {
                clearAllhighlighting();
                    var so = soHL;

                    var contentWrapper = $('#content_ifr').contents().find('body');
                    //var pArray = new Array();

                    var sugg = [];

                    $.each($(contentWrapper).find('p'), function(i,v) {

                        if (typeof so.synonyms[i] != "undefined") {

                            //pArray[i] = new Array();
                        var sent = $(v).prop('innerText').split(/[\?\.\!]\n|[\?\.\!]\s/);

                        for (var x = 0; x < sent.length; x++) {
                            //pArray[i][x] = sent[x].trim();


                            if (typeof so.synonyms[i][x] !== "undefined") {

                                //$(this).highlight(sent[x].trim(), "WordCompHighlight");
                                $(this).wrapInner("<span class='WordCompHighlight'></span>");
                                    var _this = $(this);


                                $.each(so.synonyms[i][x], function(i2, v2) {


                                    //if ($(_this).find('span.arWChighlight:contains(' + i2 + ')').length == 0) {


                                    $(_this).find("span.WordCompHighlight").highlightRegex('\\b'+i2+'\\b', {'className': 'arWChighlight'});


                                    thisWDword = $(contentWrapper).find('span.WordCompHighlight > span.arWChighlight');
                                    thisWDword.css({
                                        'background-color': 'rgba(255, 222, 137, 0.7)',
                                        'position': 'relative',
                                        'display': 'inline'
                                    });



                                    sugg = []; //reset variable
                                    for (var k = 0; k < v2.length; k++) {
                                        sugg.push(v2[k]);

                                    }

                                    $(_this).find('span.arWChighlight:contains(' + i2 + ')').data('suggestions', sugg);
                                //}
                                //    return false;
                                });

                            }
                        }
                    }
                    });

                    var contentWrapper = $('#content_ifr').contents().find('body');
                    var topbottom;
                    var beforeHover;
                    $(thisWDword).hover(function (e) {
                        beforeHover = $(this).prop("textContent");

                        var thisword = $(this).text();
                       if (e.pageX > $(contentWrapper).width() - 200) {
                            var left = -180;
                        } else {
                            var left = 0;
                        }



                        var p;
                        if (typeof p === 'undefined')
                            p = $(contentWrapper).find('span.arWChighlight:contains(' + $(this).prop("textContent") + ')').data('suggestions');


                        if ($(contentWrapper).find("#load_wordComplexity-popup").length < 1) {

                            $(this).append('<div id="load_wordComplexity-popup"></div>');
                            $(contentWrapper).find("#load_wordComplexity-popup").css({
                                "position": "absolute",
                                "display": "inline",
                            });


                            $(contentWrapper).find("#load_wordComplexity-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer_wordComplexity", function () {
                                $(contentWrapper).find(".writer_wordComplexity").removeClass("writer-hide").css({
                                    "position": "absolute",
                                    "display": "inline",
                                    "font-weight": "normal",
                                    "text-decoration": "none",
                                    "z-index": "999",
                                    "width": "480px",
                                    "text-align": "center",
                                    "color": "#666666",
                                    "border": "2px solid #666666",
                                    "background-color": "rgb(255, 222, 137)",
                                    "border-radius": "7px"
                                });
                                $(contentWrapper).find(".writer_wordComplexity .wordCompWord").append(thisword);
                                if ($.isArray(p)) {
                                    for (var key in p) {
                                        if (p.hasOwnProperty(key)) {
                                            var words = '';

                                                    for (var x = 0; x < p[key][1].length; x++) {
                                                        if (typeof p[key][1][x][0] != "undefined")
                                                            words += "<span style='display: inline; text-transform: capitalize;'" +
                                                                " class='ar-synonym'><span class=\"dashicons dashicons-update r\" style=\"vertical-align: middle;cursor: pointer;\"></span><strong>" +
                                                                p[key][1][x][0].replace("_", " ") + "</strong></span>, ";
                                                    }
                                                    if (typeof words != 'undefined')
                                                        $(contentWrapper).find('.writer_wordComplexity .wordCompright').append('<p style="margin: 0;' +
                                                            ' padding: 0; " class="word-complexity-sug">' + words.replace(/,\s*$/, "") +
                                                            "&nbsp;<span class='ARWCinfoBox' style='background-color: #5a5a5a;border-radius:" +
                                                            " 32px;width: 14px;height: 14px;display: inline-block;line-height: 10px;'><i style='color:" +
                                                            "#fff;padding: 6px;text-align: center;font-size: 11px;font-style: normal;font-weight: 600;'>i</i></span></p>");

                                                    if (typeof p[key][0] == "string")
                                                        $(contentWrapper).find('.writer_wordComplexity .wordCompright').append("<p style=\"margin: 0;" +
                                                            "padding: 0; border-top: 1px solid #fff; display: none;\"" +
                                                            " class=\"word-complexity-def\">" + p[key][0]) + "</p>";

                                        }
                                    }


                                    if (e.pageY < $(contentWrapper).find("#load_wordComplexity-popup > div").outerHeight() + 20) {
                                        var bottom = "-" + $(contentWrapper).find("#load_wordComplexity-popup > div").outerHeight() + "px";
                                        var top = null;
                                    } else {
                                        var bottom = null;
                                        var top = 0;
                                    }

                                    if (e.pageX > $(contentWrapper).find("#load_wordComplexity-popup > div").outerWidth()) {

                                        var tempWidth = $(contentWrapper).find("#load_wordComplexity-popup > div").outerWidth() - 60;
                                        var left = "-" + tempWidth + "px";
                                    } else {
                                        var left = 0;
                                    }


                                    $(contentWrapper).find("#load_wordComplexity-popup").css({
                                        "left": left,
                                        "top": top,
                                        "bottom": bottom
                                    });


                                    $(contentWrapper).find('.writer_wordComplexity .wordCompright .word-complexity-sug .ARWCinfoBox').unbind("click").bind("click", function (e) {
                                        $(this).parents('.word-complexity-sug').next(".word-complexity-def").slideToggle();
                                    });

                                    $(contentWrapper).find('.writer_wordComplexity .wordCompright .word-complexity-sug .ar-synonym .r').unbind("click").bind("click", function (e) {
                                        var x = $(this).next('strong').text();
                                        var r = this.closest("span.arWChighlight").childNodes[0].textContent;
                                        this.closest("span.arWChighlight").childNodes[0].textContent = x;
                                        $(this).next('strong').text(r);
                                    });
                                }
                            });//end .load
                        }

                    }, function () {
                        var _this = $(this);
                        var x = $(this).prop("textContent").split(" ");
                        var afterHover = x[0].trim();
                        setTimeout(function () {
                            if ($(contentWrapper).find("#load_wordComplexity-popup").length > 0)
                                if (!$(contentWrapper).find("#load_wordComplexity-popup").is(":hover")) {
                                    $(contentWrapper).find("#load_wordComplexity-popup").remove()

                                    if(beforeHover != afterHover){
                                        _this.removeAttr('style').removeClass('.arWChighlight').contents().unwrap();

                                    }

                                }
                        }, 1);
                    })
                } else {

                    var contentWrapper = $('#content_ifr').contents().find('body');

                    if ($(contentWrapper).find("span.arWChighlight").length > 0) {
                        contentWrapper.removeClass("noun").removeHighlight('.arWChighlight');
                    }
                    if ($(contentWrapper).find("span.WordCompHighlight").length > 0) {
                        contentWrapper.removeClass("noun").removeHighlight('.WordCompHighlight');
                    }

                    if ($(contentWrapper).find(".writer_wordComplexity").length > 0)
                        $(contentWrapper).find(".writer_wordComplexity").addClass("writer-hide");
                }

            });

            $("#aw_moretips_WP p").unbind('click').bind('click', function(e){
                e.preventDefault();
                $("ul#aw_titletips_WP").slideToggle();
            });
        }
    });

    function addToDictionary(w) {

        return jQuery.ajax({
                url: 'admin-ajax.php',
                data: {action: 'ar_analyzer_custom_dictionary', word: w},
                async: false
            }).responseText == 'OK';
    }

});