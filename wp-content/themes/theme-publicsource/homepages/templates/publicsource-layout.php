<?php
/**
 * Home Template: Top Stories
 * Description: A newspaper-like layout highlighting one Top Story on the left and others to the right. A popular layout choice!
 * Sidebars: Homepage Left Rail (An optional widget area that, when enabled, appears to the left of the main content area on the homepage)
 */

global $largo, $shown_ids, $tags;
$topstory_classes = (largo_get_active_homepage_layout() == 'LegacyThreeColumn') ? 'top-story span12' : 'top-story span8';
?>
<div id="homepage-featured" class="row-fluid clearfix">

	<div <?php post_class( $topstory_classes ); ?>>

	<?php
		$topstory = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'top-story'
				)
			),
			'posts_per_page' => 1
		) );
		if ( $topstory->have_posts() ) :
			while ( $topstory->have_posts() ) : $topstory->the_post(); $shown_ids[] = get_the_ID();
		?>
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'large' ); ?>
				</a>
				<div class="has-thumbnail">
					<a class="clickable" href="<?php the_permalink(); ?>"></a>
					<div class="has-thumbnail-inner">
						<h2><?php the_title(); ?></h2>
						<section class="excerpt"><?php largo_excerpt( $post, 4, false ); ?></section>
						<h5 class="byline"><?php largo_byline( true, true ); ?></h5>
					</div>
				</div>
				<?php if ( largo_post_in_series() ):
					$feature = largo_get_the_main_feature();
					$feature_posts = largo_get_recent_posts_for_term( $feature, 1, 1 );
					if ( $feature_posts ):
						foreach ( $feature_posts as $feature_post ): ?>

							<h4 class="related-story"><?php _e('Related:', 'largo'); ?> <a href="<?php echo esc_url( get_permalink( $feature_post->ID ) ); ?>"><?php echo get_the_title( $feature_post->ID ); ?></a></h4>
						<?php endforeach;
					endif;
				endif;
			endwhile;
		endif; // end top story ?>
	</div>

	<div class="sub-stories span4">
		<?php
		$posts_per_page = 4;
		$posts_per_page = apply_filters( 'largo_homepage_topstories_post_count', $posts_per_page );
		$substories = largo_get_featured_posts( array(
			'tax_query' => array(
				array(
					'taxonomy' 	=> 'prominence',
					'field' 	=> 'slug',
					'terms' 	=> 'homepage-featured'
				)
			),
			'posts_per_page' => $posts_per_page,
			'post__not_in' 	 => $shown_ids
		) );
		if ( $substories->have_posts() ) :
			$count = 0;
			while ( $substories->have_posts() ) : $substories->the_post(); $shown_ids[] = get_the_ID();
				if ( $count <= 3 ) : ?>
					<div <?php post_class( 'story' ); ?> >
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<h5 class="byline"><?php largo_byline( true, true ); ?></h5>
					</div>
				<?php elseif ( $count == 4 ) : ?>
					<h4 class="subhead"><?php _e('More Headlines', 'largo'); ?></h4>
					<h5 class="byline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
				<?php else : ?>
					<h5 class="byline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
				<?php endif;
				$count++;
			endwhile;
		endif; // end more featured posts ?>

		<?php
		// If not enough featured posts, backfill with recent posts
		if ( $count < 4 ) :
			$args = array (
				'posts_per_page' => ( 4 - $count ),
				'post__not_in' 	 => $shown_ids
			);
			$recent_posts = new WP_Query( $args );

			if ( $recent_posts->have_posts() ) :
				?>
				<?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); $shown_ids[] = get_the_ID(); ?>
					<div <?php post_class( 'story' ); ?> >
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<h5 class="byline"><?php largo_byline( true, true ); ?></h5>
					</div>
					<?php $count++; ?>
				<?php endwhile; ?>
			<?php endif; // end more featured posts ?>
		<?php endif; // end if $count < 3 ?>
	</div>
</div>

<hr />

<div id="home-recent-grid">
		<?php
		$args = array (
			'posts_per_page' => 6,
			'post__not_in' 	 => $shown_ids
		);
		$recent_posts = new WP_Query( $args );

		if ( $recent_posts->have_posts() ) :

			$count = 0;
			?>
			<?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); $shown_ids[] = get_the_ID(); ?>
				<?php if ( 0 == $count%3 ) : ?>
					<div class="row-fluid">
				<?php endif; ?>

					<div class="span4">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
						</a>
						<h5 class="top-tag"><?php largo_top_term(); ?></h5>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					</div>

				<?php if ( 2 == $count%3 ) : ?>
					</div>
				<?php endif; ?>
				<?php $count++; ?>
			<?php endwhile; ?>
		<?php endif; // end more featured posts ?>
</div>

<hr />

<div id="home-middle-feature">
	<?php
	$args = array (
		'tax_query' => array (
			array (
				'taxonomy' 	=> 'prominence',
				'field' 	=> 'slug',
				'terms' 	=> 'home-middle-feature'
			)
		),
		'posts_per_page' => 1
	);
	$posts = new WP_Query( $args );

	if ( $posts->have_posts() ) :

		$count = 0;
		?>
		<?php while ( $posts->have_posts() ) : $posts->the_post(); $shown_ids[] = get_the_ID(); ?>
			<div class="row-fluid">
				<div class="span12">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'full' ); ?>
					</a>
					<h5 class="top-tag"><?php largo_top_term(); ?></h5>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<h5 class="byline"><?php largo_byline( true, true ); ?></h5>
				</div>
			</div>
			<?php $count++; ?>
		<?php endwhile; ?>
	<?php endif; // end more featured posts ?>
</div>

<hr />

<div id="home-category-grid">
	<div class="row-fluid">
		<?php if ( !dynamic_sidebar( 'Home Category Grid' ) ) { ?>
			<aside class="span4" style="display:block;margin-left: auto;margin-right: auto;background-color:#ddd;color:#bb0000; text-align: center;"> Add some widgets to the Home Category widget area.</aside>
		<?php } ?>
	</div>
</div>

<hr />

<div id="home-bottom-feature">
	<div class="row-fluid">
		<?php if ( !dynamic_sidebar( 'Home Bottom Feature' ) ) { ?>
			<aside class="span4" style="display:block;margin-left: auto;margin-right: auto;background-color:#ddd;color:#bb0000; text-align: center;"> Add some widgets to the Home Bottom Feature widget area.</aside>
		<?php } ?>
	</div>
</div>