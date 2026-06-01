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

namespace tiny_customconfig;

use context;
use editor_tiny\editor;
use editor_tiny\plugin;
use editor_tiny\plugin_with_configuration;

/**
 * TinyMCE Custom Configuration plugin.
 *
 * Overrides TinyMCE toolbar, menubar, and other init options without modifying
 * any Moodle core files. Configuration is resolved in PHP and delivered to the
 * editor through the standard editor_tiny configuration interface.
 *
 * @package    tiny_customconfig
 * @copyright  2026 Vinzenz Leutenegger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugininfo extends plugin implements plugin_with_configuration {
    /**
     * Always enable this plugin.
     *
     * @param context $context The context the editor is used in.
     * @param array $options The options passed to the editor.
     * @param array $fpoptions The filepicker options passed to the editor.
     * @param editor|null $editor The editor instance, if available.
     * @return bool
     */
    public static function is_enabled(
        context $context,
        array $options,
        array $fpoptions,
        ?editor $editor = null
    ): bool {
        return true;
    }

    /**
     * Provide the resolved configuration to the JavaScript layer.
     *
     * @param context $context The context the editor is used in.
     * @param array $options The options passed to the editor.
     * @param array $fpoptions The filepicker options passed to the editor.
     * @param editor|null $editor The editor instance, if available.
     * @return array Data made available to amd/src/options.js.
     */
    public static function get_plugin_configuration_for_context(
        context $context,
        array $options,
        array $fpoptions,
        ?editor $editor = null
    ): array {
        return [
            'config' => config_resolver::resolve(),
        ];
    }
}
