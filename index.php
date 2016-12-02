<!DOCTYPE html>
<!--
    Nome:       Itallo Alves Batista Nunes
    Matricula:  201612320
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>ITALLO BOT</title>
        
    </head>
    <body>
        <h1 style="text-align: center;">Itallo97_bot</h1>
        <hr />
        <div style="width: 80%; height: 350px; overflow: scroll; border: 1px solid #000; margin: 0 auto;">
            <?php
            require './BancoDAO.php';

            $banco = new BancoDAO();

            $updateIdArquivo = './updateId.txt'; // Inicia o arquivo updateId.txt.
            $tokenArquivo = './token.txt'; // Inicia o arquivo token.txt.
            $token = file_get_contents($tokenArquivo); // Recupera o token do arquivo token.txt.
            define('URL_BOT', 'https://api.telegram.org/bot' . $token . '/'); // Define uma constante com URL do BOT.
            // Função para tratar a data.

            function tratarData($data) {
                date_default_timezone_set('America/Sao_Paulo'); // Define o fuso horário.
                $dataFormato = 'd/m à\s H:i'; // Define o formato da data.
                $offsetUTC = date('Z');
                $offsetClean = (int) preg_replace('/[^0-9]/', '', $offsetUTC);
                if (preg_match('/^-.*/', $offsetUTC) == 1) {
                    $dataTratada = $data - $offsetClean;
                    return gmdate($dataFormato, $dataTratada);
                } else {
                    $dataTratada = $data + $offsetClean;
                    return gmdate($dataFormato, $dataTratada);
                }
            }

            // Função para envio de mensagens.
            function enviarMensagem($id, $mensagem) {
                $chatID = $id;
                $msg = $mensagem;
                $text = urlencode($msg);
                $sendMessage = URL_BOT . 'sendMessage?chat_id=' . $chatID . '&text=' . $text;
                file_get_contents($sendMessage);
            }

            $update = file_get_contents(URL_BOT . 'getUpdates'); // Faz a requisição na api do telegram.
            $resultado = json_decode($update, true); // Decodifica o JSON retornado na requisição.
            $quantidadeMensagens = count($resultado['result']) - 1; // Conta quantas mensagens existem na api.
            // Laço para tratar as mensagens.
            for ($i = $quantidadeMensagens; $i >= 0; $i--) {
                $id = $resultado['result'][$i]['message']['from']['id'];
                $primeiroNome = $resultado['result'][$i]['message']['from']['first_name'];
                $segundoNome = $resultado['result'][$i]['message']['from']['last_name'];
                $data = $resultado['result'][$i]['message']['date'];
//                $texto = $resultado['result'][$i]['message']['text'];

                $dataTratada = tratarData($data); // Chama a função de tratar a data.

                if (isset($resultado['result'][$i]['message']['text'])) {
                    $texto = $resultado['result'][$i]['message']['text'];
                    // Trata o texto /MegaSena.
                    if ($texto == '/MegaSena') {
                        $updateId = $resultado['result'][$i]['update_id'];
                        $str = file_get_contents($updateIdArquivo); // Recupera do arquivo os updateids já respondido.
                        $arrUpdateId = explode(',', $str); // Separa os updateids por vírgula.
                        // Verifica se o updateId já foi respondido, caso não, ele responde.
                        if (!in_array($updateId, $arrUpdateId)) {
                            for ($j = 1; $j <= 6; $j++) {
                                $numerosMegaSena[] = str_pad(rand(1, 60), 2, '0', STR_PAD_LEFT);
                            }
                            sort($numerosMegaSena);
                            $numMega = implode(' - ', $numerosMegaSena);
                            enviarMensagem($id, $numMega); // Chama a função de enviar mensagens.
                            file_put_contents($updateIdArquivo, $updateId . ',', FILE_APPEND | LOCK_EX);
                            $banco->inserirDados($updateId, $primeiroNome, $texto, $numMega);
                        }
                    }
                    echo "<b>" . $primeiroNome . " " . $segundoNome . " - " . $dataTratada . ": </b>" . $texto . "<br /><hr />";
                } else if (isset($resultado['result'][$i]['message']['photo'])) {
                    echo "<b>" . $primeiroNome . " " . $segundoNome . " - " . $dataTratada . ": </b>" . "Foto enviada" . "<br /><hr />";;
                }
            }
            ?>
        </div>
    </body>
</html>
