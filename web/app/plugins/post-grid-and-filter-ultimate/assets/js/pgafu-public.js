(function ( $ ) {

	"use strict";

	/* Post Filter */
	if( $('.pgafu-filtr-container').length > 0) {

		$( '.pgafu-filter-wrp' ).each(function( index ) {

			var filter_id			= $(this).find('.pgafu-filter').attr('id');
			var filter_container	= $(this).find('.pgafu-filtr-container').attr('id');
			var active_attr			= $(this).find('.pgafu-filter .pgafu-active-filtr').attr('data-filter');
			
			$(this).imagesLoaded()
				.progress( function( instance, image ) {
			}).done( function( instance ) {

				$('#'+filter_container).isotope({
					itemSelector	: '.pgafu-post-cnt',
					filter			: active_attr,
				});

				$(document).on('click', '#'+filter_id+' li', function() {
					$('#'+filter_id+' .pgafu-filtr-cat').removeClass('pgafu-active-filtr');
					$(this).addClass('pgafu-active-filtr');

					var filterValue = $(this).attr('data-filter');
					$('#'+filter_container).isotope({ filter: filterValue });
				});

			});
		});
	}

})(jQuery);