<?php

require_once 'CLASSES/bootstrap.php';

$token = app_db_escape($conn_dalegig, app_get('token'));
$id_banda = app_get_int('id');

//checando se token existe para esta banda
$consultabanda = "SELECT * FROM artistas_dados_premium WHERE id_banda='$id_banda' AND token='$token'";
$consultabanda2= mysqli_query($conn_dalegig,$consultabanda);

if(mysqli_num_rows($consultabanda2)==0){
    
    echo "<script>alert('Você não está autorizado a acessar esta área.')</script>";
    
    echo "<script>window.location.href='https://dalegig.com'</script>";
    
    exit;
}

$_SESSION['followup_authenticated'] = true;
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
      
//run btn registrar resposta interessada
if(isset($_POST['rec_resposta_interessada'])){
    
    $resposta = addslashes($_POST['resposta']);
    $post_banco = addslashes($_POST['post_banco']);
    $post_id_proposta = addslashes($_POST['post_id_proposta']);
    $post_card = addslashes($_POST['post_card']);
    
    //envia para o Rapha
    if($post_banco==0){
        //banco A
        
        //consulta proposta
        $consultaproposta = "SELECT * FROM tours_gigs WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        //adicionar remuneração a Wilton
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','7',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
            
        
    //****PROVAVEL ERRO AQUI  VINDO DE POST_ID_PROPOSTA, que não aparece a tour id e gig id certa para poder incluir mensagens ao chat musico 
        
        unset($msg);
        $msg = "<b><font color=yellow>Wilton fez o follow up de banco A</font></b> e obteve uma resposta de contratatante interessado. Adicione as infos abaixo no chat do músico.<br><br><b>TOUR ID: ".$dadoproposta['id_tour']."<br>GIG ID: ".$dadoproposta['id_gig']."<br>Palavras do contratante: </b>".$resposta;
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='1',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,datahora) VALUES ('$msg',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Seu saldo será acumulado em algumas horas após a confirmação.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            }
            
        }
        
    }else{
        //banco B
        $consultaproposta = "SELECT * FROM send_email_box WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        
        //adicionar remuneração a Wilton
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','7',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        
    
        unset($msg);
        $msg = "<b><font color=yellow>Wilton fez o follow up de banco B</font></b> e obteve uma resposta de contratatante interessado. Adicione as infos abaixo no chat do músico.<br><br><b>TOUR ID: ".$dadoproposta['id_tour']."<br>Assunto do email: ".$dadoproposta['subject']."<br>Palavras do contratante: </b>".$resposta;
        
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='1',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,datahora) VALUES ('$msg',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Seu saldo será acumulado em algumas horas após a confirmação.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            }
            
        }
    }
    
    
    
}        
      
//run btn registrar resposta recusada
if(isset($_POST['rec_resposta_recusada'])){
    
    $resposta = addslashes($_POST['resposta']);
    $post_banco = addslashes($_POST['post_banco']);
    $post_id_proposta = addslashes($_POST['post_id_proposta']);
    $post_card = addslashes($_POST['post_card']);
    
    //envia para o Rapha
    if($post_banco==0){
        //banco A
        
        //adicionar remuneração a Wilton
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','1',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        //consulta proposta
        $consultaproposta = "SELECT * FROM tours_gigs WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
            
        unset($msg);
        $msg = "<b><font color=yellow>Wilton fez o follow up de banco A</font></b> e obteve uma resposta de contratatante recusada. Adicione as infos abaixo no chat do músico e acumule mais R$ 0,50 na sua remuneração.<br><br><b>TOUR ID: ".$dadoproposta['id_tour']."<br>GIG ID: ".$dadoproposta['id_gig']."<br>Palavras do contratante: </b>".$resposta;
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='2',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,datahora) VALUES ('$msg',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Seu saldo será acumulado em algumas horas após a confirmação.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            }
            
        }
        
    }else{
        //banco B
        $consultaproposta = "SELECT * FROM send_email_box WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        //adicionar remuneração a Wilton
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','1',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        unset($msg);
        $msg = "<b><font color=yellow>Wilton fez o follow up de banco B</font></b> e obteve uma resposta de contratatante recusada. Adicione as infos abaixo no chat do músico e acumule mais R$ 0,50 na sua remuneração.<br><br><b>TOUR ID: ".$dadoproposta['id_tour']."<br>Assunto do email proposta enviado: ".$dadoproposta['subject']."<br>Palavras do contratante: </b>".$resposta;
        
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='2',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,datahora) VALUES ('$msg',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Seu saldo será acumulado em algumas horas após a confirmação.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            }
            
        }
    }
    
}    
      
