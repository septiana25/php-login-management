<?php
function getDatabaseConfig(): array
{
    return [
        'database' => [
            'test' => [
                'dns' => 'mysql:host=localhost:3306;dbname=login_management_test',
                'user' => 'root',
                'pass' => ''
            ],
            'prod' => [
                'dns' => 'mysql:host=localhost:3306;dbname=login_management',
                'user' => 'root',
                'pass' => ''
            ]
        ]
    ];
}
