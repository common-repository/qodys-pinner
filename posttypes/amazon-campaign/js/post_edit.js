var typeahead_content = [];

jQuery(document).ready( function() {
	
	jQuery('.chzn-select').chosen();
	
	jQuery('#keyword_search').keydown( function(e) {
		
		/*jQuery('#keyword_search').typeahead( {
			source: ['Alabama','Wyoming'],
			items: 8
		});*/
		
	});
	
	jQuery('#keyword_search').typeahead( {
		items: 8,
		source: function (typeahead, query) {
			
			var param = {};
			param['output'] = 'json';
			param['client'] = 'suggest';
			param['q'] = query;
			
			jQuery.get(
				'http://google.com/s',
				param,
				function( data ) {
					
					var result_arrays = data[1];	
					typeahead_content = [];
					
					if( result_arrays.length > 0 )
					{
						for( var i = 0; i < result_arrays.length; i++ )
						{
							typeahead_content.push( result_arrays[i][0] );
						}
					}
				},
				'jsonp'
			);
			
			return typeahead_content;
		},
		// typeahead calls this function when a object is selected, and
		// passes an object or string depending on what you processed, in this case a string
		onselect: function ( data ) {
			
			jQuery('#keyword_search').val( '' );
			
			var current_value = jQuery('#keywords').val();
			
			if( current_value )
				jQuery('#keywords').val( current_value + ', ' + data );
			else
				jQuery('#keywords').val( data );
		}
	});
});

function ParseSearchData( data )
{
	var result_arrays = data[1];
	
	var new_data = []
	
	if( result_arrays.length > 0 )
	{
		for( var i = 0; i < result_arrays.length; i++ )
		{
			new_data.push( result_arrays[i][0] );
		}
	}
	console.log( new_data );
	return new_data;
	
}


	