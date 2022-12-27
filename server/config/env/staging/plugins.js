module.exports = {
  upload: {
    provider: 'google-cloud-storage',
    providerOptions: {
      bucketName: 'staging.rarespot-189d8.appspot.com',
      publicFiles: true,
      uniform: false,
      serviceAccount: {
        type: 'service_account',
        project_id: 'rarespot-189d8',
        private_key_id: 'aa8452832506f8fe960e7825d2fcf2caabc08078',
        private_key:
          '-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC3xYI4w60mOUOM\nrt+WiFjv4BxP/oH1cinnHQtHzasxFviccLIooJyMy+z4pLWLyZhU8Ua4bcBqTXq2\nlGkZuP0QPWksZExf94bR4UfRL7kOZ6fdZ1vF+SmtUiHSJiOGj8utPngOE+edUxLy\nWARr+YJOIusojz1r5kwLuS5Lyt+b2pAWfY9sh5O9PkkwUDaIoTRETUxc/SkMO8Z6\npg5gIQbR2NqfijW/5wkSkONDnK6Xx970drBuly1pfyyht2872DLwOhVDEaLMpBEZ\nn7pfUcSU3tvB38N7IRiSYMG5OOKPTkrR2ztgS5c6h5PICKWYQOLb5UwT5bB2Zgke\nXTzFGNWFAgMBAAECgf9fS0v7o3xmFcuxZ/0JUirMOKp10MQTQt/3uFfhJAVhr0e7\n17SK3L5RRG3dgYuisScM5RCHEmJ0vTik9NCLRiH0CeQ/4MK6AOEodGmJT/XmQloJ\ntU8hmdLCzZQ8JQrG/EnhzEL1Cd1Q94MbqAii9P9dBT5sCdsP2JwWeDBJnSnW5t3/\n7Fu8ihsYqR1xQCTQGkXCSvKyoKTVAm0vlBkudKMCUh40Thq77CH7fT8byvarZw2p\nrtyxPtCZEYa78HPLjPcaytzGQLWWBWkFHjQAELF45kcvlXCtv0WXg1ua/a3P5d/z\niAAnwXhlL40Oy+zMTi8WJxFcKziNT/E5n/4tIBkCgYEA5aaX2mwhfiI69s1SQO6o\nfFkYp8Ix31zH0rSE9wfhSunDJjUNuzwQ7+lp8zcy3cwaGSRpChjYt7pmzEWb0JEa\npE6+epx12iOdmIukT3lwPP0C2hkkK2B7uMf78kCt2tJrO4ypxQAkJrQUPp3Y+hhh\nmVO4y3CXdxZcd/s7tg7NLdkCgYEAzNtUYkC3Yf+9+P90ZF07kSKFuGOwHO1Za3FE\ns4dkPiTplnoP5AV403BE3oECBeZz+MpLT8unTfiZ0wAXZ0EnHnthojxSiNnBiVlg\ntlxxyg1QrSAUipS9kzRj8XjVsNscjWQ6txnA/X30KNK8ep+6roBLD1FfbIjz4g88\nGp7tHY0CgYEAyI1pFd3SjgW0uq3qRDxntVhVmMOVrDupYhA2AknGbRaiTy6L1FSL\ncgjEdxtgd6PzsrtCZAf40tKdTY0ImubtfkoqPIeAwETUL1Db6G/kHc7S+8Zy+NKk\nmWVEWHY1neCetv2LkTgjWg1vlJNi1l4OdclQm5GzJNPJYxprNf3NH1kCgYA1kGPQ\nDPvmM+iIYsMcOwSIPm6Yd/hkl+r5nPRJvO0JEr3BCsl85bFiF8jqJPFSeZ95PevT\nQXzNdmNCOJPl+7Mu1TQCJ6OtBMMGFsii5ikam8Csg+bbFWk8REadrOqzA5fDoKF5\nntJHddeRlQiTR5CYcBMwPialSmn6geFFVKjLQQKBgQCxlXDUATNZZT4wcnx9/V0v\noe3179euDeHmbVJCeQK4bE4yeMGnuUjsdRsvlMBIsUvqf3s3UgsJa89zRZZJ47Vg\n6YGx0nBMDGQ71siZC9lSu6+8bn3hag0nrII9Bjmq08/bCwZJwHbrmgt05lqMPTpz\n6hnpUBCz0vomnNIZmpuygQ==\n-----END PRIVATE KEY-----\n',
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
};
