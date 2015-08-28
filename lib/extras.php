<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more() {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Read&nbsp;more', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more',99);

function roots_excerpt_length( $length ) {
  return 25;
}
add_filter( 'excerpt_length', 'roots_excerpt_length',99 );

function get_attachment_id_from_src ($image_src) {

  global $wpdb;
  $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
  $id = $wpdb->get_var($query);
  return $id;

}

/**
 * Extend Recent Posts Widget
 *
 * Adds different formatting to the default WordPress Recent Posts Widget
 */

Class Kentblogs_Recent_Posts_Widget extends WP_Widget_Recent_Posts {

  function widget($args, $instance) {
    $cache = array();
    if ( ! $this->is_preview() ) {
      $cache = wp_cache_get( 'widget_recent_posts', 'widget' );
    }

    if ( ! is_array( $cache ) ) {
      $cache = array();
    }

    if ( ! isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }

    if ( isset( $cache[ $args['widget_id'] ] ) ) {
      echo $cache[ $args['widget_id'] ];
      return;
    }

    ob_start();

    $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

    /** This filter is documented in wp-includes/default-widgets.php */
    $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

    $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
    if ( ! $number )
      $number = 5;
    $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

    /**
     * Filter the arguments for the Recent Posts widget.
     *
     * @since 3.4.0
     *
     * @see WP_Query::get_posts()
     *
     * @param array $args An array of arguments used to retrieve the recent posts.
     */
    $r = new WP_Query( apply_filters( 'widget_posts_args', array(
        'posts_per_page'      => $number,
        'no_found_rows'       => true,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true
    ) ) );

    if ($r->have_posts()) :
      ?>
      <?php echo $args['before_widget']; ?>
      <?php if ( $title ) {
      echo $args['before_title'] . $title . $args['after_title'];
    } ?>
      <ul>
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
          <?php
            $cat = get_post_meta(get_the_ID(),'primary_category',true);
            // set a default primary category to the first category if one isn't set
			if (empty($cat)) {
              foreach (wp_get_post_categories(get_the_ID()) as $category) {
                $cat = get_category($category);
                break;
              }

              // if still empty then set it to uncategorized
              if (empty($cat)) {
                $cat = new stdClass();
                $cat->name = 'Uncategorized';
                $cat->slug  = 'uncategorized';
                $cat->term_id = 0;
              }
            }else {
              $cat = get_category($cat);
            }
          $fid = get_post_thumbnail_id();
          $fsrc=false;
          if(!empty($fid)) {
            $fsrc = wp_get_attachment_image_src($fid, 'thumbnail', false);
            $fsrc = $fsrc[0];
          }else{
              // get direct post attachments
              $media_image_ids = get_posts( array(
                  'post_parent' => get_the_ID(),
                  'post_mime_type' => 'image',
                  'post_type' => 'attachment',
                  'fields' => 'ids',
                  'posts_per_page' => 1
              ) );
              //get media in content
              if ( preg_match_all( '|<img.*?class=[\'"](.*?)wp-image-([0-9]{1,6})(.*?)[\'"].*?>|i', get_the_content(), $matches ) ) {
                  $media_image_ids = array_merge( $media_image_ids, $matches[2] );
              }
              if(!empty($media_image_ids)){
                  $fsrc = wp_get_attachment_image_src($media_image_ids[0], 'thumbnail', false);
                  $fsrc = $fsrc[0];
              }else{
                  $default = get_option('kb_default_aggregator_img');
                  if(!empty($default)){
                      $fsrc = wp_get_attachment_image_src($default, 'thumbnail', false);
                      $fsrc = $fsrc[0];
                  }else {
                      $fsrc = get_template_directory_uri() . '/assets/img/featured-holding-thumb.png';
                  }
              }
          }
          ?>
          <li>
            <a href="<?php the_permalink(); ?>"><?php if(!empty($fsrc)){?><img src="<?php echo $fsrc; ?>" class="featured-thumb"><?php } ?><?php get_the_title() ? the_title() : the_ID(); ?></a>
            <p><span class="post-cat"><?php echo strtoupper($cat->name);?></span>
            <?php if ( $show_date ) : ?>|&nbsp;<span class="post-date"><?php echo get_the_date('d F Y'); ?></span><?php endif; ?></p>
          </li>
        <?php endwhile; ?>
      </ul>
      <?php echo $args['after_widget']; ?>
      <?php
      // Reset the global $the_post as this query will have stomped on it
      wp_reset_postdata();

    endif;

    if ( ! $this->is_preview() ) {
      $cache[ $args['widget_id'] ] = ob_get_flush();
      wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
    } else {
      ob_end_flush();
    }
  }
}
function kentblog_widget_registration() {
  unregister_widget('WP_Widget_Recent_Posts');
  register_widget('Kentblogs_Recent_Posts_Widget');
}
add_action('widgets_init', 'kentblog_widget_registration');


