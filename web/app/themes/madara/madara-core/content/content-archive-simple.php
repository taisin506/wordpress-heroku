<?php 

use App\Madara;

$wp_query           = madara_get_global_wp_query();
$wp_manga           = madara_get_global_wp_manga();
$wp_manga_setting   = madara_get_global_wp_manga_setting();
$wp_manga_functions = madara_get_global_wp_manga_functions();

?>
<div class="item-summary">
	<div class="post-title font-title">
		<h5>
			<?php madara_manga_title_badges_html( get_the_ID(), 1 ); ?>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			
			<div class="meta-item rating">
				<?php
					$wp_manga_functions->manga_rating_display( get_the_ID() );
				?>
			</div>
		</h5>
	</div>
	
	<div class="list-chapter">
		<?php
			$wp_manga_functions->manga_meta( get_the_ID() );
		?>
	</div>
</div>