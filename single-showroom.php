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

    <div class="showroom_single">

        <h1 class="showroom_title"><?php the_title() ?></h1>

        <div class="showroom_taxonomy">
			<?php
			$args  = array(
				'taxonomy'   => 'types1',
				'hide_empty' => true,
				'number'     => '1',
				'object_ids' => $post->ID,
			);
			$terms = get_terms( $args );
			echo '<p>Type: ' . $terms[0]->name . '</p>'; ?>
			<?php
			$terms = get_the_terms( get_the_ID(), 'services' );
			if ( $terms && ! is_wp_error( $terms ) ) :
				$draught_links = array();
				foreach ( $terms as $term ) {
					$draught_links[] = $term->name;
				}
				$on_draught = join( ", ", $draught_links );
				?>
                <p><span>Services:</span> <?php echo $on_draught ?>  </p>
			<?php endif; ?>
        </div>

        <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2747.494853253546!2d30.722405583252005!3d46.47851294575155!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40c63137bf32d669%3A0x48acb379ad25e419!2sBeetroot+Academy!5e0!3m2!1sru!2sua!4v1552638597164"
                    width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>

        <div class="managers">

			<?php
			if ( have_rows( 'managers' ) ):
				while ( have_rows( 'managers' ) ) : the_row(); ?>


                    <div class="manager">
						<?php if ( get_sub_field( 'photo' ) ): ?>

                            <img src="<?php the_sub_field( 'photo' ); ?>"/>

						<?php endif; ?>
                        <h1><?php the_sub_field( 'name' ); ?></h1>
                        <p>Email: <?php the_sub_field( 'email' ); ?></p>
                        <p>Phone: <?php the_sub_field( 'phone' ); ?></p>
                    </div>
				<?php
				endwhile;

			else :

				// no rows found

			endif;

			?>
        </div>
        <div class="cars_in_showroom">
           	<?php
				$args      = array(
					'post_type'  => 'car',
					'meta_query' => array(
						array(
							'key'     => 'car_showroom',
							'value'   => $post->ID,
							'compare' => 'LIKE'
						)
					),
					'number'     => '1'
				);
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ) :
					while ( $the_query->have_posts() ) :
						$the_query->the_post(); ?>

                        <div class="car_in_showroom" href="<?php the_permalink(); ?>">
                            <a class="car_in_showroom_link" href="<?php the_permalink(); ?>">
							<?php if ( get_field( 'car_image' ) ): ?>

                             <img src="<?php the_field( 'car_image' ); ?>"/>

							<?php endif; ?>

							<?php
							get_template_part( 'template-parts/tax' );
							?>
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
    </div>
<?php

get_footer();
