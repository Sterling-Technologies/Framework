/* Required
-------------------------------*/
var hash = require('eden-hash')();

/* Definition
-------------------------------*/
module.exports = {
	/* Constants
	-------------------------------*/
	/* Properties
	-------------------------------*/
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
		
		if(!this.me) {
			return this.redirect({ error: 'user_invalid' });
		}
		
		var item = { auth_id: this.me.auth_id };
		
		if(this.query.session_token) {
			item.session_token = this.query.session_token;
		}
		
		var errors = this.model('session')
			.logout()
			.errors(item);
		
		if(errors.auth_id) {
			return this.redirect({ error: 'user_invalid' });
		}
		
		this.model('session').logout().process(item, function(error) {
			this.redirect({ success: 1 });	
		}.bind(this));
		
		delete this.request.session.me;
	},
	
	/**
	 * Creates a redirect url
	 *
	 * @param string the url
	 * @param object extra parameters
	 * @return string
	 */
	redirect: function(query) {
		query = query || {};
		
		var url = this.query.redirect_uri;
		
		if(this.query.state) {
			query.state = this.query.state;
		}
		
		query = hash.toQuery(query);
		
		if(!query.length) {
			this.response.redirect(url);
			return;
		}
		
		var separator = '?';
		if(url.indexOf('?') !== -1) {
			separator = '&';
		}
		
		this.response.redirect(url + separator + query);
	}
};