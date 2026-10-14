// Small site-wide snippets and inits, too small for their own component file
import '../vendors/jquery.nice-select.min.js';

jQuery(document).ready(function ($) {
	"use strict";

	// styled <select> fields
	$('select').niceSelect();

});
