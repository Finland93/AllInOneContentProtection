# All in One Content Protection — WordPress Plugin

Deters casual copying of your content. Disable right-click, text selection, copy/cut, image dragging, common keyboard shortcuts and printing — **each toggleable** from a settings page — plus a configurable developer-console warning.

> **Note:** these protections run in the visitor's browser, so a determined user can still get around them. Treat this as a deterrent against casual copying, not as DRM.

## Features

- Settings page under **Settings → Content Protection** with an on/off switch for every protection.
- Disable right-click, selection/copy/cut, image & link dragging.
- Block `Ctrl/Cmd + C/A/U/S/P/X` and dev-tools shortcuts (F12, etc.) — without interfering while typing in a field.
- Print protection.
- Configurable console warning (title + message).
- **Guests only** option so logged-in editors can still work normally.

## What changed in 2.0.0

- **Added the settings page** the old README promised but never shipped — every protection is now toggleable.
- **Proper asset handling.** 1.0 echoed a `<style>` and `<script>` straight into the page; this version enqueues a script and attaches CSS via the stylesheet API.
- **Fewer side effects.** Form fields stay selectable, image links keep working (no more `pointer-events:none`), and shortcut blocking won't fire while you're typing.
- Added a "guests only" mode, a configurable console message, and a clean uninstall.

## Manual use (non-WordPress sites)

You can still drop the equivalent CSS before `</head>` and JS before `</body>` — see the plugin's `assets/protection.js` for the script.

## License

GPLv2 or later — see [LICENSE](LICENSE).

**Author:** [Finland93](https://github.com/Finland93)
