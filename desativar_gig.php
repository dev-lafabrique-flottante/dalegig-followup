<?php

require_once 'CLASSES/bootstrap.php';

app_require_followup_session();

$bancoa_ou_b = app_get('bancoa_ou_b');
$id_gig = app_get_int('id_gig');

if ($bancoa_ou_b === '' || $id_gig <= 0) {
    app_forbidden();
}    
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
  //run btn desativar
if(isset($_POST['rec_desativar'])){

    app_verify_csrf_post();

    $motivo = app_db_escape($conn_gig_dalegig, app_post('motivo'));
    
    if($bancoa_ou_b=="a"){
        //avisar rapha para desativar no banco
        
        
        $msg = "<font color=orange>Equipe de follow up pediu para desativar a GIG do BANCO A ".$id_gig."<font> pelo seguinte motivo:".$motivo;
        
        $inserepedido = "INSERT INTO log_followup (tarefa,datahora) VALUES ('$msg',NOW())";
        
        if(mysqli_query($conn_gig_dalegig,$inserepedido)){
            echo "<script>alert('GIG desativada com sucesso! Talvez ela ainda apareça nos follow ups, para você dar algum feedback ao músico')</script>";
        }
    }else{
        
        //desativar do banco b
        $updategig = "UPDATE banco_b_contratantes SET deleted='1' WHERE id='$id_gig'";
        if(mysqli_query($conn_gig_dalegig,$updategig)){
            echo "<script>alert('GIG desativada com sucesso! Talvez ela ainda apareça nos follow ups, para você dar algum feedback ao músico')</script>";
        }
    }
    
    
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
                                //carrega dados
                                if($bancoa_ou_b=="a"){
                                    $consultagig = "SELECT * FROM cadastro_venue WHERE id='$id_gig'";
                                    $consultagig2= mysqli_query($conn_gig_dalegig,$consultagig);
                                    $dadogig = $consultagig2->fetch_array();
                                    
                                    
                                }else{
                                    $consultagig = "SELECT * FROM banco_b_contratantes WHERE id='$id_gig'";
                                    $consultagig2= mysqli_query($conn_gig_dalegig,$consultagig);
                                    $dadogig = $consultagig2->fetch_array();
                                }
                                
                              ?>
                              <h2 class="card-title" style="font-size:26px">Desativar GIG <?php echo $dadogig['gig_name'];?> para recebimento de propostas</h2>
                              <div class="table-responsive"> 
                                <!-- EXIBE OS DADOS PARA DESATIVAR GIG --> 
                                <form method='post'>
                                  <?php echo app_csrf_input(); ?>
                                  <input type='text' placeholder='Informe o motivo...' name='motivo' class='form-control'><br>     
                                  <input type='submit' placeholder='Desativar GIG' name='rec_desativar' class='btn btn-success' value='Desativar GIG'>
                                  <br><br>Ao clicar no botão, a GIG poderá levar algumas horas para ser desativada. 
                                </form>
                              </div>
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
