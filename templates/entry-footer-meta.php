<?php
$categories_list = get_the_category_list( ', ');
if ( $categories_list ) {
?>
<span class="cat-links"><span class="screen-reader-text">Categories:&nbsp;</span><?php echo $categories_list; ?></span>
<?php
}

$tags_list = get_the_tag_list( '',', ');
if ( $tags_list ) {
    ?>
    <span class="tag-links"><span class="screen-reader-text">Tags:&nbsp;</span><?php echo $tags_list; ?></span>
<?php
}

if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
echo '<span class="comments-link">';
		comments_popup_link( 'Leave a comment', '1 Comment', '% Comments');
		echo '</span>';
}