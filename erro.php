<?php

header("Content-Type:Text/html; charset=utf8");

//Caso não seja inserido o ticket, voltar para o index.php
if (isset($_POST['enviar'])){
    
}
else{
    header("location:index.php");
}

?>