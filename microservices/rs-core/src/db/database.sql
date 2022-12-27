-- sudo -i -u postgres
-- psql -d rarespot_blockchain
-- GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public  TO rarespot_backend;


create table IF NOT EXISTS transfers 
  (
    blockNumber integer, 
	  timeStamp integer,
    hash varchar(255),
    nonce varchar(255),
    blockHash varchar(255),
    "from" varchar(255),
    contractAddress varchar(255),
    "to" varchar(255),
    tokenid NUMERIC,
    tokenName varchar(255),
    tokenSymbol varchar(255),
    tokenDecimal integer,
    transactionIndex integer,
    gas integer,
    gasPrice NUMERIC,
    gasUsed integer,
    cumulativeGasUsed integer,
    input varchar(255),
    confirmations integer,
    PRIMARY KEY (hash, tokenid)
  ) 
;

create table IF NOT EXISTS token_handler 
  (
    id SERIAL, 
    tokenID varchar(255) UNIQUE,
    tokenName varchar(255),
    tokenSymbol varchar(255),
    lastBlockNumber integer,
    lastUpdateTimestamp integer,
    lastStatus varchar(50),
    priorityLevel integer,
    visibility integer,
    PRIMARY KEY (tokenid)
  ) 
;

create table IF NOT EXISTS transactions
  (
    blockNumber integer, 
	  timeStamp integer,
    hash varchar(255),
    nonce varchar(255),
    blockHash varchar(255),
    "from" varchar(255),
    "to" varchar(255),
    value NUMERIC,
    gas integer,
    gasPrice NUMERIC,
    gasUsed integer,
    cumulativeGasUsed integer,
    isError integer,
    txreceipt_status integer,
    input text,
    confirmations integer,
    PRIMARY KEY (hash, "from", "to", value)
  ) 
;

ALTER TABLE transactions
  ADD reprocessed integer;

create table IF NOT EXISTS transactions_handler 
  (
    address varchar(255) PRIMARY KEY,
    lastBlockNumber integer,
    lastUpdateTimestamp integer,
    lastStatus varchar(50),
    priorityLevel integer,
    name varchar(250)
  ) 
;

create table IF NOT EXISTS collections_update_handler
  (
    id SERIAL,
    lastblockheight integer,
    lastblocktimestamp varchar(50),
    cumulativeoffset integer
  ) 
;



-- DB PREPARATION
-- first collection
INSERT INTO token_handler(tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel)
VALUES ('0xD16bdCCAe06DFD701a59103446A17e22e9ca0eF0', 'Basic Bored Ape Club', 'BBAC', 0, 0, null, 1);

-- Autoglyphs
INSERT INTO token_handler(tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel)
VALUES ('0xd4e4078ca3495DE5B1d4dB434BEbc5a986197782', 'Autoglyphs', '-', 0, 0, null, 1);

-- CRYPTOPUNKS
INSERT INTO token_handler(tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel)
VALUES ('0xb47e3cd837dDF8e4c57F05d70Ab865de6e193BBB', 'CRYPTOPUNKS', '-', 0, 0, null, 1);

-- Azuki
INSERT INTO token_handler(tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel)
VALUES ('0xed5af388653567af2f388e6224dc7c4b3241c544', 'Azuki', '-', 0, 0, null, 1);

-- special addresses update
-- TODO OpenSea: OpenSea does charge a flat fee of 2.5% per trade,You could then multiply this value to derive the original sale price of the NFT,
-- as OpenSea's fees are consistently paid via Internal Transactions. 
UPDATE transactions_handler SET name = 'OpenSea: Wallet' WHERE address = '0x5b3256965e7C3cF26E11FCAf296DfC8807C01073';

-- Note Nifty Gateway: Regarding sales involving Nifty Gateway, it appears that transactions are performed 
-- off chain according to their docs which indicates we will not be able to track sales as a block explorer.  
UPDATE transactions_handler SET name = 'Nifty Gateway: Omnibus' WHERE address = '0xE052113bd7D7700d623414a0a4585BCaE754E9d5';

-- other addresses to be analyzed
UPDATE transactions_handler SET name = 'Genie: GenieSwap' WHERE address = '0x0a267cF51EF038fC00E71801F5a524aec06e4f07';
UPDATE transactions_handler SET name = 'OpenSea: Wyvern Exchange v1' WHERE address = '0x7Be8076f4EA4A4AD08075C2508e481d6C946D12b';
UPDATE transactions_handler SET name = 'Foundation: Market' WHERE address = '0xcDA72070E455bb31C7690a170224Ce43623d0B6f';


