# Simple Card Row

Cards with Background Image, Heading and Button

## Changelog

- v1.1.5 ( Apr 8, 2022 ) - [Bart](https://github.com/raptor235)
    - Fix issues with generate_theme self overwriting its own variable causing a fatal error when in use

- v1.1.4 ( March 16, 2022 ) - [Kaleb](https://github.com/brainfork)
	- Remove duplicate `generate_theme()` that caused PHP fatal error

- v1.1.3 ( March 14, 2022 ) - [Kaleb](https://github.com/brainfork)
	- Add additional `empty()` check for `$calloutItem->callout_button_theme` in `frontend.css.php` to catch if `generate_theme()` functions returns an empty value 

- v1.1.2
	- Update CSS selector specificity

- v1.1.1
	- Add Card overlay padding options

- v1.1.0
	- Replaced Heading font size option with typography options
	- Added Description typography options
	- Added Callout Text background colour option
	- Added Breakpoint display variant option
	- Added FLBuilderCSS rules for Callout button theme optins
	- Code clean up
	- **notes for Tru:**
		- Custom styling for any options mlisted above has been removed, use options to set these
		- `-compact` variant renders heading/description & button on single line from `sm` breakpoint down, defaults to stacked rendering from `$extra-small-screen` breakpoint down

- v1.0.0
	- Init
