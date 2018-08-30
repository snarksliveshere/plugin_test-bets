<?php
get_header(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
				<?php the_post() ?>
                <div class="post">
                    <h2><?php the_title() ?></h2>
	                <?php the_post_thumbnail('large')?>
                    <div class="content"><?php the_content() ?></div>
                </div>
            </div>
            <div class="col-md-4">
		        <?php get_sidebar() ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>