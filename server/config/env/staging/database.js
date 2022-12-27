module.exports = () => ({
  defaultConnection: 'default',
  connections: {
    default: {
      connector: 'bookshelf',
      settings: {
        client: 'postgres',
        host: 'db.rupvyjmpxzoqjajuhhyy.supabase.co',
        port: '5432',
        database: 'postgres',
        username: 'postgres',
        password: 'Codicesegreto1',
        ssl: {
          rejectUnauthorized: false,
        },
      },
      options: {},
    },
  },
});
