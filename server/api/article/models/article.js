const readingTime = require('reading-time');
('use strict');

/**
 * Read the documentation (https://strapi.io/documentation/v3.x/concepts/models.html#lifecycle-hooks)
 * to customize this model
 */

module.exports = {
  lifecycles: {
    beforeCreate(data) {
      if (data && !data.timeToRead) {
        const readingTimeObj = readingTime(data?.content);
        data.timeToRead = Math.round(readingTimeObj.minutes);
      }
    },
  },
};
