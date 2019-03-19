<?php
/**
 * Template Name: Home
 *
 */

get_header();
?>

<div class="slider">
	<?php
	if ( have_rows( 'slider_repeater' ) ):
		while ( have_rows( 'slider_repeater' ) ) : the_row();
			?>
            <div class="slider_item">
                <img class="slider_image" src="<?php the_sub_field( 'image' ) ?>" alt="Slider Image"/>
                <p class="slider_text"><?php the_sub_field( 'text' ) ?></p>
            </div>
		<?php
		endwhile;
	endif;
	?>
</div>

<div class="showrooms">
	<?php
	$post_objects = get_field( 'car_showrooms' );

	if ( $post_objects ): ?>
        <div class="showroom">
			<?php foreach ( $post_objects as $post ): // variable must be called $post (IMPORTANT) ?>
				<?php setup_postdata( $post ); ?>
                <div class="showroom1">
                    <a class="showroom1_link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
			<?php endforeach; ?>
        </div>
		<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
	<?php endif;

	/*
	*  Loop through post objects (assuming this is a multi-select field) ( don't setup postdata )
	*  Using this method, the $post object is never changed so all functions need a seccond parameter of the post ID in question.
	*/

	$post_objects = get_field( 'post_objects' );

	if ( $post_objects ): ?>
        <ul>
			<?php foreach ( $post_objects as $post_object ): ?>
                <li>
                    <a href="<?php echo get_permalink( $post_object->ID ); ?>"><?php echo get_the_title( $post_object->ID ); ?></a>
                    <span>Post Object Custom Field: <?php the_field( 'field_name', $post_object->ID ); ?></span>
                </li>
			<?php endforeach; ?>
        </ul>
	<?php endif;

	?>
</div>

<div class="content">
    <p><?php the_field( 'content_content' ); ?></p>
</div>

<div class="featured_cars">
	<?php
	$args      = array(
		'post_type'  => 'car',
		'meta_query' => array(
			array(
				'key'     => 'car_hot',
				'value'   => '1',
				'compare' => 'LIKE'
			)
		)
	);
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) :
			$the_query->the_post(); ?>
        <div class="car-search">
            <a class="car-search_link" href="<?php the_permalink(); ?>">
				<?php if ( get_field( 'car_image' ) ): ?>

                    <img src="<?php the_field( 'car_image' ); ?>"/>

				<?php endif; ?>




<?php
             get_template_part( 'template-parts/tax' );


?>

                <p> Color: <?php the_field( 'car_color' ); ?></p>
                <p> Mileage: <?php the_field( 'car_mileage' ); ?></p>
                <p> Price: <?php the_field( 'car_price' ); ?>$</p>
            </a>
        </div>
		<?php
		endwhile;
		wp_reset_query();
	endif;
	?>
</div>

<div class="partners">
	<?php

	// check if the repeater field has rows of data
	if( have_rows('partners_repeater') ):

		// loop through the rows of data
		while ( have_rows('partners_repeater') ) : the_row();?>

			<?php

$images = get_sub_field('gallery');
$size = 'full'; // (thumbnail, medium, large, full or custom size)

if( $images ): ?>

        <?php foreach( $images as $image ): ?>

            <p class="partners_logo"><?php echo wp_get_attachment_image( $image['ID'], $size ); ?></p>

        <?php endforeach; ?>

<?php endif; ?>

	<?php	endwhile;

	else :

		// no rows found

	endif;

	?>
</div>

<?php get_footer(); ?>