-- DB OPERATIONS AND TEST
-- transaction check for null values 
SELECT tok.hash, tok.from,tok.to, value from transfers tok
        LEFT JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE value IS NOT NULL
        ORDER BY value DESC;

--check no value txs
SELECT count(*) from transfers tok
        LEFT JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE value IS NULL;

--get best address for transaction list
SELECT tok.to as address, count(*) as cnttx from transfers tok
        LEFT JOIN transactions tl
        ON tok.hash = tl.hash
        WHERE value IS NULL AND tok.from != '0x0000000000000000000000000000000000000000'
        GROUP BY tok.to ORDER BY cnttx DESC;


--get best address for transaction list -- OPTION 2
    SELECT tok.to as address, count(*) as cnttx from transfers tok
        LEFT JOIN transactions tl ON tok.hash = tl.hash
        LEFT JOIN transactions_handler th ON tok.to = th.address 
        WHERE value IS NULL AND tok.from != '0x0000000000000000000000000000000000000000'
        AND (th.lastStatus IS NULL OR th.lastStatus = 'SUCCESS')
        GROUP BY tok.to ORDER BY cnttx DESC;

--check token tx by address
SELECT hash, "from","to", tokenName FROM transfers 
    WHERE "to" = '0xd71fbf15b4b5ca36f50c5419e7ece261090abb61' OR "from" = '0xd71fbf15b4b5ca36f50c5419e7ece261090abb61';

--check tx by hash
SELECT "hash", "from","to", value FROM transactions
  WHERE "hash" = '0xd2b14b257f204643750ac043c7ce86e759b810cbec236d942cd18286520ed84c';

--clean tx by address
DELETE FROM transactions_handler WHERE address = '0x67dd8db1de789301917e579d9f507da81893e4ce';
--DELETE FROM transactions WHERE 

-- address coverage
SELECT count(DISTINCT(tok.to)) FROM transfers tok
    JOIN transactions tl ON tok.hash = tl.hash
    LEFT JOIN transactions_handler th ON tok.to = th.address;
SELECT count(DISTINCT(address)) FROM transactions_handler;

-- check the transfers by collection 
SELECT contractAddress, tokenName, COUNT(*)
FROM transfers
GROUP BY contractAddress;

-- check the transactions coverage by collection 
SELECT tokenName, count(*) as tx, sum(value) as volume
FROM transactions tx
JOIN transfers t ON tx.hash = t.hash
GROUP BY tokenname;

-- test run 1 - 15 more collections
INSERT INTO token_handler(tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel)
VALUES 
('0xb47e3cd837ddf8e4c57f05d70ab865de6e193bbb', 'CryptoPunks', 'cryptopunks', 0, 0, null, 1),
('0xbc4ca0eda7647a8ab7c2061c2e118a18a936f13d', 'Bored Ape Yacht Club', 'boredapeyachtclub', 0, 0, null, 1),
('0x60e4d786628fea6478f785a6d7e704777c86a7c6', 'Mutant Ape Yacht Club', 'mutant-ape-yacht-club', 0, 0, null, 1),
('0x959e104e1a4db6317fa58f8295f586e1a978c297', 'Decentraland', 'decentraland', 0, 0, null, 1),
('0x49cf6f5d44e70224e2e23fdcdd2c053f30ada28b', 'CLONE X - X TAKASHI MURAKAMI', 'clonex', 0, 0, null, 1),
('0x5cc5b05a8a13e3fbdb0bb9fccd98d38e50f90c38', 'The Sandbox', 'sandbox', 0, 0, null, 1),
('0x8a90cab2b38dba80c64b7734e58ee1db38b8992e', 'Doodles', 'doodles-official', 0, 0, null, 1),
('0x7bd29408f11d2bfc23c34f18275bbf23bb716bc7', 'Meebits', 'meebits', 0, 0, null, 1),
('0xb66a603f4cfe17e3d27b87a8bfcad319856518b8', 'Rarible', 'rarible', 0, 0, null, 1),
('0x1a92f7381b9f03921564a437210bb9396471050c', 'Cool Cats NFT', 'cool-cats-nft', 0, 0, null, 1),
('0xba30e5f9bb24caa003e9f2f0497ad287fdf95623', 'Bored Ape Kennel Club', 'bored-ape-kennel-club', 0, 0, null, 1),
('0xff9c1b15b16263c61d017ee9f65c50e4ae0113d7', 'Loot (for Adventurers)', 'lootproject', 0, 0, null, 1),
('0x06012c8cf97bead5deae237070f9587f8e7a266d', 'CryptoKitties', 'cryptokitties', 0, 0, null, 1),
('0x3163d2cfee3183f9874e2869942cc62649eeb004', 'Decentraland Wearables', 'decentraland-wearables', 0, 0, null, 1),
('0x76be3b62873462d2142405439777e971754e8e77', 'Parallel Alpha', 'parallelalpha', 0, 0, null, 1),

