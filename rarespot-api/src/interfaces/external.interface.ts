export interface AssetReponse {
  assets: Array<Asset>;
}

interface Asset {
  token_id: string;
  name: string;
  image_url: string;
  last_sale: LastSale;
}

interface LastSale {
  event_timestamp: string;
  total_price: string;
  payment_token: PaymentToken;
}

interface PaymentToken {
  usd_price: string;
}

export interface Collection {
  name: string;
  slug: string;
  image_url: string;
  description: string;
  external_url: string;
  telegram_url: string;
  twitter_username: string;
  instagram_username: string;
  medium_username: string;
  stats: CollectionStats;
}

interface CollectionStats {
  total_volume: number;
  seven_day_volume: number;
  seven_day_change: number;
  average_price: number;
  floor_price: number;
  num_owners: number;
  total_supply: number;
  one_day_volume: number;
  one_day_change: number;
}
