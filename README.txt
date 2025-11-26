=== PackIt Plugin & Theme Exporter ===
Contributors: hugoc, meaowsdev
Donate link: https://vizuh.com/
Tags: export, plugins, themes, backup, zip, download, migration
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily package and export your WordPress plugins and themes with a single click.

== Description ==

**PackIt Plugin & Theme Exporter** is a powerful and intuitive tool that allows you to export installed plugins and themes directly from the WordPress admin panel. Perfect for backups, migrations, and sharing custom development.

**Key Features:**

* ✅ **Intuitive Interface** - Clean and easy-to-use admin panel
* ✅ **One-Click Export** - Export any plugin or theme instantly
* ✅ **Automatic ZIP Files** - Automatically creates ZIP files with timestamps
* ✅ **Real-Time Search** - Filter plugins and themes quickly
* ✅ **Status View** - See which plugins/themes are active
* ✅ **Safe and Reliable** - Follows WordPress security best practices
* ✅ **Fully Bilingual** - Available in English and Brazilian Portuguese

**Use Cases:**

* Create backups of specific plugins and themes
* Migrate plugins between different WordPress sites
* Share custom development with clients
* Archive specific versions for version control
* Package custom work for delivery

Developed with ❤️ by [Vizuh](https://vizuh.com/).

== Installation ==

**Automatic Installation:**

1. Access the WordPress admin panel
2. Go to "Plugins" > "Add New"
3. Search for "PackIt Plugin & Theme Exporter"
4. Click "Install Now"
5. Activate the plugin
6. Access "Tools" > "Export Plugins/Themes"

**Manual Installation:**

1. Download the plugin ZIP file
2. Go to "Plugins" > "Add New" > "Upload Plugin"
3. Choose the ZIP file and click "Install Now"
4. Activate the plugin after installation

**Via FTP:**
1. Upload the `packit-plugin-theme-exporter` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the "Plugins" menu in WordPress

== Frequently Asked Questions ==

= How do I use PackIt? =

After activation, go to "Tools" > "Export Plugins/Themes" in the admin panel. Choose the "Plugins" or "Themes" tab, find the item you want to export, and click "Export". The ZIP file will be downloaded automatically.

= Where are the exported files saved? =

ZIP files are downloaded directly to your browser's downloads folder when you click the "Export" button. The plugin creates a temporary file on the server that is automatically deleted after download.

= What format is the exported file? =

Files are exported as ZIP archives with automatic naming in the format: `item-name_YYYY-MM-DD_HH-MM-SS.zip`

= Can I export multiple plugins at once? =

Currently, PackIt exports one plugin or theme at a time. To export multiple items, you need to click "Export" for each one individually.

= Does the plugin work with child themes? =

Yes! PackIt exports any installed theme, including child themes and custom themes.

= Is there a size limit for exports? =

The limit depends on your PHP server configuration (memory and execution time). Very large plugins and themes may take longer to compress.

= Does the plugin require special PHP extensions? =

Yes, PackIt requires the PHP ZipArchive extension to create ZIP files. Most modern servers already have this extension enabled. The plugin checks this during activation.

= Is it safe to use PackIt? =

Yes! PackIt follows WordPress security best practices, including nonce verification, user permission validation, input sanitization, and output escaping.

== Screenshots ==

1. Main interface showing the plugins list
2. Themes tab with search functionality
3. Confirmation before exporting a plugin

== Changelog ==

= 1.0.0 =
* 🎉 Initial release of PackIt Plugin & Theme Exporter
* ✨ One-click plugin export
* ✨ One-click theme export
* 🔍 Real-time search and filtering
* 🎨 Modern and intuitive interface
* 🌐 Bilingual support (English and Brazilian Portuguese)
* 🔒 Security with nonce verification and permissions
* 📦 Automatic ZIP files with timestamp

== Upgrade Notice ==

= 1.0.0 =
Initial version of PackIt Plugin & Theme Exporter. Install now and start exporting your plugins and themes!