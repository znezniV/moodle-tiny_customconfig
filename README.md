# Custom TinyMCE Configuration (tiny_customconfig)

A small Moodle editor subplugin that customises the **TinyMCE** toolbar and a few
init options — without touching Moodle core, so it survives upgrades safely.

Configuration is editable from the Moodle admin UI (no rebuild required) and is
seeded from a bundled defaults file on install.

- **Plugin type:** `editor_tiny` subplugin (installs at `lib/editor/tiny/plugins/customconfig`)
- **Component:** `tiny_customconfig`
- **Supported Moodle:** 4.1 and later (including 5.x)
- **Licence:** GNU GPL v3 or later

## What it does

Given a JSON configuration, the plugin:

- Removes named toolbar buttons (e.g. alignment, indent/outdent, fullscreen).
- Appends extra toolbar buttons as a dedicated group (e.g. `removeformat`, `code`).
- Hides or shows the menu bar (`menubar`).
- Sets a default target for inserted links (`link_default_target`).
- Optionally passes through a small whitelist of additional TinyMCE init options (`extra`).

> **Button names** come from Moodle's `tiny_*` plugins, not raw TinyMCE. Removing or
> adding a button that isn't present is a safe no-op.

## Configuration

The configuration is a single JSON object. Example (the bundled default):

```json
{
  "menubar": false,
  "link_default_target": "_blank",
  "removeButtons": [
    "indent", "outdent",
    "alignleft", "aligncenter", "alignright", "alignjustify",
    "ltr", "rtl",
    "fullscreen"
  ],
  "addButtons": ["removeformat", "code"],
  "extra": {}
}
```

| Key                   | Type              | Description                                            |
|-----------------------|-------------------|--------------------------------------------------------|
| `menubar`             | boolean           | Show (`true`) or hide (`false`) the menu bar.          |
| `link_default_target` | string \| null    | Default target for inserted links, e.g. `"_blank"`.    |
| `removeButtons`       | string[]          | Toolbar button names to remove.                        |
| `addButtons`          | string[]          | Toolbar button names to append.                        |
| `extra`               | object            | Whitelisted extra TinyMCE init options (see below).    |

**Whitelisted `extra` keys:** `toolbar_mode`, `toolbar_sticky`, `statusbar`,
`resize`, `quickbars_selection_toolbar`, `quickbars_insert_toolbar`, `contextmenu`.
Unknown keys are ignored for safety.

### Where to edit it

**Site administration → Plugins → Text editors → TinyMCE editor → Custom TinyMCE
Configuration.** Paste/edit the JSON and save. Then purge caches (the editor reads
the value at init time):

```
php admin/cli/purge_caches.php
```

If the setting is empty or contains invalid JSON, the plugin logs a developer
debugging message and falls back to the bundled `defaults.json`, so the editor
always loads.

### Automated / infrastructure-as-code provisioning

The setting lives in Moodle config (`tiny_customconfig` / `config`). You can seed or
update it without the UI:

```
php admin/cli/cfg.php --component=tiny_customconfig --name=config \
  --set='{"menubar":false,"addButtons":["code"]}'
```

On a fresh install the value is seeded automatically from `defaults.json`. An
existing value is never overwritten on upgrade.

## Installation

### Option A — Moodle Plugins Directory

Install from **Site administration → Plugins → Install plugins**, or download the
ZIP and upload it.

### Option B — Git clone

```bash
git clone https://github.com/<your-org>/moodle-tiny_customconfig.git \
  /path/to/moodle/lib/editor/tiny/plugins/customconfig
```

### Option C — ZIP

Unzip into `lib/editor/tiny/plugins/customconfig` so that `version.php` sits at the
root of that folder.

Then complete installation:

- Visit **Site administration → Notifications** and follow the upgrade prompt, **or**
- Run `php admin/cli/upgrade.php --non-interactive`.

### Docker (moodle-docker style)

Mount the plugin into the editor plugins directory of your Moodle service, e.g.:

```yaml
volumes:
  - ./moodle-tiny_customconfig:/var/www/html/public/lib/editor/tiny/plugins/customconfig
```

Then run the upgrade once: `docker compose exec moodle php public/admin/cli/upgrade.php --non-interactive`.

## Building the JavaScript

This plugin uses Moodle's **standard Grunt AMD pipeline**. The compiled files in
`amd/build/` are committed. To rebuild after editing `amd/src/`:

```bash
# From the root of a Moodle checkout that contains this plugin:
npm install            # once, installs Moodle's JS dev dependencies
npx grunt amd --root=lib/editor/tiny/plugins/customconfig
```

CI re-runs the build and `grunt --check` to ensure `amd/build/` is in sync with
`amd/src/`. There is no custom build script.

## Privacy

This plugin stores no personal data (it only holds a site-wide editor
configuration). It implements the Moodle null privacy provider.

## Licence

GNU GPL v3 or later — see [LICENSE](LICENSE).
