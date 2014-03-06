jQuery(document).ready(function($) {
  
   $("#accordion").append($("#accordion").children(".ar_checkmarks_warning").remove());
   $("#accordion").append($("#accordion").children(".ar_checkmarks_passed").remove());
	
   $("#accordion").show();
   $("#accordion .details").hide();
   $("#accordion li").toggle(function () {
       // link wasn't working. This will fix it.
       $("a.toggle-link").click(function(e) {
          e.stopPropagation();
       });
       
       $(this).find('.details').show('slow');
       $(this).find('div').addClass('ui-state-active');	       
   }, function(){
       $(this).find('.details').hide('slow');
       $(this).find('div').removeClass('ui-state-active');
   });

 
   // Spelling Mistakes    
   aSm = $("ul.spelling-mistakes li").find('span.smText').clone().not(":last").append("\\b|\\b").end().text();
   var regexSm = new RegExp('(?:\\b|_)(' + aSm + ')(?:\\b|_)','ig');
   
   $("#highlight-sp").toggle(function(){        
     $('#content_ifr').contents().highlightRegex(regexSm, {
      tagType:   'span',
      className: 'highlight-sp',
      });
      
     $('#content_ifr').contents().find(".highlight-sp").css("border-bottom", "3px solid #fc0909"); // red
     $(this).text("Clear Spelling Mistakes");
   }, function(){
     $('#content_ifr').contents().find(".highlight-sp").removeAttr("style");
     $(this).text('Spelling Mistakes');
   });
   
     
  $('#chksp, #chksp2').change(function() {    
      if($(this).is(":checked")) {
          
          console.log("button clicked");
          
         $('#content_ifr').contents().highlightRegex(regexSm, {
          tagType:   'span',
          className: 'highlight-sp',
          });
          
         $('#content_ifr').contents().find(".highlight-sp").css("border-bottom", "3px solid #fc0909"); // red         
      }else{        
         $('#content_ifr').contents().find(".highlight-sp").removeAttr("style");    
          
      }
  });
  

  $('#chkso').change(function() {
      if($(this).is(":checked")) {
        var paragraphs = $(this).data('paragraphs');
        var domExpression = $(this).data('domExpression');
        var tooSimpleColor = $(this).data('tooSimpleColor');
        var tooComplexColor = $(this).data('tooComplexColor');

        text_paragraphs = $('#content_ifr').contents().find(domExpression);
        $.each(paragraphs, function(index, value) {
          if (value == 'HIT' || value == 'UNAVAILABLE')
            return;
          type = (value == 'TOO SIMPLE')?'too-simple':'too-complex';
          $(text_paragraphs[index]).wrapInner("<span class='highlight-so "+type+"'></span>");
        });
         $('#content_ifr').contents().find(".too-simple").css("background", tooSimpleColor);
         $('#content_ifr').contents().find(".too-complex").css("background", tooComplexColor);
      }else{
         $('#content_ifr').contents().find(".highlight-so").removeAttr("style"); 
      }
  });  


   // Grammar Mistakes    
   aGm = $("ul.grammar-mistakes li").find('span.gmText').clone().not(":last").append("|").end().text();
   var regexGm = new RegExp(aGm,'gi');

   $("#highlight-gm").toggle(function(){        
     $('#content_ifr').contents().highlightRegex(regexGm, {
      tagType:   'span',
      className: 'highlight-grm',
      });
    
     $('#content_ifr').contents().find(".highlight-grm").css("border-bottom", "3px solid #3bd15e"); // green
     $(this).text("Clear Grammar Mistakes");
   }, function(){
     $('#content_ifr').contents().find(".highlight-grm").removeAttr("style");    
     $(this).text('Grammar Mistakes');
   });
   
   
  $('#chkgm').change(function() {
      if($(this).is(":checked")) {
         $('#content_ifr').contents().highlightRegex(regexGm, {
          tagType:   'span',
          className: 'highlight-grm',
          });
        
         $('#content_ifr').contents().find(".highlight-grm").css("border-bottom", "3px solid #3bd15e"); // green
      }else{
         $('#content_ifr').contents().find(".highlight-grm").removeAttr("style");    
      }
  });    
   
   
   // Invalid Links    
   aIl = $("ul.invalid-links li").find('span.ilText').clone().not(":last").append("|").end().text();
   var linkArray = aIl.split('|');
 
   $("#highlight-il").toggle(function(){        
     
     $.each(linkArray, function( index, value ) {
       vlink = "[href='" + value + "']";
       $('#content_ifr').contents().find(vlink).css("border-bottom", "3px solid #f7b70c"); // orange
     });
     
     $(this).text("Clear Underperforming Link");
   }, function(){
     $.each(linkArray, function( index, value ) {
       vlink = "[href='" + value + "']";
       $('#content_ifr').contents().find(vlink).removeAttr("style");
     });
     
     $(this).text('Underperforming Link');
   });  
 

  $('#chkul').change(function() {
      if($(this).is(":checked")) {
         $.each(linkArray, function( index, value ) {
           vlink = "[href='" + value + "']";
           $('#content_ifr').contents().find(vlink).css("border-bottom", "3px solid #f7b70c"); // orange
         });
      }else{
         $.each(linkArray, function( index, value ) {
           vlink = "[href='" + value + "']";
           $('#content_ifr').contents().find(vlink).removeAttr("style");
         });
      }
  }); 

  // clear highlight before submiting form, this way will clean the html added to the iframe
  $('form').submit(function(){
       $('#content_ifr').contents().find(".highlight-so").contents().unwrap();
       $('#content_ifr').contents().find(".highlight-sp").removeAttr("style");
       $('#content_ifr').contents().highlightRegex(undefined, {
          tagType:   'span',
          className: 'highlight-sp',
        }); 
        
       $('#content_ifr').contents().find(".highlight-grm").removeAttr("style"); 
       $('#content_ifr').contents().highlightRegex(undefined, {
          tagType:   'span',
          className: 'highlight-grm',
        });   
        
       $.each(linkArray, function( index, value ) {
         vlink = "[href='" + value + "']";
         $('#content_ifr').contents().find(vlink).removeAttr("style");
       });               
        
       return true;
   });       
});