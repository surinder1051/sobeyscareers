# Social Share

Display links for social media sharing

## Changelog

- v1.3.2 (2021-04-28)
	- Encode email share body for URL string

- v1.3.1 (2021-04-09)
	- Translation fixes

- v1.3.0 (2021-04-07)
	- Add "Vertical" variant
	- Add multi-select Services option to select which services to display
		- Add options for sharing to LinkedIn and Pinterest
		- Show Print option has been merged into Services selection

- v1.2.1 (2021-04-01)
	- Fix Share button theme styling bugs

- v1.2.0
	- Add option for button size
		- Sets value for button width, height & line-height
		- Sets font-size for print icon, if visible
	- Add option for button spacing
		- Sets value for button padding-left
		- Sets margin-left for print button, if visible
	- Add option for icon font size
	- Code clean-up
	- **notes for Tru**:
		- Breakpoint styling for any options mentioned above has been removed, should be configured using module settings
		- Default module border styling has been removed, configure in module settings

- v1.1.0
	- Add typography options for module title
	- Add options for module padding
	- Add options for module border
	- Add option for `-compact` variant
	- Update template aria-labels with new window notice
	- Code formatting clean-up
	- **notes for Tru**:
		- Styles for `.fl-modiule-social_share.no_border_padding`, `.fl-module-social_share.collection`, `.fl-module-social_share.text-left`, `.single-recipe .fl-module-social_share .component_social_share` & `.single-article .fl-module-social_share .component_social_share` classes have been removed, should be configured using module settings
		- Conflicting `height` attribute on `.component_social_share` has been set to `inherit`
		- Default font on `.component_social_share .title` set to Open Sans bold, can be changed in module settings
		- Styling for `button` has been removed, no buttons are used in template file

- v1.0.0
	- Init