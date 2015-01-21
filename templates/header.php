<header class="banner" role="banner">
  <?php
  $header_bg = get_theme_mod('theme_header_bg');
  if(!empty($header_bg)){
    $header_bg_id = get_attachment_id_from_src($header_bg);
    error_log($header_bg);
    error_log($header_bg_id);
    $header_bg_src = wp_get_attachment_image_src($header_bg_id,array(1920,99999),false);
  ?>
  <div class="blog-header-img">
  <img src="<?php echo $header_bg_src[0]; ?>">
  </div>
  <?php
  }
  ?>
  <div class="container">
    <div class="row">
      <a class="blog-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
      <span class="blog-description"><?php bloginfo ( 'description' ); ?></span>
    </div>
  </div>
  <div class="container navbar navbar-default navbar-static-top">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new Roots_Nav_Walker(), 'menu_class' => 'nav navbar-nav'));
      endif;
      ?>
    </nav>
  </div>
</header>
