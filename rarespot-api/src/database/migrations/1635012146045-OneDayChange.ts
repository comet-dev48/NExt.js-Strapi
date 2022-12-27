import {MigrationInterface, QueryRunner} from "typeorm";

export class OneDayChange1635012146045 implements MigrationInterface {
    name = 'OneDayChange1635012146045'

    public async up(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`ALTER TABLE "public"."collection" ADD "oneDayChange" double precision NOT NULL`);
    }

    public async down(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`ALTER TABLE "public"."collection" DROP COLUMN "oneDayChange"`);
    }

}
