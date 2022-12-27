import { MigrationInterface, QueryRunner } from 'typeorm';

export class InitMigration1633111410868 implements MigrationInterface {
  name = 'InitMigration1633111410868';

  public async up(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(
      `CREATE TABLE "collection" ("id" character varying NOT NULL, "name" character varying NOT NULL, "description" character varying, "assetsCount" integer NOT NULL, "totalVolume" double precision NOT NULL, "oneDayVolume" double precision NOT NULL, "oneDayChange" double precision NOT NULL, sevenDayVolume" double precision NOT NULL, "sevenDayChange" double precision NOT NULL, "averagePrice" double precision NOT NULL, "floorPrice" double precision NOT NULL, "numOwners" integer NOT NULL, "imageUrl" character varying, "externalUrl" character varying, "telegramUrl" character varying, "twitterUsername" character varying, "instagramUsername" character varying, "mediumUsername" character varying, CONSTRAINT "PK_ad3f485bbc99d875491f44d7c85" PRIMARY KEY ("id"))`,
    );
    await queryRunner.query(
      `CREATE TABLE "asset" ("id" character varying NOT NULL, "name" character varying, "usd" character varying, "eth" character varying, "saleDate" date, "imageUrl" character varying, "collectionId" character varying, CONSTRAINT "PK_1209d107fe21482beaea51b745e" PRIMARY KEY ("id"))`,
    );
    await queryRunner.query(
      `CREATE TABLE "marketplace" ("id" character varying NOT NULL, "name" character varying NOT NULL, "logo" character varying, "volume" double precision NOT NULL, "volumeChange" double precision NOT NULL, "traders" character varying NOT NULL, "tradersChange" character varying NOT NULL, CONSTRAINT "PK_d9c9a956a1a45b27b56db53bfc8" PRIMARY KEY ("id"))`,
    );
    await queryRunner.query(
      `ALTER TABLE "asset" ADD CONSTRAINT "FK_3759b1db06e01ac14f271f3dc58" FOREIGN KEY ("collectionId") REFERENCES "collection"("id") ON DELETE NO ACTION ON UPDATE NO ACTION`,
    );
  }

  public async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(
      `ALTER TABLE "asset" DROP CONSTRAINT "FK_3759b1db06e01ac14f271f3dc58"`,
    );
    await queryRunner.query(`DROP TABLE "marketplace"`);
    await queryRunner.query(`DROP TABLE "asset"`);
    await queryRunner.query(`DROP TABLE "collection"`);
  }
}
