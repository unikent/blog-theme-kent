<article <?php post_class(); ?>>
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php $sub = get_post_meta($post->ID, 'SubHeading',true);
    if(!empty($sub)){
      ?>
      <h3><?php echo $sub; ?></h3>
    <?php
    }
    ?>
    <?php get_template_part('templates/entry-meta'); ?>
  </header>
  <?php $fid = get_post_thumbnail_id();
    if(!empty($fid)){
      $fsrc = wp_get_attachment_image_src($fid,'large',false);
      $fsrc = $fsrc[0];
  ?>
  <div class="entry-featured">
    <a href="<?php the_permalink(); ?>"><img src="<?php echo $fsrc; ?>" class="featured-img"></a>
  </div>
  <?php
    }
  ?>
  <div class="entry-summary">
    <?php the_content(); ?>
  </div>
  <footer>
    <?php get_template_part('templates/entry-footer-meta'); ?>
  </footer>
</article>
