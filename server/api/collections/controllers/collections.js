'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/developer-docs/latest/development/backend-customization.html#core-controllers)
 * to customize this controller
 */

module.exports = {
  async create(ctx) {
    let entity;
    if (Array.isArray(ctx.request.body)) {
      ctx.request.body.forEach(async (item) => {
        entity = await strapi.query('collections').create(item);
      });
    } else {
      entity = await strapi.services.collections.create(ctx.request.body);
    }
    return entity;
  },
};
