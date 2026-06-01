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
 * Option registration for the tiny_customconfig plugin.
 *
 * The resolved configuration is provided by PHP via
 * plugin_with_configuration::get_plugin_configuration_for_external() and is
 * exposed under the namespaced option name below.
 *
 * @module      tiny_customconfig/options
 * @copyright   2026 Vinzenz Leutenegger
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getPluginOptionName} from 'editor_tiny/options';
import {pluginName} from './common';

const configOptionName = getPluginOptionName(pluginName, 'config');

/**
 * Register the plugin options with the editor.
 *
 * @param {TinyMCE} editor
 */
export const register = (editor) => {
    editor.options.register(configOptionName, {
        processor: 'object',
        "default": {},
    });
};

/**
 * Read the resolved configuration object for the editor instance.
 *
 * @param {TinyMCE} editor
 * @returns {object}
 */
export const getConfig = (editor) => editor.options.get(configOptionName);
