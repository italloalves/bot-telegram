<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexao
 *
 * @author 201612320
 */
class Conexao {
    
    public function getConexao() {
        try {      
            $connection = new PDO("mysql:host=localhost;dbname=botTelegram", "root", "");
            $connection->exec("set names utf8");
            return $connection;
        } catch (PDOException $exc) {
            echo 'Falha: ' . $exc->getMessage();
            exit();
        }
    }
}