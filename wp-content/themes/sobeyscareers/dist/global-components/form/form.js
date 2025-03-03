(function($) {
	'use strict';


	var MAIN_CLASS = 'lbjs';
	var LIST_CLASS = 'lbjs-list';
	var LIST_ITEM_CLASS = 'lbjs-item';
	var SEARCHBAR_CLASS = 'lbjs-searchbar';

	function inherits(constructor, superConstructor) {
		constructor.prototype = Object.create(superConstructor.prototype);
		constructor.prototype.constructor = constructor;
		constructor.prototype.super_ = superConstructor;
	}

	function Listbox(domelement, options) {
		var settings = jQuery.extend( {
			'class': null,
			'searchbar': false
		}, options);

		this._parent = domelement;
		this._settings = settings;

		this._createListbox(); // create a fake listbox
		this._parent.css('display', 'none'); // hide a parent element
	}

	Listbox.prototype._createListbox = function() {
		this._listbox = jQuery('<div>')
			.addClass(MAIN_CLASS)
			.addClass(this._settings.class)
			.insertAfter(this._parent);

		if (this._settings.searchbar) {
			this._createSearchbar();
		}
		this._createList();
	};

	Listbox.prototype._createSearchbar = function() {

		// searchbar wrapper is needed for properly stretch
		// the seacrhbar over the listbox width
		var searchbarWrapper = jQuery('<div>')
			.addClass(SEARCHBAR_CLASS + '-wrapper')
			.appendTo(this._listbox);

		var searchbar = jQuery('<input>')
			.addClass(SEARCHBAR_CLASS)
			.appendTo(searchbarWrapper)
			.attr('placeholder', 'search...');

		// set filter handler
		var self = this;
		searchbar.keyup(function() {
			var searchQuery = jQuery(this).val().toLowerCase();

			if ('' !== searchQuery) {

				// hide list items which are not matched search query
				self._list.children().each(function(index) {
					var text = jQuery(this).text().toLowerCase();

					if (-1 != text.search('^' + searchQuery) ) {
						jQuery(this).css('display', 'block');
					} else {
						jQuery(this).css('display', 'none');
					}
				} );
			} else {

				// make visible all list items
				self._list.children().each(function() {
					jQuery(this).css('display', 'block');
				} );
			}

			// @hack: call special handler which is used only for SingleSelectListbox
			//        to prevent situation when none of items are selected
			if (self.onFilterChange) {
				self.onFilterChange();
			}
		} );

		// save for using in _resizeListToListbox()
		this._searchbarWrapper = searchbarWrapper;
	};

	Listbox.prototype._createList = function() {

		// create container
		this._list = jQuery('<div>')
			.addClass(LIST_CLASS)
			.appendTo(this._listbox);

		this._resizeListToListbox();

		// create items
		var self = this;
		this._parent.children().each(function() {
			self.addItem(jQuery(this) );
		} );
	};

	Listbox.prototype.addItem = function(parentItem) {
		var self = this;
		var item = jQuery('<div>')
			.addClass(LIST_ITEM_CLASS)
			.appendTo(this._list)
			.text(parentItem.text() )
			.click(function() {
				self.onItemClick(jQuery(this) );
			} );

		if (parentItem.attr('disabled') ) {
			item.attr('disabled', '');
		}

		if (parentItem.attr('selected') ) {
			this.onItemClick(item);
		}
	};

	Listbox.prototype._resizeListToListbox = function() {
		var listHeight = this._listbox.height();

		if (this._settings.searchbar) {
			listHeight -= this._searchbarWrapper.outerHeight(true);
		}

		this._list.height(listHeight);
	};

	function SingleSelectListbox(domelement, options) {
		this.super_.call(this, domelement, options);

		// select first item if none selected
		if (! this._selected) {
			this.onItemClick(this._list.children().first() );
		}
	}

	inherits(SingleSelectListbox, Listbox);

	SingleSelectListbox.prototype.onItemClick = function(item) {
		if (item.attr('disabled') ) {
			return;
		}

		// select a fake item
		if (this._selected) {
			this._selected.removeAttr('selected');
		}
		this._selected = item.attr('selected', '');

		// select a real item
		var itemToSelect = jQuery(this._parent.children().get(item.index() ) );
		this._parent.val(itemToSelect.val() );

		this._parent.trigger('change');
	};

	SingleSelectListbox.prototype.onFilterChange = function() {
		if (! this._selected || ! this._selected.is(':visible') ) {
			this.onItemClick(this._list.children(':visible').first() );
		}
	};

	function MultiSelectListbox(domelement, options) {
		this.super_.call(this, domelement, options);
	}

	inherits(MultiSelectListbox, Listbox);

	MultiSelectListbox.prototype.onItemClick = function(item) {
		if (item.attr('disabled') ) {
			return;
		}

		var parentItem = jQuery(this._parent.children().get(item.index() ) );
		var parentValue = this._parent.val();

		if (item.attr('selected') ) {
			item.removeAttr('selected');

			var removeIndex = parentValue.indexOf(parentItem.val() );
			parentValue.splice(removeIndex, 1);
		} else {
			item.attr('selected', '');

			if (! parentValue) {
				parentValue = [];
			}
			parentValue.push(parentItem.val() );
		}

		this._parent.val(parentValue);
		this._parent.trigger('change');
	};

	jQuery.fn.listbox = function(options) {
		return this.each(function() {
			if (jQuery(this).attr('multiple') ) {
				return !! new MultiSelectListbox(jQuery(this), options);
			}
			return !! new SingleSelectListbox(jQuery(this), options);
		} );
	};

}(jQuery) );


