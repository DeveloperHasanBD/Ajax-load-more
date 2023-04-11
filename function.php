<?php

function collection_list_load_more()
{
    $paged = $_POST['page'] ?? '';
    $get_luser_id = $_POST['get_luser_id'] ?? '';
    $args = array(
        'post_type'       => 'prodotti',
        'post_status'     => 'all',
        'posts_per_page' => 3,
        'paged' => $paged,
        'orderby' => 'ID',
        'order'   => 'DESC',
        'meta_query'      => array(
            array(
                'key'         => 'user_collection_user_id',
                'value'       => $get_luser_id,
                'compare'     => '=',
            ),
        )
    );

    $lm_post_query = new WP_Query($args);

    $post_items                   = '';
    $error = '';

    if ($lm_post_query->have_posts()) {
        while ($lm_post_query->have_posts()) {
            $lm_post_query->the_post();
            $collection_id = get_the_ID();

            $def = get_template_directory_uri() . '/assets/images/default.png';
            $gallaries = isset(get_post_meta($collection_id, 'gallarywb_catalogo_gallary')[0]) ? get_post_meta($collection_id, 'gallarywb_catalogo_gallary')[0] : array();
            $thumb = isset($gallaries[0]) ? wp_get_attachment_url($gallaries[0]) : $def;

            $get_the_title = get_the_title();
            $collection_status   = get_post_status($collection_id);
            $set_pending_msg = '';
            if ($collection_status == 'pending') {
                $set_pending_msg = 'In attesa di approvazione';
            }
            $edit_item_link = site_url() . '/crea-una-nuova-collezione?id=' . $collection_id;
            $pencil_icon = get_template_directory_uri() . '/assets/images/icons/pencil.svg';
            $delete_icon = get_template_directory_uri() . '//assets/images/icons/delete.svg';


            $post_items .= '
            <div class="collection_list_item">
            <input type="hidden" name="coll_item_id" value="' . $collection_id . '">
                <div class="collection_item_left">
                    <img src="' . $thumb . '" alt="">
                    <p>' . $get_the_title . '
                        <span>' . $set_pending_msg . '</span>
                    </p>
                </div>
                <div class="collection_item_right">
                    <a href="' . $edit_item_link . '">
                        <img src="' . $pencil_icon . '" alt="pencil">
                    </a>
                    <a onclick="' . $collection_id . '">
                        <img src="' . $delete_icon . '" alt="delete">
                    </a>
                </div>
            </div>
            ';
        }
        wp_reset_query();
    } else {
        $error = true;
    }

    $message                = '<span class="cat_msg_display">Nessun articolo trovato</span>';

    $response['results']    = array(
        'post_tems'         => $post_items,
        'error'             => $error,
        'message'           => $message,
    );
    echo  json_encode($response);
    die;
}

add_action('wp_ajax_collection_list_load_more', 'collection_list_load_more');
add_action('wp_ajax_nopriv_collection_list_load_more', 'collection_list_load_more');
