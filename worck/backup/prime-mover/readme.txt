=== Migrate WordPress Website & Backups - Prime Mover ===
Contributors: codexonics, freemius
Donate link: https://codexonics.com
Tags: migrate wordpress, multisite migration, wordpress backup, multisite, backup, database backup, migration
Requires at least: 4.9.8
Tested up to: 5.6
Requires PHP: 5.6
Stable tag: 1.2.8
License: GPLv3 or later
License URI: https://codexonics.com

The simplest all-around WordPress migration tool/backup plugin. These support multisite backup/migration or clone WP site/multisite subsite.

== Description ==

= Easily Transfer WordPress Site to New Host/Server/Domain =

*   Move single-site installation to another single site server.
*   Move WP single-site to existing multisite sub-site.
*   Migrate subsite to another multisite sub-site.
*   Migrate multisite sub-site to single-site.
*   Migrate within WordPress admin.
*   WordPress backup and restore packages within single-site or multisite.
*   Backup WordPress subsite (in multisite).
*   You can backup the WordPress database within admin before testing something and restore it with one click.
*   Cross-platform compatible (Nginx / Apache / Microsoft IIS / Localhost).
*   Clone single site and restore it to any server.
*   Clone subsite in multisite and restore it as single-site or multisite.
*   Debug package.

https://youtu.be/QAVVXcoQU8g

= PRO Features =

*   Save tons of time during migration with the direct site to site package transfer.
*   Move the backup location outside WordPress public directory for better security.
*   Migrate or backup WordPress multisite main site.
*   Encrypt WordPress database in backups for maximum data privacy.
*   Encrypt WordPress upload files inside backup for better security.
*   Encrypt plugin and theme files inside the backup/package for protection.
*   Export and restore the backup package from Dropbox.
*   Save and restore packages from and to Google Drive.
*   Exclude plugins from the backup (or network activated plugins if multisite).
*   Exclude upload directory files from the backup to reduce the package size.
*   Create a new multisite subsite with a specific blog ID.
*   Disable network maintenance in multisite so only affected subsite is in maintenance mode.
*   One-click button to delete all packages, temporary files, and logs.
*   Option to enable/disable migration and advance logging.
*   Configure migration parameters to optimize and tweak backup/migration packages.
*   It includes all complete restoration options at your own choice and convenience.
*   You can use the settings screen to manage all basic and plugin advanced configurations.

= Documentation =

*	[Prime Mover Documentation](https://codexonics.com/prime_mover/prime-mover/)

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Optionally, opt in to security & feature updates notification including non-sensitive diagnostic tracking with freemius.com. If you skip this, that's okay! Prime Mover will still work just fine.
4. You should see the Prime Mover Control Panel. Click "Go to Migration Tools" to start migrating sites.

== Frequently Asked Questions ==

= What makes Prime Mover unique to other existing migration and backup plugins? =
	
* The free version has no restriction on package size, number of websites, mode of migration (single-site or multisite will work). We don't put that kind of limitation.
* The free version supports full WordPress multisite migration.
* It can backup WordPress multisite sub-sites or migrate multisite.
* No need to delete your WordPress installation, create/delete the database, and all other technical stuff. It will save you a lot of time.
* This is not hosting-dependent. Prime Mover is designed to work with any hosting companies you want to work with.
* The free version has full multisite migration functionality. This feature is usually missing in most migration plugins free version.
* Full versatility - migrating from your localhost, dev site, or from a live site to another live site.
* You will be doing the entire migration inside the WordPress admin. Anyone with administrator access can do it. There is no need to hire a freelancer to do the job - saves you money.
* No messing with complicated migration settings, the free version has no settings on it. Only choose a few options to export and migrate, that's it. 
* You can save, download, delete, and migrate packages using the management page.
* No need to worry about PHP configuration and server settings. Compatible with most default PHP server settings even in limited shared hosting. 
* Prime Mover works with modern PHP versions 5.6 to 8.0
* The code is following PHP-fig coding standards (standard PHP coding guidelines).
* You don't need to worry about setting up users or changing user passwords after migration. It does not overwrite existing site users after migration.

For more common questions, please read the [plugin FAQ listed in the developer site](https://codexonics.com/prime_mover/prime-mover/faq/).

== Screenshots ==

1. Migration Tools
2. Export Options
3. Restoring package zip via Import
4. Control Panel
5. Backups Management page

== Upgrade Notice ==

Update now to get all the latest bug fixes, improvements and features!

== Changelog ==

= 1.2.8 =
  
* Fixed: Different search and replace issues associated with page builders data.
* Fixed: Chunk upload performance issues caused by dirsize_cache.
* Fixed: Unnormalized paths in footprint configuration.
* Fixed: Outdated Freemius SDK library.
* Fixed: Activation errors due to outdated PHP versions.

= 1.2.7 =
  
* Fixed: Incorrect progress tracker values changing during restoration.
* Fixed: Unable to activate plugin properly when wp-config.php is readonly.
* Fixed: Corrupted settings when security keys are changed.

= 1.2.6 =

* Fixed: Removed deprecated zip extension dependency.
* Fixed: Runtime errors caused by cached plugin drop-in files and activated caching.
* Fixed: Added error controls when restoring deprecated zip package.
* Fixed: Runtime error during search replace due to null byte in localized setups.
* Fixed: Runtime error associated with network activated plugin.

See the previous changelogs in changelog.txt
