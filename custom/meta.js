jQuery(document).ready(function ($) {


    $("#ARajaxData").on("load", "#accordion", ARaccordion());

    function ARaccordion() {
        $("#accordion").append($("#accordion").children(".ar_checkmarks_warning").remove());
        $("#accordion").append($("#accordion").children(".ar_checkmarks_passed").remove());

        $("#accordion").show();
        $("#accordion .details").hide();

        $("#accordion li").toggle(function () {
            // link wasn't working. This will fix it.
            $("a.toggle-link").click(function (e) {
                e.stopPropagation();
            });

            $(this).find('.details').show('slow');
            $(this).find('div').addClass('ui-state-active');
        }, function () {
            $(this).find('.details').hide('slow');
            $(this).find('div').removeClass('ui-state-active');
        });
    }

    $("#ARajaxData").on('change', '#chksp, #chksp2', function () {
        if ($(this).is(":checked")) {
            var words = $.map($('#chksp').data('words'), function (word, i) {
                //return word.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
                return word.replace(/[\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "");
            });
            //var regexSm = new RegExp('(\\b[\\W]*'+words.join("[\\W]*\\b|\\b[\\W]*") +'[\\W]*\\b)','ig');
            var regexSm = new RegExp('(\\b' + words.join("?[\\x27]?[\\S]+\\b|\\b") + '?[\\x27]?[\\S]+\\b)', 'ig');
            $('#content_ifr').contents().highlightRegex(regexSm, {
                tagType: 'span',
                className: 'highlight-sp'
            });

            $('#content_ifr').contents().find(".highlight-sp").css("border-bottom", "3px solid #fc0909"); // red
            $('#chksp, #chksp2').attr('checked','checked');
        } else {
            $('#content_ifr').contents().find(".highlight-sp").removeAttr("style");
            $('#chksp, #chksp2').removeAttr('checked');
        }
    });


    $("#ARajaxData").on('change', '#chkso, #chkso2', function () {
        if ($(this).is(":checked")) {
            var paragraphs = $('#chkso').data('paragraphs');
            var domExpression = $('#chkso').data('domExpression');
            var tooSimpleColor = $('#chkso').data('tooSimpleColor');
            var tooComplexColor = $('#chkso').data('tooComplexColor');

            text_paragraphs = $('#content_ifr').contents().find(domExpression);
            $.each(paragraphs, function (index, value) {
                if (value == 'HIT' || value == 'UNAVAILABLE')
                    return;
                type = (value == 'TOO SIMPLE') ? 'too-simple' : 'too-complex';
                $(text_paragraphs[index]).wrapInner("<span class='highlight-so " + type + "'></span>");
            });
            $('#content_ifr').contents().find(".too-simple").css("background", tooSimpleColor);
            $('#content_ifr').contents().find(".too-complex").css("background", tooComplexColor);
        } else {
            $('#content_ifr').contents().find(".highlight-so").removeAttr("style");
        }
    });


    $("#ARajaxData").on('change', '#chkgm, #chkgm2', function () {
        if ($(this).is(":checked")) {
            var words = $.map($('#chkgm').data('words'), function (word, i) {
                return word.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
            });
            //var regexGm = new RegExp('(\\b[\\W]*'+words.join("[\\W]*\\b|\\b[\\W]*") +'[\\W]*\\b)','g');
            var regexGm = new RegExp('(\\b' + words.join("?[\\x27]?[\\S]+\\b|\\b") + '?[\\x27]?[\\S]+\\b)', 'g');
            $('#content_ifr').contents().highlightRegex(regexGm, {
                tagType: 'span',
                className: 'highlight-grm'
            });
            $('#content_ifr').contents().find(".highlight-grm").css("border-bottom", "3px solid #3bd15e"); // green

            $('#chkgm, #chkgm2').attr('checked','checked');

        } else {
            $('#content_ifr').contents().find(".highlight-grm").removeAttr("style");
            $('#chkgm, #chkgm2').removeAttr('checked');
        }
    });


    // Invalid Links
    aIl = $("ul.invalid-links li").find('span.ilText').clone().not(":last").append("|").end().text();
    var linkArray = aIl.split('|');

    $("#ARajaxData").on('toggle', '#highlight-il', function () {

        $.each(linkArray, function (index, value) {
            vlink = "[href='" + value + "']";
            $('#content_ifr').contents().find(vlink).css("border-bottom", "3px solid #f7b70c"); // orange
        });

        $(this).text("Clear Underperforming Link");
    }, function () {
        $.each(linkArray, function (index, value) {
            vlink = "[href='" + value + "']";
            $('#content_ifr').contents().find(vlink).removeAttr("style");
        });

        $(this).text('Underperforming Link');
    });


    $("#ARajaxData").on('change', '#chkul', function () {
        if ($(this).is(":checked")) {
            $.each(linkArray, function (index, value) {
                vlink = "[href='" + value + "']";
                $('#content_ifr').contents().find(vlink).css("border-bottom", "3px solid #f7b70c"); // orange
            });
        } else {
            $.each(linkArray, function (index, value) {
                vlink = "[href='" + value + "']";
                $('#content_ifr').contents().find(vlink).removeAttr("style");
            });
        }
    });

    /*--------Paragraph Density--------------*/
    $("#ARajaxData").on('change', '#chkpwd, #chkpwd2', function () {

        var paragraphs = $('#chkpwd').data('paragraphs');
        var domExpression = $('#chkpwd').data('domExpression');
        var tooShortColor = $('#chkpwd').data('tooShortColor');
        var tooLongColor = $('#chkpwd').data('tooLongColor');

        perParagraphHighlight(this, 'TOOSHORT', 'TOOLONG', tooShortColor, tooLongColor, domExpression, paragraphs, 'pwd');
    });

    function perParagraphHighlight(element, stateA, stateB, colorA, colorB, domExpression, dataToHighlight, dimension)
    {
        if($(element).is(":checked")) {
            stateLabelA = stateA.toLowerCase().replace(' ', '-');
            stateLabelB = stateB.toLowerCase().replace(' ', '-');
            text_paragraphs = $('#content_ifr').contents().find(domExpression);
            $.each(dataToHighlight, function(index, value) {
                if (value == 'HIT' || value == 'UNAVAILABLE' || value == '' || value == 'length_hit')
                    return;
                type = (value == stateLabelA)?stateLabelA:stateLabelB;
                $(text_paragraphs[index]).wrapInner("<span class='highlight-"+dimension+" "+type+"'></span>");
            });
            $('#content_ifr').contents().find("."+stateLabelA).css("background", colorA);
            $('#content_ifr').contents().find("."+stateLabelB).css("background", colorB);
        }else{
            $('#content_ifr').contents().find(".highlight-"+dimension).contents().unwrap();
        }
    };

    // clear all highlights
    function clearAllHighlights(){
        $('#content_ifr').contents().find(".highlight-so").contents().unwrap();
        $('#content_ifr').contents().find(".highlight-pwd").contents().unwrap();
        $('#content_ifr').contents().find(".highlight-sp").removeAttr("style");
        $('#content_ifr').contents().highlightRegex(undefined, {
            tagType: 'span',
            className: 'highlight-sp'
        });

        $('#content_ifr').contents().find(".highlight-grm").removeAttr("style");
        $('#content_ifr').contents().highlightRegex(undefined, {
            tagType: 'span',
            className: 'highlight-grm'
        });

        $.each(linkArray, function (index, value) {
            vlink = "[href='" + value + "']";
            $('#content_ifr').contents().find(vlink).removeAttr("style");
        });

        return true;
    }

    // clear highlight before submiting form, this way will clean the html added to the iframe
    $('form').submit(function () {
        clearAllHighlights();
    });
    CustomDictionary = {
        add: function (w) {
            return jQuery.ajax({
                url: 'admin-ajax.php',
                data: { action: 'ar_analyzer_custom_dictionary', word: w },
                async: false
            }).responseText == 'OK';
        }
    }

    /********************************/
    /**** Score Button & new-meta.php Tabs ui ****/
    /********************************/
    $("#ARajaxData").on("load", "ul.AR-tabs", ARTabs());
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


    $("#ARajaxData").on("click", "#AR-scoreBtn", function (e) {
        e.preventDefault();
        var title = jQuery("#title").val();
        var content = tinyMCE.activeEditor.getContent();
        var segmentId = jQuery("#_ar_meta_audience_list").val();

        var query = window.location.search.substring(1);
        var qSplit = query.split("&");
        var arPostID = qSplit[0].split("=")[1];

        clearAllHighlights();
        $("#ARajaxData").empty();
        $("#AR-Loading").removeClass("AR-hide").addClass("AR-show");
        $('html, body').animate({
            scrollTop: $("#aranalyzer_metabox").offset().top - 30 }, 900);

        var data = {
            'action': 'aranalyzer_ajax',
            'postID': arPostID,
            'segmentId': segmentId,
            'arTitle': title,
            'arContent': content
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        $.post(ajaxurl, data,function (response) {
            //alert('Got this from the server: ' + response);
            $("#AR-Loading").removeClass("AR-show").addClass("AR-hide");
            $("#ARajaxData").append(response);

        }).done(function () {
            $('html, body').animate({
                scrollTop: $("#aranalyzer_metabox").offset().top - 30 }, 900);
            ARTabs();
            ARaccordion();
        }).always(function(){

        });

    });


});
