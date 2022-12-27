import { BaseEntity, Column, Entity, OneToMany, PrimaryColumn } from 'typeorm';
import { AssetEntity } from 'src/database/entities/asset.entity';

@Entity('collection')
export class CollectionEntity extends BaseEntity {
  @PrimaryColumn('varchar')
  id: string;

  @Column()
  name: string;

  @Column({ nullable: true })
  description?: string;

  @Column()
  assetsCount: number;

  @Column('float')
  totalVolume: number;

  @Column('float')
  oneDayVolume: number;

  @Column('float')
  sevenDayVolume: number;

  @Column('float')
  oneDayChange: number;

  @Column('float')
  sevenDayChange: number;

  @Column('float')
  averagePrice: number;

  @Column('float')
  floorPrice: number;

  @Column()
  numOwners: number;

  @Column({ nullable: true })
  imageUrl?: string;

  @Column({ nullable: true })
  externalUrl?: string;

  @Column({ nullable: true })
  telegramUrl?: string;

  @Column({ nullable: true })
  twitterUsername?: string;

  @Column({ nullable: true })
  instagramUsername?: string;

  @Column({ nullable: true })
  mediumUsername?: string;

  @OneToMany(() => AssetEntity, (asset) => asset.collection)
  assets: AssetEntity[];
}
