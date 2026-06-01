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
 * English language strings for the Custom TinyMCE Configuration plugin.
 *
 * @package    tiny_customconfig
 * @copyright  2026 Vinzenz Leutenegger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['config'] = 'Editor configuration (JSON)';
$string['config_desc'] = 'JSON object that customises the TinyMCE editor. Supported keys:
<ul>
<li><code>menubar</code> (boolean) — show or hide the menu bar.</li>
<li><code>link_default_target</code> (string) — default target for inserted links, e.g. <code>"_blank"</code>.</li>
<li><code>removeButtons</code> (array of strings) — toolbar buttons to remove.</li>
<li><code>addButtons</code> (array of strings) — extra toolbar buttons to append.</li>
<li><code>extra</code> (object) — additional whitelisted TinyMCE init options.</li>
</ul>
Button names come from Moodle\'s <code>tiny_*</code> plugins, not raw TinyMCE. If the value is empty or invalid, the bundled defaults are used.';
$string['config_invalid'] = 'The custom configuration is not valid JSON. The bundled defaults are being used instead.';
$string['pluginname'] = 'Custom TinyMCE Configuration';
$string['privacy:metadata'] = 'The Custom TinyMCE Configuration plugin does not store any personal data.';
$string['settings'] = 'Custom configuration';
