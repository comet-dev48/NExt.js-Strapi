'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/developer-docs/latest/development/backend-customization.html#core-controllers)
 * to customize this controller
 */

module.exports = {
    async create(ctx) {
        try {
            const fromEmail = process.env.FROM_EMAIL;
            const toEmail = process.env.TO_EMAIL;
            const subject = process.env.SUBJECT;

            const {
                firstName,
                lastName,
                email,
                message
            } = ctx.request.body;
            const data = {
                firstName,
                lastName,
                email,
                messageText: message
            };
            await strapi.services.messages.create(data);
            await strapi.services.sendmail.send(fromEmail, toEmail, subject, data);
    
            return ctx.send({
                status: 200,
                statusText: 'Your message has been sent successfully.'
            });
        } catch (err) {
            return ctx.send({
                status: 500,
                statusText: err.message
            });
        }
    }
};