function kentblogs_introtext_form(){
    global $post;
    $value= get_post_meta($post->ID,'IntroText',true);
    echo '<textarea name="kb_introtext" placeholder="Intro Text (optional)">' . $value . '</textarea>';
}

function kentblogs_introtext_save($post_id, $post, $update){
    if(isset($_POST['kb_introtext'])){
        update_post_meta($post_id, 'IntroText', $_POST['kb_introtext']);
    }
    return $post_id;
}
add_action('save_post', 'kentblogs_introtext_save',10,3);


function kentblogs_add_introtext_metabox()
{
    add_meta_box('kentblogs_introtext', 'Intro Text', 'kentblogs_introtext_form', 'post', 'after_title','low');
}


add_action('current_screen','kentblogs_add_introtext_metabox');


function kentblogs_introtext_scripts(){
    ?>
    <script type="text/javascript">
        /* <![CDATA[ */

        jQuery(function($)
        {
            //hide screen options
            jQuery('.metabox-prefs label[for=kentblogs_introtext-hide]').remove();

            //remove title
            jQuery('#kentblogs_introtext > h3, #kentblogs_introtext > .handlediv').remove();

        });
        /* ]]> */
    </script>
    <style type="text/css">
        #kentblogs_introtext {
            border: none;
            background: transparent;
        }
        #kentblogs_introtext .inside{
            margin:0;
            padding:0;
        }
        #kentblogs_introtext textarea{
            background-color: #fff;
            font-size: 1em;
            height: 3em;
            line-height: 100%;
            margin: 0;
            outline: 0 none;
            padding: 2px 8px;
            width: 100%;
        }
    </style>
    <?php
}
add_action('admin_head', 'kentblogs_introtext_scripts');


function kentblogs_insert_introtext($html){
    global $post;
    $intro = get_post_meta($post->ID, 'IntroText',true);
    if(!empty($intro)){
      return '<p class="lead">' . $intro . '</p>' . $html;
    }
    return $html;
}
add_filter('the_content', 'kentblogs_insert_introtext',9);

function get_image_attribution($id){
    $meta = get_post_meta($id);
    $meta = array_map(function($i){
        return $i[0];
    },$meta);

    $out='';
    $title = get_post($id)->post_title;
    if(empty(trim($title))){
        $title = 'Picture';
    }else{
        $title = '"' . $title .'"';
    }
    $out .= (array_key_exists("credit-tracker-publisher",$meta) && !empty($meta["credit-tracker-publisher"]))? $meta["credit-tracker-publisher"]. ': ' : '';
    if(array_key_exists("credit-tracker-author",$meta) && !empty($meta["credit-tracker-author"])){
        if(array_key_exists("credit-tracker-link",$meta) && !empty($meta["credit-tracker-link"])){
            $out .= $title . ' by <a href="' . $meta["credit-tracker-link"] . '">' . $meta["credit-tracker-author"] . '</a>.';
        }else{
            $out .= $title . ' by ' . $meta["credit-tracker-author"] .'.';
        }
    }
    if(array_key_exists("credit-tracker-license",$meta) && !empty($meta["credit-tracker-license"])){
        $out .= ' ' . $meta["credit-tracker-license"];
    }

    return $out;
}