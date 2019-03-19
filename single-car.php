<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */

get_header(); ?>

    <section>
		<?php if ( get_field( 'car_image' ) ): ?>

            <img class="car_image" src="<?php the_field( 'car_image' ); ?>"/>

		<?php endif; ?>
        <div class="main_information">

	        <?php

	        $args = array(
		        'taxonomy' => 'brand',
		        'hide_empty' => true,
		        'number' => '1',
		        'object_ids' => $post->ID,
		        'parent' => '0',
	        );
	        $terms = get_terms($args);
	        foreach ($terms as $term) {
		        $args = array(
			        'taxonomy' => 'brand',
			        'hide_empty' => true,
			        'number' => '1',
			        'object_ids' => $post->ID,
			        'parent' => $term->term_id
		        );
		        $child_terms = get_terms($args);
		        foreach ($child_terms as $child_term) {
			        $link_parent = get_term_link($term->term_id, $term->taxonomy);
			        $link_child = get_term_link($child_term->term_id, $child_term->taxonomy);

			        echo '<p>Brand: <a class="single_car_link" href="' . $link_parent .'">' . $term->name . '</a></p>
                            <p>Model: <a class="single_car_link" href="' . $link_child .'"> ' . $child_term->name . '</a></p>';
		        }
	        }

	        ?>

            <p class="car_color">Цвет: <?php the_field( 'car_color' ); ?></p>
            <p class="car_mileage">Пробег: <?php the_field( 'car_mileage' ); ?></p>
            <p class="car_price">Цена: <?php the_field( 'car_price' ); ?>$</p>

	        <?php

	        $post_object = get_field( 'car_showroom' );

	        if ( $post_object ):

		        // override $post
		        $post = $post_object;
		        setup_postdata( $post );

		        ?>

                    <p>Выставочный зал: <a class="single_car_link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                    <!--				<span>Выставочный зал: --><?php //the_field('field_name');
			        ?><!--</span>-->

		        <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly
		        ?>
	        <?php endif; ?>

        </div>
        <div class="car_gallery"><?php

	        $images = get_field('car_gallery');

	        if( $images ): ?>

			        <?php foreach( $images as $image ): ?>

                            <a href="<?php echo $image['url']; ?>">
                                <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
                            </a>
                            <p><?php echo $image['caption']; ?></p>

			        <?php endforeach; ?>

	        <?php endif; ?></div>


        <p class="single_car_description">Описание: <?php the_field( 'car_description' ); ?></p>


    </section>

<?php

get_footer();
