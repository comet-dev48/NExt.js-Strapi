import {MigrationInterface, QueryRunner} from "typeorm";

export class MarketplaceTraderData1634231235657 implements MigrationInterface {
    name = 'MarketplaceTraderData1634231235657'

    public async up(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`ALTER TABLE "public"."marketplace" DROP COLUMN "traders"`);
        await queryRunner.query(`ALTER TABLE "public"."marketplace" ADD "traders" integer NOT NULL`);
        await queryRunner.query(`ALTER TABLE "public"."marketplace" DROP COLUMN "tradersChange"`);
        await queryRunner.query(`ALTER TABLE "public"."marketplace" ADD "tradersChange" double precision NOT NULL`);
    }

    public async down(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`ALTER TABLE "public"."marketplace" DROP COLUMN "tradersChange"`);
        await queryRunner.query(`ALTER TABLE "public"."marketplace" ADD "tradersChange" character varying NOT NULL`);
        await queryRunner.query(`ALTER TABLE "public"."marketplace" DROP COLUMN "traders"`);
        await queryRunner.query(`ALTER TABLE "public"."marketplace" ADD "traders" character varying NOT NULL`);
    }

}
