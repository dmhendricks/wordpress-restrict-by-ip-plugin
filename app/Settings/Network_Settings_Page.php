<?php
namespace CloudVerve\RestrictByIP\Settings;
use CloudVerve\RestrictByIP\Plugin;
use CloudVerve\RestrictByIP\Helpers;
use Carbon_Fields\Datastore\Datastore\Serialized_Theme_Options_Datastore;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
  * A class to create a settings page in Network Admin
  *
  * @link https://carbonfields.net/docs/containers-network/ Carbon Fields Network Container
  * @since 0.5.0
  */
class Network_Settings_Page extends Plugin {

  public function __construct() {

    $remote_addr = $_SERVER['REMOTE_ADDR'];
    //var_dump( $this->is_ipv4( $remote_addr ) );

    $notices[] = '<strong>Important:</strong> If you are the administrator and completely lock yourself out, you can temporarily disable this plugin by adding <tt>define( \'RBIP_DISABLE_PLUGIN\', true );</tt> to your <tt>wp-config.php</tt>.';
    if( $this->is_ipv4( $remote_addr ) ) $notices[] = sprintf( "You can also whitelist your IP addresses in <tt>wp-config.php</tt> by adding your <a href=\"%s\" target=\"_blank\">public</a> IP: <tt>define( 'RBIP_WHITELIST', [ '%s' ] )</tt>", 'https://ipinfo.io/ip', $remote_addr );
    if( $this->is_plugin_admin_page() ) Helpers::show_notice( implode( '<br />', $notices ), [ 'type' => 'error', 'dismissible' => false ] );

    // Flush the cache when settings are saved
    add_action( 'carbon_fields_network_container_saved', array( $this, 'options_saved_hook' ) );

    // Create tabbed plugin options page (Settings > Plugin Name)
    $this->create_network_options_page();

  }

  /**
    * Create network options/settings page in WP Network Admin > Settings > Global Settings
    *
    * @since 0.5.0
    */
  public function create_network_options_page() {

    Container::make( 'network', self::$config->get( 'network/default_options_container' ), __( 'Restrict by IP', self::$textdomain ) )
      ->set_page_parent( 'settings.php' )
      ->add_tab( __( 'General', self::$textdomain ), array(
        Field::make( 'html', $this->prefix( 'html_network_general_lockout_help' ) )
          ->set_html( '<h3>Important!</h3><p>If you are the administrator and completely lock yourself out, you can temporarily disable this plugin via (S)FTP by renaming its directory in <tt>wp-content/plugins</tt>. Be careful, as this is a last resort.</p>' ),
        Field::make( 'separator', $this->prefix( 'placeholder_network_general_features' ), __( 'Features', self::$textdomain ) ),
        Field::make( 'checkbox', $this->prefix( 'enabled' ), __( 'Enabled', self::$textdomain ) )
          ->set_default_value( true )
          ->help_text( __( 'Untick this checkbox is you need to temporarily disable IP blocking.', self::$textdomain ) ),
        Field::make( 'checkbox', $this->prefix( 'enable_subsites' ), __( 'Enable Sub-Site Interface', self::$textdomain ) )
          ->help_text( __( 'Ticking this checkbox will allow sub-sites to define additional IP restriction rules. Global rules always take precedence.', self::$textdomain ) ),
        Field::make( 'checkbox', $this->prefix( 'enable_backend' ), __( 'Enable Rules for WP Admin', self::$textdomain ) )
          ->help_text( __( 'Ticking this checkbox will allow blocking rules to apply to the backend/WP Admin as well.<br /><strong>DANGER:</strong> Make sure you add yourself to the whitelist first so you don\'t accidentally lock yourself out!', self::$textdomain ) ),
        Field::make( 'checkbox', $this->prefix( 'enable_softblock' ), __( 'Enable Soft Blocking', self::$textdomain ) )
          ->set_default_value( true )
          ->help_text( sprintf( __( '"Soft blocking" is used when the visitor\'s IPv4 address isn\'t passed. It is not fool proof as it can be defeated by disabling JavaScript. It is also ignored by search indexes/bots. That said, it is a good idea to keep it enabled as a fallback. It is possible that it has <a href="%s" target="_blank">GDPR</a> ramifications since it collects the visitor\'s IPv4 address via a <a href="%s" target="_blank">third-party service</a>, sponsored by <a href="%s" target="_blank">Alpha Geek Solutions</a>. This IPv6 to IPv4 resolution is cached by WordPress for performance reasons. If you are concerned, you can block the <a href="%s" target="_blank">subnets</a> of European countries.', self::$textdomain ), 'https://en.wikipedia.org/wiki/General_Data_Protection_Regulation', 'https://seeip.org/', 'https://agsllc.us/', 'https://www.ip2location.com/free/visitor-blocker' ) ),
        Field::make( 'checkbox', $this->prefix( 'block_all_ipv6' ), __( 'Block All IPv6 Addresses', self::$textdomain ) )
          ->help_text( __( '<strong>DANGER:</strong> This will block all visitors that report an IPv6 address. <strong>It is not recommended that you enable this feature</strong> unless you know that your allowed visitors will always be reporting an IPv4 address (for example, a company Intranet where IPv6 is not enabled for employee workstations). Unless you know what you\'re doing, it is <strong>not</strong> recommended if your web host has IPv6 enabled.', self::$textdomain ) ),
        Field::make( 'separator', $this->prefix( 'placeholder_network_general_behavior' ), __( 'Behavior', self::$textdomain ) ),
        Field::make( 'radio', $this->prefix( 'block_action' ), __( 'Blocked Access Action', self::$textdomain ) )
          ->add_options( array(
            'die' => __( 'Display Notification Message', self::$textdomain ),
            'redirect' => __( 'Redirect to Link:', self::$textdomain )
          ))
          ->set_classes( 'carbon-fields-custom-radio-horizontal' ),
        Field::make( 'text', $this->prefix( 'redirect_url' ), __( 'Redirect Link', self::$textdomain ) )
          ->set_attribute( 'type', 'url' )
          ->set_attribute( 'placeholder', site_url( 'blocked/' ) )
          ->set_classes( 'carbon-fields-custom-field-url' )
          ->help_text( __( 'The link to redirect blocked visitors to if soft blocking or redirect are enabled.', self::$textdomain ) ),
        Field::make( 'checkbox', $this->prefix( 'add_403_header' ), __( 'Add "403 Forbidden" Header for Blocked Visitors', self::$textdomain ) )
          ->set_conditional_logic( [ [
            'field' => $this->prefix( 'block_action' ),
            'value' => 'die',
            'compare' => '='
          ]])
          ->help_text( __( 'This notifies bots that access is forbidden to help prevent further visits. Disabled when soft block occurs.', self::$textdomain ) ),

      )
    )
    ->add_tab( __( 'Rules', self::$textdomain ), array(
      Field::make( 'separator', $this->prefix( 'temp_separator' ), __( 'Temporary Separator', self::$textdomain ) )
    ))
    ->add_tab( __( 'Sub-Sites', self::$textdomain ), array(
      Field::make( 'separator', $this->prefix( 'temp_separator2' ), __( 'Temporary Separator', self::$textdomain ) )
    ));

  }

  /**
    * Logic that is run when settings are saved.
    */
  public function options_saved_hook() {

    // Clear the cache so that new settings are loaded
    self::$cache->flush();

  }

}
