# Mega Menu

Display a mega menu with various flyout options

# Version

1.6.13

## Variants

- `-vertical`, `-show-desktop`, `-show-mobile`, `-show-tablet`

## Beaver Builder Styling Settings

### General Settings

`Text Align`

Aligns the text for the dropdown items on large screens. Mobile is always left align


`Vertical`

Show the menu as stacked (vertical) or horizontal
Mobile is always stacked


`Show Mobile Toggler`

Use this module to show the mobile toggle button


`Menu Display`

Select which screen sizes the menu should be displayed on
- Multi-select
- Show/hide is controlled by frontend.css.php


`Menu Sticky`

Is this a sticky menu on md/large screens
- Default = Yes
- Controlled by js and frontend.css.php


`Show language switcher`

Enables a language switcher with the following options
- Select screen size options
- Set the descriptive text option

Requires Polylang to be active


### Default Theming Settings


`Optional: Set the menu background Colour`

By default the menu background colour is white, but this can be updated
- Colour options available are set using WP admin > Theme Options


`Main Theme (top level link)`

Set the top level nav link theme
- Set theme options using WP admin > Theme Options
- New theme options must be applied to "background"


`Main theme padding (top level link)`

Set the padding of the top level nav items


`Dropdown Item Theme`

Set the default colour of the dropdown items
- Set theme options using WP Admin > Theme Options


`Set the dropdown menu width`

Set the width of the dropdown menu item if it should be a custom size regardless of the parent item width
Medium/Large Screens only


`Set the dropdown menu left margin`

Set the dropdown offset for medium and large screens.


`Set the second level item padding`

Set the padding of the dropdown items. This can be different than the top level


`Set the third level item padding`

Set the padding for the third level dropdown items.


`Dropdown Item Border Color`

Set the dropdown item border colour
All screen sizes unless the mobile border colour is set
- Set border colour options using WP Admin > Theme Options


`Set the standard flyout width`

For flyout menus using bs_card layout, set the flyout width


`Set the recipe card flyout width`

For flyout menus using recipe_crd layout, set the flyout width


### Mobile Theming Settings

`Optional: Set a different colour for the mobile background`

Set a different theme for the mobile layout
- Set theme options using WP admin > Theme Options
- New theme options must be applied to "background"

`Optional: Mobile Dropdown Item Theme`

Set a unique theme for dropdown items if different from top level nav
- Set theme options using WP admin > Theme Options
- New theme options must be applied to "background"


`Mobile: Dropdown Item Border Color`

Set a different border colour for the mobile layout
- Set border colour options using WP admin > Theme Options


## Changelog

- v1.6.13 ( February 27, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Add code to hide enhanced sub-menus when hovering to a different menu item

- v1.6.12 ( February 16, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Add `current-menu-item` to `aria-current` class list to cover top-level menu items

- v1.6.11 ( January 29, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Add aria-expanded to top level menus items that have sub menus
	- Remove sidemenus when menu is closed via escape key

- v1.6.10 ( January 16, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Change what-input mouse interaction to focus instead of hover

- v1.6.9 ( Dec 19, 2022 ) - [Karen](mailto:karen@flowpress.com)
	- Accessibility issues from Sobey's reports
	- Custom navwalker was added the module to enable changes to html without breaking other navigation modules
	- Gulpfile.json, package.json are udpated to include what-input.js
	- Enqueue.php is updated to load what-input

- v1.6.7 ( Nov 30, 2021 ) - [Bart](mailto:bart@flowpress.com)
	- Fix issue with string + int fatal error warning in PHP8

- v1.6.6 ( September 21, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Remove `role` attribute from navbar `<ul>` tag

- v1.6.5 (May 28, 2021)
	- Change dulplicate ACF menu location primary to primary-alt__en

- v1.6.4 (May 14, 2021)
	- Set default display options array.
	- Update gitignore

- v1.6.3 (May 6, 2021)
	- Force tabindex on top level nav items that don't have a URL (accessibility)

- v1.6.2 (April 28, 2021)
	- Equalize height between sidemenu and nav

- v1.6.1 (April 14, 2021)
	- Fix the default styling for the flyout card with image_cover

- v1.6 (April 7, 2021)
	- Fix sticky menu max width, margin and transparency

- v1.5 (April 6, 2021)
	- Fix a bug where the left position for dropdown menu wasn't set

- v1.4 (March 29, 2021)
	- Fix a bug where tabbing out didn't clear the current top level link styling

- v1.3 (March 19, 2021)
	- Add setting for mobile dropdown item theme.
	- Fix the hide/show on mobile when there are only two levels of nav

- v1.2 (March 17, 2021)
	- Fix hide menu on mouseleave for med/large screens and fix the menu flyout positioning when it's past the right window width

- v1.1
	- Fix the mobile hover theming for top level nav items on hover/current

- v1.0
	- Added versioning and updated the variants array.
	- Add ACF fields for recipe card.
	- Add additional nav depth.
	- Reconfigure JS and ACF attributes sections to display both types of flyouts.
	- Allow flyout to switch L-R depending on screen size.
	- Add a number of new MM settings for colours, padding, widths and alignment
