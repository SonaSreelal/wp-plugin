<?php
// Register custom post type 'movies'
function custom_movies_post_type() {
    $labels = array(
        'name'               => 'Movies',
        'singular_name'      => 'Movie',
        'menu_name'          => 'Movies',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Movie',
        'edit_item'          => 'Edit Movie',
        'new_item'           => 'New Movie',
        'view_item'          => 'View Movie',
        'search_items'       => 'Search Movies',
        'not_found'          => 'No movies found',
        'not_found_in_trash' => 'No movies found in trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'publicly_queryable' => false,
        'query_var'          => false,
        'rewrite'            => true,
        'capability_type'    => 'post',
        'supports'           => array('title', 'thumbnail'),
    );

    register_post_type('movies', $args);
}
add_action('init', 'custom_movies_post_type');

// Add custom fields for 'movies' post type
function custom_movies_fields() {
    add_meta_box('movie_director', 'Director', 'movie_director_callback', 'movies', 'normal', 'high');
    add_meta_box('movie_actor', 'Actor', 'movie_actor_callback', 'movies', 'normal', 'high');
}
add_action('add_meta_boxes', 'custom_movies_fields');

// Callback for 'Director' custom field
function movie_director_callback() {
    global $post;
    $director = get_post_meta($post->ID, '_movie_director', true);
    echo '<input type="text" class="widefat" name="movie_director" value="' . esc_attr($director) . '" />';
}

// Callback for 'Actor' custom field
function movie_actor_callback() {
    global $post;
    $actor = get_post_meta($post->ID, '_movie_actor', true);
    echo '<input type="text" class="widefat" name="movie_actor" value="' . esc_attr($actor) . '" />';
}

// Save custom field data when 'movies' post type is saved
function save_custom_movies_fields($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    if (isset($_POST['movie_director'])) {
        update_post_meta($post_id, '_movie_director', sanitize_text_field($_POST['movie_director']));
    }

    if (isset($_POST['movie_actor'])) {
        update_post_meta($post_id, '_movie_actor', sanitize_text_field($_POST['movie_actor']));
    }
}
add_action('save_post', 'save_custom_movies_fields');