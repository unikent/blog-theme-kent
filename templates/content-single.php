<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php $sub = get_post_meta($post->ID, 'SubHeading',true);
      if(!empty($sub)){
        ?>
        <h2><?php echo $sub; ?></h2>
      <?php
      }
      ?>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <?php $fid = get_post_thumbnail_id();
    if(!empty($fid)){
      $attribution = get_image_attribution($fid);
      $fsrc = wp_get_attachment_image_src($fid,'large',false);
      $fsrc = $fsrc[0];
      ?>
      <div class="entry-featured">
        <img src="<?php echo $fsrc; ?>" class="featured-img">
        <?php if(!empty($attribution)){ ?>
        <div class="attribution"><i class="kf-camera"></i><span>&nbsp;&nbsp;<?php echo $attribution; ?></span></div>
        <?php } ?>
      </div>
    <?php
    }
    ?>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php get_template_part('templates/entry-footer-meta'); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
