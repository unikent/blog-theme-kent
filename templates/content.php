<?php $fid = get_post_thumbnail_id();
if(!empty($fid)) {
  $fsrc = wp_get_attachment_image_src($fid, 'large', false);
  $fsrc = $fsrc[0];
}else{

    // get direct post attachments
    $media_image_ids = get_posts( array(
        'post_parent' => $post->ID,
        'post_mime_type' => 'image',
        'post_type' => 'attachment',
        'fields' => 'ids',
        'posts_per_page' => 1
    ) );
    //get media in content
    if ( preg_match_all( '|<img.*?class=[\'"](.*?)wp-image-([0-9]{1,6})(.*?)[\'"].*?>|i', $post->post_content, $matches ) ) {
        $media_image_ids = array_merge( $media_image_ids, $matches[2] );
    }
    if(!empty($media_image_ids)){
        $fsrc = wp_get_attachment_image_src($media_image_ids[0], 'large', false);
        $fsrc = $fsrc[0];
    }else{
        $default = get_option('kb_default_aggregator_img');
        if(!empty($default)){
            $fsrc = wp_get_attachment_image_src($default, 'large', false);
            $fsrc = $fsrc[0];
        }else {
            $fsrc = get_template_directory_uri() . '/assets/img/featured-holding.png';
        }
    }
}
?>
<article class="grid-post">
  <div class="grid-post-img">
    <a title="<?php echo esc_attr(get_the_title()); ?>" href="<?php the_permalink(); ?>"><img class="img-responsive" src="<?php echo preg_replace('/\/wp-content\/blogs.dir\/\d+/','',$fsrc); ?>"></a>
  </div>
  <header>
    <h2 class="entry-title"><a title="<?php echo esc_attr(get_the_title()); ?>" href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>
    <?php get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="entry-content">
    <?php if(empty($post->post_excerpt)){
        the_excerpt();
    }else{
        $text = $post->post_excerpt;
        $text = strip_shortcodes( $text );
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);
        $excerpt_length = apply_filters('excerpt_length', 55);
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if ( count($words) > $excerpt_length ) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
        echo $text;
    } ?>
  </div>
</article>