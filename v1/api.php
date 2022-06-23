<?php

use LDAP\Result;

require_once '../model/Opercao.php';

function isTheseParametersAvailable($params){
$avalible = true; 
$missingparams = "";

foreach($params as $param){
    if(!isset($_POST[$param]) || strlen ($_POST[$param])<=0 ){
        $avalible = false;
        $missingparams = $missingparams. " ,". $param;
}
}

if(!$avalible){
    $response = array();
    $response['error'] = true;
    $response['message'] = 'Parameters' .substr($missingparams, 1, strlen($missingparams)).'missing';

    echo json_encode($response);

    die();
}
}

$response = array();

if(isset($_GET['apicall'])){
    switch($_GET['apicall']){

        case 'createFruta':
            isTheseParametersAvailable(array('campo_2','campo_3', 'campo_4'));

            $db = new Operacao();

            $result = $db->createFruta(
                $_POST['campo_2'],
                $_POST['campo_3'],
                $_POST['campo_4'],

            );

            if($result){
                $response['error'] = false;
                $response['message'] = 'Dados inseridos com sucesso';
                $response['dadoscreate'] = $db->getFrutas();
            }else{
                $response['error'] = true;
                $response['messege'] = 'Dados nao foram inseridos ';
            }

            break;
            case 'getFrutas': 
                $db = new Operacao();
                $response ['error'] = false;
                $response ['message'] = 'Dados listados com sucesso.';
                $response['dadoslista'] = $db-> getFrutas();

                break;
                case 'updateFrutas':
                    isTheseParametersAvailable(array('campo_1','campo_2','campo_3','campo_4'));

                    $db = new Operacao();
                    $result = $db -> updateFrutas(
                        $_POST['campo_1'],
                        $_POST['campo_2'],
                        $_POST['campo_3'],
                        $_POST['campo_4']
                    );

                    if($Result){
                        $response['error'] = false;
                        $response['message'] = "Dados alterados com sucesso";
                        $response['dadosalterar'] = $db -> getFrutas();
                    }else{
                        $response['error'] = true;
                        $response['message'] = "Dados não alterados ";
                    }
                    break;
                    case 'deleteFrutas':
                        if(isset($_GET['campo_1'])){
                            $db = new Operacao();
                            if($bd  -> deleteFrutas($_GET['campo_1'])){
                                $response['error'] = false;
                                $response['message'] = "Dado excluido com sucesso";
                                $response['deleteFrutas'] = $bd -> getFrutas();
                                
                            }else{
                                $response['error'] = true;
                                $response['response'] = "Algo deu errado";
                            }
                            }else{
                                $response['error'] = true;
                                $response['response'] = "Dados não apagados.";
                            }
                            break;
                        }
                    
}else{
    $response['error'] = true;
    $response['message'] = "Chamada de api com defeito";
}
echo json_encode($response);