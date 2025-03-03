# Breadcrumbs

Display breadcrumb navigation links

## Changelog

- v1.2.6 ( November 14, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Replace single breadcrumb link `ol` wrapper with `div` for a11y
	- Add escaping functions in template

- v1.2.5 ( April 25, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Add filter hooks for breadcrumb item URL/title to allow them to be dynamically changed

- v1.2.4 (2021-05-29)
	- fix ternary if formatting for ICL_LANGUAGE_CODE in template file

- v1.2.3 ( May 20, 2021 )
	- Fix issue with homepage links for fr / en

- v1.2.2 ( Mar 22, 2021 )
	- Removed `max-width` styling on `.breadcrumb` element
	- Reset bootstrap `left-padding` on `.breadcrumb-item` elements
	- Updated CSS selector specificity in frontent.css

- v1.2.1 ( Mar 19, 2021 )
	- Fix issue with non translated post types in breadcrumbs

- v1.2.0
	- Added Separator icon option
	- Updated CSS selector specificity
	- Bug fixes
	- **notes for Tru**:
		- The `breadcrumb_separator` filter is no longer used
		- The `i.icon-arrow-01` element following the "home" breadcrumb has been removed

- v1.1.0
	- Added padding, colour theme, and border options for menu
		- Component Theme Colours for 'a' elements must be set
		- Uses 'Text Colour' and 'Text Hover Colour' for links
		- Uses 'Default Colour' for menu background color
	- Added typography and hover underline settings for links
	- Added color, font size, and left/right margin options for separator

- v1.0.0
	- Init
