<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Restrict by IP
 * Plugin URI:        https://github.com/cloudverve/restrict-by-ip/
 * Description:       Restrict access to site(s), pages and/or content access by IP address or subnet mask.
 * Version:           0.5.1
 * Author:            Daniel M. Hendricks
 * Author URI:        https://www.danhendricks.com
 * License:           GPL-2.0
 * License URI:       https://opensource.org/licenses/GPL-2.0
 * Text Domain:       restrict-by-ip
 * Domain Path:       languages
 * GitHub Plugin URI: cloudverve/restrict-by-ip
 */

/*	Copyright 2018	  Daniel M. Hendricks (https://www.danhendricks.com/)

		This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if( !defined( 'ABSPATH' ) ) die();

require( __DIR__ . '/vendor/autoload.php' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Initialize plugin
new \CloudVerve\RestrictByIP\Plugin();
