<?php
// Register the widget
function custom_movies_widget_init() {
    register_widget('Custom_Movies_Widget');
}
add_action('widgets_init', 'custom_movies_widget_init');

// Widget class
class Custom_Movies_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'custom_movies_widget',
            __('Movies Widget', 'custom_movies_widget_domain'),
            array('description' => __('Displays movies on the front end', 'custom_movies_widget_domain'))
        ); 
        // Enqueue the custom CSS file
        add_action('wp_enqueue_scripts', array($this, 'enqueue_custom_movies_widget_styles'));
    }

    // Enqueue the custom CSS file
    public function enqueue_custom_movies_widget_styles() {
        wp_enqueue_style('custom-movies-widget', plugins_url('../css/custom-movies-widget.css', __FILE__));
    }

    // Front-end display of the widget
    public function widget($args, $instance) {
        echo $args['before_widget'];
        $title = apply_filters('widget_title', $instance['title']);
        $num_movies = isset($instance['num_movies']) ? absint($instance['num_movies']) : 5;

        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Display movies based on the number of movies specified
        $args = array(
            'post_type' => 'movies',
            'posts_per_page' => $num_movies,
        );
        $movies_query = new WP_Query($args);

        if ($movies_query->have_posts()) {
            echo '<ul>';
            while ($movies_query->have_posts()) {
                $movies_query->the_post();
                $movie_title = get_the_title();
                $movie_image = get_the_post_thumbnail(get_the_ID(), 'medium');
                $movie_director = get_post_meta(get_the_ID(), '_movie_director', true);
                $movie_actor = get_post_meta(get_the_ID(), '_movie_actor', true);

                echo '<li class="movie">';
                echo '<strong>' . $movie_title . '</strong><br>';
                echo $movie_image . '<br>';
                echo 'Director: ' . $movie_director . '<br>';
                echo 'Actor: ' . $movie_actor . '<br>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No movies found.</p>';
        }

        echo $args['after_widget'];
    }

    // Backend form of the widget
    public function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_movies = isset($instance['num_movies']) ? absint($instance['num_movies']) : 0;
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('num_movies'); ?>"><?php _e('Number of Movies to Display:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('num_movies'); ?>" name="<?php echo $this->get_field_name('num_movies'); ?>" type="number" min="1" step="1" value="<?php echo $num_movies; ?>" />
        </p>
<?php
    }

    // Update widget settings
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['num_movies'] = (!empty($new_instance['num_movies'])) ? absint($new_instance['num_movies']) : 5;
        return $instance;
    }
}