FlowPress - Post Importer
====

## Setup 
This plugin needs to be installed on both the host and client servers. The plugin can either be run manually via WP-Admin, automatic via scheduled cron job or via WP-CLI command.

### Host Setup
1. Install and activate the **FlowPress Post Importer** plugin on the host side.
2. Goto WP-Admin > Tools > Post Importer.
3. Under **Site Setup**, select **Host**.
4. Under **Exportable Post Types**, check off each post type you want to allow to be exported to the client site.
5. Click **Save Changes**.
6. Copy the **Host URL** & **API Token** for use when setting up the client site.

### Client Setup
1. Install and activate the **FlowPress Post Importer** plugin on the host side.
2. Goto WP-Admin > Tools > Post Importer.
3. Under **Site Setup**, select **Client**.
4. Click **Save Changes**.
5. Now copy the **Host URL** and **API Token** from the Host Setup into the same fields on this screen.
6. Click **Save Changes**.
7. Now click **Test Connection** button, the page will refresh and you will have the available post types you are able to import, select the ones you would like to include when the importer runs.
8. You can set the importable languages via **WPML Importable Languages** dropdown.
9. Under **Import Schedule** drop down, you can select how often you want the importer to run in the background syncing the posts.
10. Click **Save Changes**. 

## Running the importer

### Manually via WP-Admin
1. On the client site. Goto WP-Admin > Tools > Post Importer.
2. Scroll down to the **Client**.
3. The **Last modified import date**, is the date time stamp of when the importer ran last. The importer will fetch posts with a modified date of this date time or newer. You can manually set the date and time here if you'd like to override when the importer should start pulling posts from.
4. Check off the **Download Attachments**, if you'd like to fetch and download the images from the host site post to the client site.
5. If you would prefer to import a specific list of posts instead of doing a sync, enter the post IDs (from the host site) of the posts you want to insert and click **Run Import Now**.
6. If you'd like to import all posts, ensure the date time is set from when you want it to start from, and click **Run Import Now**. 
7. You will see a running list of the importer running and presenting the results.

### Automatically
1. On the client site. Goto WP-Admin > Tools > Post Importer.
2. Scroll down to the **Import Schedule** and select a frequency other then **Off** and click **Save Changes**.

### WP-CLI
1. In WP-CLI supported console enter:
``` wp post-importer run importer```

## Multi-language Support
- Currently this plugin supports exporting multi-language from the host-side only if the host has the WPML plugin.
- Current it only supports this if the has WPML setup for `Language URL format` in setttings with sub-directories (vs. params), and ensure the default language also has a subdirectory.
- It supports importing on the client side if either WPML or PolyLang plugin is installed.

## Include / Exclude Terms 
You can include or exclude posts on import by creating rules for each post type on the client site.
You can have the plugin filter the posts in the query to the API, instead of the client-side, by setting the `Include Tags Filter Method` to `Client-Side` on the client importer settings. This is useful for initial imports as a large first import could run into timeout problems. Note for this to work, the taxonomy you are querying on the host side must be publicly_queryable.
1. On the client site. Goto WP-Admin > Tools > Post Importer.
2. Scroll down to the **Importable Post Types**.
3. For each post type, you will see two fields, **Include Terms** & **Exclude Terms**.
Format for the include and exclude terms are as follows. Multiple terms can be included for each line separated by a `,`. 
`taxonomy_name1=taxonomy_term1,taxonomy_name1=taxonomy_term1`

i.e. Post type: **recipes** - Include Terms:`recipe_tags=desserts`.
Would only import recipes that have a recipe tag with **desserts**

i.e. Post type: **recipes** - Exclude Terms:`recipe_tags=beverages`.
Would exclude all recipes with the recipe tag with **beverages**.

## Filter Overrides
`fp_post_importer_should_create_terms` - (boolean) - filter to override whether we create terms that don't exist on import.

`fp_post_importer_should_append_terms` - (boolean) - filter to override whether we append terms or overwrite on an post import update.

