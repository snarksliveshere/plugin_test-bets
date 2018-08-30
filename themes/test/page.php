<?php
get_header(); ?>
	<div class="container">
		<div class="row">
			<div class="">
				<?php the_post() ?>
				<div class="post">
					<h2><?php the_title() ?></h2>
					<?php the_post_thumbnail('large')?>
					<div class="content"><?php the_content() ?></div>
				</div>

                <?php if(is_user_logged_in()) :?>
	                <?php $terms = get_terms([
		                'taxonomy' => 'bet_type',
		                'hide_empty' => false
	                ]);
	                ?>
                <div class="add-bets">
                    <h4 class="my-3"><?php _e('Добавить ставку', 'test') ?></h4>
                    <form action="" class="mb-4 add-bets_form" data-cookie="else">
                        <input type="hidden" name="id_user" value="<?=wp_get_current_user()->data->ID?>">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="Заголовок">
                        </div>
                        <div class="form-group">
                            <textarea name="content" cols="10" rows="5" class="form-control" placeholder="Описание"></textarea>
                        </div>
                        <div class="form-group">
                            <select name="bets_type" class="form-control">
		                        <?php foreach ($terms as $term) :?>
                                    <option value="<?=$term->term_taxonomy_id?>"><?=$term->name?></option>
		                        <?php endforeach ?>
                            </select>
                        </div>
                        </div>
                        <input type="submit" class="btn btn-primary add-bets_send mb-4" value="Отправить">
                    </form>
                    <p class="after-bets_ajax"></p>
                </div>
                <?php endif ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>