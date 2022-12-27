'use strict';

/**
 * Read the documentation (https://strapi.io/documentation/developer-docs/latest/development/backend-customization.html#lifecycle-hooks)
 * to customize this model
 */
const Boom = require('boom');

module.exports = {
  lifecycles: {
    async beforeCreate(data) {
      if (data) {
        const blockchainId = data.blockchain;
        const marketplaceId = data.marketplace;
        const blockchain = await strapi
          .query('blockchains')
          .find({ id: blockchainId });
        const marketplace = await strapi
          .query('marketplaces')
          .find({ id: marketplaceId });

        if (
          !blockchain ||
          !blockchain[0] ||
          !(blockchain[0].id == blockchainId)
        ) {
          const error = new Error('Blockchain is required!');
          const boomError = Boom.boomify(error, {
            statusCode: 422,
          });
          throw boomError;
        }

        if (
          !marketplace ||
          !marketplace[0] ||
          !(marketplace[0].id == marketplaceId)
        ) {
          const error = new Error('Marketplace is required!');
          const boomError = Boom.boomify(error, {
            statusCode: 422,
          });
          throw boomError;
        }
      }
    },
    async beforeUpdate(data, params) {
      if (
        params &&
        params.hasOwnProperty('blockchain') &&
        params.hasOwnProperty('marketplace')
      ) {
        const blockchainId = params.blockchain;
        const marketplaceId = params.marketplace;
        const blockchain = await strapi
          .query('blockchains')
          .find({ id: blockchainId });
        const marketplace = await strapi
          .query('marketplaces')
          .find({ id: marketplaceId });

        if (
          !blockchain ||
          !blockchain[0] ||
          !(blockchain[0].id == blockchainId)
        ) {
          const error = new Error('Blockchain is required!');
          const boomError = Boom.boomify(error, {
            statusCode: 422,
          });
          throw boomError;
        }

        if (
          !marketplace ||
          !marketplace[0] ||
          !(marketplace[0].id == marketplaceId)
        ) {
          const error = new Error('Marketplace is required!');
          const boomError = Boom.boomify(error, {
            statusCode: 422,
          });
          throw boomError;
        }
      }
    },
  },
};
