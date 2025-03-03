# FP Slider

Custom slick slider implementation for FP Foundation framework

## Available Shortcodes
- fp_slide
- fp_slider

## Changelog

- v2.4.8 ( January 29, 2023 ) - [Kaleb](https://github.com/brainfork)
	- Prevent slides from taking focus on change if the slider doesn't already have focus.

- v2.4.7 ( December 22, 2022 ) - [Kaleb](https://github.com/brainfork)
	- Enable `focusOnChange` setting in slick slider config

- v2.4.6 ( September 26, 2022 ) - [FP](https://github.com/flowpress)
	- Increase max slide count to 20

- v2.4.5 ( March 08, 2022 ) - [Kaleb](https://github.com/brainfork)
	- Resolve PHP warnings when slide post styling isn't set in slider module `frontend.css.php`

- v2.4.4 ( November 30, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Remove unused `slide_list` attribute from `[fp_accordion_slider]` shortcode attributes

- v2.4.3 ( November 09, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Change slide heading/description sanitization output to `wp_kses_post()` to allow HTML code output

- v2.4.2 ( November 08, 2021 ) - [TRU]
	- Added filter for slider image media min-width and max-width

- v2.4.1 ( November 04, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Replace play/pause `alt` attribute with `aria-label`

- v2.4.0 ( October 07, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Merge Accordion Slider module into plugin
	- [WPCS](https://make.wordpress.org/core/handbook/best-practices/coding-standards/) refactoring
	- Add functionality to dynamically change play/pause button `alt` text

- v2.3.3 ( October 01, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Add `alt` text to slider play/pause button
	- Added ID to each slide image

- v2.3.2
	- Add image width and height to satify google page insights

- v2.3.1 ( July 27, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Add `overflow: hidden;` to slider module container
	- Add fallback for mobile srcset if `wp_get_attachment_image_srcset()` returns false

- v2.3.0 ( July 22, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Add options for arrow button width/height & font size
	- Adjust arrow button/icon positioning to center better
	- Add dot hover styling to match active styling
	- Fix responsive styling settings for slide peek, slide margin, heading margin, and description margin
	- Remove `show_alpha` setting from color pickers, can't be set in module settings

- v2.2.0 (2021-07-09) - [Kaleb](https://github.com/brainfork)
	- Add Slide peek margin option and remove default `margin-right` styling from slides
	- Add Text Box content alignment option
	- Fix Text Box padding option responsive styling settings
	- Invert Play/Pause button based symbol based on slider autoplay status

- v2.1.0 (2021-06-29)
	- Add option to display slick dots below or as overlay on slider
	- Add option to change dot type to lines or dots
	- Add option to show autoplay play/pause button when slick dots are shown below slider
	- Add option to enable slide peek on previous slide
	- Update default slick arrow styling to use unicode CSS codes to prevent unexpected icons from being displayed by default
	- Move slick event functions above initialization to ensure they're triggered as expected

- v2.0.3 (2021-05-21)
	- Add space before `with-text-bg` class

- v2.0.2
	- Add `alt` text from media to slide images

- v2.0.1
	- Update a11y tab navigation functions

- v2.0.0
	- Merged `slider` module into plugin
	- Replaced enquire.js dependancy with `srcset` implementation
	- Added global module styling options as fallback for slide-specific options
	- Refactored template markup
	- Refactored Sass styling
	- **notes:**
		- Moving the `slider` module is a breaking change, all instances of the module will need to be regenerated
		- The enqueued `script requires `fp-embed-video` from a FP Foundation theme

- v1.0.0
	- Init
