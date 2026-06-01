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
 * Install-time setup for the Custom TinyMCE Configuration plugin.
 *
 * @package    tiny_customconfig
 * @copyright  2026 Vinzenz Leutenegger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Seed the editor configuration from the bundled defaults on fresh install.
 *
 * Only seeds when no value is stored yet, so re-installs never clobber an
 * administrator's customised configuration.
 *
 * @return bool
 */
function xmldb_tiny_customconfig_install(): bool {
    $existing = get_config('tiny_customconfig', 'config');
    if ($existing !== false && trim((string) $existing) !== '') {
        return true;
    }

    $defaultspath = __DIR__ . '/../defaults.json';
    if (is_readable($defaultspath)) {
        $defaults = (string) file_get_contents($defaultspath);
        if (trim($defaults) !== '') {
            set_config('config', $defaults, 'tiny_customconfig');
        }
    }

    return true;
}
