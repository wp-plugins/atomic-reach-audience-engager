jQuery(document).ready(function ($) {
    $("#aranalyzer_metabox").on("change", ".onoffswitch > input[type=checkbox]", function(e){
        jQuery(".onoffswitch > input[type=checkbox]").not($(this)).prop('checked', false);
    })
    function unwrapAllHighlighting() {
        var element = $('#content_ifr').contents().find('body');

        if ($(element).find("span.arSMhighlight").length > 0) {
            $(element).find("span.arSMhighlight").contents().unwrap();
        }

        if ($(element).find("span.arGMhighlight").length > 0) {
            $(element).find("span.arGMhighlight").contents().unwrap();
        }

        if ($(element).find("span.SenCompHighlight").length > 0) {
            $(element).find("span.SenCompHighlight").contents().unwrap();
        }
        if ($(element).find("span.WordCompHighlight").length > 0) {
            $(element).find("span.WordCompHighlight").contents().unwrap();
        }
        if ($(element).find("span.arWChighlight").length > 0) {
            $(element).find("span.arWChighlight").contents().unwrap();
        }
        if ($(element).find("span.pwdHighlight").length > 0) {
            $(element).find("span.pwdHighlight").contents().unwrap();
        }
        if ($(element).find("span.arLNhighlight").length > 0) {
            $(element).find("span.arLNhighlight").contents().unwrap();
        }


    }

    function clearAllhighlighting() {
        var element = $('#content_ifr').contents().find('body');
        if ($(element).find("span.arSMhighlight").length > 0) {
            $(element).find("span.arSMhighlight").removeAttr('style');
        }

        if ($(element).find("span.arGMhighlight").length > 0) {
            $(element).find("span.arGMhighlight").removeAttr('style');
        }
        if ($(element).find("span.SenCompHighlight").length > 0) {
            $(element).find("span.SenCompHighlight").removeAttr('style');
        }
        if ($(element).find("span.WordCompHighlight").length > 0) {
            $(element).find("span.WordCompHighlight").contents().unwrap();
        }
        if ($(element).find("span.arWChighlight").length > 0) {
            $(element).find("span.arWChighlight").contents().unwrap();
        }
        if ($(element).find("span.pwdHighlight").length > 0) {
            $(element).find("span.pwdHighlight").removeAttr('style');
        }
        if ($(element).find("span.arLNhighlight").length > 0) {
            $(element).find("span.arLNhighlight").removeAttr('style');
        }

        unwrapAllHighlighting();
    }

    $(document).ajaxComplete(function (event, xhr, settings) {
        if (settings.url.match("admin-ajax.php").length == 1) {


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

                                    contentWrapper.highlightRegex(spellHL.detail[i].string, {'className': 'arSMhighlight'});

                                    thisSMword = $(contentWrapper).find('span.arSMhighlight');
                                    thisSMword.css({'background-color': 'rgba(234, 129, 142, 0.7)', 'position': 'relative', 'display': 'inline'});
                                    $(contentWrapper).find('span.arSMhighlight:contains(' + spellHL.detail[i].string + ')').data('suggestions', spellHL.detail[i].suggestions.option);
                                }
                            }

                            $(thisSMword).hover(function (e) {


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
                                            "width": "180px",
                                            "text-align": "center",
                                            "border": "1.5px solid #666666",
                                            "background-color": "#EA818E",
                                            "border-radius": "7px"
                                        });



                                        //var p =  $(contentWrapper).find(_this.data('suggestions'));


                                        if ($.isArray(p)) {

                                            for (var key in p) {
                                                if (p.hasOwnProperty(key)) {

                                                    $(contentWrapper).find('.writer-spelling_fix .spellings_list').prepend('<li' +
                                                        ' style="display:inline; padding: 0 3px">' + p[key] + '</li>');
                                                }
                                            }
                                        } else {
                                            $(contentWrapper).find('.writer-spelling_fix .spellings_list').prepend('<li style="display:inline">' + p + '</li>');
                                        }


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

                                setTimeout(function () {
                                    if ($(contentWrapper).find("#load_spelling-popup").length > 0)
                                        if (!$(contentWrapper).find("#load_spelling-popup").is(":hover")) {
                                            $(contentWrapper).find("#load_spelling-popup").remove();
                                        }
                                }, 900);
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
                                                "border": "1.5px solid #666666",
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


            $("#writer_ParaDensity").change(function () {
                clearAllhighlighting();
                var contentWrapper = $('#content_ifr').contents().find('body');

                if ($("#writer_ParaDensity").is(":checked")) {

                    $.map(pwdHL.detail.paragraphTeasers, function (val, index) {
                        teaser = val.substring(0, val.length - 3);
                        data = pwdHL.detail.paragraphDOM;
                        var finalElement;
                        var allowedElements = data.split(",");
                        $.map(allowedElements, function (ele, i) {
                            element = $(contentWrapper).find(ele + ":contains('" + teaser + "')");
                            if (element.length == 1) {
                                $.map(element, function (v, h) {
                                    finalElement = v;
                                });
                                return false;
                            }
                        });
                        var data1 = null;
                        $.map(pwdHL.detail.paragraphDetails, function (v1, i1) {
                            if (v1.index == index && v1.matchResult != "length_hit") {
                                data1 = v1;
                            }
                        });
                        if (data1 != null) {
                            $(contentWrapper).find(finalElement).data("value", data1);
                            $.map($(contentWrapper).find(finalElement).contents(), function (n, m) {
                                if (n.nodeType == 3) {
                                    contentWrapper.highlight(n.textContent, "pwdHighlight");
                                    $(contentWrapper).find('span.pwdHighlight').css({
                                        'background-color': 'rgba(220, 220, 244, 0.7)',
                                        'position': 'relative',
                                        'display': 'inline'
                                    });
                                }
                            });
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

                        var responseData = $(this).parent().data();

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
                    $.map(soHL.paragraphTeasers, function (val, index) {
                        teaser = val.substring(0, val.length - 3);
                        data = soHL.paragraphDOM;
                        var finalElement;
                        var allowedElements = data.split(",");

                        var contentWrapper = $('#content_ifr').contents().find('body');

                        $.map(allowedElements, function (ele, i) {
                            element = $(contentWrapper).find(ele + ":contains('" + teaser + "')");
                            if (element.length == 1) {
                                $.map(element, function (v, h) {
                                    finalElement = v;
                                });
                                return false;
                            }
                        });
                        var data1 = null;
                        $.map(soHL.paragraphDetails, function (v1, i1) {
                            if (v1.index == index) {
                                data1 = v1;
                            }
                        });
                        if (data1 != null) {
                            var contentWrapper = $('#content_ifr').contents().find('body');

                            $(contentWrapper).find(finalElement).data("value", data1);

                            $.map($(contentWrapper).find(finalElement).contents(), function (n, m) {
                                if (n.nodeType == 3 || 1) {
                                    $(contentWrapper).removeClass("noun").highlight(n.textContent, "SenCompHighlight");
                                    var sentenceSO = $(contentWrapper).find('span.SenCompHighlight');
                                    sentenceSO.css({"background-color": 'rgba(246, 197, 164, 0.70)', "position": "relative", "display": "inline"});
                                }
                            });
                        }
                    });

                    //$(contentWrapper).removeClass("noun").highlight('overladen');
                    var contentWrapper = $('#content_ifr').contents().find('body');


                    $(contentWrapper).find("span.SenCompHighlight").hover(function (e) {

                        $(this).click(function () {
                            $(this).children("#load_SenComp-popup").remove();
                            $(this).unbind('hover');
                        });

                        if (e.pageY > $(contentWrapper).height() - 180) {
                            var top = -155;
                        } else {
                            var bottom = 0;
                        }

                        var responseData = $(this).parent().data();

                        if ($(contentWrapper).find("#load_SenComp-popup").length == 0)
                            $(this).append('<div id="load_SenComp-popup"></div>');
                        $(contentWrapper).find("#load_SenComp-popup").css({
                            'left': '0',
                            'bottom': bottom + 'px',
                            'top': top + 'px',
                            'z-index': '99999',
                            'position': 'absolute',
                            'display': 'inline'
                        });

                        $(contentWrapper).find("#load_SenComp-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer-sentence_Complexity", function () {
                            //$( $(".writer-word_Complexity")[0] ).text(responseData.value.matchResult);

                            if (responseData.value.matchResult == "TOO COMPLEX") {

                                $(contentWrapper).find('.writer-sentence_Complexity .ar-tooComplex').show();
                            } else if (responseData.value.matchResult == "TOO SIMPLE") {
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
                        $(contentWrapper).find("span.SenCompHighlight").contents().unwrap();
                        $(contentWrapper).find(".writer-sentence_Complexity").addClass("writer-hide");
                    }
                }
            });


            // +++++++++++++ WORD COMPLEXITY +++++++++++++++++++
            /*$("#writer_WordComp").change(function () {
                //clearAllhighlighting();
                if ($("#writer_WordComp").is(":checked")) {
                    var so = soHL;
                    $.map(so.paragraphTeasers, function (val, index) {

                        teaser = val.substring(0, val.length - 3);

                        data = so.paragraphDOM;
                        var finalElement;
                        var allowedElements = data.split(",");
                        var contentWrapper = $('#content_ifr').contents().find('body');

                        var sugg = [];
                        $.map(so.synonyms, function (v1, i1) {
                            if (i1 == index) {
                                $.map(allowedElements, function (ele, i) {
                                    element = $(contentWrapper).find(ele + ":contains('" + teaser + "')");
                                    if (element.length == 1) {
                                        var x = $(element).prop('innerText').split(/[\?\.\!]/); //store sentences from the paragraphs.
                                        //iterate through the sentences and wrap around span.WordCompHighlight tag
                                        $.map(x, function (v, h) {
                                            var s = v.replace(/^\s+/, '');
                                            $(contentWrapper).highlight(s, "WordCompHighlight");
                                        });
                                        return false;
                                    }
                                });
                                $.map(v1, function (v2, i2) {
                                    $.each(v2, function (v3, i3) {
                                        if ($(contentWrapper).find('span.WordCompHighlight > span.arWChighlight:contains(' + v3 + ')').length == 0) {
                                            $(contentWrapper).find("span.WordCompHighlight").highlightRegex(v3, {'className': 'arWChighlight'});
                                            thisWDword = $(contentWrapper).find('span.WordCompHighlight > span.arWChighlight');
                                            thisWDword.css({
                                                'background-color': 'rgba(255, 222, 137, 0.7)',
                                                'position': 'relative',
                                                'display': 'inline'
                                            });

                                            sugg = []; //reset variable
                                            for (var k = 0; k < i3.length; k++) {
                                                sugg.push(i3[k]);
                                            }
                                            $(contentWrapper).find('span.arWChighlight:contains(' + v3 + ')').data('suggestions', sugg);
                                        }

                                    })
                                });
                            }
                        });
                    });
                    var contentWrapper = $('#content_ifr').contents().find('body');
                    $(thisWDword).hover(function (e) {
                        var thisword = $(this).text();
                        if (e.pageX > $(contentWrapper).width() - 200) {
                            var left = -180;
                        } else {
                            var left = 0;
                        }
                        if (e.pageY > $(contentWrapper).height() - 180) {
                            var top = -115;
                        }

                        var p;
                        if (typeof p === 'undefined')
                            p = $(contentWrapper).find('span.arWChighlight:contains(' + $(this).prop("textContent") + ')').data('suggestions');


                        if ($(contentWrapper).find("#load_wordComplexity-popup").length < 1) {

                            $(this).append('<div id="load_wordComplexity-popup"></div>');
                            $(contentWrapper).find("#load_wordComplexity-popup").css({
                                "position": "absolute",
                                "bottom": "0px",
                                "left": left + "px",
                                "top": top + "px",
                                "display": "inline"
                            });


                            $(contentWrapper).find("#load_wordComplexity-popup").load("../wp-content/plugins/atomic-reach-audience-engager/custom/html/popups.html .writer_wordComplexity", function () {
                                $(contentWrapper).find(".writer_wordComplexity").removeClass("writer-hide").css({
                                    "position": "absolute",
                                    "display": "inline",
                                    "z-index": "999",
                                    "width": "480px",
                                    "text-align": "center",
                                    "color": "#000000",
                                    "border": "1.5px solid rgb(255, 222, 137)",
                                    "background-color": "rgb(255, 222, 137)",
                                    "border-radius": "7px"
                                });
                                $(contentWrapper).find(".writer_wordComplexity .wordCompWord").append(thisword);
                                if ($.isArray(p)) {
                                    for (var key in p) {
                                        if (p.hasOwnProperty(key)) {
                                            var words = '';
                                            for (var a = 0; a < p[key].length; a++) {
                                                if (!$.isArray(p[key][a])) {
                                                    if (typeof p[key][a] == "string")
                                                        $(contentWrapper).find('.writer_wordComplexity .wordCompright').append("<p style=\"margin: 0;" +
                                                            "padding: 0\" class=\"word-complexity-def\">" + p[key][a]) + "</p>";
                                                } else {
                                                    for (var x = 0; x < p[key][a].length; x++) {
                                                        if (typeof p[key][a][x][0] != "undefined")
                                                            words += "<span style='display: inline; text-transform: capitalize;' class='ar-synonym'>" + p[key][a][x][0] + "</span>, ";
                                                    }
                                                    if (typeof words != 'undefined')
                                                        $(contentWrapper).find('.writer_wordComplexity .wordCompright').append('<p style="margin: 0;' +
                                                            ' padding: 0; border-bottom: 1px solid #fff;" class="word-complexity-sug"><strong>' + words.replace(/,\s*$/, "") + "</strong></p>");
                                                }
                                            }
                                        }
                                    }
                                } else {

                                    //$(contentWrapper).find('.writer_wordComplexity').prepend('<p style="display:inline">' + p + '</p>');
                                }

                            });//end .load
                        }

                    }, function () {
                        setTimeout(function () {
                            if ($(contentWrapper).find("#load_wordComplexity-popup").length > 0)
                                if (!$(contentWrapper).find("#load_wordComplexity-popup").is(":hover")) {
                                    $(contentWrapper).find("#load_wordComplexity-popup").remove();
                                }
                        }, 900);
                    })
                } else {

                    var contentWrapper = $('#content_ifr').contents().find('body');

                    if ($(contentWrapper).find("span.arWChighlight").length > 0) {
                        contentWrapper.removeClass("noun").removeHighlight('.arWChighlight');
                    }
                    if ($(contentWrapper).find("span.WordCompHighlight").length > 0) {
                        $(contentWrapper).find("span.WordCompHighlight").contents().unwrap();
                    }

                    if ($(contentWrapper).find(".writer_wordComplexity").length > 0)
                        $(contentWrapper).find(".writer_wordComplexity").addClass("writer-hide");
                }

            });*/

            $("#aw_moretips_WP p").unbind('click').bind('click', function(e){
                e.preventDefault();
                $("ul#aw_titletips_WP").slideToggle();
            });

            //$("#aud_info_WP").);

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