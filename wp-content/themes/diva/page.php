<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<!--page content container start-->
    <div class="content_warp">
      <div class="heading_warp">
        <h1><?php the_title(); ?></h1>
        <h2><a href="<?php echo get_bloginfo('url');?>">Home</a> &gt; <?php the_title();?></h2>
      </div>
      <div class="about_content_div">
        <?php the_content(); ?>
      </div>
    </div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
