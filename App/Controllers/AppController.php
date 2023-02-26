<?php
    namespace App\Controllers;
    //os recursos do miniframework
    use MF\Controller\Action;
    use MF\Model\Container;

    class AppController extends Action{

        public function timeline(){

            $this->validaAuth();
           
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id_usuario', $_SESSION['id']);
                
            $tweets = $tweet->getAll();
            $this->view->tweets = $tweets;

            $this->render('timeline');
        
        
        
        
        
        }

        public function tweet(){
            
           $this->validaAuth();
           
            $tweet = Container::getModel('Tweet');

            $tweet->__set('tweet', $_POST['tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);
                
            $tweet->salvar();

            header('Location: /timeline');
            
        }

        public function validaAuth(){
            session_start();

            if(!isset($_SESSION['id']) || empty($_SESSION['id']) || !isset($_SESSION['nome']) || empty($_SESSION['nome']) ){
                header('Location: /?login=erro');
            }
        }

        public function quemSeguir(){
            $this->validaAuth();
            $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
            print_r($pesquisarPor);
            
            $usuarios = array();
            if($pesquisarPor != ''){
                $usuario = Container::getModel('Usuario');
                $usuario->__set('nome', $pesquisarPor);
                $usuarios = $usuario->getAll();

            }

            $this->view->usuarios = $usuarios;
            
            $this->render('quemSeguir');



        }
    }

?>
