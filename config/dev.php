<?php
/** 
This PHP array configuration sets up a MySQL database connection.
You'll need to replace the placeholders with your actual database details:

- 'host' with your database host (e.g., `localhost`).
- 'name' with your database name.
- 'user' with your database username.
- 'password' with your database password.

The 'charset' and 'collation' settings ensure the database uses UTF-8 encoding, which supports a wide range of characters.
The 'prefix' can be used if you want to add a prefix to all your database tables. 
*/
return [
    'db' => [
        'mysql' => [
            'host' => 'YOUR_HOST',
            'name' => 'YOUR_DATABASE_NAME',
            'user' => 'YOUR_DATABASE_USERNAME',
            'password' => 'YOUR_DATABASE_USER_PASSWORD',
            'charset' => 'utf8',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => ''
        ]
    ]
];
