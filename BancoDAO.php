<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BancoDAO
 *
 * @author 201612320
 */

require 'Conexao.php';

class BancoDAO {
    
    private $pdo;
    private $conexao;
    
    function __construct() {
        $this->pdo = new Conexao();
        $this->conexao = $this->pdo->getConexao();
    }

    public function inserirDados($updateid, $nm_usuario, $nm_comando, $txt_resposta ) {
        $idComando;
        if ($nm_comando == "/MegaSena") {
            $idComando = 1;
        }
        $insert = "insert into resposta(updateid, nm_usuario, nm_comando, txt_resposta) values (?,?,?,?)";
        
         try {
            $pstmt = $this->conexao->prepare($insert);
            $pstmt->bindParam(1, $updateid);
            $pstmt->bindParam(2, $nm_usuario);
            $pstmt->bindParam(3, $idComando);
            $pstmt->bindParam(4, $txt_resposta);
            $pstmt->execute();
        } catch (PDOException $exc) {
            echo 'Falha: ' . $exc->getMessage();
        }
    }
    
    public function selectDados() {
        
    }
}
