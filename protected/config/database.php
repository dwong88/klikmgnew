<?php

// This is the database connection configuration.
/*return array(
    'connectionString' => 'mysql:host=127.0.0.1;dbname=dbveritrans',
    'emulatePrepare' => true,
    'username' => 'kontraks_usr',
    'password' => 'kontrak123',
    'charset' => 'utf8',
);*/

return array(
    'connectionString' => 'mysql:host=127.0.0.1;dbname=unify',
    'emulatePrepare' => true,
    'username' => 'root',
    'password' => '12345678',
    'charset' => 'utf8',
    'attributes'=>array(
        PDO::MYSQL_ATTR_LOCAL_INFILE => True
      ),
);
