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
 * TinyMCE configuration overrides for Moodle, driven by the resolved plugin config.
 *
 * Moodle calls configure() with the current instance config and merges the
 * returned object into the TinyMCE init. The custom configuration (resolved in
 * PHP from the admin setting or bundled defaults) is read from the same instance
 * config under the namespaced option name, so values stay in sync with options.js.
 *
 * Do NOT set `selector` or `plugins` here — those are managed by Moodle.
 *
 * @module      tiny_customconfig/configuration
 * @copyright   2026 Vinzenz Leutenegger
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {getPluginOptionName} from 'editor_tiny/options';
import {pluginName} from './common';

const configOptionName = getPluginOptionName(pluginName, 'config');

/**
 * Read the resolved custom configuration.
 *
 * Moodle merges the namespaced plugin options into `instanceConfig` only AFTER
 * every plugin's configure() has run, so at this point the value is not yet on
 * `instanceConfig`. The raw plugin data is available on the `options` argument
 * instead, under `options.plugins[pluginName].config` — which is exactly what
 * PHP returned from get_plugin_configuration_for_context(), i.e. `{config: {...}}`.
 *
 * @param {object} instanceConfig
 * @param {object} options
 * @returns {object}
 */
const readCustomConfig = (instanceConfig, options) => {
    const pluginData = options && options.plugins ? options.plugins[pluginName] : undefined;
    const fromOptions = pluginData && pluginData.config ? pluginData.config.config : undefined;
    if (fromOptions && typeof fromOptions === 'object') {
        return fromOptions;
    }

    // Defensive fallback: if the namespaced value is already present (e.g. a
    // future ordering change), read it from instanceConfig.
    const value = instanceConfig ? instanceConfig[configOptionName] : null;
    return (value && typeof value === 'object') ? value : {};
};

/**
 * Produce a new toolbar with the configured buttons removed and added.
 *
 * Returns a new structure without mutating the incoming toolbar.
 *
 * @param {Array|*} toolbar The current toolbar configuration.
 * @param {string[]} removeButtons Button names to strip.
 * @param {string[]} addButtons Button names to append as a new group.
 * @returns {Array|*}
 */
const transformToolbar = (toolbar, removeButtons, addButtons) => {
    if (!Array.isArray(toolbar)) {
        return toolbar;
    }

    let result = toolbar.map((group) => {
        if (group && Array.isArray(group.items)) {
            return {
                ...group,
                items: group.items.filter((item) => !removeButtons.includes(item)),
            };
        }
        return group;
    });

    result = result.filter((group) => !group || !Array.isArray(group.items) || group.items.length > 0);

    if (addButtons.length > 0) {
        result = [...result, {name: 'customtools', items: addButtons}];
    }

    return result;
};

/**
 * Return the configuration options to merge into TinyMCE's init.
 *
 * @param {object} instanceConfig The current TinyMCE instance configuration.
 * @param {object} options The editor options, including per-plugin config data.
 * @returns {object} Options to merge in.
 */
export const configure = (instanceConfig, options) => {
    const custom = readCustomConfig(instanceConfig, options);
    const removeButtons = Array.isArray(custom.removeButtons) ? custom.removeButtons : [];
    const addButtons = Array.isArray(custom.addButtons) ? custom.addButtons : [];
    const extra = (custom.extra && typeof custom.extra === 'object') ? custom.extra : {};

    const result = {
        ...extra,
        toolbar: transformToolbar(instanceConfig.toolbar, removeButtons, addButtons),
    };

    if (typeof custom.menubar === 'boolean') {
        result.menubar = custom.menubar;
    }
    if (typeof custom.link_default_target === 'string') {
        // eslint-disable-next-line camelcase
        result.link_default_target = custom.link_default_target;
    }

    return result;
};
