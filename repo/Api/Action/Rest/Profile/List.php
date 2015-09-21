/* Required
-------------------------------*/
/* Definition
-------------------------------*/
module.exports = {
	/* Constants
	-------------------------------*/
	FAIL: 'Failed to get products',
	
	/* Properties
	-------------------------------*/
	/* Construct
	-------------------------------*/
	constructor: function() {
		this.sync()

		//get data
		.then(function(next) {
			this.item.public = true;
			
			next();
		})
		
		//validate
		.then(function(next) {
			var errors = this.model('profile')
				.list()
				.errors(this.item);
		
			if(Object.keys(errors).length) {
				return this.fail(this.FAIL, errors);
			}
			
			next();
		})
		
		//get total
		.then(function(next) {
			this._search = this.model('profile')
				.list()
				.process(this.item)
				.getTotal(next);
		})
		
		//get rows
		.then(function(error, total, next) {
			if(error) {
				return this.fail(error.toString());
			}
			
			this._total = total;
			
			this._search.getRows(next)
		})
		
		//end
		.then(function(error, rows, meta, next) {
			if(error) {
				return this.fail(error.toString());
			}
			
			this._rows = rows;
			
			this.success({ 
				total	: this._total, 
				rows	: this._rows });
		});
	}
};