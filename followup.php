<?php

require_once 'CLASSES/bootstrap.php';

app_authorize_followup();
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

    app_verify_csrf_post();

    $resposta_raw = app_post('resposta');
    $resposta = app_db_escape($conn_dalegig, $resposta_raw);
    $post_banco = app_post_int('post_banco');
    $post_id_proposta = app_post_int('post_id_proposta');
    $post_card = app_post_int('post_card');
    $id_tour_post = app_post_int('id_tour_post');
    $nome_gig_post = app_post('nome_gig_post');
    

    if($post_banco==0){
        //banco A
        
        //consulta proposta
        $consultaproposta = "SELECT * FROM tours_gigs WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        //adicionar remuneração a equipe follow up
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','7',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
            
        //pega id_tour e nome_gig
        
        
        
        $consultaflup = "SELECT * FROM followup_cards WHERE id='$post_card'";
        $consultaflup2= mysqli_query($conn_dalegig,$consultaflup);
        $dadoflup = $consultaflup2->fetch_array();
        unset ($id_banda_search);
        unset ($id_gig_search);
        $id_banda_search = $dadoflup['id_banda'];
        $id_gig_search = $dadoflup['id_gig'];
        
        $consultatours = "SELECT * FROM tours WHERE id_musico='$id_banda_search' ORDER BY id_tour DESC LIMIT 2";
        $consultatours2= mysqli_query($conn_dalegig,$consultatours);
        while($dadotours = $consultatours2->fetch_array()){
            
            unset ($id_tour_search);
            $id_tour_search = $dadotours['id_tour'];
            
            //com cada tour verifica se tem essa gig
            $consultagig = "SELECT * FROM tours_gigs WHERE id_gig='$id_gig_search' AND id_tour='$id_tour_search'";
            $consultagig2= mysqli_query($conn_dalegig,$consultagig);
            if(mysqli_num_rows($consultagig2)==1){
                
                $dadogig = $consultagig2->fetch_array();
                
                $id_tour_found = $id_tour_search;
                unset($nome_gig_post);
                $nome_gig_post = $dadogig['gig_name'];
                
                
            }
        }
        
        
    
        
        //adiciona saldo a GIG
        $inseresaldo = "INSERT INTO saldo (id_gig,bancoa_ou_b,valor,datahora) VALUES ('$id_gig_search','a','1.34',NOW())";
        if(mysqli_query($conn_gig_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        //adiciona saude a esta gig
        $consultasaude = "SELECT * FROM cadastro_venue WHERE ids_dalegig LIKE '%$id_gig_search%' AND online='1'";
        $consultasaude2= mysqli_query($conn_gig_dalegig,$consultasaude);
        while($dadosaude = $consultasaude2->fetch_array()){
            
            // com cada gig encontrada obtem a saude e adiciona 1 ponto (sabemos que não é exato mas tudo bem)
            $id_gig_a_saude = $dadosaude['id'];
            $saude_gig = ($dadosaude['saude']+3);
            
            
            //atualiza
            $updatesaude = "UPDATE cadastro_venue SET saude='$saude_gig' WHERE id='$id_gig_a_saude'";
            if(mysqli_query($conn_gig_dalegig,$updatesaude)){
                //feito
            }
        }
        
        
        
        
        unset($msg);
        
        
        $msg = "<b><font color=yellow>Equipe fez o follow up de banco A</font></b> e obteve uma resposta de contratatante interessado. Adicione as infos abaixo no chat do músico.<br><br><b>TOUR ID: ".$id_tour_found."<br>GIG: ".$nome_gig_post."<br>Palavras do contratante: </b>".$resposta;
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='1',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,feito,datahora) VALUES ('$msg','1',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Seu saldo será acumulado em alguns segundos.')</script>";
              //avisa equipe para registar no gerente dalegig
              echo "<script>window.location.href='inserir_gerente.php?tour=".rawurlencode((string) $id_tour_found)."&resposta=1&gig=".rawurlencode((string) $nome_gig_post)."&motivo=".rawurlencode($resposta_raw)."'</script>";
            }
            
        }
        
        
        
    }else{
        //banco B
        $consultaproposta = "SELECT * FROM send_email_box WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        
        //adicionar remuneração a equipe follow up
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','7',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        
        
        
        //assinalar como respondida
        unset($sujeitoproposta);
        $sujeitoproposta = $dadoproposta['subject'];
        unset($idtourproposta);
        $idtourproposta = $dadoproposta['id_tour'];
        
        $updateresposta = "UPDATE send_email_box SET send_receive='1' WHERE subject='$sujeitoproposta' AND id_tour='$idtourproposta'";
        if(mysqli_query($conn_dalegig,$updateresposta)){
            //feito
        }
        
        //adicionar saldo
        unset($emailgigb);
        $emailgigb = $dadoproposta['email_destination'];
        
        $consultaidb = "SELECT * FROM banco_b_contratantes WHERE email='$emailgigb' AND deleted='0'";
        $consultaidb2= mysqli_query($conn_gig_dalegig,$consultaidb);
        if(mysqli_num_rows($consultaidb2)>0){
            
            $dadoidb = $consultaidb2->fetch_array();
            unset($idb);
            $idb = $dadoidb['id'];
            
            $addsaldo = "INSERT INTO saldo (id_gig,bancoa_ou_b,valor,datahora) VALUES ('$idb','b','1.69',NOW())";
            if(mysqli_query($conn_gig_dalegig,$addsaldo)){
                //feito
            }
            
            //adicionar saude
            unset($saude);
            $saude = ($dadoidb['saude']+3);
            
            $updatesaude = "UPDATE banco_b_contratantes SET saude='$saude' WHERE id='$idb'";
            if(mysqli_query($conn_gig_dalegig,$updatesaude)){
                //feito
            }
            
            
            
            //adicionar msg chat
            
            //consultabanda
            unset($tourid);
            $tourid = $dadoproposta['id_tour'];
            $consultabanda = "SELECT * FROM tours WHERE id_tour='$tourid'";
            $consultabanda2= mysqli_query($conn_dalegig,$consultabanda);
            $dadobd = $consultabanda2->fetch_array();
            unset($musicoid);
            $musicoid = $dadobd['id_musico'];
            
            //pega token da conversa
            $consultatoken = "SELECT * FROM chat_conversa_proposta WHERE id_gig_bancob='$idb' AND id_banda='$musicoid'";
            $consultatoken2= mysqli_query($conn_dalegig,$consultatoken);
            $dadotoken = $consultatoken2->fetch_array();
            
            unset ($tokenchat);
            $tokenchat = $dadotoken['conversa'];
            
            
            if(empty($tokenchat)){
                $tokenchat = openssl_random_pseudo_bytes(26);
                $tokenchat = bin2hex($tokenchat);
            }
            
            
            unset($cidadegig);
            $cidadegig = $dadoidb['cidade']."/".$dadoidb['estado'];
            
            //primeiro msg chico
            unset($msg);
            $msg = "Proposta enviada com acordo aberto a negociar";
            
            
            $inseremsg = "INSERT INTO chat_conversa_proposta (conversa,proposta,id_gig_bancob,cidade,id_banda,ativa,remetente,msg,datarecord) VALUES ('$tokenchat','turbinatour','$idb','$cidadegig','$musicoid','1','chico','$msg',NOW())";
            if(mysqli_query($conn_dalegig,$inseremsg)){
                //feito
                
                //segundo msg da gig
                $inseremsg = "INSERT INTO chat_conversa_proposta (conversa,proposta,id_gig_bancob,cidade,id_banda,ativa,remetente,msg,datarecord) VALUES ('$tokenchat','turbinatour','$idb','$cidadegig','$musicoid','1','gig','$resposta',NOW())";
                if(mysqli_query($conn_dalegig,$inseremsg)){
                 //feito   
                }
            }
            
        }
        
        
        
        
    
        unset($msg);
        $msg = "<b><font color=yellow>Equipe fez o follow up de banco B</font></b> e obteve uma resposta de contratatante interessado. Adicione as infos abaixo no chat do músico.<br><br><b>TOUR ID: ".$dadoproposta['id_tour']."<br>Assunto do email: ".$dadoproposta['subject']."<br>Palavras do contratante: </b>".$resposta;
        
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='1',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            

            $inseretarefa = "INSERT INTO log_followup (tarefa,feito,datahora) VALUES ('$msg','1',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Você será encaminhado para a tela de passo a passo de inserção de feedback para contratante A.')</script>";
              echo "<script>window.location.href='followup.php'</script>";
            }
            
        }
    }
    
    
    
}        
      
