'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/developer-docs/latest/development/backend-customization.html#core-controllers)
 * to customize this controller
 */

const { sanitizeEntity } = require('strapi-utils');
module.exports = {
  async findOne(ctx) {
    const { eventId } = ctx.params;

    const entity = await strapi.services.events.findOne({ eventId });
    return sanitizeEntity(entity, { model: strapi.models.events });
  },
};
