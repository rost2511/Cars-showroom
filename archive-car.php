<?php
get_header(); ?>
    <div class="archive_car_content">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

			echo '<div class="one_car">';
			?>
        <a class="one_car_link" href="<?php the_permalink(); ?>">
			<?php if ( get_field( 'car_image' ) ): ?>

                <img class="car_image" src="<?php the_field( 'car_image' ); ?>"/>

			<?php endif; ?>
<p><?php the_title(); ?></p>
			<?php get_template_part( 'template-parts/tax' ); ?>
            <p> Mileage: <?php the_field( 'car_mileage' ); ?></p>
            <p> Price: <?php the_field( 'car_price' ); ?>$</p>
			<?php
			echo '</div>';
		endwhile; endif; ?>
        </a>
    </div>
<?php
get_footer();
