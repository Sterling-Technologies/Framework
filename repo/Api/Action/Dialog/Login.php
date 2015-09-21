/* Required
-------------------------------*/
/* Definition
-------------------------------*/
module.exports = {
	/* Constants
	-------------------------------*/
	FAIL_VALIDATION	: 'There are some errors on the form.',
	FAIL_NOT_EXISTS	: 'User or Password is incorrect',
	
	/* Properties
	-------------------------------*/
	title: 'Log In',
	
	/* Construct
	-------------------------------*/
	constructor: function() {
		//there should be a client_id, redirect_uri
		//client_id is already checked in the router
		//state is optional
		if(!this.query.redirect_uri
		|| !this.query.redirect_uri.length) {
			this.template = 'dialog-invalid';
			return this.output();
		}
		
		//okay it is permitted
		//if there's a session
		if(this.me) {
			//no need to login
			var query = this.request.url.split('?')[1];
			return this.response.redirect('/dialog/request?' + query);
		}
		
		this.data.logo = true;
		
		//if it's a post
		if(this.request.method === 'POST') {
			return this.check();
		}
		
		this.data.query = this.request.url.split('?')[1];
		
		//Just load the page
		return this.output();
	},
	
	/* Methods
	-------------------------------*/
	/**
	 * When the form is submitted
	 *
	 * @return void
	 */
	check: function() {
		this.sync()

		//validate
		.then(function(next) {
			//get errors
			var errors = this.model('session')
				.login()
				.errors(this.item);
		
			//if there are errors
			if(Object.keys(errors).length) {
				return this.fail(this.FAIL_VALIDATION, errors, this.item);
			}
			
			next();
		})
		
		//login
		.then(function(next) {
			this.model('session')
				.login()
				.process(this.item, next);
		})
		
		//end
		.then(function(error, row, next) {
			if(error) {
				return this.fail(error);	
			}
			
			if(!row) {
				return this.fail(this.FAIL_NOT_EXISTS);
			}
			
			delete row.auth_password;
			
			//assign a new session
			this.request.session.me = row;
			
			//pass the request query
			var query = this.request.url.split('?')[1];
			this.response.redirect('/dialog/request?' + query);
		});	
	}
};