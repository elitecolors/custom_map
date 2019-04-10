<?php
/**
 * admin page configuration map
 * @param $actions
 * @param $file
 * @return mixed
 */
function map_settings_link($actions, $file)
{
    if (false !== strpos($file, 'prestawp')) {
        $actions['settings'] = '<a href="options-general.php?page=map-coach">' .
            __('Settings', 'custom_map') . '</a>';
    }

    return $actions;
}
add_filter('plugin_action_links', 'map_settings_link', 2, 2);


add_action('admin_menu', 'map_add_admin_menu');


function map_add_admin_menu()
{
    add_options_page(
        'Map-Coach',
        'Map-Coach',
        'manage_options',
        'map-coach',
        'map_options_page'
    );
}

add_action('admin_init', 'map_settings_init');

function map_settings_init()
{
    register_setting('pluginPage', 'map_settings');
    add_settings_section(
        'pswp_pluginPage_section',
        __('Settings', 'custom_map'),
        'map_settings_section_callback',
        'pluginPage'
    );
    add_settings_field(
        'map_key',
        __('Map key:', 'custom_map'),
        'map_key_render',
        'pluginPage',
        'pswp_pluginPage_section'
    );
    add_settings_field(
        'map_zoom',
        __('Zoom:', 'custom_map'),
        'map_zoom_render',
        'pluginPage',
        'pswp_pluginPage_section'
    );
}

/**
 * input text map key
 */
function map_key_render()
{
    $options = get_option('map_settings');
    ?>
    <input type='text' name='map_settings[map_key]' value='<?php echo $options['map_key']; ?>' class="regular-text code active">
    <?php
    echo __('GET KEY FROM GOOGLE API', 'custom_map');
}

/**
 * input text zoom map
 */
function map_zoom_render()
{
    $options = get_option('map_settings');
    ?>
    <input type='text' name='map_settings[map_zoom]'
           value='<?php echo $options['map_zoom']; ?>' class="regular-text code active">
    <?php echo __('SET NUMBER', 'custom_map'); ?>
    <?php
}
function map_settings_section_callback()
{
//	echo __('This section description', 'custom_map');
}


function map_options_page()
{
    ?>
    <form action='options.php' method='post'>

        <h2><?php echo __('Map configuration:', 'custom_map'); ?> </h2>
        <?php
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        ?>

        <h3><?php echo __('Shortcodes', 'custom_map'); ?>:</h3>
        <ol>
            <li>
            <?php echo __('[widget widget_name="wpb_widget"]', 'custom_map'); ?>    

            </li>
           
        </ol>
       
    </form>
    <?php
}

?>