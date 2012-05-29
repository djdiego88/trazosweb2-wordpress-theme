$(document).on('ready', function(){
	
	
	$("a.tw2-open").click(function(){

		$($(this).attr('href')).fadeIn('normal');
        return false;
		
	});

	
	$('a.tw2-close').click(function() {
	
        $($(this).attr('href')).fadeOut();
        return false;
        
    });
	
	
});