(function( $ ) {
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$clicket = 	$(function () {
		$("#custom-headers").next().addClass("for-length");
		$ul = $(".for-length").find(".ms-selection");
		$liLen = $ul.find(".ms-selected").length;

		$clicket = $liLen;

		return $clicket;
	});


	$selectable = $(".ms-elem-selectable");


	$selectable.on("click", function (e)
	{
		$selectable = $(".ms-elem-selectable");
		$selection = $(".ms-elem-selection .ms-selected");

		console.log($selection.length)
		$selection.removeClass("hide");
		var id = e.currentTarget.id;
		id = id.replace("-selectable", "-selection");
		$area = $(".ms-list")[7];
		$area = $(".ms-list").children(".ms-selected").length;

		var selection = $(id);
		$has = "#";

		$("#chi_selected_in_claim_posts").next().addClass("for-length");
		$ul = $(".for-length").find(".ms-selection");
		$liLen = $ul.find(".ms-selected").length;
		$clicket = $liLen;
		console.log($clicket);

		//console.log(id);
		//console.log($area);
		//console.log(selection);

		id  = $has.concat(id);
		//console.log(id);

		selection = $(".ms-list").children(id)[1];

		$clicket++;

		if ($clicket > 3)
		{
			$(this).toggleClass("show");

			//selection = selection.find("li");
			//selection.style.display = "none";
			$(selection).toggleClass("hide");
			$clicket = 3;
		}


		console.log("Left:");
		console.log($clicket);
	});

	$selected = $(".ms-selection");


	$selection = $(".ms-elem-selection .ms-selected");
	$selection.on("click", function (e){
		$("#chi_selected_in_claim_posts").next().addClass("for-length");
		$ul = $(".for-length").find(".ms-selection");
		$liLen = $ul.find(".ms-selected").length;
		$clicket = $liLen;
		if ($clicket > 2)
		{
			$clicket = 1;
		}
		$clicket--;

		console.log("Right:");
		console.log($clicket);

	});
})( jQuery );


