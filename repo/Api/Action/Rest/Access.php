/* Required
-------------------------------*/
/* Definition
-------------------------------*/
module.exports = {
	/* Constants
	-------------------------------*/
	FAIL_EXPIRED	: 'Expired Token',
	
	/* Properties
	-------------------------------*/
	/* Construct
	-------------------------------*/
	constructor: function() {
		this.sync()

		//get the data
		.then(function(next) {
			this.item.client_id 		= this.source.access_token;
			this.item.client_secret 	= this.source.access_secret;
			
			next();
		})
		
		//validate
		.then(function(next) {
			var errors = this.model('session')
				.access()
				.errors(this.item);
		
			if(errors.code) {
				return this.fail(errors.code);
			}
			
			next();
		})
		
		//process
		.then(function(next) {
			this.job('session-access')({
				data: { item: this.item }
			}, next);
		})
		
		//end
		.then(function(error, results, next) {
			if(error) {
				return this.fail(error.toString());	
			}
			
			this.success(results.session);	
		});
	}
};