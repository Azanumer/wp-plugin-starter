# WP Plugin Starter

A minimal, secure, object-oriented WordPress plugin boilerplate. Copy it, rename it, ship your plugin.

## What's inside

| File | Purpose |
|---|---|
| `wp-plugin-starter.php` | Main file: singleton class, Settings API page, `[mps_hello]` shortcode, activation/deactivation hooks |
| `uninstall.php` | Deletes the plugin's own options on full uninstall |

## Built-in best practices

- `ABSPATH` guard on every file
- All input sanitized (`sanitize_text_field`, `wp_unslash`), all output escaped (`esc_html`, `esc_attr`)
- Capability check (`manage_options`) on the settings page
- `flush_rewrite_rules()` on activation **and** deactivation
- Full data cleanup on uninstall

## Use it

1. Rename the folder + files (`my-plugin`), replace `My_Plugin_Starter`, `mps_` prefix and text domain.
2. Zip the folder and upload via **Plugins → Add New → Upload**.
3. Activate, then configure under **Settings → My Plugin**.

MIT licensed.