//run btn registrar resposta recusada
if(isset($_POST['rec_resposta_recusada'])){

    app_verify_csrf_post();

    $resposta_raw = app_post('resposta');
    $resposta = app_db_escape($conn_dalegig, $resposta_raw);
    $post_banco = app_post_int('post_banco');
    $post_id_proposta = app_post_int('post_id_proposta');
    $post_card = app_post_int('post_card');
    $id_tour_post = app_post_int('id_tour_post');
    $nome_gig_post = app_post('nome_gig_post');
    
    //envia para o Rapha
    if($post_banco==0){
        //banco A
        
        //adicionar remuneração a equipe follow up
        $inseresaldo = "INSERT INTO followup_saldo (user,valor,datahora) VALUES ('1','1',NOW())";
        if(mysqli_query($conn_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        //consulta proposta
        $consultaproposta = "SELECT * FROM tours_gigs WHERE id='$post_id_proposta'";
        $consultaproposta2= mysqli_query($conn_dalegig,$consultaproposta);
        $dadoproposta = $consultaproposta2->fetch_array();
        
        //pega id_tour e nome_gig
        
        $consultaflup = "SELECT * FROM followup_cards WHERE id='$post_card'";
        $consultaflup2= mysqli_query($conn_dalegig,$consultaflup);
        $dadoflup = $consultaflup2->fetch_array();
        unset ($id_banda_search);
        unset ($id_gig_search);
        $id_banda_search = $dadoflup['id_banda'];
        $id_gig_search = $dadoflup['id_gig'];
        
        $consultatours = "SELECT * FROM tours WHERE id_musico='$id_banda_search' ORDER BY id_tour DESC LIMIT 2";
        $consultatours2= mysqli_query($conn_dalegig,$consultatours);
        while($dadotours = $consultatours2->fetch_array()){
            
            unset ($id_tour_search);
            $id_tour_search = $dadotours['id_tour'];
            
            //com cada tour verifica se tem essa gig
            $consultagig = "SELECT * FROM tours_gigs WHERE id_gig='$id_gig_search' AND id_tour='$id_tour_search'";
            $consultagig2= mysqli_query($conn_dalegig,$consultagig);
            if(mysqli_num_rows($consultagig2)==1){
                
                $dadogig = $consultagig2->fetch_array();
                
                $id_tour_found = $id_tour_search;
                unset($nome_gig_post);
                $nome_gig_post = $dadogig['gig_name'];
                
                
            }
        }
        
        
        //adiciona saldo a GIG
        $inseresaldo = "INSERT INTO saldo (id_gig,bancoa_ou_b,valor,datahora) VALUES ('$id_gig_search','a','0.24',NOW())";
        if(mysqli_query($conn_gig_dalegig,$inseresaldo)){
            //saldo inserido
        }
        
        //adiciona saude a esta gig
        $consultasaude = "SELECT * FROM cadastro_venue WHERE ids_dalegig LIKE '%$id_gig_search%' AND online='1'";
        $consultasaude2= mysqli_query($conn_gig_dalegig,$consultasaude);
        while($dadosaude = $consultasaude2->fetch_array()){
            
            // com cada gig encontrada obtem a saude e adiciona 1 ponto (sabemos que não é exato mas tudo bem)
            $id_gig_a_saude = $dadosaude['id'];
            $saude_gig = ($dadosaude['saude']+1);
            
            
            //atualiza
            $updatesaude = "UPDATE cadastro_venue SET saude='$saude_gig' WHERE id='$id_gig_a_saude'";
            if(mysqli_query($conn_gig_dalegig,$updatesaude)){
                //feito
            }
        }
        
        
            
        unset($msg);
        $msg = "<b><font color=yellow>Equipe fez o follow up de banco A</font></b> e obteve uma resposta de contratatante recusada. Adicione as infos abaixo no chat do músico.<br><br><b>TOUR ID: ".$id_tour_found."<br>GIG: ".$nome_gig_post."<br>Palavras do contratante: </b>".$resposta;
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='2',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,feito,datahora) VALUES ('$msg','1',NOW())";
            if(mysqli_query($conn_gig_dalegig,$inseretarefa)){
              //inserida
              echo "<script>alert('Follow up registrado com sucesso e está em processo de aprovação! Você será encaminhado para a tela de passo a passo de inserção de feedback para contratante A.')</script>";
              //avisa equipe para registar no gerente dalegig
              echo "<script>window.location.href='inserir_gerente.php?tour=".rawurlencode((string) $id_tour_found)."&resposta=2&gig=".rawurlencode((string) $nome_gig_post)."&motivo=".rawurlencode($resposta_raw)."'</script>";
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
        
        
        
        //assinalar como respondida
        unset($sujeitoproposta);
        $sujeitoproposta = $dadoproposta['subject'];
        unset($idtourproposta);
        $idtourproposta = $dadoproposta['id_tour'];
        
        $updateresposta = "UPDATE send_email_box SET send_receive='1' WHERE subject='$sujeitoproposta' AND id_tour='$idtourproposta'";
        if(mysqli_query($conn_dalegig,$updateresposta)){
            //feito
        }
        
        //adicionar saldo
        unset($emailgigb);
        $emailgigb = $dadoproposta['email_destination'];
        
        $consultaidb = "SELECT * FROM banco_b_contratantes WHERE email='$emailgigb' AND deleted='0'";
        $consultaidb2= mysqli_query($conn_gig_dalegig,$consultaidb);
        if(mysqli_num_rows($consultaidb2)>0){
            
            $dadoidb = $consultaidb2->fetch_array();
            unset($idb);
            $idb = $dadoidb['id'];
            
            $addsaldo = "INSERT INTO saldo (id_gig,bancoa_ou_b,valor,datahora) VALUES ('$idb','b','0.16',NOW())";
            if(mysqli_query($conn_gig_dalegig,$addsaldo)){
                //feito
            }
            
            //adicionar saude
            unset($saude);
            $saude = ($dadoidb['saude']+1);
            
            $updatesaude = "UPDATE banco_b_contratantes SET saude='$saude' WHERE id='$idb'";
            if(mysqli_query($conn_gig_dalegig,$updatesaude)){
                //feito
            }
            
            
            
            //adicionar msg chat
            
            //consultabanda
            unset($tourid);
            $tourid = $dadoproposta['id_tour'];
            $consultabanda = "SELECT * FROM tours WHERE id_tour='$tourid'";
            $consultabanda2= mysqli_query($conn_dalegig,$consultabanda);
            $dadobd = $consultabanda2->fetch_array();
            unset($musicoid);
            $musicoid = $dadobd['id_musico'];
        
            
            //pega token da conversa
            $consultatoken = "SELECT * FROM chat_conversa_proposta WHERE id_gig_bancob='$idb' AND id_banda='$musicoid'";
            
            $consultatoken2= mysqli_query($conn_dalegig,$consultatoken);
            $dadotoken = $consultatoken2->fetch_array();
            
            unset ($tokenchat);
            $tokenchat = $dadotoken['conversa'];
            
            if(empty($tokenchat)){
                $tokenchat = openssl_random_pseudo_bytes(26);
                $tokenchat = bin2hex($tokenchat);
            }
            
            unset($cidadegig);
            $cidadegig = $dadoidb['cidade']."/".$dadoidb['estado'];
            
            //primeiro msg chico
            unset($msg);
            $msg = "Proposta enviada com acordo aberto a negociar";
            
            
            $inseremsg = "INSERT INTO chat_conversa_proposta (conversa,proposta,id_gig_bancob,cidade,id_banda,ativa,remetente,msg,datarecord) VALUES ('$tokenchat','turbinatour','$idb','$cidadegig','$musicoid','1','chico','$msg',NOW())";
            if(mysqli_query($conn_dalegig,$inseremsg)){
                //feito
                
                //segundo msg da gig
                $inseremsg = "INSERT INTO chat_conversa_proposta (conversa,proposta,id_gig_bancob,cidade,id_banda,ativa,remetente,msg,datarecord) VALUES ('$tokenchat','turbinatour','$idb','$cidadegig','$musicoid','1','gig','$resposta',NOW())";
                if(mysqli_query($conn_dalegig,$inseremsg)){
                 //feito   
                }
            }
            
        }
        
        
        
        unset($msg);
        $msg = "<b><font color=yellow>Wilton fez o follow up de banco B</font></b> e obteve uma resposta de contratatante recusada. Adicione as infos abaixo no chat do músico e acumule mais R$ 0,50 na sua remuneração.<br><br><b>TOUR ID: ".$dadoproposta['id_tour']."<br>Assunto do email proposta enviado: ".$dadoproposta['subject']."<br>Palavras do contratante: </b>".$resposta;
        
        
        //atualiza o follow up card com o resultado
        $updateflup = "UPDATE followup_cards SET resultado='2',resultado_texto='$resposta' WHERE id='$post_card'";
        if(mysqli_query($conn_dalegig,$updateflup)){
            
            $inseretarefa = "INSERT INTO log_followup (tarefa,feito,datahora) VALUES ('$msg','1',NOW())";
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

    app_verify_csrf_post();

    $resposta = app_db_escape($conn_dalegig, app_post('resposta'));
    $post_banco = app_post_int('post_banco');
    $post_id_proposta = app_post_int('post_id_proposta');
    $post_card = app_post_int('post_card');
    
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
      
      
// A limpeza automatica de cards duplicados foi desativada.
// Apagar registros em um GET da tela principal era um comportamento inseguro.
      
      
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
                      $consultaflup = "SELECT * FROM followup_cards WHERE resultado='0' AND resultado_texto='' AND id_gig>'0' AND datamaxima>=NOW()-INTERVAL 2 DAY ORDER by datamaxima ASC";
                      $consultaflup2= mysqli_query($conn_dalegig,$consultaflup);
                      echo mysqli_num_rows($consultaflup2);    
                    ?> follow ups</b> em andamento</p>
                    <br><br>
                    <?php
                    //exibe follow ups pendentes
                if(mysqli_num_rows($consultaflup2)>0){
                while($dadoflup = $consultaflup2->fetch_array()){
                    
                    
                    
                            $id_card = $dadoflup['id'];
                            
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
                                
                                
                                //obtem o numero da tour
                                $consultatours = "SELECT * FROM tours WHERE id_musico='$id_banda' ORDER BY data_record_here DESC LIMIT 2";
                                $consultatours2=mysqli_query($conn_dalegig,$consultatours);
                                $dadotour = $consultatours2->fetch_array();

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
                    
            $nome_gig_html = h($nome_gig);
            $nome_produtor_html = h($nome_produtor);
            $cidade_gig_html = h($cidade_gig);
            $tel_gig_html = h($tel_gig);
            $app_gig_html = h($app_gig);
            $desc_gig_html = h($desc_gig);
            $email_gig_html = h($email_gig);
            $artist_name_html = h($dadobanda['name']);
            $artist_image_url = h($dadobanda['url_image1']);
            $photo1_gig_url = h($photo1_gig);
            $photo2_gig_url = h($photo2_gig);
            $photo3_gig_url = h($photo3_gig);
            $audience_estimate = app_guess_audience_estimate($desc_gig, $cidade_gig);
            $audience_estimate_html = h($audience_estimate);
            $ai_prompt_html = h(app_ai_coherence_prompt($dadobanda['name'], $nome_gig, $cidade_gig, $audience_estimate));

            //check se o produtor existe para abrir card ou cancelar card
            if(!empty($nome_produtor)&&!empty($nome_gig)){
                   echo "<div class='row mt-12'>
						<div class='col-lg-12 grid-margin stretch-card'>
                          <div class='card' style='border:2px solid purple'>
                            <div class='card-body'>
                              <h2 class='card-title' style='font-size:26px'></h2>
                              <div class='table-responsive'> ";
                    
                        //consulta se é cutucada, recutucada ou saude
                    switch($dadoflup['cutucada_ou_saude']){
                                
                        case 0:   
                            //cutucada proposta
                        
                            echo "<h2 class='card-title' style='font-size:26px'>Contatar ".$nome_produtor_html." (".$nome_gig_html." em ".$cidade_gig_html.") sobre artista ".$artist_name_html;
                            
                            
                            
                            
                            
                            if(date("d/m",strtotime($dadoflup['datamaxima']))==(date("d/m"))){
                                echo "<p style='color:red;font-size:25px'>";
                            }else{
                                echo "<p style='color:orange'>";
                            }
                            echo "Realizar até ".date("d/m",strtotime($dadoflup['datamaxima']));
                            
                            //avisar se existe mais outros follow ups para este mesmo contratante (para fazer tudo de uma só vez)
                            switch($dadoflup['bancoa_ou_b']){
                                case '0':
                                    //banco a
                                    echo " <font color=red>- Verifique se tem outros artistas para este mesmo contratante para fazer um contato único</font>";
                                    
                                    
                                break;
                                case '1':
                                    //banco b
                                    $consultaoutros = "SELECT * FROM followup_cards WHERE id_gig='$id_gig' AND id<>'$id_card' AND resultado='0' AND datamaxima<=NOW()+ INTERVAL 1 DAY";
                                    $consultaoutros2= mysqli_query($conn_dalegig,$consultaoutros);
                                    if(mysqli_num_rows($consultaoutros2)>0){
                                        echo " <font color=red>- Aproveite para falar do follow up dos seguintes artistas (procure nesta lista): ";
                                        while($dadooutro = $consultaoutros2->fetch_array()){
                                            //consulta artista
                                            $outro_id_banda = (int) $dadooutro['id_banda'];
                                            $consultaart = "SELECT * FROM artistas WHERE id_banda='$outro_id_banda'";
                                            $consultaart2= mysqli_query($conn_dalegig,$consultaart);
                                            $dadoart = $consultaart2->fetch_array();
                                            
                                            echo $dadoart['name']." ";
                                        }
                                        echo "</font>";
                                    }
                                    
                                break; 
                            }
                            
                            echo "</p></h2></h2>";
                            
                            //mostra fotos do contratante
                            echo "<a href=https://gig.dalegig.com/people_zone/".$photo1_gig_url." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo1_gig_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                            
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A, mostra mais 2 fotos
                                if(!empty($photo2_gig)){
                                    echo "&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/people_zone/".$photo2_gig_url." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo2_gig_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                                }
                                
                                if(!empty($photo3_gig)){
                                    echo "&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/people_zone/".$photo3_gig_url." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo3_gig_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                                }
                            }
                            
                            //mostra foto e perfil do músico
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/perfil_artista.php?id=".$id_banda." target=_blank><img src='".$artist_image_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>
                            <br><br>
                            <a href=https://gig.dalegig.com/perfil_artista.php?id=".$id_banda." target=_blank class='btn btn-success'>Ver perfil musical de ".$artist_name_html."</a><br><br>";
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                echo "<p><b>Contato do contratante A</b> (está cadastrado na daleGig):  <b>".$tel_gig_html."</b>";
                                if(!empty($app_gig)){
                                    echo "(opcao ".$app_gig_html." disponivel)";
                                }
                                echo "</p> ";
                            }else{
                                //banco B
                                echo "<p><b>Contato do contratante B</b> (nao conhece muito a daleGig):  <b>".$tel_gig_html."</b>";
                            }
                            
                            echo "<p><b>Um pouco sobre a GIG: </b>".$desc_gig_html."</p>
                            <p><b>Estimativa de público para usar na oferta/WhatsApp: </b>".$audience_estimate_html."</p>
                            <br>
                            <h3 style='color:orange'><b>Abordagem</b></h3>  
                            <p>1) Perguntar o que acha da proposta enviada por email dia  ".date("d/m",strtotime($dataenviada));
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                //obtém o número da tour
                                 unset($id_tour_check);
                                 $id_tour_check = $dadotour['id_tour'];
                                 
                                echo ". <a href=https://www.dalegig.com/gerente/dashboard/".$id_tour_check." target=_blank>Clique aqui para acessar a tour #".$id_tour_check."</a> e clica no contratante <b>".$nome_gig_html."</b>. Esta proposta foi enviada";
                            }else{
                                //banco B
                                echo ". <a href=followup_preview.php?id_email_sendbox=".$id_gig_ou_emailbox.">Clique aqui para ver a proposta enviada</a>";
                                
                            }
                            
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
                            
                            echo "<p>2) Antes de colar qualquer oferta no WhatsApp, revise a mensagem com IA usando este contexto: <textarea class='form-control' rows='3' readonly>".$ai_prompt_html."</textarea></p>
                            <p>3) Estudar pontos fortes do artista e preparar até 3 argumentos conectados com o perfil da GIG, sem inventar dados.</p>
                            <p>4) Se a GIG for nova ou estiver pouco preenchida, fazer onboarding: perguntar tipo de programação, faixa real de público, dias/horários preferidos, estilos que funcionam, ticket/cache médio, restrições técnicas e melhor canal para propostas.</p>
                            <p>5) Obter a resposta: se interessa conversar diretamente com o artista. Caso ele responda interessado, ele receberá as próximas conversas por email";
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                echo " ou pelo painel dele na <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a>";
                            }
                                   
                            echo ". Se ele não se interessar, entender o porquê da recusa.</p>";       
                                
                            unset($check_sesc);       
                            $check_sesc = preg_match_all("/sesc/i",$nome_gig,$macthes);
                            if($check_sesc==0){
                                
                                echo "<p>6) Se sentir abertura:
                                    <li>Convidar para responder as propostas que chegam por email, pois ele recebe remuneração (<a href=images/saldo1.png target=_blank>Explicativo 1</a> / <a href=images/saldo2.png target=_blank>Explicativo 2</a>)</li>";
                                
                                
                                if($dadoflup['bancoa_ou_b']==0){
                                    //banco A
                                    echo "<li>Convidar para deixar o perfil sempre atualizado pois a daleGig vai afinar as propostas a partir dele. Ele pode acessar seu perfil em <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a> usando o email <b>".$email_gig_html."</b></li>
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
                                <a href='followup_reenvia_proposta.php?reenvia=a&id_banda=".$id_banda."&venue=".rawurlencode($nome_gig)."' class='btn btn-outline-primary btn-rounded' style='color:black'>Solicitar reenvio de proposta</a>
                                &nbsp;&nbsp;<a href=desativar_gig.php?bancoa_ou_b=a&id_gig=".$id_gig_a." class='btn btn-outline-primary btn-rounded' style='color:black'>Desativar este contratante</a>
                                <br><br>";
                                
                            }else{
                                //banco B
                                echo "<a href=atualizar_gig.php?bancoa_ou_b=b&id_gig=".$id_gig." class='btn btn-outline-primary btn-rounded' style='color:black'>Atualizar dados errados deste produtor</a>
                                &nbsp;&nbsp;
                                <a href='followup_reenvia_proposta.php?id_proposta=".$id_gig_ou_emailbox."' class='btn btn-outline-primary btn-rounded' style='color:black'>Solicitar reenvio de proposta</a>&nbsp;&nbsp;
                                <a href=desativar_gig.php?bancoa_ou_b=b&id_gig=".$id_gig." class='btn btn-outline-primary btn-rounded' style='color:black'>Desativar este contratante</a>
                                <br><br>";
                            }  
                                   
                            echo "<h3 style='color:orange'><b>Resultado obtido</b></h3>   
                                <form method='post'>
                                    ".app_csrf_input()."
                                    <input type=hidden name=post_banco value=".$dadoflup['bancoa_ou_b'].">
                                    <input type=hidden name=post_id_proposta value=".$id_gig_ou_emailbox.">
                                    <input type=hidden name=post_card value=".$dadoflup['id'].">
                                    <input type=text name=resposta maxlength=300 placeholder='Escreva aqui EXATAMENTE as palavras do contratante em primeira pessoa ou motivo de não conseguir falar com ele. Esta informação será colada para mostrar ao músico' class='form-control' required>
                                    <input type=submit name=rec_resposta_interessada value='Contratante interessado (+ R$ 7,00)' class='btn btn-success'>
                                    <input type=submit value='Proposta recusada (+ R$ 1,00)' name=rec_resposta_recusada class='btn btn-danger'>
                                    <input type=submit name=rec_nao_consegui value='Não consegui contatar' class='btn btn-warning'>";
                            
                            //se for banco a pega nome da gig e id_tour e manda via post em opacity:0
                            if($dadoflup['bancoa_ou_b']==0){
                              
                                echo "<input type=hidden name=nome_gig_post value='".h($nome_gig)."'>";
                                
                                //pega o id desta tour
                                $consultaidtour = "SELECT * FROM tours_gigs WHERE gig_name='$nome_gig' AND data_record_here>NOW()-INTERVAL 30 DAY";
                                $consultaidtour2= mysqli_query($conn_dalegig,$consultaidtour);
                                while($dadoidtour = $consultaidtour2->fetch_array()){
                                    
                                    //com cada gig checa se é este artista
                                    unset($id_tour_check);
                                    $id_tour_check = $dadoidtour['id_tour'];
                                    
                                    $consultaartistatour = "SELECT * FROM tours WHERE id_tour='$id_tour_check' AND id_musico='$id_banda'";
                                    $consultaartistatour2= mysqli_query($conn_dalegig,$consultaartistatour);
                                    if(mysqli_num_rows($consultaartistatour2)==1){
                                        $id_tour_post=$id_tour_check;
                                    }
                                    
                                }
                                echo "<input type=hidden name=id_tour_post value='".$id_tour_post."'>";
                                
                            }      
                            
                            
                            echo "</form> ";       
                                   
                                    echo "</div>
                                    </div>
                                  </div>
                                </div> 
                            </div>";
                          
                          break;
                          case 1:
                            //recutucada - para cutucar contratantes interessados que não respondem mais (1 única vez)
                             echo "<h2 class='card-title' style='font-size:26px'><font color=green>Recutucar</font> ".$nome_produtor_html." (".$nome_gig_html." em ".$cidade_gig_html.") sobre artista ".$artist_name_html;
                            
                            if(date("d/m",strtotime($dadoflup['datamaxima']))==(date("d/m"))){
                                echo "<p style='color:red;font-size:25px'>";
                            }else{
                                echo "<p style='color:orange'>";
                            }
                            echo "Realizar até ".date("d/m",strtotime($dadoflup['datamaxima']));
                            
                            //avisar se existe mais outros follow ups para este mesmo contratante (para fazer tudo de uma só vez)
                            switch($dadoflup['bancoa_ou_b']){
                                case '0':
                                    //banco a
                                    echo " <font color=red>- Verifique se tem outros artistas para este mesmo contratante para fazer um contato único</font>";
                                    
                                    
                                break;
                                case '1':
                                    //banco b
                                    $consultaoutros = "SELECT * FROM followup_cards WHERE id_gig='$id_gig' AND id<>'$id_card' AND resultado='0' AND datamaxima<=NOW()+ INTERVAL 1 DAY";
                                    $consultaoutros2= mysqli_query($conn_dalegig,$consultaoutros);
                                    if(mysqli_num_rows($consultaoutros2)>0){
                                        echo " <font color=red>- Aproveite para falar do follow up dos seguintes artistas (procure nesta lista): ";
                                        while($dadooutro = $consultaoutros2->fetch_array()){
                                            //consulta artista
                                            $outro_id_banda = (int) $dadooutro['id_banda'];
                                            $consultaart = "SELECT * FROM artistas WHERE id_banda='$outro_id_banda'";
                                            $consultaart2= mysqli_query($conn_dalegig,$consultaart);
                                            $dadoart = $consultaart2->fetch_array();
                                            
                                            echo $dadoart['name']." ";
                                        }
                                        echo "</font>";
                                    }
                                    
                                break; 
                            }
                            
                            echo "</p></h2></h2>";
                            
                            //mostra fotos do contratante
                            echo "<a href=https://gig.dalegig.com/people_zone/".$photo1_gig_url." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo1_gig_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                            
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A, mostra mais 2 fotos
                                if(!empty($photo2_gig)){
                                    echo "&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/people_zone/".$photo2_gig_url." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo2_gig_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                                }
                                
                                if(!empty($photo3_gig)){
                                    echo "&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/people_zone/".$photo3_gig_url." target=_blank><img src='https://gig.dalegig.com/people_zone/".$photo3_gig_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>";
                                }
                            }
                            
                            //mostra foto e perfil do músico
                            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=https://gig.dalegig.com/perfil_artista.php?id=".$id_banda." target=_blank><img src='".$artist_image_url."' style='height:150px;border-radius:10px;cursor:zoom-in'></a>
                            <br><br>
                            <a href=https://gig.dalegig.com/perfil_artista.php?id=".$id_banda." target=_blank class='btn btn-success'>Ver perfil musical de ".$artist_name_html."</a><br><br>";
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                echo "<p><b>Contato do contratante A</b> (está cadastrado na daleGig):  <b>".$tel_gig_html."</b>";
                                if(!empty($app_gig)){
                                    echo "(opcao ".$app_gig_html." disponivel)";
                                }
                                echo "</p> ";
                            }else{
                                //banco B
                                echo "<p><b>Contato do contratante B</b> (nao conhece muito a daleGig):  <b>".$tel_gig_html."</b>";
                            }
                            
                            echo "<p><b>Um pouco sobre a GIG: </b>".$desc_gig_html."</p>
                            <p><b>Estimativa de público para orientar a conversa: </b>".$audience_estimate_html."</p>
                            <br>
                            <h3 style='color:orange'><b>Abordagem da RECUTUCADA</b></h3>";
                            
                             if($dadoflup['bancoa_ou_b']==0){
                                //banco A
                                //obtém o número da tour
                                 unset($id_tour_check);
                                 $id_tour_check = $dadotour['id_tour'];
                                 
                                echo "<p>1) <a href=https://www.dalegig.com/gerente/dashboard/".$id_tour_check." target=_blank>Clique aqui para acessar o chat da tour #".$id_tour_check."</a> e clica no contratante <b>".$nome_gig_html."</b> para ler a conversa";
                            }else{
                                //banco B
                                //obtém o token da conversa
                                 $consultatoken = "SELECT * FROM artistas WHERE id_banda='$id_banda'";
                                 $consultatoken2= mysqli_query($conn_dalegig,$consultatoken);
                                 $dadotoken = $consultatoken2->fetch_array();
                                 unset($token);
                                 $token = $dadotoken['temp_token'];
                                 
                                echo "<p>1) <a href=https://workgreat.today/premium/2dlpro/dalegig/rpa/talk_user.php?token=".$token." target=_blank>Clique aqui para acessar o chat de negociações</a> e clica no contratante ".$nome_gig_html." para ler a conversa";
                                
                            }
                            
                            
                            echo "<p>2) Revisar a mensagem com IA antes de enviar: <textarea class='form-control' rows='3' readonly>".$ai_prompt_html."</textarea></p>";
                            echo "<p>3) Estudar pontos fortes do artista</p>";


                            echo "<p>4) Entra em contato com o contratante informando a última mensagem do músico e solicitando uma resposta a esta mensagem e se aceita uma chamada com o artista em dia/hora a desejar.";
                            
                            if($dadoflup['bancoa_ou_b']==0){
                                echo " ou pelo painel dele na <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a>";
                            }
                                   
                            echo ". Se ele não se interessar, entender o porquê da recusa.</p>";       
                                
                            unset($check_sesc);       
                            $check_sesc = preg_match_all("/sesc/i",$nome_gig,$macthes);
                            if($check_sesc==0){
                                
                                echo "<p>5) Se sentir abertura:
                                    <li>Convidar para responder as propostas que chegam por email, pois ele recebe remuneração (<a href=images/saldo1.png target=_blank>Explicativo 1</a> / <a href=images/saldo2.png target=_blank>Explicativo 2</a>)</li>";
                                
                                
                                if($dadoflup['bancoa_ou_b']==0){
                                    //banco A
                                    echo "<li>Convidar para deixar o perfil sempre atualizado pois a daleGig vai afinar as propostas a partir dele. Ele pode acessar seu perfil em <a href=https://gig.dalegig.com target=_blank>gig.dalegig.com</a> usando o email <b>".$email_gig_html."</b></li>
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
                                <a href='followup_reenvia_proposta.php?reenvia=a&id_banda=".$id_banda."&venue=".rawurlencode($nome_gig)."' class='btn btn-outline-primary btn-rounded' style='color:black'>Solicitar reenvio de proposta</a>
                                &nbsp;&nbsp;<a href=desativar_gig.php?bancoa_ou_b=a&id_gig=".$id_gig_a." class='btn btn-outline-primary btn-rounded' style='color:black'>Desativar este contratante</a>
                                <br><br>";
                                
                            }else{
                                //banco B
                                echo "<a href=atualizar_gig.php?bancoa_ou_b=b&id_gig=".$id_gig." class='btn btn-outline-primary btn-rounded' style='color:black'>Atualizar dados errados deste produtor</a>
                                &nbsp;&nbsp;
                                <a href='followup_reenvia_proposta.php?id_proposta=".$id_gig_ou_emailbox."' class='btn btn-outline-primary btn-rounded' style='color:black'>Solicitar reenvio de proposta</a>
                                &nbsp;&nbsp;<a href=desativar_gig.php?bancoa_ou_b=b&id_gig=".$id_gig." class='btn btn-outline-primary btn-rounded' style='color:black'>Desativar este contratante</a>
                                <br><br>";
                            }  
                                   
                            echo "<h3 style='color:orange'><b>Resultado obtido</b></h3>   
                                <form method='post'>
                                    ".app_csrf_input()."
                                    <input type=hidden name=post_banco value=".$dadoflup['bancoa_ou_b'].">
                                    <input type=hidden name=post_id_proposta value=".$id_gig_ou_emailbox.">
                                    <input type=hidden name=post_card value=".$dadoflup['id'].">
                                    <input type=text name=resposta maxlength=300 placeholder='Escreva aqui EXATAMENTE as palavras do contratante em primeira pessoa ou motivo de não conseguir falar com ele. Esta informação será colada para mostrar ao músico' class='form-control' required>
                                    <input type=submit name=rec_resposta_interessada value='Contratante continua interessado (+ R$ 7,00)' class='btn btn-success'>
                                    <input type=submit value='Contratante desistiu (+ R$ 1,00)' name=rec_resposta_recusada class='btn btn-danger'>
                                    <input type=submit name=rec_nao_consegui value='Não consegui contatar' class='btn btn-warning'>";
                            
                            //se for banco A pega nome da gig e id_tour e manda via post em opacity:0
                            if($dadoflup['bancoa_ou_b']==0){
                              
                                echo "<input type=hidden name=nome_gig_post value='".h($nome_gig)."'>";
                                echo "<input type=hidden name=id_tour_post value='".$id_tour_check."'>";
                                
                            }      
                            
                            
                            echo "</form> ";       
                                   
                                    echo "</div>
                                    </div>
                                  </div>
                                </div> 
                            </div>";
                            
                          break;
                          case 2:
                            //SAUDE
                            
                            
                            echo "</div>
                                    </div>
                                  </div>
                                </div> 
                            </div>";
                            
                          break;    
                            
                        }
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
