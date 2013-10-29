var map_div, map_input, map_lat, map_lng, map_canvas, map_listing;

(function($) {
	$(function(){
		// Map
		map_tab = $('.map-tab');
		map_div = $('#page-location');
		map_canvas = $('#map_canvas');
		map_input = map_div.find('input.text');
		map_listing = map_div.find('select[name|="listing_map_marker"]');
		map_lat = map_div.find('input[name|="listing_lat"]');
		map_lng = map_div.find('input[name|="listing_lng"]');
		// Map events
		map_input.blur(function(){geocode()})
		map_tab.click(function(){setTimeout ( "initialize()", 1000 )})
		map_listing.change(function(){initialize()})
		// Images Update
		$('#folder_id').change(function(){
			$.get(SITE_URL + 'admin/estate/ajax_select_folder/' + $(this).val(), function(data) {
				if (data) {
					// remove images from last selection
					$('#estate_images_list').empty();
					$('#thumbnail_id optgroup, .images-manage').remove();
					$('.images-placeholder:visible').slideUp('fast');
					if (data.images) {
						
						$('#thumbnail_id').append(
							'<optgroup label="Thumbnails">'+
								'<option selected value="0">No Thumbnail</option>'+
							'</optgroup>'
						);
						
						$.each(data.images, function(i, image){
							$('#estate_images_list').append(
							'<li>' +
								'<img src="' + SITE_URL + 'files/thumb/' + image.id + '" alt="' + image.name + '" title="Title: ' + image.name + ' -- Caption: ' + image.description + '"' +
							'</li>'
							);
							
							$('#thumbnail_id optgroup[label="Thumbnails"]').append(
							'<option value="' + image.id + '">' + image.name + '</option>'
							);
						});
						$('.images-placeholder').slideDown('slow');
					}
				}
				else {
					$('.images-placeholder').hide();
				}
				$.uniform.update('#thumbnail_id')
			}, 'json');							
		})
		
		form = $('form.crud');
		
		$('input[name="title"]', form).keyup($.debounce(300, function(){
		
			slug = $('input[name="slug"]', form);
			
			if(slug.val() == 'home' || slug.val() == '404')
			{
				return;
			}
			
			$.post(BASE_URI + 'index.php/ajax/url_title', { title : $(this).val() }, function(new_slug){
				slug.val( new_slug );
			});
		}));
		
		// Map
		
		
	});
})(jQuery);

function geocode(){
	var field_values = [],
		state = map_div.find('select[name|="state"]'),
		location_field = map_div.find('input.text');
	location_field.splice(3,0,state[0]);
	for(var j = 0; j < location_field.length; j++){
		field_values.push(jQuery(location_field[j]).val())
	}
	if(field_values[0] && field_values[2] && (field_values[3] || field_values[4])){
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({address : field_values.toString()},
			function(results, status){
				if ( status == google.maps.GeocoderStatus.OK){
					map_lat.val(results[0].geometry.location.lat());
					map_lng.val(results[0].geometry.location.lng());
					initialize()
				}
			}
		)
	}
};
function initialize() { 
	var lat = map_lat.val(),
		lng = map_lng.val();
	
	if(lat && lng){
		var latlng = new google.maps.LatLng(lat,lng),
			map = new google.maps.Map(map_canvas.get(0),{
				zoom: 17,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
	}
	if(map_listing.val() != 0){
		image = estate_type_icon[map_listing.val()-1];
		marker = new google.maps.Marker({
			   map : map,
			   position : latlng,
			   icon: image
		});
	}else{
		marker = new google.maps.Marker({
			   map : map,
			   position : latlng,
		});
	}
}