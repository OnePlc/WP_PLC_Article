<?php
/**
 * Plugin reset and uninstall cleanup.
 *
 * @package   OnePlace\Article
 * @copyright 2020 Verein onePlace
 * @license   https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html GNU General Public License, version 2
 * @link      https://1plc.ch
 */

namespace OnePlace\Article;

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' )) {
    die;
}

$aOptions = [
];

foreach($aOptions as $sOption) {
    // delete option
    delete_option($sOption);
    // for site options in Multisite
    delete_site_option($sOption);
}
