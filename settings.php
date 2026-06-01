<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Admin settings for the Custom TinyMCE Configuration plugin.
 *
 * @package    tiny_customconfig
 * @copyright  2026 Vinzenz Leutenegger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $default = '';
    $defaultspath = __DIR__ . '/defaults.json';
    if (is_readable($defaultspath)) {
        $default = (string) file_get_contents($defaultspath);
    }

    $settings->add(new admin_setting_configtextarea(
        'tiny_customconfig/config',
        get_string('config', 'tiny_customconfig'),
        get_string('config_desc', 'tiny_customconfig'),
        $default,
        PARAM_RAW,
        75,
        20
    ));
}
