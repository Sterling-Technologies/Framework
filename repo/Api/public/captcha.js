(function() {
	if(/Android/i.test(navigator.userAgent)
	|| /BlackBerry/i.test(navigator.userAgent)
	|| /iPhone|iPad|iPod/i.test(navigator.userAgent)
	|| /IEMobile/i.test(navigator.userAgent)) {
		$('div.captcha').hide();
		$('form button.btn-primary').attr('type', 'submit');
		return;
	}
	
	var width		= 500;
	var height		= 200;
	var font		= 'bold 48px "Comic Sans MS", cursive, sans-serif';
	var possible 	= 'ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789';
	var answer	 	= '';
	
	var generate 	= function() {
		$('div.captcha canvas').remove();
		
		var canvas 		= $('<canvas>')
			.css('display', 'block')
			.css('margin-bottom', '10px')
			.width(width).height(height);
		
		var context 	= canvas[0].getContext("2d");
		
		$('div.captcha label').after(canvas);
		
		answer	 = '';
	
		var randomColor1 = 'rgb(' 
			+ Math.floor(Math.random() * 255) + ', ' 
			+ Math.floor(Math.random() * 255) + ', ' 
			+ Math.floor(Math.random() * 255) + ')';
		
		var randomColor2 = 'rgb(' 
			+ Math.floor(Math.random() * 255) + ', ' 
			+ Math.floor(Math.random() * 255) + ', ' 
			+ Math.floor(Math.random() * 255) + ')';
		
		var length = Math.floor((Math.random() * 3) + 3);
		
		for (var i = 0; i < length; i++) {
			answer += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		
		var gradient1 = context.createLinearGradient(0, 0, width, 0);
		
		gradient1.addColorStop(0, randomColor1);
		gradient1.addColorStop(1, randomColor2);
	
		context.fillStyle = gradient1;
		context.fillRect(0, 0, width, height);
	
		var gradient2 = context.createLinearGradient(0, 0, width, 0);
		
		gradient2.addColorStop(0, randomColor2);
		gradient2.addColorStop(1, randomColor1);
	
		context.font = font;
		context.fillStyle = gradient2;
	
		context.setTransform(
			(Math.random() / 10) + 0.9,    	//scalex
			0.1 - (Math.random() / 5),      //skewx
			0.1 - (Math.random() / 5),      //skewy
			(Math.random() / 10) + 0.9,     //scaley
			(Math.random() * 20) + 10,      //transx
			100);                           //transy
	
		context.fillText(answer, 0, 0);
		context.setTransform(1, 0, 0, 1, 0, 0);
		
		window.localStorage.setItem('csrf', '');
	};
	
	$('div.captcha a').click(function() {
		generate();
	}).trigger('click');
	
	$('div.captcha input:first').keyup(function() {
		$('form button.btn-primary').attr('type', 'button');
		setTimeout(function() {
			var guess = $('div.captcha input:first').val();
			$('div.captcha').addClass('has-error');
			
			if(guess.toLowerCase() === answer.toLowerCase()) {
				$('div.captcha').removeClass('has-error');
				$('form button.btn-primary').attr('type', 'submit');
			}
		});
	});
})();