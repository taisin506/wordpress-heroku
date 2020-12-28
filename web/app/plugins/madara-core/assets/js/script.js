jQuery(document).ready(function($){

	function chapterNavigationAjax( navigation ){

		if( typeof mangaReadingAjax == 'undefined' ){
			return false;
		}

		var readContainer  = $('.read-container'),
			loadingHTML       = '<i class="fas fa-spinner fa-spin"></i>',
			currentPageSelect = $('.wp-manga-nav select#single-pager > option[selected="selected"]'),
			prevBtn           = $('.nav-links .nav-previous'),
			nextBtn           = $('.nav-links .nav-next'),
			breadCrumb        = $('.wp-manga-nav .entry-header_wrap').html(),
			pagination        = $('.select-pagination');

		$.ajax({
			type : 'GET',
			url : manga.ajax_url + '?' + navigation,
			beforeSend : function(){
				readContainer.html( loadingHTML );
				pagination.hide();
				$('.wp-manga-nav select').each(function(){
					$(this).prop('disabled', true);
				});
			},
			success : function( response ){
				if(response.success){

					readContainer.html( response.data.data.content );
					$('.wp-manga-nav').replaceWith( response.data.data.nav );
					$('.entry-header .wp-manga-nav').prepend( '<div class="entry-header_wrap">' + breadCrumb + '</div>');

					pagination.show();

					$('.wp-manga-nav select').each(function(){
						$(this).prop('disabled', false);
					});

				} else {
					readContainer.html(response.data.message);
				}

				if( $('.go-to-top.active').length > 0 ){
					$('.go-to-top.active').click();
				}
			},
		});
	}

	function chapterPreloadedImagesNavigation( next = true ){

		var $curPage = $('#single-pager'),
			curPageVal = $curPage.val();

		if( next ){
			curPageVal++;
		}else{
			curPageVal--;
		}

		var resp = loadPreloadedImage( curPageVal );

		return resp;

	}

	function loadPreloadedImage( page ){

		page = parseInt( page );

		// Remove selected from current page option
		$( '#single-pager option[value="' + page + '"]' ).removeAttr( 'selected' );

		var curPageOption = $( '#single-pager option[value="' + page + '"]' );
		var $readingContent = $('.reading-content');

		if( curPageOption.length > 0 ){

			// Mark destination page as selected
			curPageOption.attr( 'selected', 'selected' );
			$('#single-pager').val( page );

			// Change the URL in prev & next btn
			var prevPage = page - 1,
				nextPage = page + 1,
				prevPageOption = $( '#single-pager option[value="' + prevPage + '"]' ),
				nextPageOption = $( '#single-pager option[value="' + nextPage + '"]' );

			var volSelect = $('.volume-select');

			var mangaHasVolume = volSelect.length > 0 ? true : false;

			var curVol = mangaHasVolume ? volSelect.val() : 0;

			var	curVolChapSelect = $('.selectpicker_chapter[for="volume-id-' + curVol + '"] .single-chapter-select');

			// Index starts from 1
			var curChapIndex = curVolChapSelect[0].selectedIndex + 1,
				curVolIndex = mangaHasVolume ? volSelect[0].selectedIndex + 1 : null;

			curVolChapSelect = $(curVolChapSelect[0]);

			if( mangaHasVolume ){
				volSelect = $(volSelect[0]);
			}

			var nextBtn = $('.nav-next a'),
				prevBtn = $('.nav-previous a');

			// On any case, show both btn first
			nextBtn.show();
			prevBtn.show();

			// Handle prev
			var prevURL = '';
			var prevNavURL = '';

			if( prevPageOption.length > 0 ){

				// console.log( 'Chapter has prev page' );

				prevURL    = prevPageOption.data( 'redirect' );

				prevNavURL = $('.nav-next a').data( 'navigation' );

				if( typeof prevNavURL !== 'undefined' ){
					var replace = manga.manga_paged_var + '=(\d+)';
					var re = new RegExp(replace,"g");

					prevNavURL = prevNavURL.replace( re, prevPage );
				}
			}else{
				// If this is first page of chapter
				var prevChapIndex = curChapIndex - 1;

				if( prevChapIndex <= 0 ){ // If this is the first chapter of volume

					// console.log( 'First chap' );

					var prevVolIndex = mangaHasVolume ? curVolIndex - 1 : null;

					if( prevVolIndex === null || prevVolIndex <= 0 ){

						// console.log( 'First volume' );

						// If it's the first volume then hide the prev button
						prevBtn.hide();
					}else{
						// Else go to the first chapter of next volume
						var prevChapSelect = $('.selectpicker_chapter:nth-child(' + prevVolIndex + ') .single-chapter-select option:nth-child(2)');

						prevURL    = prevChapSelect.data( 'redirect' );
						prevNavURL = prevChapSelect.data( 'navigation' );
					}
				}else{ // if this isn't first chapter, then give prev chapter URL for prev btn
					var prevChapSelect = curVolChapSelect.find( 'option:nth-child(' + prevChapIndex + ')' );

					prevURL    = prevChapSelect.data( 'redirect' );
					prevNavURL = prevChapSelect.data( 'navigation' );

					if( typeof prevChapLastPage !== 'undefined' ){
						// add manga-paged to prev chap URLs
						prevURL += '?' + manga.manga_paged_var + '=' + prevChapLastPage;
						prevNavURL += '&' + manga.manga_paged_var + '=' + prevChapLastPage;
					}
				}
			}
			prevBtn.data( 'navigation', prevNavURL );
			prevBtn.attr( 'href', prevURL );

			// Handle next
			var nextURL = '';
			var nextNavURL = '';

			if( nextPageOption.length > 0 ){ // if there is next page on chapter

				// console.log( 'Chapter has next page' );

				nextURL    = nextPageOption.data( 'redirect' );

				nextNavURL = $('.nav-next a').data( 'navigation' );

				if( typeof nextNavURL !== 'undefined' ){
					var replace = manga.manga_paged_var + '=(\d+)';
					var re = new RegExp(replace,"g");

					nextNavURL = nextNavURL.replace( re, nextPage );
				}

			}else{ // If this is the last page of chapter

				// console.log( 'Chapter ended' );

				var nextChapIndex = curChapIndex + 1;

				if( curChapIndex === curVolChapSelect.find('option').length ){ // If this is the last chapter of volume

					// console.log( 'Volume ended' );

					var nextVolIndex = curVolIndex + 1;

					if( ! mangaHasVolume || curVolIndex === volSelect.find('option').length ){ //If it's last volume or manga has no volume and this is the last chap

						// console.log( 'Manga ended' );

						// If it's the last volume then hide the next button
						nextBtn.hide();
					}else{
						// Else go to the first chapter of next volume
						var nextChapSelect = $('.selectpicker_chapter:nth-child(' + nextVolIndex + ') .single-chapter-select option:nth-child(2)');

						nextURL    = nextChapSelect.data( 'redirect' );
						nextNavURL = nextChapSelect.data( 'navigation' );
					}
				}else{ // if this isn't last chapter, then give next chapter URL for next btn
					var nextChapSelect = curVolChapSelect.find( 'option:nth-child(' + nextChapIndex + ')' );

					nextURL    = nextChapSelect.data( 'redirect' );
					nextNavURL = nextChapSelect.data( 'navigation' );
				}
			}

			nextBtn.data( 'navigation', nextNavURL );
			nextBtn.attr( 'href', nextURL );

			// Remove all current images
			$readingContent.find( 'img' ).remove();

			var paged = chapter_images_per_page * ( page - 1 ) + 1;
			var hasCursorLink = $('.reading-content .page-prev-link').length;

			for( var i = 1; i <= chapter_images_per_page; i++ ){

				if( typeof chapter_preloaded_images[ paged ] !== 'undefined' ){
					var img = '<img id="image-' + paged + '" data-image-paged="' + paged + '" src="' + chapter_preloaded_images[ paged ] + '" class="wp-manga-chapter-img">';

					if( hasCursorLink ){ //If there is cursor link then append after the prev cursor link
						$(img).insertAfter( '.reading-content .page-prev-link' );
					}else{ //or just append it to .reading-content
						$('.reading-content').append( img );
					}

					paged++;
				}else{
					break;
				}
			}

			return true;
		}else{
			return false;
		}

	}

	//chapter next page ajax
	$(document).on('click', '.wp-manga-nav .nav-links > div > a.btn', function(e){

		var navigation = $(this).data('navigation'),
			url = $(this).attr('href');

		var use_preloaded_images = false,
			redirect = true;

		// If use preloaded images is on, then use it first
		if( typeof chapter_preloaded_images !== 'undefined' ){
			use_preloaded_images = chapterPreloadedImagesNavigation( $(this).hasClass('next_page') ? true : false );

			if( use_preloaded_images ){
				e.preventDefault();
				redirect = false;
			}
		}

		// If use preloaded image failed, then use ajax if it's on
		if( ! use_preloaded_images && typeof navigation !== 'undefined' && navigation !== '' && typeof mangaReadingAjax !== 'undefined' ){
			e.preventDefault();
			chapterNavigationAjax( navigation );
			redirect = false;
		}

		if( typeof url !== 'undefined' && url !== '' ){
			history.pushState({}, null, url );
		}

		if( redirect ) {
			return true;
		}else{
			return false;
		}

	});

	$(document).on('click', '.c-blog-post .entry-content .entry-content_wrap .reading-content img', function(e){
		e.preventDefault();
		$('.wp-manga-nav .nav-links a.next_page')[0].click();
	});

	//show appropriate chapter select for volume
	$(document).on('change', '.selectpicker.volume-select', function(){
		$('.chapter-selection > .c-selectpicker.selectpicker_chapter').each(function(){
			$(this).hide();
		});
		$('.chapter-selection > .c-selectpicker.selectpicker_chapter[for="volume-id-' + $(this).val() + '"]').show();
	});

	//navigate by keyword button
	$(document).keydown(function(e){
		var redirect = '';

		if( e.keyCode == 37 && $('.wp-manga-nav .nav-links .nav-previous > a').length > 0 ){	//left key
			$('.wp-manga-nav .nav-links a.prev_page')[0].click();
		}else if( e.keyCode == 39 &&  $('.wp-manga-nav .nav-links .nav-next > a').length > 0 ){ //right key
			$('.wp-manga-nav .nav-links a.next_page')[0].click();
		}
	});

	//prevent empty Comment content submitting
	$(document).ready(function(){
		$('textarea#comment').attr('required', 'required');

		$('#commentform').on('submit', function(){
			if( !$('#commentform')[0].checkValidity() ){
				alert('Comment cannot be empty');
				return false;
			}
			return true;
		});
	});

	$(document).on( 'change', '.wp-manga-nav .single-chapter-select, #single-pager, .wp-manga-nav .host-select, .wp-manga-nav .reading-style-select', function(e){

		e.preventDefault();

		var isPageSelect = $(this).attr('id') == 'single-pager' ? true : false;
		var isChapterSelect = $(this).hasClass('single-chapter-select') ? true : false;

		if( isPageSelect || isChapterSelect ){
			var navigation = $(this).find('option:selected').data('navigation');
			var url = $(this).find('option:selected').data('redirect');

			var use_preloaded_images = false,
				redirect = true;

			if( isPageSelect && typeof chapter_preloaded_images !== 'undefined' ){
				use_preloaded_images = loadPreloadedImage( $(this).val() );

				if( use_preloaded_images ){
					redirect = false;
				}
			}

			if( ! use_preloaded_images && typeof mangaReadingAjax !== 'undefined' && typeof navigation !== 'undefined' && navigation !== '' ){
				chapterNavigationAjax( navigation );
				redirect = false;
			}

			if( typeof url !== 'undefined' && url !== '' ){
				history.pushState( {}, null, url );
			}

			if( ! redirect ){
				return false;
			}

		}

		var t = $(this);
		var redirect = t.find(':selected').attr('data-redirect');
		window.location = redirect;
	});

	var ajaxHandling = false;
	var loginModal = false;

	// bookmark action
	$(document).on( 'click', '.wp-manga-action-button', function(e) {

		e.preventDefault();

		if($(this).attr('data-action') == 'bookmark') {
			if( typeof requireLogin2BookMark !== 'undefined' ){
				$('.modal#form-login').modal('show');
				$('input[name="bookmarking"]').val('1');
				return;
			}

			if( $('.add-bookmark').length != 0 ) {
				$('.add-bookmark').css('opacity', '0.5');
			}else{
				$('.action_list_icon > li:first-child').css('opacity', '0.5');
			}

			if ( !ajaxHandling ) {
				ajaxHandling = true;
				var t       = $(this);
				var postID  = t.data( 'post' );
				var chapter = t.data( 'chapter' );
				var page    = t.data( 'page' );
				jQuery.ajax({
					url: manga.ajax_url,
					type: 'POST',
					data: {
						action : 'wp-manga-user-bookmark',
						postID : postID,
						chapter : chapter,
						page : page,
					},
					success: function( response ) {
						if ( response.success ) {
							if( $('.add-bookmark').length != 0 ) {
								$('.add-bookmark').empty();
								$('.add-bookmark').append( response.data );
								$('.add-bookmark').css('opacity', '1');
							}else{
								$('.action_list_icon > li:first-child').empty();
								$('.action_list_icon > li:first-child').append( response.data );
								$('.action_list_icon > li:first-child').css('opacity', '1');
							}

						} else {
							if ( response.data.code == 'login_error' ) {

							} else {
								alert( response.data.code );
							}
						}
					},
					complete: function(xhr, textStatus) {
						ajaxHandling = false;
					}
				});
			}
		} else {
			if($(this).attr('data-action') == 'toggle-contrast'){
				$('body').toggleClass('text-ui-light');
			}
		}
	})

	var ajaxBookmarkDelete = false;
	$(document).on( 'click', '.wp-manga-delete-bookmark', function(e){

		e.preventDefault();

		if ( !ajaxBookmarkDelete ) {

			ajaxBookmarkDelete = true;
			var t = $( this );
			var postID = t.data( 'post-id' );
			var rowBookmark = $(this).closest("tr");

			if( rowBookmark.length != 0 ) {
				rowBookmark.css('opacity', '0.5');
				var isMangaSingle = 0;
			}

			if( $('.add-bookmark .action_icon .wp-manga-delete-bookmark').length != 0 ) {
				var isMangaSingle = 1;
				$('.add-bookmark').css('opacity', '0.5');
			}else{
				var isMangaSingle = 0;
				$('.action_list_icon > li:first-child').css('opacity', '0.5');
			}

			jQuery.ajax({
		        url: manga.ajax_url,
		        type: 'POST',
		        data: {
		            action : 'wp-manga-delete-bookmark',
		            postID: postID,
					isMangaSingle : isMangaSingle
		        },
		        success: function( response ) {
		            if ( response.success ) {
						if( rowBookmark.length != 0 ) {
							rowBookmark.fadeOut();
							rowBookmark.remove();
						}
						if( typeof isMangaSingle !== 'undefined' && isMangaSingle == true ) {
							$('.add-bookmark').empty();
							$('.add-bookmark').append( response.data );
							$('.add-bookmark').css('opacity', '1');
						}else if( typeof isMangaSingle !== 'undefined' && isMangaSingle == false ){
							$('.action_list_icon > li:first-child').empty();
							$('.action_list_icon > li:first-child').append( response.data );
							$('.action_list_icon > li:first-child').css('opacity', '1');
						}
		            }
		        },
		        complete: function(xhr, textStatus) {
		        	ajaxBookmarkDelete = false;
		        }
		    });
		}
	})

	var ajaxBookmarkDelete = false;
	$(document).on( 'click', '#delete-bookmark-manga', function(e){
		e.preventDefault();
		if ( !ajaxBookmarkDelete ) {
			ajaxBookmarkDelete = true;
			var bookmark = [];
			$('.bookmark-checkbox:checkbox:checked').each(function(i){
	          bookmark[i] = $(this).val();
			  $(this).closest('tr').addClass('remove');
			  $(this).closest('tr').css( 'opacity', '0.5' );
	        });
			jQuery.ajax({
		        url: manga.ajax_url,
		        type: 'POST',
		        data: {
		            action : 'wp-manga-delete-multi-bookmark',
		            bookmark: bookmark,
		        },
		        success: function( response ) {

		            if ( response.success ) {
						$('tr.remove').remove();
		            }
		        },
		        complete: function(xhr, textStatus) {
		        	ajaxBookmarkDelete = false;
		        }
		    });
		}
	})

	$(document).on( 'change', '#wp-manga-bookmark-checkall', function(e){
		e.preventDefault();
		var t = $(this);
		var chechbox = $('.bookmark-checkbox');
		if ( chechbox.length > 0 ) {
			if ( t.is(':checked') ) {
				$.each( chechbox, function(i,e){
					$(e).prop( 'checked', true );
				})
			} else {
				$.each( chechbox, function(i,e){
					$(e).prop( 'checked', false );
				})
			}
		}
	})

    // search
    // manga-search-field
	$('form.manga-search-form input.manga-search-field').each(function(){

		var searchIcon = $(this).parent().children('.ion-ios-search-strong');

	 	var append = $(this).parent();

		$(this).autocomplete({
			appendTo: append,
			source: function( request, resp ) {
				$.ajax({
					url: manga.ajax_url,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'wp-manga-search-manga',
						title: request.term,
					},
					success: function( data ) {
						resp( $.map( data.data, function( item ) {
							if ( true == data.success ) {
								return {
									label: item.title,
									value: item.title,
									url: item.url,
								}
							} else {
								return {
									label: item.message,
									value: item.message,
									click: false
								}
							}
						}))
					}
				});
			},
			select: function( e, ui ) {
				if ( ui.item.url ) {
					window.location.href = ui.item.url;
				} else {
					if ( ui.item.click ) {
						return true;
					} else {
						return false;
					}
				}
			},
			open: function( e, ui ) {
				var acData = $(this).data( 'uiAutocomplete' );
				acData.menu.element.addClass('manga-autocomplete').find('li').each(function(){
					var $self = $(this),
						keyword = $.trim( acData.term ).split(' ').join('|');
					$self.html( $self.text().replace( new RegExp( "(" + keyword + ")", "gi" ), '<span class="manga-text-highlight">$1</span>' ) );
				});
			}
		});
	});

	$(document).on( 'click', '#wp-manga-upload-avatar', function(e){

		e.preventDefault();

		var thisBtn = $(this),
			chooseAvatar = $('.choose-avatar'),
			userAvatarSection = $('.c-user-avatar');

		var fd = new FormData();
		var userAvatar = $('input[name="wp-manga-user-avatar"]')[0].files[0];
		var userID = $('input[name="userID"]').val();
		var _wpnonce = $('input[name="_wpnonce"]').val();

		fd.append( 'action', 'wp-manga-upload-avatar' );
		fd.append( 'userAvatar', userAvatar );
		fd.append( 'userID', userID );
		fd.append( '_wpnonce', _wpnonce );

		jQuery.ajax({
			url : manga.ajax_url,
			type : 'POST',
			enctype: 'multipart/form-data',
			cache: false,
			contentType: false,
			processData: false,
			data : fd,
			beforeSend: function(){
				thisBtn.attr('disabled', 'disabled');
				chooseAvatar.addClass('uploading');
			},
			success: function( response ) {
				if( response.success ) {
					userAvatarSection.empty();
					userAvatarSection.append( response.data );
				}
			},
			complete: function(){
				thisBtn.removeAttr('disabled');
				chooseAvatar.removeClass('uploading');
			}
		});

	});

	$(document).on( 'click', '.remove-manga-history', function(e){
		e.preventDefault();
		var postID = $(this).data('manga');
		var item = $(this).closest('.col-md-4');

		item.css( 'opacity', '0.5' );
		$.ajax({
			url : manga.ajax_url,
			type : 'POST',
			data : {
				'action' : 'manga-remove-history',
				'postID' : postID,
			},
			success : function( response ) {
				if( response.success ) {
					item.fadeOut();
					item.remove();
					if( response.data.is_empty == true ){
						$('.tab-pane#history').empty();
						$('.tab-pane#history').append( response.data.msg );
					}
				}else{
					item.css( 'opacity', '1' );
				}
			}
		});
	});

	//Ajax pagination
	var wpMangaAjaxPosts = false;

	$( document ).on( 'click', '.wp-manga-ajax-button', function(e){

		e.preventDefault();
		if ( wpMangaAjaxPosts == false ) {

			wpMangaAjaxPosts = true;

			var t = $( this ),
				e = $(this).data('element'),
				template = $(this).data('template'),
				button = $(this).parent();

			t.addClass('loading');

			$.ajax({
				url: manga.ajax_url,
				type: 'POST',
				data: {
					action: 'wp_manga_archive_loadmore',
					manga_args : manga_args,
					template : template
				},
				success: function( response ){
					t.removeClass('loading');
					$('.wp-manga-query-vars').remove();
					$(e).append( response );

					if( manga_args.paged >= manga_args.max_num_pages ){
						button.remove();
					}
				}
			})


		}
	});
	
	// quick navigate to first chapter for Video Manga
	$(".page-item-detail.video").each(function(){
		$('.item-thumb a',$(this)).on('click', function(e){
			var parent = $(this).closest('.page-item-detail');
			
			$latest_chapter = $('.list-chapter .chapter-item', parent)[0];
			if($latest_chapter){
				var chapter_url = $($('.chapter a', parent)[0]).attr('href');
				
				location.href = chapter_url;
				
				e.stopPropagation();
				return false;
			}
		});
		
	});
	
	$('.video-light').on('click', function(e){
		$('.video-light').toggleClass('off');
		$('.chapter-video-frame').toggleClass('off');
		
		e.stopPropagation();
		return false;	
	});
	
	// turn on the light
	if($('.video-light').length > 0){
		$(document).on('click', function(e){
			var container = $(".chapter-video-frame");
			
			if(container.hasClass('off')){
				// if the target of the click isn't the container nor a descendant of the container
				if (container.is(e.target) && container.has(e.target).length === 0) 
				{
					$('.video-light').toggleClass('off');
					$('.chapter-video-frame').toggleClass('off');
				}
			}
		});
	}

});