//run btn registrar nao consegui contatar
if(isset($_POST['rec_nao_consegui'])){
    
    $resposta = addslashes($_POST['resposta']);
    $post_banco = addslashes($_POST['post_banco']);
    $post_id_proposta = addslashes($_POST['post_id_proposta']);
    $post_card = addslashes($_POST['post_card']);
    
    //envia para o Rapha
    if($post_banco==0){
        //banco A
        
        //consulta proposta
        $consultaproposta = "SELECT * FROM tours_gigs WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='3',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
              //inserida
              echo "<script>alert('Follow up registrado com sucesso.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            
        }
        
    }else{
        //banco B
        $consultaproposta = "SELECT * FROM send_email_box WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='3',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
              //inserida
              echo "<script>alert('Follow up registrado com sucesso.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            
            
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
                    <p style='color:purple'>Olá Wilton!<br>Seu saldo atual é de <b>
                     <?php
                     //checa saldo do mês atual
                     $consultasaldo = "SELECT SUM(valor) as saldo FROM followup_saldo WHERE user='1' AND MONTH(datahora)=MONTH(NOW()) AND YEAR(datahora)=YEAR(NOW())";  
                     $consultasaldo2= mysqli_query($conn_dalegig,$consultasaldo);
                     $dadosaldo = $consultasaldo2->fetch_array();
                        
                     if($dadosaldo['saldo']==null){
                         echo "R$ 0,00";
                     }else{
                         echo "R$ ".$dadosaldo['saldo'];
                     }    
                        
                     ?></b> (será repassado no início de cada mês) e você possui <b>
                    <?php
                      //mostra o número de follow ups pendentes
                      $consultaflup = "SELECT * FROM followup_cards WHERE resultado='0' AND resultado_texto='' AND datamaxima>=NOW()-INTERVAL 2 DAY ORDER by datamaxima ASC";
                      $consultaflup2= mysqli_query($conn_dalegig,$consultaflup);
                      echo mysqli_num_rows($consultaflup2);    
                    ?> follow ups</b> em andamento</p>
                    <br><br>
                    <?php
                    //exibe follow ups pendentes
                if(mysqli_num_rows($consultaflup2)>0){
                while($dadoflup = $consultaflup2->fetch_array()){
                        
                        //abre card
                        ?>
                        <div class="row mt-12">
						<div class="col-lg-12 grid-margin stretch-card">
                          <div class="card" style='border:2px solid purple'>
                            <div class="card-body">
                              <h2 class="card-title" style="font-size:26px"></h2>
                              <div class="table-responsive"> 
                                <!-- EXIBE OS CARDS DE FOLLOW UP --> 
                                
                        <?php
                        //consulta se é cutucada ou saude
                        if($dadoflup['cutucada_ou_saude']==0){
                            //cutucada
                            //consulta dados do músico
                            $id_banda = $dadoflup['id_banda'];
                            $consultabanda = "SELECT * FROM artistas WHERE id_banda='$id_banda'";
                            $consultabanda2= mysqli_query($conn_dalegig,$consultabanda);
                            $dadobanda = $consultabanda2->fetch_array();
                            
                            
                            //consulta dados da gig de acordo com o banco
                            $id_gig = $dadoflup['id_gig'];
                            $id_gig_ou_emailbox = $dadoflup['id_gig_ou_emailbox'];

                            unset($nome_gig);
                            unset($nome_produtor);
                            unset($cidade_gig);
                            unset($tel_gig);
                            unset($app_gig);
                            unset($desc_gig);
                            unset($email_gig);
                            unset($photo1_gig);
                            unset($photo2_gig);
                            unset($photo3_gig);
                            unset($dataenviada);
                            unset($acordo);
                            unset($valor_acordo);
                            unset($dia_show);
                            unset($situacao_resposta);
                            unset($preview_proposta);

                            if($dadoflup['bancoa_ou_b']==0){
                                //banco a
                                
                                unset($id_gig_a);
                                
                                $consultagig = "SELECT * FROM cadastro_venue WHERE ids_dalegig LIKE '%$id_gig%' AND id<>'10'";
                                $consultagig2= mysqli_query($conn_gig_dalegig,$consultagig);
                                while($dadogig = $consultagig2->fetch_array()){
                                    
                                    //com cada gig verifica se é essa
                                    $gig_filter = explode(",",$dadogig['ids_dalegig']);
                                    foreach($gig_filter as $gig_check){
                                        
                                        if($gig_check==$id_gig){
                                            
                                            $id_gig_a = $dadogig['id'];
                                        }
                                    }
                                }
                                
                                $consultagig = "SELECT * FROM cadastro_venue WHERE id = '$id_gig_a' AND id<>'10'";
                                $consultagig2= mysqli_query($conn_gig_dalegig,$consultagig);
                                
                                
                                $consultagig2= mysqli_query($conn_gig_dalegig,$consultagig);
                                $dadogig = $consultagig2->fetch_array();
                                $nome_gig = $dadogig['gig_name'];
                                $nome_produtor = $dadogig['gig_producer'];
                                $cidade_gig = $dadogig['gig_city'];
                                $tel_gig = $dadogig['gig_tel_number'];
                                $app_gig = $dadogig['gig_tel'];
                                $desc_gig = $dadogig['gig_description'];
                                $email_gig = $dadogig['gig_email'];
                                $photo1_gig = $dadogig['gig_photo1'];
                                $photo2_gig = $dadogig['gig_photo2'];
                                $photo3_gig = $dadogig['gig_photo3'];

                                //consulta dados da proposta
                                $consultaproposta = "SELECT * FROM tours_gigs WHERE id='$id_gig_ou_emailbox'";
                                $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
                                $dadoproposta = $consultaproposta2->fetch_array();
                                $dataenviada = $dadoproposta['data_record_here'];
                                $acordo = $dadoproposta['cache_tipo'];
                                $valor_acordo = $dadoproposta['cache_solicitado'];
                                $dia_show = $dadoproposta['dia_show'];
                                $situacao_resposta = $dadoproposta['situacao_resposta'];


                            }else{
                                //banco b
                                $consultagig = "SELECT * FROM banco_b_contratantes WHERE id='$id_gig'";
                                $consultagig2= mysqli_query($conn_gig_dalegig,$consultagig);
                                $dadogig = $consultagig2->fetch_array();
                                $nome_gig = $dadogig['venue'];
                                $nome_produtor = $dadogig['produtor'];
                                $cidade_gig = $dadogig['cidade']."/".$dadogig['estado'];
                                $tel_gig = $dadogig['tel_num'];
                                $desc_gig = $dadogig['description'];
                                $email_gig = $dadogig['email'];
                                $photo1_gig = $dadogig['photo'];

                                //consulta dados da proposta
                                $consultaproposta = "SELECT * FROM send_email_box WHERE id='$id_gig_ou_emailbox'";
                                $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
                                $dadoproposta = $consultaproposta2->fetch_array();
                                $dataenviada = $dadoproposta['date_sent'];
                                $dia_show = explode("DISPONIBILIDADE:",$dadoproposta['body']);
                                $dia_show = explode(" ",$dia_show[1]);
                                $dia_show = explode(">",$dia_show[3]);
                                $dia_show = $dia_show[1];
                                $situacao_resposta = $dadoproposta['send_receive'];
                            }
                        
                        
                            echo "<h2 class='card-title' style='font-size:26px'>Contatar ".$nome_produtor." (".$nome_gig." em ".$cidade_gig.") sobre artista ".$dadobanda['name'];
                            
                            if(date("d/m",strtotime($dadoflup['datamaxima']))==(date("d/m"))){
                                echo "<p style='color:red;font-size:25px'>";
                            }else{
                                echo "<p style='color:orange'>";
                            }
                            echo "Realizar até ".date("d/m",strtotime($dadoflup['datamaxima']))."</h2>";
                            
                            echo "</p></h2>";
                            
                            //mostra fotos do contratante
                            echo "<a href=https://gig.dalegig.com/people_zone/".$photo1_gig." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo1_gig."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                            
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A, mostra mais 2 fotos
                                if(!empty($photo2_gig)){
                                    echo "&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/people_zone/".$photo2_gig." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo2_gig."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                                }
                                
                                if(!empty($photo3_gig)){
                                    echo "&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/people_zone/".$photo3_gig." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo3_gig."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                                }
                            }
                            
                            //mostra foto e perfil do músico
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/perfil_artista.php?id=".$id_banda." target=_blank><img src='".$dadobanda['url_image1']."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>
                            <br><br>
                            <a href=https://gig.dalegig.com/perfil_artista.php?id=".$id_banda." target=_blank class='btn btn-success'>Ver perfil musical de ".$dadobanda['name']."</a><br><br>";
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                echo "<p><b>Contato do contratante A</b> (está cadastrado na daleGig):  <b>".$tel_gig."</b>";
                                if(!empty($app_gig)){
                                    echo "(opção ".$app_gig." disponível)";
                                }
                                echo "</p> ";
                            }else{
                                //banco B
                                echo "<p><b>Contato do contratante B</b> (não conhece muito a daleGig):  <b>".$tel_gig."</b>";
                            }
                            
                            echo "<p><b>Um pouco sobre a GIG: </b>".$desc_gig."</p>
                            <br>
                            <h3 style='color:orange'><b>Abordagem</b></h3>  
                            <p>1) Perguntar se recebeu a proposta enviada ".date("d/m",strtotime($dataenviada));
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                echo " pelo Chico por <b>".$acordo;
                                if($acordo=="cache"){
                                 echo " de R$ ".$valor_acordo."</b>.</p>";
                                }else{
                                 echo "</b>.</p>";    
                                }
                            }else{
                                //banco B
                                echo " pela Lena (bookings@dalegig.com) com acordo em aberto a negociar</b>.</p>";
                            }
                            
                            echo "<p>2) Estudar pontos fortes do artista e se preparar com até 3</p>
                            <p>3) Obter a resposta: se interessa conversar diretamente com o artista. Caso ele responda interessado, ele receberá as próximas conversas por email";
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                echo " ou pelo painel dele na <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a>";
                            }
                                   
                            echo ". Se ele não se interessar, entender o porquê da recusa.</p>";       
                                
                            unset($check_sesc);       
                            $check_sesc = preg_match_all("/sesc/i",$nome_gig,$macthes);
                            if($check_sesc==0){
                                
                                echo "<p>4) Se sentir abertura: 
                                    <li>Convidar para responder as propostas que chegam por email, pois ele recebe remuneração (<a href=images/saldo1.png target=_blank>Explicativo 1</a> / <a href=images/saldo2.png target=_blank>Explicativo 2</a>)</li>";
                                
                                
                                if($dadoflup['bancoa_ou_b']==0){
                                    //banco A
                                    echo "<li>Convidar para deixar o perfil sempre atualizado pois a daleGig vai afinar as propostas a partir dele. Ele pode acessar seu perfil em <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a> usando o email <b>".$email_gig."</b></li>
                                    </p>";
                                }else{
                                    //banco B
                                    echo "<li>Convidar para se cadastrar em <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a> e assim receber propostas mais afinadas e realizar resgate de seus saldos acumulados</li>
                                    </p>";
                                }
                            }       
                                   
                            //botões de ação
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                echo "<a href=atualizar_gig.php?bancoa_ou_b=a&id_gig=".$id_gig_a." class='btn btn-outline-primary btn-rounded' style='color:black'>Atualizar dados errados deste produtor</a>
                                &nbsp;&nbsp;
                                <a href='followup_reenvia_proposta.php?reenvia=a&id_proposta=".$id_gig_ou_emailbox."' class='btn btn-outline-primary btn-rounded' style='color:black'>Solicitar reenvio de proposta</a>
                                <br><br>";
                                
                            }else{
                                //banco B
                                echo "<a href=atualizar_gig.php?bancoa_ou_b=b&id_gig=".$id_gig." class='btn btn-outline-primary btn-rounded' style='color:black'>Atualizar dados errados deste produtor</a>
                                &nbsp;&nbsp;
                                <a href='followup_reenvia_proposta.php?id_proposta=".$id_gig_ou_emailbox."' class='btn btn-outline-primary btn-rounded' style='color:black'>Solicitar reenvio de proposta</a>
                                <br><br>";
                            }  
                                   
                            echo "<h3 style='color:orange'><b>Resultado obtido</b></h3>   
                                <form method='post'>
                                    <input type=text name=post_banco style='opacity:0' value=".$dadoflup['bancoa_ou_b'].">
                                    <input type=text name=post_id_proposta style='opacity:0' value=".$id_gig_ou_emailbox.">
                                    <input type=text name=post_card style='opacity:0' value=".$dadoflup['id'].">
                                    <input type=text name=resposta maxlength=300 placeholder='Escreva aqui EXATAMENTE as palavras do contratante em primeira pessoa ou motivo de não conseguir falar com ele. Esta informação será colada para mostrar ao músico' class='form-control' required>
                                    <input type=submit name=rec_resposta_interessada value='Contratante interessado (+ R$ 7,00)' class='btn btn-success'>
                                    <input type=submit value='Proposta recusada (+ R$ 1,00)' name=rec_resposta_recusada class='btn btn-danger'>
                                    <input type=submit name=rec_nao_consegui value='Não consegui contatar' class='btn btn-warning'>
                                    
                                </form> ";       
                                   
                                    echo "</div>
                                    </div>
                                  </div>
                                </div> 
                            </div>";
                        }else{
                            
                            //saude
                            //FALTA FAZER
                            
                            echo "</div>
                                    </div>
                                  </div>
                                </div> 
                            </div>";
                            
                        }
                      }
                        
                    }else{
                        echo "Parabéns! Você não possui follow up a realizar. Está tudo em dia! yeah ;)";
                    
                        echo "</div>
                                    </div>
                                  </div>
                                </div> 
                            </div>";
                    }
                    
                    
                    
                    ?>
                              
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
