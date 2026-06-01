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

/**
 * Resolves and validates the editor configuration.
 *
 * Precedence: the admin setting `tiny_customconfig/config` (valid JSON) wins;
 * otherwise the bundled `defaults.json` is used. All values are type-validated
 * before being returned so malformed admin input can never break the editor.
 *
 * @package    tiny_customconfig
 * @copyright  2026 Vinzenz Leutenegger
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_resolver {

    /** @var string[] Whitelisted keys allowed inside the `extra` pass-through object. */
    private const EXTRA_WHITELIST = [
        'toolbar_mode',
        'toolbar_sticky',
        'statusbar',
        'resize',
        'quickbars_selection_toolbar',
        'quickbars_insert_toolbar',
        'contextmenu',
    ];

    /**
     * Return the validated configuration array to hand to JavaScript.
     *
     * @return array{menubar:bool,link_default_target:?string,removeButtons:string[],addButtons:string[],extra:array}
     */
    public static function resolve(): array {
        $raw = self::read_admin_setting();
        if ($raw === null) {
            $raw = self::read_defaults();
        }
        return self::validate($raw);
    }

    /**
     * Read and decode the admin setting. Returns null when unset or invalid JSON.
     *
     * @return array|null Decoded associative array, or null to trigger fallback.
     */
    private static function read_admin_setting(): ?array {
        $value = get_config('tiny_customconfig', 'config');
        if ($value === false || trim((string) $value) === '') {
            return null;
        }
        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            debugging('tiny_customconfig: admin setting is not valid JSON, falling back to defaults.', DEBUG_DEVELOPER);
            return null;
        }
        return $decoded;
    }

    /**
     * Read and decode the bundled defaults file.
     *
     * @return array Decoded defaults, or an empty-safe structure if the file is missing/broken.
     */
    private static function read_defaults(): array {
        $path = __DIR__ . '/../defaults.json';
        if (!is_readable($path)) {
            debugging('tiny_customconfig: defaults.json is missing or unreadable.', DEBUG_DEVELOPER);
            return [];
        }
        $decoded = json_decode((string) file_get_contents($path), true);
        if (!is_array($decoded)) {
            debugging('tiny_customconfig: defaults.json does not contain valid JSON.', DEBUG_DEVELOPER);
            return [];
        }
        return $decoded;
    }

    /**
     * Coerce arbitrary decoded input into a safe, well-typed configuration array.
     *
     * @param array $raw Decoded configuration of unknown shape.
     * @return array Validated configuration.
     */
    private static function validate(array $raw): array {
        return [
            'menubar' => isset($raw['menubar']) ? (bool) $raw['menubar'] : false,
            'link_default_target' => self::clean_string($raw['link_default_target'] ?? null),
            'removeButtons' => self::clean_string_list($raw['removeButtons'] ?? []),
            'addButtons' => self::clean_string_list($raw['addButtons'] ?? []),
            'extra' => self::clean_extra($raw['extra'] ?? []),
        ];
    }

    /**
     * Return a trimmed string, or null when the value is not a usable string.
     *
     * @param mixed $value Candidate value.
     * @return string|null
     */
    private static function clean_string($value): ?string {
        if (!is_string($value)) {
            return null;
        }
        $value = trim($value);
        return $value === '' ? null : $value;
    }

    /**
     * Keep only non-empty string entries from a list, dropping anything else.
     *
     * @param mixed $value Candidate list.
     * @return string[]
     */
    private static function clean_string_list($value): array {
        if (!is_array($value)) {
            return [];
        }
        $clean = [];
        foreach ($value as $item) {
            if (is_string($item) && trim($item) !== '') {
                $clean[] = trim($item);
            }
        }
        return array_values($clean);
    }

    /**
     * Filter the `extra` object down to whitelisted, safe keys.
     *
     * @param mixed $value Candidate object.
     * @return array
     */
    private static function clean_extra($value): array {
        if (!is_array($value)) {
            return [];
        }
        $clean = [];
        foreach (self::EXTRA_WHITELIST as $key) {
            if (array_key_exists($key, $value) && (is_scalar($value[$key]) || is_null($value[$key]))) {
                $clean[$key] = $value[$key];
            }
        }
        return $clean;
    }
}
