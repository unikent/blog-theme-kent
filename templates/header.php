<header class="banner" role="banner">
  <?php
  $header_bg = get_theme_mod('theme_header_bg');
  if(!empty($header_bg)){
    $header_bg_id = get_attachment_id_from_src($header_bg);
    $header_bg_src = wp_get_attachment_image_src($header_bg_id,'blog_header',false);
  }else{
    $header_bg_src = array(get_template_directory_uri() . '/assets/img/blog-header-default.jpg');
  }
  ?>
  <div class="blog-header-img">
  <img src="<?php echo $header_bg_src[0]; ?>">
  </div>
  <div class="container blog-header">
    <div class="row">
      <div class="blog-title-wrap">
        <a class="blog-title" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        <span class="nav-menu-toggle" data-toggle="collapse" data-target=".navbar-collapse"></span>
      </div>
      <span class="blog-description"><?php bloginfo ( 'description' ); ?></span>
    </div>
  </div>
  <div class="container navbar navbar-default navbar-static-top">
    <div class="collapse navbar-collapse">
    <nav class="navbar-left" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(array('theme_location' => 'primary_navigation', 'walker' => new Roots_Nav_Walker(), 'menu_class' => 'nav navbar-nav'));
      endif;
      ?>
    </nav>
    <form role="search" method="get" class="navbar-form navbar-right search-form form-inline" action="<?php echo esc_url(home_url('/')); ?>">
      <label class="sr-only"><?php _e('Search for:', 'roots'); ?></label>
      <div class="input-group">
        <input type="search" value="<?php echo get_search_query(); ?>" name="s" class="search-field form-control" placeholder="Search this blog" required>
    <span class="input-group-btn">
      <button type="submit" class="search-submit btn btn-primary"><span class="sr-only"><?php _e('Search', 'roots'); ?></span><i class="kf-search"></i></button>
    </span>
      </div>
    </form>
    </div>
  </div>
</header>
