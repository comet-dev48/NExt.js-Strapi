import { BaseEntity, Column, Entity, PrimaryColumn } from 'typeorm';

@Entity('marketplace')
export class MarketplaceEntity extends BaseEntity {
  @PrimaryColumn('varchar')
  id: string;

  @Column()
  name: string;

  @Column({ nullable: true })
  logo?: string;

  @Column('float')
  volume: number;

  @Column('float')
  volumeChange: number;

  @Column()
  traders: number;

  @Column('float')
  tradersChange: number;
}
