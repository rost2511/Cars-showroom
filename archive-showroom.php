<?php

get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post();

	echo '<div class="entry-content">'; ?>
    <h1><?php the_title(); ?></h1>

    <section class="showroom_flex">

			<?php
			if ( have_rows( 'managers' ) ):
				while ( have_rows( 'managers' ) ) : the_row(); ?>
        <div class="archive_showroom_manager">
                    <h2><?php the_sub_field( 'name' ); ?></h2>

					<?php if ( get_sub_field( 'photo' ) ): ?>

                        <img src="<?php the_sub_field( 'photo' ); ?>"/>

					<?php endif; ?>
                    <p class="archive_showroom_p">Email: <?php the_sub_field( 'email' ); ?></p>
                    <p class="archive_showroom_p">Phone: <?php the_sub_field( 'phone' ); ?></p>
        </div>
				<?php
				endwhile;

			else :

				// no rows found

			endif;

			?>

    </section>
<?php
endwhile; endif;

get_footer();
