jQuery.noConflict();
jQuery(function($) {
	$(document).ready(function(){
		$( ".datepickerRange" ).datepicker({
			dateFormat: 'dd-mm-yy',
			prevText:'',
			nextText:'',
			minDate: 0,
			maxDate: "+1M",
			showOn: "button",
			buttonImage: divadatepicker.image_url+"/calendar.jpeg",
			buttonImageOnly: true
		});
		$( ".datepicker" ).datepicker({
			dateFormat: 'dd-mm-yy',
			prevText:'',
			nextText:'',
			showOn: "button",
			buttonImage: divadatepicker.image_url+"/calendar.jpeg",
			buttonImageOnly: true
		});
	});
});