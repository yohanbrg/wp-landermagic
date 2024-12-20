<?php
/*
Plugin Name: LanderMagic
Description: Install LanderMagic magic script on your website
Version: 1.0.0
Author: LanderMagic
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function landermagic_add_settings_menu()
{
    add_options_page(
        'LanderMagic Settings',         
        'LanderMagic',                  
        'manage_options',               
        'landermagic-settings',         
        'landermagic_render_settings_page'
    );
}
add_action('admin_menu', 'landermagic_add_settings_menu');

function landermagic_render_settings_page()
{
?>
    <div class="wrap">
        <h1>LanderMagic Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('landermagic_settings_group');
            do_settings_sections('landermagic-settings');
            submit_button();
            ?>
        </form>
        <div style="width: 50%; height: 500px; background-image: url('<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/landermagic_screen_project.png'); ?>'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </div>
    </div>
<?php
}

function landermagic_register_settings()
{
    register_setting('landermagic_settings_group', 'landermagic_project_id');

    add_settings_section(
        'landermagic_main_settings',
        'Main Settings',
        null,
        'landermagic-settings'
    );

    add_settings_field(
        'landermagic_project_id',
        'Project ID',
        'landermagic_project_id_field',
        'landermagic-settings',
        'landermagic_main_settings'
    );
}
add_action('admin_init', 'landermagic_register_settings');

function landermagic_project_id_field()
{
    $value = get_option('landermagic_project_id', '');
    echo '
    <input type="text" name="landermagic_project_id" value="' . esc_attr($value) . '" style="width: 100%;" placeholder="Enter your LanderMagic Project ID">
    <p>LanderMagic Project ID can be found in the settings page of your LanderMagic project</p>
    ';
}



function landermagic_enqueue_dynamic_script()
{
    if (!is_admin()) {
        $project_id = get_option('landermagic_project_id', '');

        if (!empty($project_id)) {
            $script_url = 'https://app.landermagic.com/storage/keywords/' . esc_attr($project_id) . '/replacer.js';

            wp_enqueue_script('landermagic-script', $script_url, array(), "1.0.0", false);
        }
    }
}
add_action('wp_enqueue_scripts', 'landermagic_enqueue_dynamic_script', 1);
