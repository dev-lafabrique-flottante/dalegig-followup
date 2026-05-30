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
  $id_email_sendbox = app_get_int('id_email_sendbox');

  if ($id_email_sendbox <= 0) {
      app_forbidden();
  }
 
        
  //carrega dados da proposta
    $consultagig = "SELECT * FROM send_email_box WHERE id='$id_email_sendbox'";
    $consultagig2= mysqli_query($conn_dalegig,$consultagig);
    $dadoproposta = $consultagig2->fetch_array();
      
      
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
                    <a href='<?php echo h(app_followup_url()); ?>' class='btn btn-danger' style='position:fixed;z-index:9999'>Voltar</a><br><br>
                        <div class="row mt-12">
						<div class="col-lg-12 grid-margin stretch-card">
                          <div class="card" style='border:2px solid purple'>
                            <div class="card-body">
                              <button type="button" class="btn btn-warning" onclick="hideProposalMessage()">Não inserir no WhatsApp</button>
                              <br><br>
                              <div id="proposal-message-panel">
                              <?php
                                echo app_safe_html_block($dadoproposta['body']);
                              ?>
                              </div>
                              <p id="proposal-message-hidden" style="display:none;color:purple"><b>Exibição removida nesta tela.</b></p>
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
              function hideProposalMessage() {
                document.getElementById('proposal-message-panel').style.display = 'none';
                document.getElementById('proposal-message-hidden').style.display = 'block';
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
