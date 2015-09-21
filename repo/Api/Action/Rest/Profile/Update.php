/* Required
-------------------------------*/
/* Definition
-------------------------------*/
module.exports = {
	/* Constants
	-------------------------------*/
	FAIL	: 'Could not update profile',
	TITLE	: 'Profile Update by %s',
	
	/* Properties
	-------------------------------*/
	/* Construct
	-------------------------------*/
	constructor: function() {
		this.sync()

		//get the data
		.then(function(next) {
			this.item.profile_id = this.source.profile_id;
		
			//they cannot change their profile type
			if(this.item.profile_type) {
				delete this.item.profile_type;
			}
			
			next();
		})
		
		//validate
		.then(function(next) {
			var errors = this.model('profile')
				.update()
				.errors(this.item);
			
			if(Object.keys(errors).length) {
				return this.fail(this.FAIL, errors);
			}
			
			next();
		})
		
		//process
		.then(function(next) {
			this.controller.queue.create('profile-update', {
				title	: this.TITLE.replace('%s', this.source.profile_name),
				item	: this.item,
			}).save(next);
		})
		
		//end
		.then(function(error, results, next) {
			if(error) {
				return this.fail(error.toString());
			}
			
			//success
			this.success();
		});
	}
};