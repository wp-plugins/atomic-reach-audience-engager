/*
 * SimpleModal OSX Style Modal Dialog
 * http://simplemodal.com
 *
 * Copyright (c) 2013 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */

jQuery(function ($) {
	var MODALWINDOWS = {
		container: null,
		init: function () {
			$("input.mw, a.mw").click(function (e) {
				e.preventDefault();	

				$("#mw-modal-content").modal({
					overlayId: 'mw-overlay',
					containerId: 'mw-container',
					closeHTML: null,
					maxHeight: 390,
					minHeight: 390,
					opacity: 65, 
					position: ['60px',],
					overlayClose: true,
					onOpen: MODALWINDOWS.open,
					onClose: MODALWINDOWS.close,
					maxWidth:810,
					minWidth:810,
				});
			});
		},
		open: function (d) {
			var self = this;
			self.container = d.container[0];
			d.overlay.fadeIn('slow', function () {
				$("#mw-modal-content", self.container).show();
				var title = $("#mw-modal-title", self.container);
				title.show();
				d.container.slideDown('slow', function () {
					setTimeout(function () {
						var h = $("#mw-modal-data", self.container).height()
							+ title.height()
							+ 40; // padding
							// console.log(h);
						d.container.animate(
							{height: h}, 
							200,
							function () {
								$("div.close", self.container).show();
								$("#mw-modal-data", self.container).show();
							}
						);
					}, 300);
				});
			})
		},
		close: function (d) {
			var self = this; // this = SimpleModal object
			d.container.animate(
				{top:"-" + (d.container.height() + 20)},
				500,
				function () {
				  //  urlcb = d.container[0].baseURI // + '&modekeys=true'
				    urlcb = window.location.protocol + "//" + window.location.host + window.location.pathname + '?page=ar-analyzer-admin';
				    var dataString = '&modekeys=true';
            $.ajax({
              type:'POST',
              data:dataString,
              url: urlcb,
                success:function(response) {
                   if (response !== null && response !== undefined && response ) {
                     // console.log(response);
                      window.location = urlcb;
                   }
             },
          });
					self.close(); // or $.modal.close();
				}
			);
		}
	};

	MODALWINDOWS.init();

});