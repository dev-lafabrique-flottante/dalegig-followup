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

function app_login_url()
{
    return 'login.php';
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

function app_allowed_login_emails()
{
    $configuredEmails = app_env('FOLLOWUP_ALLOWED_EMAILS');
    if ($configuredEmails !== '') {
        $emails = explode(',', $configuredEmails);
    } else {
        $emails = array(
            'rapha@lafabriqueflottante',
            'rapha@lafabriqueflottante.com',
            'rapha@2dlpro.com',
            'contato@2dlpro.com',
        );
    }

    $allowed = array();
    foreach ($emails as $email) {
        $email = strtolower(trim($email));
        if ($email !== '') {
            $allowed[] = $email;
        }
    }

    return $allowed;
}

function app_is_allowed_login_email($email)
{
    return in_array(strtolower(trim((string) $email)), app_allowed_login_emails(), true);
}

function app_create_login_code($email)
{
    $code = (string) random_int(100000, 999999);

    $_SESSION['followup_login_email'] = strtolower(trim((string) $email));
    $_SESSION['followup_login_code_hash'] = password_hash($code, PASSWORD_DEFAULT);
    $_SESSION['followup_login_code_expires'] = time() + 600;
    $_SESSION['followup_login_code_attempts'] = 0;

    return $code;
}

function app_send_login_code($email, $code)
{
    $subject = 'Codigo de acesso ao Follow up daleGig';
    $body = "Seu codigo de acesso ao Follow up daleGig e: " . $code . "\n\nEle expira em 10 minutos.";
    $headers = "From: contato@2dlpro.com\r\n";

    return mail($email, $subject, $body, $headers);
}

function app_complete_login($email)
{
    $_SESSION['followup_authenticated'] = true;
    $_SESSION['followup_authenticated_email'] = strtolower(trim((string) $email));
    unset($_SESSION['followup_login_email']);
    unset($_SESSION['followup_login_code_hash']);
    unset($_SESSION['followup_login_code_expires']);
    unset($_SESSION['followup_login_code_attempts']);
    session_regenerate_id(true);
}

function app_authorize_followup()
{
    $configuredToken = app_env('FOLLOWUP_ACCESS_TOKEN');

    $token = app_get('token');
    if (app_env('FOLLOWUP_ALLOW_TOKEN_LOGIN') === '1' && $configuredToken !== '' && $token !== '' && hash_equals($configuredToken, $token)) {
        app_complete_login('token');
        return;
    }

    if (!empty($_SESSION['followup_authenticated'])) {
        return;
    }

    app_redirect(app_login_url());
}

function app_require_followup_session()
{
    if (empty($_SESSION['followup_authenticated'])) {
        app_redirect(app_login_url());
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

function app_guess_audience_estimate($description, $city)
{
    $text = strtolower((string) $description . ' ' . (string) $city);

    if (preg_match('/sesc|teatro|auditorio|audit[oó]rio|centro cultural|instituto/i', $text)) {
        return '80 a 250 pessoas';
    }

    if (preg_match('/festival|prefeitura|pra[cç]a|arena|parque/i', $text)) {
        return '300 a 1.500 pessoas';
    }

    if (preg_match('/bar|pub|restaurante|caf[eé]|bistro/i', $text)) {
        return '40 a 120 pessoas';
    }

    if (preg_match('/casa de show|club|clube|boate/i', $text)) {
        return '120 a 400 pessoas';
    }

    return 'estimativa pendente: conferir historico/perfil antes de colar a oferta no WhatsApp';
}

function app_ai_coherence_prompt($artistName, $venueName, $city, $audienceEstimate)
{
    return 'Revise a mensagem de proposta para WhatsApp em portugues do Brasil, com tom humano e direto. Mantenha coerencia entre artista, venue, cidade, data, cache/acordo e estimativa de publico. Nao invente dados. Dados: artista "' . $artistName . '", venue "' . $venueName . '", cidade "' . $city . '", estimativa de publico "' . $audienceEstimate . '".';
}