INSERT INTO token_handler(tokenID,tokenName,tokenSymbol,lastBlockNumber,lastUpdateTimestamp,lastStatus,priorityLevel)
VALUES 
('0x1cb1a5e65610aeff2551a50f76a87a7d3fb649c6','CrypToadz by GREMPLIN','CrypToadz_by_GREMPLIN',0,0,null,1),
('0x8c9f364bf7a56ed058fc63ef81c6cf09c833e656','SuperRare','SuperRare',0,0,null,1),
('0xe785e82358879f061bc3dcac6f0444462d4b5330','World of Women','World_of_Women',0,0,null,1),
('0x57a204aa1042f6e66dd7730813f4024114d74f37','CyberKongz','CyberKongz',0,0,null,1),
('0xbd3531da5cf5857e7cfaa92426877b022e612cf8','Pudgy Penguins','Pudgy_Penguins',0,0,null,1),
('0x4db1f25d3d98600140dfc18deb7515be5bd293af','HAPE PRIME','HAPE_PRIME',0,0,null,1),
('0x629a673a8242c2ac4b7b8c5d8735fbeac21a6205','Sorare','Sorare',0,0,null,1),
('0xa3aee8bce55beea1951ef834b99f3ac60d1abeeb','VeeFriends','VeeFriends',0,0,null,1),
('0x3bf2922f4520a8ba0c2efc3d2a1539678dad5e9d','0N1 Force','0N1_Force',0,0,null,1),
('0x9a534628b4062e123ce7ee2222ec20b86e16ca8f','MekaVerse','MekaVerse',0,0,null,1),
('0x22c36bfdcef207f9c0cc941936eff94d4246d14a','Bored Ape Chemistry Club','Bored_Ape_Chemistry_Club',0,0,null,1),
('ss/0xa5f1ea7df861952863df2e8d1312f7305dabf','15,ZED RUN Legacy','ZED_RUN_Legacy',0,0,null,1),
('0xbd4455da5929d5639ee098abfaa3241e9ae111af','NFT Worlds','NFT_Worlds',0,0,null,1),
('0xc2c747e0f7004f9e8817db2ca4997657a7746928','Hashmasks','Hashmasks',0,0,null,1),
('0xd2f668a8461d6761115daf8aeb3cdf5f40c532c6','Karafuru','Karafuru',0,0,null,1),
('0x73da73ef3a6982109c4d5bdb0db9dd3e3783f313','My Curio Cards','My_Curio_Cards',0,0,null,1),
('0x59468516a8259058bad1ca5f8f4bff190d30e066','Invisible Friends','Invisible_Friends',0,0,null,1),
('0xc92ceddfb8dd984a89fb494c376f9a48b999aafc','Creature World','Creature_World',0,0,null,1),
('0x348fc118bcc65a92dc033a951af153d14d945312','RTFKT - CloneX Mintvial','RTFKT_-_CloneX_Mintvial',0,0,null,1),
('0x28472a58a490c5e09a238847f66a68a47cc76f0f','adidas Originals Into the Metaverse','adidas_Originals_Into_the_Metaverse',0,0,null,1),
('0x67d9417c9c3c250f61a83c7e8658dac487b56b09','PhantaBear','PhantaBear',0,0,null,1),
('0x79fcdef22feed20eddacbb2587640e45491b757f','mfers','mfers',0,0,null,1),
('0xccc441ac31f02cd96c153db6fd5fe0a2f4e6a68d','FLUF World','FLUF_World',0,0,null,1),
('0x82c7a8f707110f5fbb16184a5933e9f78a34c6ab','Emblem Vault [Ethereum]','Emblem_Vault_[Ethereum]',0,0,null,1),
('0x7ea3cca10668b8346aec0bf1844a49e995527c8b','CyberKongz VX','CyberKongz_VX',0,0,null,1),
('0xa7206d878c5c3871826dfdb42191c49b1d11f466','LOSTPOETS','LOSTPOETS',0,0,null,1),
('0x6632a9d63e142f17a668064d41a21193b49b41a0','Prime Ape Planet PAP','Prime_Ape_Planet_PAP',0,0,null,1),
('0xb4d06d46a8285f4ec79fd294f78a881799d8ced9','3Landers','3Landers',0,0,null,1),
('0xf4ee95274741437636e748ddac70818b4ed7d043','The Doge Pound','The_Doge_Pound',0,0,null,1),
('0xc36cf0cfcb5d905b8b513860db0cfe63f6cf9f5c','Town Star','Town_Star',0,0,null,1),
('0x86825dfca7a6224cfbd2da48e85df2fc3aa7c4b1','RTFKT - MNLTH','RTFKT_-_MNLTH',0,0,null,1),
('0x8943c7bac1914c9a7aba750bf2b6b09fd21037e0','Lazy Lions','Lazy_Lions',0,0,null,1),
('0xf5b0a3efb8e8e4c201e2a935f110eaaf3ffecb8d','Axie Infinity','Axie_Infinity',0,0,null,1),
('0x33cfae13a9486c29cd3b11391cc7eca53822e8c7','Pixel Vault MintPass','Pixel_Vault_MintPass',0,0,null,1),
('0x617913dd43dbdf4236b85ec7bdf9adfd7e35b340','MyCryptoHeroes','MyCryptoHeroes',0,0,null,1),
('0x892848074ddea461a15f337250da3ce55580ca85','CyberBrokers','CyberBrokers',0,0,null,1),
('0xdf801468a808a32656d2ed2d2d80b72a129739f4','Somnium Space VR','Somnium_Space_VR',0,0,null,1),
('0xfe8c6d19365453d26af321d0e8c910428c23873f','Creepz Genesis','Creepz_Genesis',0,0,null,1),
('0xd532b88607b1877fe20c181cba2550e3bbd6b31c','Zipcy s SuperNormal','Zipcys_SuperNormal',0,0,null,1),
('0x0c2e57efddba8c768147d1fdf9176a0a6ebd5d83','Kaiju Kingz','Kaiju_Kingz',0,0,null,1),
('0x2acab3dea77832c09420663b0e1cb386031ba17b','DeadFellaz','DeadFellaz',0,0,null,1),
('0x79986af15539de2db9a5086382daeda917a9cf0c','Cryptovoxels','Cryptovoxels',0,0,null,1),
('0xad9fd7cb4fc7a0fbce08d64068f60cbde22ed34c','VOX Collectibles','VOX_Collectibles',0,0,null,1),
('0x123b30e25973fecd8354dd5f41cc45a3065ef88c','alien frens','alien_frens',0,0,null,1),
('0x86c10d10eca1fca9daf87a279abccabe0063f247','Cool Pets NFT','Cool_Pets_NFT',0,0,null,1),
('0xf61f24c2d93bf2de187546b14425bf631f28d6dc','World of Women Galaxy','World_of_Women_Galaxy',0,0,null,1);


