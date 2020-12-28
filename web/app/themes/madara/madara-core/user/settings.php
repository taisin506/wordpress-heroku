<?php

	if ( ! is_user_logged_in() ) {
		return;
	}

	global $wp_manga_user_actions;
	$wp_manga_template = madara_get_global_wp_manga_template();

	$tab_pane = isset( $_GET['tab'] ) ? $_GET['tab'] : 'bookmark';

	$bookmark = $wp_manga_user_actions->get_user_tab_url( 'bookmark' );
	$history  = $wp_manga_user_actions->get_user_tab_url( 'history' );
	$reader   = $wp_manga_user_actions->get_user_tab_url( 'reader-settings' );
	$account  = $wp_manga_user_actions->get_user_tab_url( 'account-settings' );

	$array_compare = apply_filters( 'madara_user_settings_tab_array_compare', [
		'bookmark',
		'history',
		'reader-settings',
		'account-settings',
	] );

?>

<div class="row settings-page">
    <div class="col-md-3 col-sm-3">
        <div class="nav-tabs-wrap">
            <ul class="nav nav-tabs">
                <li class="<?php echo esc_attr( $tab_pane == 'bookmark' ? 'active' : ''); ?>">
                    <a href="<?php echo esc_url( $bookmark ); ?>"><i class="ion-android-bookmark"></i><?php esc_html_e( 'Bookmarks', 'madara' ); ?>
                    </a>
                </li>
                <li class="<?php echo esc_attr( $tab_pane == 'history' ? 'active' : ''); ?>">
                    <a href="<?php echo esc_url( $history ); ?>"><i class="ion-android-alarm-clock"></i><?php esc_html_e( 'History', 'madara' ); ?>
                    </a>
                </li>
                <li class="<?php echo esc_attr( $tab_pane == 'reader-settings' ? 'active' : ''); ?>">
                    <a href="<?php echo esc_url( $reader ); ?>"><i class="ion-gear-b"></i><?php esc_html_e( 'Reader Settings', 'madara' ); ?>
                    </a>
                </li>
                <li class="<?php echo esc_attr( $tab_pane == 'account-settings' ? 'active' : ''); ?>">
                    <a href="<?php echo esc_url( $account ); ?>"><i class="ion-android-person"></i><?php esc_html_e( 'Account Settings', 'madara' ); ?>
                    </a>
                </li>
				<?php do_action( 'madara_user_nav_tabs', $tab_pane, $account ); ?>
            </ul>
        </div>
    </div>
    <div class="col-md-9 col-sm-9">
        <div class="tabs-content-wrap">
            <div class="tab-content">
				<?php if ( in_array( $tab_pane, $array_compare ) ) { ?>
                    <div class="tab-pane active">
						<?php $wp_manga_template->load_template( "user/page/$tab_pane" ); ?>
                    </div>
				<?php } ?>

				<?php do_action( 'madara_user_nav_contents', $tab_pane, $account ); ?>
            </div>
        </div>
    </div>
</div>