`fp_post_importer_should_search_replace` - (boolean) - filter to override whether we perform a search and replace on the post_content.


## Changelog
- v3.3.6 - [FP] Update image used for IGA
- v3.3.5 - [FP] Assign a default recipe image on sync if none is set
- v3.3.4 - [dale] Reduce memory consumption by using recursive setTimeout instead of setInterval for admin manual sync; if error add delay in fetchLog() and retry
- v3.3.3 - [dale] Improve manual imports, especially when by post ID. Add clarity in text in admin for manual sync settings (by date or post ids). Minor display updates to log messages display.
- v3.3.2 - [dale] Check for null value on $sitepress variable; use array_chunk for post loop on large post loop array, array_intersect & array_merge for the array_diff to handle large array diffs, and attempts to raise admin memory limit before check_for_existing_posts() runs 
- v3.3.1 - [dale] Update Action Scheduler library to 3.4.1 (was 3.1.6), adds better PHP 8 support + fixes. Tweak scheduler adjustments for performance.
- v3.3.0 - Added support for host site to use Polylang plugin. Added redirect for REST API with language URL prefix for Polylang.
- v3.2.9 - Fix issues with site with only one language not being able to import translated content.
- v3.2.8 - Fix for order of languages, i.e. french is first. Added fix to set the languages for the import based on the host settings and force the order by code.
- v3.2.7 - Fix to not check trash for existing posts.
- v3.2.6 - Added support to delete posts that no longer are tagged on source.
- v3.2.5 - Fix for single language site.
- v3.2.4 - Added support to import post meta by slug match.
- v3.2.3 - Removed local url replacement.
- v3.2.2 - Fixed title for debugging.
- v3.2.1 - Media gallery image support without un-finished check by slug.
- v3.2 - Media gallery image support.
- v3.1 - Fix parent terms not being translated, fixed 503 with WPML sending too many cookies, fixed find by slug duplicate issue, added more debugging notices, fixed mixed translation term issues.
- v3.0 - Added option to skip unmodified posts.
- v2.9 - Fixed original term list output.
- v2.8 - Moved and restructured the original term list.
- v2.7 - Added no tail log option, updated featured image debug messages, fixed taxonomy translatable mismatch.
- v2.6 - Hide meta fields unless authorized. 
- v2.5 - Fixed import progress bar, removed queries from front-end. Fixed thumbnail bug. Fixed duplicate posts bug.
- v2.4 - Added post count validations.
- v2.3 - Fixed cron and taxonomy tagging.
- v2.2 - Added support for hierarchical terms.
- v2.1 - Added more tuning and clearing for imports.
- v2.0 - Added option for email notification on import start.
- v1.9 - Fixed bug for delete, exception and reduced log storage time.
- v1.8 - Updated refresh rate and total job count store.
- v1.7 - Updated to run on action scheduler.
- v1.6 - Fixed duplicate image/attachment issue.
- v1.5 - Fixed cron and CLI with options. Also added options for manual host run import.
- v1.4 - Updated workflow to have long imports run client side. Need to test cron jobs.
- v1.3 - Updated workflow to allow for long running imports, added cancel and timeout support to restart.
- v1.2 - Added support to filter include tags client or host side.
- v1.1 - Added support for Polylang posts and taxonomy translations on client side. Added deletion tracking on host and deletion process on client side.
- v1.09 - fixed minor bugs.
- v1.0.8 - added support for the include/exclude terms feature.
- v1.0.7 - fix php 7.3 regex incompatibility.
- v1.0.6 - fixed bug for notice appearing on admin page.
- v1.0.5 - fixed bug for not properly downloading images for posts.
- v1.0.4 - added support for private posts, fixed bug with title, fixed bug with import post IDS.
- v1.0.3 - fixed bug with post status.
- v1.0.2 - added support to import image attribute data.
- v1.0.1 - added support to import 'draft' status if API token is set.
