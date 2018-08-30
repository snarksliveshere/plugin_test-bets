<!doctype html>
<html <?php language_attributes() ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ) ?>">
    <meta name="viewport" content="width=device-width">
	<?php wp_head() ?>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="logo"><a href="<?php home_url() ?>" class="display-1"><?php bloginfo( 'name' ) ?></a></div>
        <?php
            wp_nav_menu([
                'theme_location' => 'top',
                'container' => null,
                'menu_id' => '',
                'menu_class' => 'nav header_nav mt-2 mb-4',
                'items_wrap' => '<ul class="%2$s">%3$s</ul>'
            ])
        ?>
    </div>
</div>