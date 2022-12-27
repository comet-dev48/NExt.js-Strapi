'use strict';

/**
 * `sendmail` service.
 */

const nodemailer = require('nodemailer');
const userEmail = process.env.EMAIL_ADDRESS;
const userPassword = process.env.EMAIL_PASSWORD;

const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: userEmail,
    pass: userPassword,
  },
});

module.exports = {
  send: (from, to, subject, text) => {
    return new Promise((resolve, reject) => {
      transporter.sendMail({
        from: from,
        to: to,
        subject: subject,
        html: `<p>First Name: ${text.firstName}</p>
              <p>Last Name: ${text.lastName}</p>
              <p>email: ${text.email}</p>
              <p>Message: ${text.messageText}</p>
              `,
      }, (err, info) => {
        if (err) {
          reject(err);
        } else {
          resolve(info);
        }
      });
    });
  }
};
