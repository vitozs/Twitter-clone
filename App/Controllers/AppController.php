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
                
           

            //variaveis de páginação
            $total_registros_pagina = 10;
            $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
            $deslocamento = ($pagina - 1) * $total_registros_pagina;

            $tweets = $tweet->getPorPagina($total_registros_pagina, $deslocamento);
            
            $total_tweets = $tweet->getTotalRegistros();
           
            $total_de_paginas = ceil($total_tweets['total'] / $total_registros_pagina);

            $this->view->total_de_paginas = $total_de_paginas;
            $this->view->pagina_ativa = $pagina;


            $this->view->tweets = $tweets;


            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotalTweets();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_seguidores = $usuario->getTotalSeguidores();


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
                $usuario->__set('id', $_SESSION['id']);
                $usuarios = $usuario->getAll();

            }

            $this->view->usuarios = $usuarios;
            
            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);
            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_tweets = $usuario->getTotalTweets();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_seguidores = $usuario->getTotalSeguidores();

        
            $this->render('quemSeguir');



        }

        public function acao(){
            $this->validaAuth();

            //acao
            $acao = isset($_GET['acao']) ? $_GET['acao'] : "";
            $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : "";

            $usuario = Container::getModel('Usuario');
            $usuario->__set('id', $_SESSION['id']);

            if($acao == "seguir"){
                $usuario->seguirUsuario($id_usuario_seguindo);
            }else if($acao == 'deixar_de_seguir'){
                $usuario->deixarSeguirUsuario($id_usuario_seguindo);

            }

            header('Location: /quem_seguir');

            //id_usuario
        }

        public function removerTweet(){
            $this->validaAuth();
            $id_tweet = $_GET['id_tweet'];

            $tweet = Container::getModel('Tweet');
            $tweet->__set('id', $id_tweet);
            $tweet->removerTweet();

            header('Location: /timeline');
        }
    }

?>
