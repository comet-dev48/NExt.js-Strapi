<?php 
    $username = 'rarespot_backend';
    $password = 'Zen.Fej78.Ppl745.';
    $dbName = 'rarespot_blockchain';
    $connectionName = "rarespot-core:europe-west6:rsdata";
    $socketDir = "/cloudsql";

    // Connect using UNIX sockets
    $dsn = sprintf(
        'pgsql:dbname=%s;host=%s/%s',
        $dbName,
        $socketDir,
        $connectionName
    );

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
         $pdo = new PDO($dsn, $username, $password, $options);
    } catch (\PDOException $e) {
         throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
?>