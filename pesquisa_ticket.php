<?php
    //Recebendo o Ticket da pagina anterior
    $numero_chamado = $_POST['numero_chamado'];
    //Conexão ao Banco
    include_once __DIR__.'/conexao.php';

    //Pesquisa no banco as informações completas do chamado
    $pesquisa_chamado = "SELECT * FROM glpi_consult_website WHERE ticket = '$numero_chamado' ";
    $resultado_chamado = mysqli_query($conn, $pesquisa_chamado);
    $row_chamado = mysqli_fetch_assoc($resultado_chamado);


    //Condição pra verificar se o ticket ta fechado, solucionado, nao existe ou nao pertence a bancada
    /*if(empty($row_chamado)){
        header('location:index.php?ticket=0');
    }else if($row_chamado['status'] == 6){
        header('location:index.php?ticket=1');
    }else if($row_chamado['status'] == 5){
        header('location:index.php?ticket=2');
    }*/

    //Variaveis
    $emails = "";
    $i = 0;

    //Repetição para receber todos os e-mails existentes no chamado
    $resultado_emails = mysqli_query($conn, $pesquisa_chamado);
    while($row_emails = mysqli_fetch_assoc($resultado_emails)){
        
        //Recebe o número da ID do usuário requerente do chamado
        $id_usuario = $row_emails['id_usuario'];

        //Caso a ID seja 0, o requerente tem um e-mail que não é cadastrado no glpi.
        //Caso a ID seja diferente de 0, o requerente tem cadastro no glpi.
        if($id_usuario != 0){
            //Pesquisa no banco as usuários com acesso criado no glpi - Solução para campos do e-mail em branco na View glpi_consult_website
            $pesquisa_usuario = 
            "SELECT uexis.email as email_glpi_existente FROM glpi_consult_website glpi inner join glpi_useremails uexis on glpi.id_usuario = uexis.users_id WHERE glpi.ticket = '$numero_chamado' AND glpi.id_usuario = '$id_usuario'";
            $resultado_usuario = mysqli_query($conn, $pesquisa_usuario);
            $row_usuario = mysqli_fetch_assoc($resultado_usuario);

            $email_linha_atual = $row_usuario['email_glpi_existente'];

            var_dump($row_usuario); 
        }else{
            $email_linha_atual = $row_emails['email_inserido'];
        }
        
        //Condição para inserir |, caso tenha mais de 1 e-mail - SEPARAR
        if($email_linha_atual != ""){
            if($i >= 1){
                $emails.= " | ".$email_linha_atual;
            }else{
                $emails .= $email_linha_atual;
            }
        }
        else{
        }
        $i++;
    }
?>