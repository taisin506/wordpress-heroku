(function ($) {

	"use strict";

	jQuery(document).ready(function ($) {

		function updateHistory() {

			if( typeof user_history_params == 'undefined' ){
				return;
			}

			if( $('.wp-manga-chapter-img').length > 0 ){
				var img = $('.wp-manga-chapter-img').prop('id');
				var img_id = img.replace('image-', '');
			}else{
				var img_id = '';
			}

			$.ajax({
				url: user_history_params.ajax_url,
				type: 'POST',
				data: {
					action:      'manga-user-history',
					postID:      user_history_params.postID,
					chapterSlug: user_history_params.chapter,
					paged:       user_history_params.page,
					img_id:      img_id,
					nonce:       user_history_params.nonce
				}
			});
		}

		if( typeof user_history_params !== 'undefined' ){
			setTimeout(updateHistory, user_history_params.interval );
		}

	});

})(jQuery);
