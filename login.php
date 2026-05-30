<?php

require_once 'CLASSES/bootstrap.php';

if (!empty($_SESSION['followup_authenticated'])) {
    app_redirect(app_followup_url());
}

$message = '';
$error = '';
$email = strtolower(app_post('email'));

if (isset($_POST['request_code'])) {
    app_verify_csrf_post();

    if (!app_is_allowed_login_email($email)) {
        $error = 'Email nao autorizado para acessar esta area.';
    } else {
        $code = app_create_login_code($email);
        if (app_send_login_code($email, $code)) {
            $message = 'Codigo enviado para ' . $email . '. Ele expira em 10 minutos.';
        } else {
            $error = 'Nao foi possivel enviar o codigo por email. Verifique a configuracao de email do servidor.';
        }
    }
}

if (isset($_POST['verify_code'])) {
    app_verify_csrf_post();

    $code = app_post('code');
    $pendingEmail = isset($_SESSION['followup_login_email']) ? $_SESSION['followup_login_email'] : '';
    $expires = isset($_SESSION['followup_login_code_expires']) ? (int) $_SESSION['followup_login_code_expires'] : 0;
    $attempts = isset($_SESSION['followup_login_code_attempts']) ? (int) $_SESSION['followup_login_code_attempts'] : 0;
    $hash = isset($_SESSION['followup_login_code_hash']) ? $_SESSION['followup_login_code_hash'] : '';

    if ($pendingEmail === '' || $hash === '' || $expires < time()) {
        $error = 'Codigo expirado. Solicite um novo codigo.';
    } elseif ($attempts >= 5) {
        $error = 'Muitas tentativas. Solicite um novo codigo.';
    } elseif (!password_verify($code, $hash)) {
        $_SESSION['followup_login_code_attempts'] = $attempts + 1;
        $error = 'Codigo invalido.';
    } elseif (!app_is_allowed_login_email($pendingEmail)) {
        $error = 'Email nao autorizado para acessar esta area.';
    } else {
        app_complete_login($pendingEmail);
        app_redirect(app_followup_url());
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Follow up daleGig</title>
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="main-panel">
          <div class="content-wrapper d-flex align-items-center justify-content-center" style="min-height:100vh">
            <div class="card" style="max-width:520px;width:100%;border:2px solid purple">
              <div class="card-body">
                <h1 class="card-title" style="font-size:26px">Acesso ao Follow up</h1>
                <?php if ($message !== '') { ?>
                  <p style="color:green"><?php echo h($message); ?></p>
                <?php } ?>
                <?php if ($error !== '') { ?>
                  <p style="color:red"><?php echo h($error); ?></p>
                <?php } ?>

                <form method="post">
                  <?php echo app_csrf_input(); ?>
                  <label>Email autorizado</label>
                  <input type="email" name="email" class="form-control" value="<?php echo h($email); ?>" required>
                  <br>
                  <input type="submit" name="request_code" class="btn btn-primary" value="Receber codigo">
                </form>

                <hr>

                <form method="post">
                  <?php echo app_csrf_input(); ?>
                  <label>Codigo recebido por email</label>
                  <input type="text" name="code" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required>
                  <br>
                  <input type="submit" name="verify_code" class="btn btn-success" value="Entrar">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="vendors/base/vendor.bundle.base.js"></script>
    <script src="js/template.js"></script>
  </body>
</html>
