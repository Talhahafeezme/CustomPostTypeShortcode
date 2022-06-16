
/**
 *  Shortcode for quote  slide & Slider
 */

function quotesFieldShortcode( $post_id ) {

   if ( get_post_type($post_id) == 'quote_slider' ) {

        // If this is just a revision, don't send the email.
        if ( wp_is_post_revision( $post_id ) )
        return;

        $value = '[quotes sliderid="'.$post_id.'"]'; // The value depends in fact on the value of another field
        update_post_meta($post_id, 'shortcode_quote_slider', $value);

    }
     if ( get_post_type($post_id) == 'quote_slide' ) {

        // If this is just a revision, don't send the email.
        if ( wp_is_post_revision( $post_id ) )
        return;

        $value = '[quotes slideid="'.$post_id.'"]'; // The value depends in fact on the value of another field
        update_post_meta($post_id, 'shortcode_quote_slide', $value);

    }

}

add_action( 'save_post', 'quotesFieldShortcode', 10, 2 );

function sendoso_quote_slide_shortcode($atts) {

// global $wp_query, $post;

$typeName = $atts['category'];
$slideid = $atts['slideid'];
$sliderid = $atts['sliderid'];


if ( ! empty( $slideid ) )
{

$newArray = explode(',', $slideid);

}
if ( ! empty( $sliderid ) )
{

$newArray = explode(',', $sliderid);

}


if (!empty($typeName) && empty($slideid) && empty($sliderid))
{
$args = array(
'posts_per_page' => -1,
    'post_type'         => 'quote_slide',
    'post_status'       => 'publish',
'order' => 'ASC',
    'tax_query'         => array(
        array(
            'taxonomy'  => 'quote_category',
            'field'     => 'slug',
            'terms'     => $typeName
       )
   )
);

}
elseif(!empty($typeName) && !empty($slideid) && empty($sliderid)){

$args = array(
'posts_per_page' => -1,
    'post_type'         => 'quote_slide',
    'post__in' => $newArray,
'post_status'   => 'publish',  
'orderby'       => 'post__in',
'tax_query'         => array(
        array(
            'taxonomy'  => 'quote_category',
            'field'     => 'slug',
            'terms'     => $typeName
       )
   )
   
);
}
elseif(empty($typeName) && !empty($slideid) && empty($sliderid)){

$args = array(
'posts_per_page' => -1,
    'post_type'         => 'quote_slide',
    'post__in' => $newArray,
'post_status'   => 'publish',  
'orderby'       => 'post__in',
   
);
}
elseif(!empty($sliderid) && empty($typeName) && empty($slideid)){
$pod = pods( 'quote_slider', $sliderid );

$related = $pod->field( 'select_quote_slide');

if ( ! empty( $related ) )
{
foreach ( $related as $rel )
{
$newArray[] = $rel[ 'ID' ];
}
}

$args = array(
'post_type' => 'quote_slide',
'post__in' => $newArray,
'post_status'   => 'publish',  
'orderby'       => 'post__in',
);
}

else{

$atts = shortcode_atts(
array(
'posts_per_page' => -1,
'post_type' => 'quote_slide',
'orderby' => 'date',
'post_status'   => 'publish',  
'order' => 'ASC',
), $atts);

$args = array(
'post_type' => array($atts['post_type']),
'posts_per_page' => $atts['posts_per_page'],
'orderby' => $atts['orderby'],
'order' => $atts['order'],
);
}
$output = '';

$slider_query = new WP_Query($args);


$output .= '<div class="slider quote-slide" style="width: 100%;">';
if ($slider_query->have_posts()) :
while ($slider_query->have_posts()) :

$slider_query->the_post();
$post_id = get_the_ID();
$contentslide = get_the_content();

$quote_image = get_post_field('quoter_image',$post_id);
$quote_logo = get_post_field('company_logo',$post_id);


$quote_name = get_post_field('quoter_name',$post_id);
$quote_designation = get_post_field('quoter_designation',$post_id);

$output .= '<div class="items ">';
$output .= '<div class="quotedesc"><p>'.$contentslide.'</p></div>';
$output .= '<div class="quoteinfo ">';
if(!empty($quote_image)){
$output .= '<div class="quoteimage">';

$output .= '<img src="'.$quote_image['guid'].'">';

$output .= '</div>';
}
$output .= '<div class="quoterowlast">';
$output .= '<div class="quotedesarea">';
$output .= '<div class="quotename"><h4>'.$quote_name.'</h4></div>';
$output .= '<div class="quote_designation"><h5>'.$quote_designation.'</h5></div>';

$output .= '</div>';
if(!empty($quote_logo)){
$output .= '<div class="quotelogo">';

$output .= '<img src="'.$quote_logo['guid'].'">';

$output .= '</div>';
}
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';


endwhile;
wp_reset_postdata();
else :
echo '<div class="no-result"><h4 class="no-found">Sorry, there are no result for quote.</h4></div>';
endif;

$output .= '</div>';

return $output;

}

add_shortcode('quotes', 'sendoso_quote_slide_shortcode');

/**** End Quotes Code **/