-- description table
create table IF NOT EXISTS descriptions 
  (
    tokenid varchar(255) UNIQUE,
    description TEXT,
    PRIMARY KEY (tokenid)
  ) 
;

INSERT INTO descriptions(tokenid,description)  VALUES
('0xf61f24c2d93bf2de187546b14425bf631f28d6dc','World of Women Galaxy 3'),
...
('1xf61f24c2d93bf2de187546b14425bf631f28d6dc','World of Women Galaxy 3')
ON CONFLICT (tokenid) 
DO 
   UPDATE SET description = EXCLUDED.description;


create table IF NOT EXISTS nfts 
  (
    token_address varchar(255), 
	  token_id varchar(255),
    owner_of varchar(255),
    token_hash varchar(255),
    blockHash varchar(255),
    block_number varchar(255),
    block_number_minted varchar(255),
    contract_type varchar(255),
    token_uri varchar(255),
    m_name varchar(255),
    m_description varchar(255),
    m_image varchar(255),
    m_external_link varchar(255),
    m_animation_url varchar(255),
    m_attributes TEXT,
    minter_address varchar(255),
    last_token_uri_sync varchar(255),
    last_metadata_sync varchar(255),
    amount varchar(255),
    name varchar(255),
    symbol varchar(255),
    last_update varchar(255),
    PRIMARY KEY (token_address, token_id)
  ) 
;

ALTER TABLE nfts
ALTER COLUMN m_attributes TYPE TEXT;