jQuery(document).on('nfFormReady', function(e) {

	if (1 > jQuery('.component_form .list-multiselect-wrap select').siblings('.lbjs').length) {
		jQuery('.component_form .list-multiselect-wrap select').listbox();
	}

	jQuery('.component_form .ninja-forms-field').each(function() {
		var placeholder;
		if (jQuery(this).attr('required') ) {
			var val = jQuery(this).val();
			if ('' != val) {
				jQuery(this).parent().closest('.nf-row').addClass('nf-row-focused').removeClass('nf-row-error');
				jQuery(this).removeClass('required-field');
				jQuery('.nf-after-form-content').hide();
			} else {
				jQuery(this).parent().closest('.nf-row').removeClass('nf-row-focused').addClass('nf-row-error');
				jQuery(this).addClass('required-field');
				jQuery('.nf-after-form-content').show();
			}
			if ('text' == jQuery(this).attr('type') || 'email' == jQuery(this).attr('type') || 'tel' == jQuery(this).attr('type') || jQuery(this).is('textarea') ) {
				placeholder = jQuery(this).attr('placeholder');
				jQuery(this).attr('placeholder', placeholder.replace('*', '') + '*');
				jQuery(this).attr('title', 'This is a required field');
			} else if (jQuery(this).is('select') ) {
				jQuery(this).attr('title', 'This is a required field');
			}
		}
	} );
	jQuery('.component_form input[type="checkbox"]').each(function(index, element) {
		if (jQuery(this).attr('required') ) {
			if (true == jQuery(this).prop('checked') ) {
				jQuery(this).parent().closest('.nf-row').removeClass('nf-row-checkbox-error');
				jQuery('.nf-after-form-content').hide();
			} else if (false == jQuery(this).prop('checked') ) {
				jQuery(this).parent().closest('.nf-row').addClass('nf-row-checkbox-error');
				jQuery('.nf-after-form-content').show();
			}
		}
		jQuery(this).on('focus', function() {
			var $parent = jQuery(this).parent();
			if (jQuery($parent).hasClass('nf-field-element') ) {
				jQuery(this).parent('.nf-field-element').addClass('hover');
			} else {
				jQuery(this).parent('li').addClass('hover');
			}
		} ).on('blur', function() {
			var $parent = jQuery(this).parent();
			jQuery(this).parent('li, div').removeClass('hover');
		} );
	} );
	jQuery('.component_form input[type="radio"]').on('focus', function() {
		var $parent = jQuery(this).parent();
		if (jQuery($parent).hasClass('nf-field-element') ) {
			jQuery(this).parent('.nf-field-element').addClass('hover');
		} else {
			jQuery(this).parent('li').addClass('hover');
		}
	} ).on('blur', function() {
		var $parent = jQuery(this).parent();
		jQuery(this).parent('li, div').removeClass('hover');
	} );
	jQuery('.component_form input[type="button"]').each(function(index, element) {
		var $formID = jQuery(this).parents('.component_form').attr('data-form-id');
		var $form = jQuery(this).parents('form');
		if (jQuery('.nf-before-form-content .nf-form-fields-required', $form).length) {
			jQuery('.nf-before-form-content .nf-form-fields-required', $form).attr('id', 'descriptionSubmit' + $formID);
			jQuery(this).attr('aria-describedby', 'descriptionSubmit' + $formID);
		}
	} );

	jQuery('.component_form .pikaday__display').each(function(index, element) {
		var $elID = jQuery(this).parents('.date-wrap').attr('data-field-id');
		jQuery(this).attr('id', 'pikaday__display' + $elID);
		jQuery(this).attr('placeholder', 'Select a Date');
		jQuery(this).attr('aria-labelledby', 'pikaday__display_label' + $elID);
		jQuery(this).parent('div').append('<label for="pikaday__display' + $elID + '" id="pikaday__display_label' + $elID + '" class="assistive-text">Select a Date</label>');
	} );

	/*
	The Ninja Forms code doesn't include the same code that we got with the CSS. When these items are not added below, there are no checkboxes or radio buttons beside the list, so I have written the below two intervals to add them. I needed to use intervals because the checkbox and radio lists didn't show up in the DOM right away on document ready.
	*/


	jQuery('.listradio-wrap').initialize(function() {
		var radioButtonInterval = 0;
		var addRadiobuttons = setInterval(function() {
			if (jQuery('.listradio-wrap').length) {
				jQuery.each(jQuery('.listradio-wrap input'), function(number, element) {
					if (0 < jQuery(element).parent().find('.radiomark').length) {
						return;
					}
					var $radiobox = jQuery('<div class=\'radiomark\'></div>');
					jQuery($radiobox).insertAfter(element);
				} );
				if (0 === jQuery(this).parent().parent().parent().parent().find('.label-hidden-error').length) {
					jQuery(this).parent().parent().parent().parent().find('.nf-field-label').addClass('label-hidden-error');
				}
				if (10 === radioButtonInterval++) { } else {
					clearInterval(addRadiobuttons);
				}
			}
		}, 100);
	} );

	jQuery('.listcheckbox-wrap').initialize(function() {
		var checkBoxInterval = 0;
		var addCheckBoxes = setInterval(function() {
			if (jQuery('.listcheckbox-wrap').length) {
				jQuery.each(jQuery('.listcheckbox-wrap input'), function(number, element) {
					if (0 < jQuery(element).parent().find('.checkmark').length) {
						return;
					}
					var $checkbox = jQuery('<div class=\'checkmark\'></div>');
					jQuery($checkbox).insertAfter(element);
				} );

				if (10 === checkBoxInterval++) { } else {
					clearInterval(addCheckBoxes);
				}
			}
		}, 100);
	} );

	jQuery('.checkbox-wrap').initialize(function() {
		var checkBoxContainerInterval = 0;
		var addCheckBoxContainers = setInterval(function() {
			if (jQuery(document).find('.checkbox-wrap').length) {
				jQuery.each(jQuery('.checkbox-wrap input'), function(number, element) {
					if (0 < jQuery(element).parent().find('.checkmark').length) {
						return;
					}
					var $checkbox = jQuery('<div class=\'checkmark\'></div>');
					if (! jQuery(this).parent().find('.checkmark').length) {
						jQuery($checkbox).insertAfter(element);
					}
				} );

				if (10 === checkBoxContainerInterval) { } else if (10 >= checkBoxContainerInterval) {
					clearInterval(checkBoxContainerInterval);
				}
				checkBoxContainerInterval++;

			}
		}, 100);
	} );

	jQuery('.ninja-forms-field').focus(function() {
		jQuery(this).parent().closest('.nf-row').addClass('nf-row-focused').removeClass('nf-row-filled');
	} );

	jQuery('.ninja-forms-field').keyup(function() {
		if (jQuery(this).attr('required') ) {
			var val = jQuery(this).val();
			if ('' != val) {
				jQuery(this).parent().closest('.nf-row').addClass('nf-row-focused').removeClass('nf-row-error');
				jQuery(this).removeClass('required-field');
			} else {
				jQuery(this).parent().closest('.nf-row').removeClass('nf-row-focused').addClass('nf-row-error');
				jQuery(this).addClass('required-field');
			}
		}
		jQuery(this).parent().closest('.nf-row').addClass('nf-row-focused');
	} );

	jQuery('.ninja-forms-field').change(function(e) {
		if (jQuery(this).attr('required') ) {
			check_form_errors(jQuery(this) );
			var val = jQuery(this).val();
			if ('' != val) {
				jQuery(this).parent().closest('.nf-row').addClass('nf-row-focused').removeClass('nf-row-error');
				jQuery(this).removeClass('required-field');
			} else {
				jQuery(this).parent().closest('.nf-row').removeClass('nf-row-focused').addClass('nf-row-error');
				jQuery(this).addClass('required-field');
			}
		}
	} );

	jQuery('.ninja-forms-field').blur(function(e) {

		// check_form_errors(jQuery(this));
		if (! jQuery(this).val() ) {
			jQuery(this).parent().closest('.nf-row').removeClass('nf-row-focused');

			if (jQuery(this).attr('required') ) {
				jQuery(this).addClass('required-field');
				jQuery(this).parent().closest('.nf-row').removeClass('nf-row-focused').addClass('nf-row-error');
			}
		} else {
			jQuery(this).parent().closest('.nf-row').addClass('nf-row-filled');
		}
	} );

	jQuery('.component_form input[type="checkbox"]').click(function() {
		if (jQuery(this).attr('required') ) {
			if (true == jQuery(this).prop('checked') ) {
				jQuery(this).parent().closest('.nf-row').removeClass('nf-row-checkbox-error');
			} else if (false == jQuery(this).prop('checked') ) {
				jQuery(this).parent().closest('.nf-row').addClass('nf-row-checkbox-error');
			}
		}
	} );

} );

function check_form_errors(selector) {
	$component = jQuery(selector).parents('.component_form');
	setTimeout(function() {
		if (0 < $component.find('.nf-fail:first').length) {
			var scrollTo = $component.find('.nf-fail:first').offset().top;
			var scrollTop = jQuery(window).scrollTop();
			var window_height = jQuery(window).height();
			if (scrollTo < scrollTop) {
				jQuery('body, html').animate( { scrollTop: scrollTo - 50 }, 500);
			} else if (scrollTo > (scrollTop + window_height) ) {
				jQuery('body, html').animate( { scrollTop: scrollTo - 50 }, 500);
			}
		}
	}, 500);
}
