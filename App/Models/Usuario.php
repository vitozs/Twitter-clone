<?php

    namespace App\Models;
    use MF\Model\Model;
    class Usuario extends Model{
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($atr, $val){
            $this->$atr = $val;
        }

        //salvar
        public function salvar(){
            $query="
                insert into usuarios(nome, email, senha)values(:nome, :email, :senha)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindvalue(":nome", $this->__get('nome'));
            $stmt->bindvalue(":email", $this->__get('email'));
            $stmt->bindvalue(":senha", $this->__get('senha'));
            $stmt->execute();

            return $this;
        }

        //validar se um cadastro pode ser feito
        public function validarCadastro(){
            $valido = true;

            if(strlen($this->__get('nome')) < 3){
                $valido = false;
            }

            if(strlen($this->__get('email')) < 3){
                $valido = false;
            }

            if(strlen($this->__get('senha')) < 3){
                $valido = false;
            }


            return $valido;
        }

        //recuperar usuario pelo email
        public function getUsuarioEmail(){
            $query = "select nome, email from usuarios where email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }



?>