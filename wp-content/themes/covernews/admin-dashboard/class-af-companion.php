<?php
if (!class_exists('AFcompanion')) {
  class AFcompanion
  {
    private $theme_name;

    private $current_user_name;
    private $theme_version;
    private $menu_name;
    private $page_name;
    private $page_slug;

    /**
     * Theme slug.
     *
     * @var string $theme_slug Theme slug.
     *
     * @since 1.0.0
     */
    private $theme_slug;
    private $config;
    const templatespare_old_version = '2.0.0';
    public function __construct()
    {
      $theme = wp_get_theme();
      $this->theme_name = $theme->get('Name');
      $this->theme_version = $theme->get('Version');
      $this->theme_slug = $theme->get_template();
      $this->menu_name = isset($this->config['menu_name']) ? $this->config['menu_name'] : $this->theme_name;
      $this->page_name = isset($this->config['page_name']) ? $this->config['page_name'] : $this->theme_name;
      $this->page_slug = $this->theme_slug;
      add_action('admin_menu', array($this, 'covernews_companion_admin_menu'));
      add_action('init', array($this, 'covernews_companion_dashboard_function'));
    }
    public function covernews_companion_admin_menu()
    {
     

      add_submenu_page(
        $this->theme_slug, // Parent slug.
        __('Speed Booster', 'covernews'), // Page title.
        __('Speed Booster', 'covernews'), // Menu title.
        'manage_options', // Capability.
        'af-speed', // Menu slug.
        array($this, 'covernews_companion_dashboard_function'), // Callback function.
        7
      );
      add_submenu_page(
        $this->theme_slug, // Parent slug.
        __('Growth Tools', 'covernews'), // Page title.
        __('Growth Tools', 'covernews'), // Menu title.
        'manage_options', // Capability.
        'af-growth', // Menu slug.
        array($this, 'covernews_companion_dashboard_function'), // Callback function.
        8
      );
      add_submenu_page(
        $this->theme_slug, // Parent slug.
        __('System Status', 'covernews'), // Page title.
        __('System Status', 'covernews'), // Menu title.
        'manage_options', // Capability.
        'af-status', // Menu slug.
        array($this, 'covernews_companion_dashboard_function'), // Callback function.
        11
      );
    }

    public function covernews_companion_dashboard_function()
    {

      $current_page = isset($_GET['page']) ? $_GET['page'] : 'af-companion';

      $covernews_companion_installed = covernews_get_plugin_file('af-companion');
      $covernews_companion_verison = '';

      if (!empty($covernews_companion_installed)) {
        $covernews_companion_info = get_plugin_data(WP_PLUGIN_DIR . '/' . $covernews_companion_installed);
        $covernews_companion_verison = $covernews_companion_info['Version'];
      }

      $covernews_companion_active = is_plugin_active('af-companion/af-companion.php');
      $install = [];
      $activate = [];
      if ($covernews_companion_installed == null) {
        $install = ['af-companion'];
      }

      if ($covernews_companion_active == false && $covernews_companion_installed != null) {
        $activate = ['af-companion'];
      }
      $plugin_update = 'false';
      if (!empty($covernews_companion_verison) && $covernews_companion_verison < self::templatespare_old_version) {
        $plugin_update = 'true';
      }

      if (($covernews_companion_installed && $covernews_companion_active) && $plugin_update == 'false') { ?>

        <div id="covernews-af-companion"></div>
      <?php
      } else {
        wp_enqueue_style('templatespare');
        $message = '';


        if (!empty($covernews_companion_verison) && $covernews_companion_active && $covernews_companion_verison < self::templatespare_old_version) {
          $class = admin_url('plugins.php');

          $message = __('The AF Companion plugin should be updated to the latest version', 'covernews');
        } else {

          $class = 'false';
          $message = __('Performance Optimization, and Publisher Growth Tools!', 'covernews');
        }

      ?>
        <div id="templatespare-plugin-install-activate" data-class=<?php echo $class; ?> current-theme='af-companion' install=<?php echo json_encode($install); ?> activate=<?php echo json_encode($activate); ?> data-plugin-page="<?php echo esc_attr($current_page); ?>" message='<?php echo $message; ?>' ispro=''></div>
<?php
      }
    }
  }

  $aft_dashboard = new AFcompanion;
}
