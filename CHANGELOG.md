# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-06-01

### Added
- Initial public release as a standalone `editor_tiny` subplugin
  (`tiny_customconfig`), extracted from a private Moodle Docker setup.
- Native admin setting (Site administration → TinyMCE editor → Custom TinyMCE
  Configuration) for editing the editor configuration as JSON, without rebuilding.
- Bundled `defaults.json` that seeds the admin setting on install and is used as a
  fallback when the setting is empty or invalid.
- PHP configuration resolver with type validation, whitelisted `extra` pass-through
  options, and safe fallback (never fatals on malformed admin input).
- TinyMCE overrides: remove/add toolbar buttons, toggle `menubar`, set
  `link_default_target`.
- Standard Grunt AMD build with committed `amd/build/` output (no bespoke build
  script).
- GPLv3 licence, documentation, and Moodle Plugin CI workflow.

[1.0.0]: https://github.com/<your-org>/moodle-tiny_customconfig/releases/tag/v1.0.0
