<?php
get_header(); ?>
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<?php the_post() ?>
            <div class="jumbotron set-bets">
                <h1 class="display-4"><?php the_title() ?></h1>
	            <?php the_post_thumbnail('large')?>
                <p class="lead text-muted"><?php the_terms( $post->ID, 'bet_type', __('Тип ставки: ', 'test')) ?></p>
                <p class="lead text-muted"><?php the_terms( $post->ID, 'bet_status', __('Статус ставки: ', 'test')) ?></p>
                <hr class="my-4">
                <p><?php the_content() ?></p>
                <p class="lead">
                    <?php get_post_meta( $post->ID, 'is_new' ) ?>
                <form action="" class="mb-4 set_bets_form" data-cookie="setBets">
                    <input type="hidden" name="id_set_bet" value="<?=$post->ID?>">
                    <input type="submit"
                           class="btn btn-success btn-lg set_bets_send"
                           value="<? _e('Ставка пройдет', 'test')?>">
                </form>
                <p class="after-bets_ajax"></p>
            </div>
		</div>
		<div class="col-md-4">
            <?php get_sidebar() ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
