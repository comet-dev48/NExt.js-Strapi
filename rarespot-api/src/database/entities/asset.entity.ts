import { BaseEntity, Column, Entity, ManyToOne, PrimaryColumn } from 'typeorm';
import { CollectionEntity } from 'src/database/entities/collection.entity';

@Entity('asset')
export class AssetEntity extends BaseEntity {
  @PrimaryColumn()
  id: string;

  @Column({ nullable: true })
  name: string;

  @Column({ nullable: true })
  usd: string;

  @Column({ nullable: true })
  eth: string;

  @Column('date', { nullable: true })
  saleDate: string;

  @Column({ nullable: true })
  imageUrl?: string;

  @ManyToOne(() => CollectionEntity, (collection) => collection.assets)
  collection: CollectionEntity;
}
