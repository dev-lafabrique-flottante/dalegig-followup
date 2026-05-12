<?php

function app_env_or_default($key, $default)
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }

    return $value;
}

function app_connect_db($host, $usuario, $senha, $bd)
{
    $conn = new mysqli($host, $usuario, $senha, $bd);
    if ($conn->connect_errno) {
        http_response_code(500);
        exit('Falha na conexao com o banco de dados.');
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}

$host = app_env_or_default('DALEGIG_DB_HOST', 'localhost');
$usuario = app_env_or_default('DALEGIG_DB_USER', 'change-me');
$senha = app_env_or_default('DALEGIG_DB_PASSWORD', 'change-me');

$conn_gig_dalegig = app_connect_db(
    $host,
    $usuario,
    $senha,
    app_env_or_default('DALEGIG_DB_GIG', 'change-me')
);

$conn_dalegig = app_connect_db(
    $host,
    $usuario,
    $senha,
    app_env_or_default('DALEGIG_DB_MAIN', 'change-me')
);

$conn = app_connect_db(
    $host,
    $usuario,
    $senha,
    app_env_or_default('DALEGIG_DB_INTRANET', 'change-me')
);
