jQuery(function($) {

	if($('select').hasClass('js-cities')){
		var ajax_url = ctf7vn_params['ajax_url']
		var data_cities = ctf7vn_params["ctf7vn_cities"]

	    var $select_cities = $('.js-cities');
	    var $select_district = $('.js-district');
	    var $select_ward = $('.js-ward');


	    $.getJSON( data_cities, function( data ) {
	        $.each( data, function( key, val ) {
	            var $option = $("<option/>").attr("value", val.name ).attr("data-code", val.code ).text(val.name);
	                $select_cities.append($option);
	        });

	    });

	    // js-district
	    $('.js-cities').on("change",function(e) {

	        city_code = $(this).find(':selected').data('code')

	        $select_district.html('<option>Loading...</option>')
	        $select_ward.html('<option></option>')

	        $.ajax({
	            type   : "POST",
	            dataType: "html",
	            url    : ajax_url,
	            data : {
	                action: "district_handler",
	                data : city_code,
	            },
	            success: function(data){
	                $select_district.html(data)
	            },
	            error: function(jqXHR, textStatus, errorThrown)
	            {
	                console.log(textStatus)
	            }
	        });
	    });

	    // js-ward
	    $('.js-district').on("change",function(e) {

	        district_code = $(this).find(':selected').data('code')
	        $select_ward.html('<option>Loading...</option>')

	        $.ajax({
	            type   : "POST",
	            dataType: "html",
	            url    : ajax_url,
	            data : {
	                action: "ward_handler",
	                data : district_code,
	            },
	            success: function(data){
	                $select_ward.html(data)
	            },
	            error: function(jqXHR, textStatus, errorThrown)
	            {
	                console.log(textStatus)
	            }
	        });
	    });

	    $('.js-ward').on("change",function(e) {
	        ward = $(this).find(':selected').text()
	        $('.js-ward-value').val(ward)
	    });

	    $('.js-cities, .js-district, .js-ward').select2({ width: '100%'});
	}
});