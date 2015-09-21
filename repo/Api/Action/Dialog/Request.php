/* Required
-------------------------------*/
var hash = require('eden-hash')();

/* Definition
-------------------------------*/
module.exports = {
	/* Constants
	-------------------------------*/
	FAIL_SESSION	: 'Failed to create session.',
	FAIL_VALIDATION : 'There are some errors on the form.',
	
	/* Properties
	-------------------------------*/
	title			: 'Log In',
	
	/* Construct
	-------------------------------*/
	constructor: function() {
		this.data.blank = true;
		
		//there should be a client_id, redirect_uri
		//client_id is already checked in the router
		//state is optional
		if(!this.query.redirect_uri
		|| !this.query.redirect_uri.length) {
			this.template = 'dialog-invalid';
			return this.output();
		}
		
		//scope by default is public_sso
		this.requestPermissions = this.query.scope  || 'public_sso';
		this.requestPermissions = this.requestPermissions.split(',');
		
		//okay it matches
		//make app permissions into an array
		var appPermissions = this.source.app_permissions.split(',');
		
		//check scopes with registered app permissions
		var permitted = true;
		this.requestPermissions.forEach(function(permission) {
			if(appPermissions.indexOf(permission) === -1) {
				permitted = false;
			}
		});
		
		//did they all match ?
		if(!permitted) {
			this.template = 'dialog-invalid';
			return this.output();
		}
		
		//okay it is permitted
		//if there's not session
		if(!this.me) {
			//go back to the login
			//pass the request query
			var query = this.request.url.split('?')[1];
			this.response.redirect('/dialog/login?' + query);
			return;
		}
		
		//if it's a post
		if(this.request.method === 'POST') {
			return this.check();
		}
		
		//no post, so we need to render
		//we want to sparse the user and other permissions(global)
		var roles = this.controller.config('roles');
		
		var userPermissions 	= [];
		var globalPermissions 	= [];
		
		//give public permissions
		if(typeof roles.Public === 'object') {
			globalPermissions = Object.keys(roles.Public);
		}
		
		this.requestPermissions.forEach(function(role) {
			//if its not a user permission, it's a global permission
			if(typeof roles.User[role] === 'undefined') {
				globalPermissions.push(role);
				return;
			}
			
			//okay it has to be a user permission
			userPermissions.push({
				name		: role,
				icon		: roles.User[role].icon || 'user',
				title		: roles.User[role].title,
				description	: roles.User[role].description
			});
		});
		
		//Now we can load the page
		this.data.app 					= this.source;
		this.data.user_permissions 		= userPermissions;
		this.data.global_permissions 	= globalPermissions;
		
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

		//get the item
		.then(function(next) {
			this.item.app_id = this.request.source.app_id;
			this.item.auth_id = this.request.session.me.auth_id;
			
			if(this.item.session_permissions instanceof Array) {
				this.item.session_permissions = this.item.session_permissions.join(',');
			}
			
			//no need to process if the action is not allow
			if(this.item.action !== 'allow') {
				//go back to the app
				return this.redirect({ error: 'access_denied' });
			}
			
			next();
		})
		
		//validation
		.then(function(next) {
			var errors = this.model('session')
				.request()
				.errors(this.item);
		
			if(Object.keys(errors).length) {
				return this.fail(this.FAIL_VALIDATION, errors, this.item);
			}
			
			next();
		})
		
		//process
		.then(function(next) {
			this.job('session-request')({
				data: { item: this.item }
			}, next);
		})
		
		//end
		.then(function(error, results, next) {
			if(error) {
				return this.fail(error.toString());	
			}
			
			if(!results.session) {
				return this.fail(this.FAIL_SESSION);
			}
			
			//success
			this.redirect({ code: results.session.session_token });
		});
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
