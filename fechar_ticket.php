<?php
    //Colocar a data retirada no fuso horario
    date_default_timezone_set('America/Sao_Paulo');

    //Recebendo os campos do formulário
    $numero_chamado = $_POST['numero_chamado'];
    $nome_cliente = $_POST['nome_cliente'];
    $email_cliente = $_POST['email_cliente'];
    $data_retirada = $_POST['data_retirada'];
    $data_retirada = Date("'".$data_retirada." H:i:s'");
    $nome_resposavel_retirada = $_POST['nome_resposavel_retirada'];
    $documento_resposavel_retirada = "";
    $documento_resposavel_retirada = $_POST['documento_resposavel_retirada'];
    $telefone_resposavel_retirada = "";
    $telefone_resposavel_retirada = $_POST['telefone_resposavel_retirada'];
    $tecnico_resposavel_entrega = $_POST['tecnico_resposavel_entrega'];
    $observacao = "";
    $observacao = $_POST['observacao'];
    
    //pega a extensao do arquivo
    $extensao = strtolower(substr($_FILES['arquivo']['name'], -4)); 
    //Codição para adicionar um . no inicio da extensão para extensões que não entregam com o .
    $validar = strpos($extensao, '.');
    if($validar === false){
        $extensao = ".".$extensao; 
    }

    //define o nome do arquivo
    $novo_nome_arquivo = md5(time()).$extensao; 
    //define o diretorio para onde enviaremos o arquivo
    $diretorio = "upload/"; 
    //Move o arquivo para a pasta que irá ficar salvo
    move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome_arquivo);


    //Texto da Interação do chamado e 'ADDSLASHER' para nao conflitas as aspas com o banco
    $interacao_chamado = "&#60;p&#62;Olá,&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Seu equipamento foi retirado por:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;".$nome_resposavel_retirada."&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Documento da pessoa que retirou:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;".$documento_resposavel_retirada."&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Telefone da pessoa que retirou:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;".$telefone_resposavel_retirada."&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Técnico responsável pela entrega:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;".$tecnico_resposavel_entrega."&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Observação:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;".$observacao."&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Foto da ficha da Máquina:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;"."http://192.168.100.198/formulario_bancada/upload/".$novo_nome_arquivo."&#60;/p&#62; &#60;p&#62;&#60;strong&#62;Número do ticket:&#60;/strong&#62;&#60;/p&#62; &#60;p&#62;&#60;strong&#62;".$numero_chamado."&#60;/strong&#62;&#60;/p&#62;";
    $interacao_chamado = addslashes($interacao_chamado);

    //Conexão ao Banco
    include_once 'conexao.php';
    
    //Inserir Interação no chamado
    $inserir_interacao = "insert into GLPI_4INFRA.glpi_itilfollowups (id, itemtype, items_id, date, users_id, users_id_editor, content, is_private, requesttypes_id, date_mod, date_creation, timeline_position, sourceitems_id, sourceof_items_id) values (NULL, 'Ticket', ".$numero_chamado.", $data_retirada, 1285, 0, '".$interacao_chamado."', 0, 14, $data_retirada, $data_retirada, 4, 0, 0)";
    $enviar_atualizacao = mysqli_query($conn, $inserir_interacao);

    //Fechar o chamado, atualizando o status para FECHADO
    $atualizar_chamado = "UPDATE glpi_tickets SET Status = 6 where id = $numero_chamado";
    $enviar_atualizacao = mysqli_query($conn, $atualizar_chamado);


    /*
    Insert de exemplo:
    insert into GLPI_4INFRA.glpi_itilfollowups (id, itemtype, items_id, date, users_id, users_id_editor, content, is_private, requesttypes_id, date_mod, date_creation, timeline_position, sourceitems_id, sourceof_items_id) values (NULL, 'Ticket', 46021, '2023-05-02 13:06:00', 1285, 0, 'TESTE DO FORMULARIO externo!!! Deu certo?', 0, 14, '2023-05-02 13:06:00', '2023-05-02 13:06:00', 4, 0, 0);
    ID -> Auto Increment.
    Itemtype -> "Ticket" por padrão
    items_id -> Numero do Chamado
    date -> Data da inserção
    users_id -> ID do usuário que vai fazer a interação do chamado
    users_id_editor -> 0, por padrão
    content -> Conteudo da interação
    is_private -> 0, por padrão
    requesttypes_id -> Id da requisição feita na interação. "14" é laboratorio.
    date_mod -> Data da inserção, POR PADRÃO
    date_creation -> Data da inserção
    timeline_position -> 4 É DIREITA, 1 É ESQUERDA
    sourceitems_id -> 0, por padrão
    sourceof_items_id -> 0, por padrão
    */
?>