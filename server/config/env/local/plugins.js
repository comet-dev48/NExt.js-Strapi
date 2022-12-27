module.exports = ({ env }) => ({
  upload: {
    provider: 'google-cloud-storage',
    providerOptions: {
      bucketName: 'staging.rarespot-189d8.appspot.com',
      publicFiles: true,
      uniform: false,
      serviceAccount: {
        type: 'service_account',
        project_id: 'rarespot-189d8',
        private_key_id: '36546894951cd38a408e2e27e2bdab283858c30b',
        private_key:
          '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDAaQDCYe2clk5M\nu01XIGn9HeHBDT9dVQnLJhmjFDYH6elURuZI7vZi0N1qONR1EO0ZKDA7rOesw6S2\nGVFKpCsW6z24F8JU9Ss1+F8hdhj+5UgE11w7BMgv2HELyVpMMa92h+ib4jsXzlny\n58We6UREjN+PW9PGJf9oTm/gqzgA2/Hl1ncNDnFTLrFeDY3Gn5AYyllip3ezE04g\nv7DzmcQFOMhtVjjkL/Et4hXi7W1i5BnQlCabjDrXjJ1UgmFKKj2O/PdPGLpwZyES\nJCuLeFiT6mfnkz5hUbnoWJnRUwl8JcdIjnYiGDNr7ijvedaUTE2yuPef/9lRFJg6\nj3GbH6rNAgMBAAECggEATjZgqKNGAzDWAzrf5Vq3urw62E6aQ0YeBQYnxbKhg/YB\nctCfK2Z3oRNl7ZfMRQnsVx2O/bL7Oa8NdTg1YzH4kghtvjdqNz6xoe6kPOXUo0hw\nTgd85kC7XzeMPE5M9kv8FyKH21XKz5KB3ct4+W+44GSjO5MIMrOfbrbzo0/2I+KO\neXHZp6lewldAYixOpzNSY0Ac22K43UEhFe8nndIyUQTk5KT7UrBLfzpQTNV1XeDv\n9K6oDO5/tSO3JUTRfdF3dcxfir3zWclxFQk+tDRbPc811lqdCGY52nbXzPygMD8w\nIPr211PJHq6yKeRdYmOMs70ND9RESR7BlsGiRwjNwQKBgQD5V5VnFJxrikL0rs/c\nWmzZu1r4zNrKdQMtnaFkHAOrKi0WJPLQfb42zGluqx/iJGvC+TeuF2+ZS28T3xMA\nbO0e6VSWaNwVkyuw90w2nCPMkbrqgSd7lq6oPorbn9UjwBCsNbZ4Ha1mafZvrdwh\nIfHRDntmikhVhZStNEGnLQIvQwKBgQDFjECSQyQB+3K06AIHVliY71LHLqKB4lRo\n3MEmIBBUezAUSTdH4mlxWjBkukjVUj5lFhsoJS7OaJC79RbnrlC/3W76H5xzb1Q9\n143PwECQclNHiGVsqRZc152Yo/7sh6iNT5+kqg/GOAON+A8b74AC88qNHgG9BDSh\nK3s3DP10rwKBgQDW1pQwYR31uwe98/G9l1w6rqfPwBK0ZuX6I9uvekPe81QtxGBD\nLFT8ulwNsvyhyWfgcwN6yU6q8sgegxLIRxJ4k+sCPg+Dt27p5+qPEzH1TYuUCvyO\nDGMpK9pRKgJn71FuUEmMbWW+3IYt68vD8I9+5c+Fpph0t8hHBMOc27cKKQKBgDvu\n6Cw3uHXtfoiKFwt409paSWfqqxzyuJ8z0lmpwqqO0TBmYiIp+8mLDkGRDsXBdPKg\nOU79sOyoZrjPi8pwZUlAegnii0muTgWRHxIXhr8MIF4EcJj9164FMGf94TqLkcSF\nkq6Xdb+ygMmSp6rXB6RdqGcV4nshCoKXOYOjKkY7AoGBAJfzxccGrMk9Ch9SSTtF\nsE2NfbRMOOWZBkiufY9FJEEtE+udXHiYuLvHPEbGdSxh1CX3+rdHdodg9gUiGh+Z\nGAf6810qZDsi7ljlsDdXQaEO0yHZF3DjKuafRdZxaa1sh2a/owK16QnkM6p04e4E\n8/pg8XOjM+RRHBu8GhPZCkLx\n-----END PRIVATE KEY-----\n',
        client_email:
          'firebase-adminsdk-eg8mv@rarespot-189d8.iam.gserviceaccount.com',
        client_id: '117500887177857240770',
        auth_uri: 'https://accounts.google.com/o/oauth2/auth',
        token_uri: 'https://oauth2.googleapis.com/token',
        auth_provider_x509_cert_url:
          'https://www.googleapis.com/oauth2/v1/certs',
        client_x509_cert_url:
          'https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-eg8mv%40rarespot-189d8.iam.gserviceaccount.com',
      },
      baseUrl:
        'https://storage.googleapis.com/staging.rarespot-189d8.appspot.com',
      basePath: '',
    },
  },
});
