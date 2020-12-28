<?php

    /**
 	 * Text Chapter for WP Manga
 	 **/

    class WP_MANGA_TEXT_CHAPTER{

        function manga_nav( $args ){

            global $wp_manga_template, $wp_manga, $wp_manga_functions;

            extract( $args );

            ?>

            <div class="wp-manga-nav">
                <div class="select-view">
					<?php
						$servers = $wp_manga->get_chapter_video_server_list($this->get_chapter_content_post($chapter['chapter_id']));
						
						if(count($servers) > 1) { ?>
                            <!-- select host -->
                            <div class="c-selectpicker selectpicker_version">
                                <label>
                                    <select class="selectpicker host-select">
										<?php
											foreach($servers as $server){
												?>
												<option data-redirect="<?php echo add_query_arg('host',$server,$_SERVER['REQUEST_URI']);?>" value="<?php echo $server;?>" <?php selected( $server, isset($_GET['host']) ? $_GET['host'] : '', true );?>><?php echo $server;?></option>
												<?php
											}
										?>
                                    </select> </label>
                            </div>
						<?php }
					?>

                    <!-- select chapter -->
                    <div class="c-selectpicker selectpicker_chapter">
                        <label>
                            <select class="selectpicker single-chapter-select">
                                <?php
                                    foreach ( $all_chaps as $chap ) {

                                        $link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chap );

                                        if( isset( $cur_chap_passed ) && !isset( $next_chap ) ){
                                            $next_chap = $link;
                                        }

                                        if( $chap['chapter_slug'] == $cur_chap ){
                                            $cur_chap_passed = true;
                                            $cur_chap_link = $link;
                                        }

                                        //always set current chap in loop as $prev_chap, stop once current chap is passed
                                        if( !isset( $cur_chap_passed ) ){
                                            $prev_chap = $link;
                                        }

                                        ?>
                                        <option class="short" data-limit="40" value="<?php echo $chap['chapter_slug'] ?>" data-redirect="<?php echo esc_url( $link ) ?>" <?php selected( $chap['chapter_slug'], $cur_chap, true ) ?>><?php echo esc_attr( $chap['chapter_name'] . $wp_manga_functions->filter_extend_name( $chap['chapter_name_extend'] ) ); ?></option>

                                    <?php } ?>
                            </select>
							
                        </label>
                    </div>
					<?php 
					$chapter_type = get_post_meta( get_the_ID(), '_wp_manga_chapter_type', true );
					
					if($chapter_type == 'video') {?>
					<a href="#" class="video-light"><i class="fas fa-lightbulb"></i> <span class="text text-off"><?php esc_html_e('Light off',WP_MANGA_TEXTDOMAIN);?></span> <span class="text text-on"><?php esc_html_e('Light on',WP_MANGA_TEXTDOMAIN);?></span></a>
					
					<?php } ?>
                </div>

                <div class="select-pagination">
                    <div class="nav-links">
                        <?php if ( isset( $prev_chap ) && $prev_chap !== $cur_chap_link ): ?>
                            <div class="nav-previous"><a href="<?php echo $prev_chap; ?>" class="btn prev_page"><?php esc_html_e('Prev', WP_MANGA_TEXTDOMAIN ); ?></a>
                            </div>
                        <?php endif ?>
                        <?php if ( isset( $next_chap ) ): ?>
                            <div class="nav-next"><a href="<?php echo $next_chap ?>" class="btn next_page"><?php esc_html_e('Next', WP_MANGA_TEXTDOMAIN ); ?></a></div>
                        <?php endif ?>
                    </div>
                </div>

            </div>

            <?php

        }

        /**
     	 * Get chapter_content post type which contains content for this chapter
    	 */
        function get_chapter_content_post( $chapter_id ){

            $chapter_post_content = new WP_Query(
                array(
                    'post_parent' => $chapter_id,
                    'post_type'   => 'chapter_text_content'
                )
            );

            if( $chapter_post_content->have_posts() ){
                return $chapter_post_content->posts[0];
            }

            return false;

        }

        /**
        * Handle insert Chapter Content Type
        */
        function insert_chapter( $args ){

            global $wp_manga_chapter, $wp_manga_storage;

            $chapter_content = $args['chapter_content'];
            unset( $args['chapter_content'] );

            //post_id require, volume id, chapter name, chapter extend name, chapter slug
    		$chapter_args = array_merge(
                $args,
                array(
                    'chapter_slug' => $wp_manga_storage->slugify( $args['chapter_name'] )
                )
            );

    		$chapter_id = $wp_manga_chapter->insert_chapter( $chapter_args );

    		if( ! $chapter_id ){
    			wp_send_json_error( array( 'message' => esc_html__('Cannot insert Chapter', WP_MANGA_TEXTDOMAIN ) ) );
    		}

    		$chapter_content_args = array(
    			'post_type'    => 'chapter_text_content',
    			'post_content' => $chapter_content,
    			'post_status'  => 'publish',
    			'post_parent'  => $chapter_id, //set chapter id as parent
    		);

    		$resp = wp_insert_post( $chapter_content_args );

            if( $resp ){
                if( is_wp_error( $resp ) ){
                    return $resp;
                }else{
                    return $chapter_id;
                }
            }else{
                return new WP_Error( 'create_content_chapter_failed', __( 'Cannot create chapter', WP_MANGA_TEXTDOMAIN ) );
            }

        }

        function upload_handler( $post_id, $zip_file, $volume_id = 0 ){

            global $wp_manga_functions, $wp_manga, $wp_manga_storage, $wp_manga_volume;

    		$uniqid = $wp_manga->get_uniqid( $post_id );

    		$temp_name = $zip_file['tmp_name'];
    		$temp_dir_name = $wp_manga_storage->slugify( explode( '.', $zip_file['name'] )[0] );

    		//open zip
    		$zip_manga = new ZipArchive();

    		if( ! $zip_manga->open( $temp_name ) ) {
    			wp_send_json_error( __('Cannot open Zip file ', 'madara' ) );
    		}

            $extract = WP_MANGA_DATA_DIR . $uniqid . '/' . $temp_dir_name;

            $zip_manga->extractTo( $extract );
		    $zip_manga->close();

            //scan all dir
    		$scandir_lv1 = glob( $extract . '/*' );
    		$result = array();

            $is_invalid_zip_file = true;

    		//Dir level 1
    		foreach( $scandir_lv1 as $dir_lv1 ) {

    			if( basename( $dir_lv1 ) === '__MACOSX' ){
    				continue;
    			}

    			if( is_dir( $dir_lv1 ) ) {

    				$has_volume = true;

                    foreach( glob( $dir_lv1 . '/*' ) as $dir_lv2 ) {

    					if( basename( $dir_lv2 ) === '__MACOSX' ){
    						continue;
    					}

    					//if dir level 2 is dir then dir level 1 is volume
    					if( is_dir( $dir_lv2 ) && $has_volume == true ) {

    						//By now, dir lv1 is volume. Then check if this volume is already existed or craete a new one
    						$this_volume = $wp_manga_volume->get_volumes(
    							array(
    								'post_id' => $post_id,
    								'volume_name' => basename( $dir_lv1 ),
    							)
    						);

    						if( $this_volume == false ){
    							$this_volume = $wp_manga_storage->create_volume( basename( $dir_lv1 ), $post_id );
    						}else{
    							$this_volume = $this_volume[0]['volume_id'];
    						}

                            $chapters = glob( $dir_lv2 . '/*' );

                            foreach( $chapters as $chapter ){
                                //create chapter
                                $chapter_args = array(
                                    'post_id'             => $post_id,
                                    'chapter_name'        => basename( $dir_lv2 ),
                                    'chapter_name_extend' => '',
                                    'volume_id'           => $this_volume,
                                    'chapter_content'     => file_get_contents( $chapter )
                                );

                                $this->insert_chapter( $chapter_args );
                            }
    					}else{

                            if( $has_volume ){
                                $has_volume = false;
                            }

                            //create chapter
                            $chapter_args = array(
                                'post_id'             => $post_id,
                                'chapter_name'        => basename( $dir_lv1 ),
                                'chapter_name_extend' => '',
                                'volume_id'           => $volume_id,
                                'chapter_content'     => file_get_contents( $dir_lv2 )
                            );

                            $this->insert_chapter( $chapter_args );
                        }

    				}
    			}else{
                    $is_invalid_zip_file = false;
                }
    		}

    		$wp_manga_storage->local_remove_storage( $extract );

            if( !$is_invalid_zip_file ){
                return array(
                    'success' => false,
                    'message' => esc_html__('Upload failed', 'madara')
                );
            }

            return array(
                'success' => true,
                'message' => esc_html__('Upload successfully', 'madara')
            );
        }
    }

    $GLOBALS['wp_manga_text_type'] = new WP_MANGA_TEXT_CHAPTER();
