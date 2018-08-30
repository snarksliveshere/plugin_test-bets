<?php
get_header(); ?>
	<div class="container">
<!--        TODO:query_post-->
		<div class="row">
			<div class="col-md-8">
				<?php if(have_posts()) :
					while (have_posts()) :  the_post() ?>
						<div class="item">
							<h2><?php the_title() ?></h2>
							<a href="<?php the_permalink()?>">
								<?php the_post_thumbnail([100,100])?>
							</a>
						</div>
					<?php endwhile; ?>
				<?php else: ?>
<!--					TEMP-->
				<?php endif;?>
			</div>
			<div class="col-md-4">
				<?php get_sidebar() ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>