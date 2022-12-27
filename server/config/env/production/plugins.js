module.exports = {
  upload: {
    provider: 'google-cloud-storage',
    providerOptions: {
      bucketName: 'rarespotio-6d484.appspot.com',
      publicFiles: true,
      uniform: false,
      serviceAccount: {
        type: 'service_account',
        project_id: 'rarespotio-6d484',
        private_key_id: 'd9d5d74cf1a49549b8a0505b470c09497cf8d463',
        private_key:
          '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC3G2JEm3OCb/y0\nFS5aYXqmG9D0EW9eQGcHP+O5N0GYDI7s7p6drDk4DPXz1UtaeUEz7bFb1XbrjYIx\n0IkN0MEwLBluAKEG75/lwEy04rgCrxgRekrnAZ9iXIQL80eT6ada6NhFu9i9ui+P\n3dCBFVjoVJdFns0arw45dJUuQSexT1ENIywyILxLyBr5KbASEFQgQTqBOowRz36a\n2gvNiRjUI9Nym2MYZxm+GCXUF5rnOFyVMJgoFf+nzlB4qCGy+2118fzE33my/pwj\nf6MdRYCYhHvRORKeif5A2xmDSh0zQcFbfFYXKWtp0A24/DeaSmYZXf8Gy+afM15s\nkQ0E87LZAgMBAAECggEACrC6WMOTp5ymrhPjgCrbSInsteczr5bGfR2di8F+XqRz\nDsGyuIVm1M8q5+i+zMIyDJhWx8QLfJ6nKIXijpAuy0Nz26CFRjykwvrqb5AiCqQS\n3WhcqaaGWe2JVxJFzkywtOXNxKp+MbuGTdm3M+YdafkQWOP32RQ52iBkmk37BYbA\n6SQfYsIpv5lBzPxa/JTHLU9tvzZ5bJAaNCot9/pQf66rg9IHSoopDpz7qPrLJ3R7\nRpaYtiq7WiFAISumA9cq9MdDd3fyWb0YmgyihY+4nX33JRnJvE0A5yNlu+GBPm21\nQb5ppmNC9K1TcITCMPkaHkgmOOybZoGvlmQ56hxCIQKBgQDhdX5TouWNg+ZcQmdy\nD/4TndUhxJUPpgqenZwX/EJuZEuh6hubbp0iJfOZP9WmtiT8TgaP2EArdS5xvmgg\nv7zKbN+rVOv9gjdfR9r2BH/0iuhd1/CbmAtXFal+FfSIq/sU3lPCUpperPmjjK49\nPbIv4ha7cO1EUrxJ89RyDphySQKBgQDP6TNN4zZDwU7TWt6Ln6JvNECA3fNweci8\n2HfViCl6v+uEJXVRgJPzXGSPZEWxEW/Kp3v4+QLwbLnHYe6RAAL0D/+VrtC5AzxE\ndXYr68+no0HI1MYCPA3NUAFtLR8tVHsQ0Wpi+RpTWDVdFimAAhWSPIvK3Wuq1p7q\nlUjjIbM8EQKBgE1fgBH91l+hg33oAA5B9MUQpmMnTqyHJB/ZIQeON9Igs2AGsqC9\n7kkY0yelo5HJ8plvwYq+AQ+o8F5ypNOQWZ7yjDQFOdPPS2d0hm1lj/ABd8s030rW\nfBXPxlISANCijNFVl1MZ/AY/wJBjKCy2Lp+GLJz5HUQUTK/ocOEPpzuZAoGAZF2q\nA558sqadu8/xAKv2SQKLZOpAO3q+2dMRvkX16Ci/s9I1WTvdsCxY6xO3xOuq8MR7\nR0xGC/QglsyC0wKTamhStFporfeO32lou3khjEZ2WlHEqI1/CC7oEWEn3MUch5jg\nZ5jjCXraf+5T6FSZPzf1L+BFunnSy2F3hxXcpVECgYAk6o1wVduSFzAwCGxFzZjJ\nhKYiSu19lhXZiyQrSN2rr7XCFdrQFj7FAgwHFm+nUZbqGAPq6CSKDbS3ED/3q/sW\n6kpl6JWbEt5rffgBJTG8NCVTCjDUGtBwPVlbkQuc4hYs8JOnf3Rz1p3TxCBsEF/o\nTEbIuLPUBvy4FkIyNWmmfw==\n-----END PRIVATE KEY-----\n',
        client_email:
          'strapi-storage-bucket-access@rarespotio-6d484.iam.gserviceaccount.com',
        client_id: '110366750146021465537',
        auth_uri: 'https://accounts.google.com/o/oauth2/auth',
        token_uri: 'https://oauth2.googleapis.com/token',
        auth_provider_x509_cert_url:
          'https://www.googleapis.com/oauth2/v1/certs',
        client_x509_cert_url:
          'https://www.googleapis.com/robot/v1/metadata/x509/strapi-storage-bucket-access%40rarespotio-6d484.iam.gserviceaccount.com',
      },
      baseUrl: 'https://storage.googleapis.com/rarespotio-6d484.appspot.com',
      basePath: '',
    },
  },
};
