<?php

require_once __DIR__ . '/conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_env($key, $default = '')
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }

    return $value;
}

function app_get($key, $default = '')
{
    return isset($_GET[$key]) ? trim((string) $_GET[$key]) : $default;
}

function app_post($key, $default = '')
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function app_get_int($key, $default = 0)
{
    return isset($_GET[$key]) ? (int) $_GET[$key] : $default;
}

function app_post_int($key, $default = 0)
{
    return isset($_POST[$key]) ? (int) $_POST[$key] : $default;
}

function app_db_escape($conn, $value)
{
    return mysqli_real_escape_string($conn, (string) $value);
}

function app_followup_url()
{
    return 'followup.php';
}

function app_redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function app_forbidden()
{
    http_response_code(403);
    echo "<script>alert('Voce nao esta autorizado a acessar esta area.')</script>";
    echo "<script>window.location.href='https://dalegig.com'</script>";
    exit;
}

function app_authorize_followup()
{
    $configuredToken = app_env('FOLLOWUP_ACCESS_TOKEN');
    if ($configuredToken === '') {
        http_response_code(500);
        exit('Configuração de acesso ausente.');
    }

    $token = app_get('token');
    if ($token !== '' && hash_equals($configuredToken, $token)) {
        $_SESSION['followup_authenticated'] = true;
        session_regenerate_id(true);
        return;
    }

    if (!empty($_SESSION['followup_authenticated'])) {
        return;
    }

    app_forbidden();
}

function app_require_followup_session()
{
    if (empty($_SESSION['followup_authenticated'])) {
        app_forbidden();
    }
}

function app_csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function app_csrf_input()
{
    return '<input type="hidden" name="csrf_token" value="' . h(app_csrf_token()) . '">';
}

function app_verify_csrf_post()
{
    $posted = app_post('csrf_token');
    if ($posted === '' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $posted)) {
        http_response_code(419);
        exit('Falha de validacao da sessao.');
    }
}

function app_safe_html_block($html)
{
    return nl2br(h((string) $html));
}
