<?php


/**
 * <b>Limita os Palavras:</b> Limita a quantidade de palavras a serem exibidas em uma string!
 * @param STRING $String = Uma string qualquer
 * @return INT = $Limite = String limitada pelo $Limite
 */
function Words($String, $Limite, $Pointer = null) {
    $content = strip_tags(trim($String));
    $Format = (int) $Limite;

    $ArrWords = explode(' ', $content);
    $NumWords = count($ArrWords);
    $NewWords = implode(' ', array_slice($ArrWords, 0, $Format));

    $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer );
    $Result = ( $Format < $NumWords ? $NewWords . $Pointer : $content );
    return $Result;
}



// pega o nome da cidade a partir de um ID relacionado
// function getCidade($id, $tabela)
// {
//     if (empty($id) && empty($tabela)) {
//         return null;
//     }
//     $cidade = Illuminate\Support\Facades\DB::table(''.$tabela.'')->where('cidade_id', '=', $id)->get();
//     if(!empty($cidade)){
//         return $cidade[0]->cidade_nome;
//     }else{
//         return null;
//     }
// }

// pega o nome da cidade a partir de um ID relacionado
// function getEstado($id, $tabela, $campo = null)
// {
//     if (empty($id) && empty($tabela)) {
//         return null;
//     }
//     $estado = Illuminate\Support\Facades\DB::table(''.$tabela.'')->where('estado_id', '=', $id)->get();
//     if(!empty($estado)){
//         if($campo == null){
//             return $estado[0]->estado_nome;
//         }else{
//             return $estado[0]->{$campo};
//         }
//     }else{
//         return null;
//     }
// }

/*****************************
    FUNÇÃO PARA PEGAR SOMENTE O USUÁRIO DA URL FACEBOOK
*****************************/
// function fbUser($url)
// {
//     $regex ='/https?\:\/\/(?:www\.|web\.|m\.|touch\.)?(?:facebook\.com|fb(?:\.me|\.com))\/(\d+|[A-Za-z0-9\.]+)\/?/';
//     if( preg_match( $regex, $url, $matches ) ){
//         return $matches['1'];
//     }else{
//         return false;
//     } 
//  }
/*****************************
    FUNÇÃO PARA VALIDAR CPF
*****************************/
// function validaCPF($cpf) {
 
//     // Extrai somente os números
//     $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
//     // Verifica se foi informado todos os digitos corretamente
//     if (strlen($cpf) != 11) {
//         return false;
//     }

//     // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
//     if (preg_match('/(\d)\1{10}/', $cpf)) {
//         return false;
//     }

//     // Faz o calculo para validar o CPF
//     for ($t = 9; $t < 11; $t++) {
//         for ($d = 0, $c = 0; $c < $t; $c++) {
//             $d += $cpf[$c] * (($t + 1) - $c);
//         }
//         $d = ((10 * $d) % 11) % 10;
//         if ($cpf[$c] != $d) {
//             return false;
//         }
//     }
//     return true;
// }

// function limpaCPF_CNPJ($valor){
//     $valor = trim($valor);
//     $valor = str_replace(".", "", $valor);
//     $valor = str_replace(",", "", $valor);
//     $valor = str_replace("-", "", $valor);
//     $valor = str_replace("/", "", $valor);
//     return $valor;
// }

