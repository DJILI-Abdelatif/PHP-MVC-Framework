<?php

    namespace app\core;
    use app\core\DB\Database;
    use app\core\DB\DbModel;

    class Application
    {
        public static string $ROOT_DIR;
        public static Application $app;
        public Router $router;
        public Request $request;
        public Response $response;
        // public Controller $controller;
        public Database $DB;
        public Session $session;
        public ?UserModel $user;
        public string $userClass;
        public ?Controller $controller = null;
        public string $layout = 'main';
        public View $view;

        public function __construct($rootPath, array $config) {
            self::$ROOT_DIR = $rootPath;
            self::$app      = $this;
            $this->request  = new Request();
            $this->response = new Response();
            $this->router   = new Router($this->request, $this->response);
            $this->DB       = new Database($config['db']);
            $this->session  = new Session();
            $this->userClass = $config['userClass'];
            $primaryValue = $this->session->get('user');
            if($primaryValue) {
                $primarykey = $this->userClass::primaryKey();
                $this->user = $this->userClass::findOne([$primarykey => $primaryValue]);
            } else {
                $this->user = null;
            }
            $this->view = new View();
        }        

        public static function isGuest() {
            return !self::$app->user;
        }

        public function run() {
            try {
                echo $this->router->resolve();
            } catch (\Exception $e) {
                $this->response->setStatusCode($e->getCode());
                echo $this->view->renderView('_error', [
                    'exception' => $e
                ]);
            }
        }
        
        public function getController() {
            return $this->controller;
        }

        public function setController(Controller $controller) {
            $this->controller = $controller;
        }

        public function login(UserModel $user) {
            $this->user = $user;
            $primarykey = $user->primaryKey();
            $primaryValue = $user->{$primarykey};
            $this->session->set('user', $primaryValue);
            return true;
        }

        public function logout() {
            $this->user = null;
            $this->session->remove('user');
        }

    }
    

?>