var request 	= require('request');
var nodemailer 	= require('nodemailer');

module.exports = function(job, done) {
	var data		= job.data;
	var controller	= job.controller || this;
	var config 		= controller.config('mail');

	// create reusable transporter object using SMTP transport
	var transporter = nodemailer.createTransport(config);

	if(!data.html && !data.text) {
		return done(new Error('Either html or text is required'));
	}

	// NB! No need to recreate the transporter object. You can use
	// the same transporter object for all e-mails

	// setup e-mail data with unicode symbols
	var mailOptions = {
	    from: config.auth.user, // sender address
	    to: data.to, // list of receivers
	    subject: data.subject // Subject line
	};

	if(!this.request.session.checkout) {
		this.request.session.checkout = {};
	}
	
	if(data.html) {
		mailOptions.html = data.html;
	}

	if(data.text) {
		mailOptions.text = data.text;
	}

	if(data.cc) {
		mailOptions.cc = data.cc;
	}

	if(data.bcc) {
		mailOptions.bcc = data.bcc;
	}

	if(data.attachments && data.attachments instanceof Array) {
		var error = false;
		// validation
		data.attachments.forEach(function(attachment){
			if (error) {
				return;
			}

			if(!attachment.filename || !attachment.filename.length) {
				error = false;
				return;
			}

			if(!attachment.content || !(attachment.content instanceof String) || !attachment.content.length) {
				error = false;
				return;
			}
		});

		if(error) {
			return done(new Error('Invalid attachments'));
		}

		mailOptions.attachments = data.attachments;
	}

	// send mail with defined transport object
	transporter.sendMail(mailOptions, function(error, info){
	    if(error){
	        return done(error);
	    }
	    
	    done();
	});

};