$(function() {
	map_canvas = $('#map_canvas');
	if(window.map_array && map_array.length > 0){
		initialize_map(map_array)
	}else{
		map_canvas.hide();	
	}
});

function initialize_map(estate_values) {
	var map = new google.maps.Map(map_canvas.get(0),{
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}),
	latlngbounds = new google.maps.LatLngBounds( ),
	infowindow = new google.maps.InfoWindow({content: 'Loading ...', maxWidth: 180}),
	defaultZoom = 17;
	
	if(!$.isArray(estate_values)){
		estate_values = [estate_values];
		defaultZoom = 19;
	}

	for (var i = estate_values.length; i--;) {
		var listing = estate_values[i],
			myLatLng = new google.maps.LatLng(listing.listing_lat, listing.listing_lng),
			marker;
			if(listing.listing_status != 0){
				
				var image = estate_type_icon[listing.listing_type-1];
				marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					icon: image,
					title: listing.title,
					html: '<div class="info_header">'+listing.title+'</div><div class="info_body">'+listing.intro+'</div>'
				})
			}else{
				marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					title: listing.title,
					html: '<div class="info_header">'+listing.title+'</div><div class="info_body">'+listing.intro+'</div>'
				})
			}
		latlngbounds.extend(myLatLng)
		
		google.maps.event.addListener(marker, 'click', function() {
		  infowindow.setContent(this.html);
		  infowindow.open(map,this);
		});
	};
	
	 map.setCenter( latlngbounds.getCenter( ))

	 if(estate_values.length > 1){
	 	map.fitBounds( latlngbounds );
	 }else{
		map.setZoom(defaultZoom) 
	 };
}