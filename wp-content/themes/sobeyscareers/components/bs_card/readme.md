# BS Card

Display a bootstrap-type card in a loop or as a standalone module

## Variants

- `-border`, `-image-background`, `-theme-light`, `-theme-dark`

## Changelog

- v1.8.0 ( November 18, 2021 ) - [Kaleb](mailto:kaleb@flowpress.com)
	- Restore missing changes
		- Fix date issue where not the right $post was used for module dates (v1.6.1)
		- Add code for meta position (v1.6.2)
		- Convert to inline thumbnail images as ajax requests weren't getting images loaded (v1.6.3)

- v1.7.1 ( Oct 7, 2021 ) - Bart Dabek
	- Added check for background-image so we don't print an empty tag

- v1.7.0 ( Jun 4, 2021 )
	- Major change to bs_card image generation to bring inline in order to work with FacetWP ajax reloading of data vs pull page refresh

- v1.6.0 (2021-05-21)
	- Fix `show_date`, `show_category` and `overlay_only_image` options to use correct boolean labels
	- Add default text link when used as shortcode with `enable_overlay` set to false
		- Add `bs_card_shortcode_text_link_title` filter to allow link text to be set based on post type
	- Code formatting clean-up

- v1.5.0 (April 29, 2021)
	- Fix the overlay opacity issue and auto define term link when a shortcode is used

- v.1.4.0 (March 30, 2021)
	- Remove unnecessary aria roles. Add theme option of "none"

- v.1.3.0 (March 16, 2021)
	- Ajust the image sizing via javascript and depending on the thumbnail size selected

- v1.2.0 (March 11, 2021)
	- Add a check for default image size constant from functions.php

- v1.1.0 (Mar 1, 2021)
	- Clean up module further ( big section of code wasn't used )
	- Add support for for show date
	- Add support for show first term
	- Fix opacity not being applied to overlay
	- Add support for ID being passed in
	- Fix cta target being blank and firing notices
	- Add default thumbnail if image source should be there but is missing

- v1.0.0
	- Added versioning and updated the variants array
	- Update schema
	- Remove nested buttons (a > button)
	- Allow empty aria labels for text
	- Determine if aria-label is required or an use an embedded screen-reader-text element for link accessibility
	- Allow descriptions to have a character cut-off with expand/collapse buttons
	- These will need to be removed where hardcoded in the content
	- New attributes available for facet template/shortcode use: 
		- description_expand(yes|no)
		- description_expand_limit(#)
		- description_expand_icon(default:fas fa-plus-circle)
		- description_hide_icon(default:fas fa-minus-circle)
	- Add settings for `description_padding`, `link_style` (button|text), `link_theme/colour`, `overlay_colour`, `overlay_button_colour`
