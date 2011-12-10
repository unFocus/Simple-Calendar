jQuery(document).ready(function($) {
	var i = document.createElement( "input" );
	i.setAttribute( "type", "date" );
	if ( i.type == "text" ) {
		$( ".simple-datepicker" ).datepicker({
			//beforeShow: function( input, inst ) { console.log('wtf'); },
			//onClose: function( dateText, inst ) { console.log('wtf'); },
		});
	}
});
