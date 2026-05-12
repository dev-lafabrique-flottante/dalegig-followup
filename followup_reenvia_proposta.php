<?php

require_once 'CLASSES/bootstrap.php';

app_require_followup_session();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Follow up dalegig - Área de acompanhamento de propostas</title>
    <!-- base:css -->
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    
    <link rel="stylesheet" href="css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
  </head>
  <body>
<?php
  //pega informações desta proposta do banco B
  $reenvia = app_get('reenvia');
  $id_proposta = app_get_int('id_proposta');
  $id_banda = app_get_int('id_banda');
  $nome_venue = app_get('venue');

  if ($id_proposta <= 0 && $reenvia !== 'a') {
      app_forbidden();
  }
        
  //carrega dados da proposta
    $dadoproposta = array(
        'body' => '',
        'subject' => '',
        'id_tour' => ''
    );
    if ($id_proposta > 0) {
        $consultagig = "SELECT * FROM send_email_box WHERE id='$id_proposta'";
        $consultagig2= mysqli_query($conn_dalegig,$consultagig);
        $dadoproposta = $consultagig2->fetch_array();
    }
      
      
  $nome_banda = '';
  $nome_gig = '';
  $nome_produtor = '';
  $email_gig = '';
  $body_novo = '';
  $subject_novo = '';

  if ($id_proposta > 0 && !empty($dadoproposta['body'])) {
      //obtém dados desta gig atualizados
      $nome_banda = explode("ofereço-lhe <b>", $dadoproposta['body']);
      $nome_banda = explode("</b>", $nome_banda[1]);
      $nome_banda = $nome_banda[0];
      $nome_gig = explode(" a todos de ", $dadoproposta['body']);
      $nome_gig = explode(",<br>", $nome_gig[1]);
      $nome_gig = $nome_gig[0];

      //obtém email e nome do produtor
      $consultagig = "SELECT * FROM banco_b_contratantes WHERE venue='" . app_db_escape($conn_gig_dalegig, $nome_gig) . "'";
      $consultagig2 = mysqli_query($conn_gig_dalegig, $consultagig);
      $dadogig = $consultagig2->fetch_array();
      $nome_produtor = $dadogig['produtor'];
      $email_gig = $dadogig['email'];

      //altera o body com o nome antigo do produtor para o novo
      $nome_antigo_produtor = explode("<br>Bom dia ", $dadoproposta['body']);
      $nome_antigo_produtor = explode(" a todos de ", $nome_antigo_produtor[1]);
      $nome_antigo_produtor = $nome_antigo_produtor[0];

      $body_novo = str_replace($nome_antigo_produtor, $nome_produtor, $dadoproposta['body']);
      $subject_novo = str_replace($nome_antigo_produtor, $nome_produtor, $dadoproposta['subject']);
  }
      
      
      
?>
<div class="container-scroller">
    <!-- ALERT POP UP TOP SCREEN-->
		<?php //include("partials/_alert_top.php"); ?>	
    <!-- END ALERT POP UP -->
    <!-- NAVBAR HORIZONTAL -->    
        <?php include("partials/_horizontal-navbar.php"); ?>
    <!-- END NAVBAR HORIZONTAL -->
    <!-- BODY -->
		<div class="container-fluid page-body-wrapper">
			<div class="main-panel">
				<div class="content-wrapper">
                    <a href='<?php echo h(app_followup_url()); ?>' class='btn btn-danger'>Voltar</a><br><br>
                        <div class="row mt-12">
						<div class="col-lg-12 grid-margin stretch-card">
                          <div class="card" style='border:2px solid purple'>
                            <div class="card-body">
                              <?php
                                if($reenvia=='a'){
                                    
                                  //carregar dados
                                  unset($tours);    
                                  $consultatours = "SELECT * FROM tours WHERE id_musico='$id_banda' ORDER BY data_record_here DESC LIMIT 2";
                                  $consultatours2= mysqli_query($conn_dalegig,$consultatours);
                                  while($dadotours = $consultatours2->fetch_array()){
                                      
                                      $tours = $tours." #".$dadotours['id_tour'];
                                  }    
      
                                  echo "1) <a href=https://www.dalegig.com/gerente/tours target=_blank><b>Clique aqui</b></a> para entrar no dash de propostas e procure pela proposta para <b>".h($nome_venue)."</b> nas tours mais recentes do artista (acione o Rapha se nao possuir acesso)<br><br>2) Entre em <b>tour".h($tours)."</b> e procure a aba da proposta ".h($nome_venue)."</b><br><br>3) Copie a proposta e cole em um corpo de email, enviando como Chico ou Lena. Utilize o assunto <i>Att.: (Coloque o nome do produtor na proposta) - Proposta musical de (Coloque o nome da banda)</i>";

                                  exit;

                              }     
                              ?>
                              <h2 class="card-title" style="font-size:26px">Reenviar proposta para contratante de banco B</h2>
                              <h2>Wilton, envie o email abaixo, através do email Chico ou Lena</h2><br>    
                              <h3><b>Destinatário:</b> <?php echo h($email_gig);?></h3>
                              <h3><b>Cole o assunto do email:</b> <?php echo h($subject_novo);?></h3>
                              <br><br>
                              <h2>Copie e cole o corpo do email abaixo:</h2>
                              <br><br>
                              <div style="white-space:pre-wrap"><?php echo app_safe_html_block($body_novo);?></div>
                                
                            </div>
                          </div>
                        </div> 
                    </div>
				</div>
				<!-- content-wrapper ends -->
                <!-- FOOTER -->
                <?php include("partials/_footer.php"); ?>
                <!-- END FOOTER -->
            <!-- END BODY -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
    </div>
        <!-- container-scroller -->
        <script>
              var posicao = localStorage.getItem('posicaoScroll');
              if(posicao) {
                /* Timeout to work in Chrome */
                setTimeout(function() {
                    window.scrollTo(0, posicao);
                }, 1);
              }
              /* check position and save it */
              window.onscroll = function (e) {
                posicao = window.scrollY;
                localStorage.setItem('posicaoScroll', JSON.stringify(posicao));
              }
          </script>
    <!-- base:js -->
    <script src="vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="js/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/progressbar.js/progressbar.min.js"></script>
		<script src="vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js"></script>
		<script src="vendors/justgage/raphael-2.1.4.min.js"></script>
		<script src="vendors/justgage/justgage.js"></script>
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
      <!-- End custom js for this page-->
    </body>
</html>
