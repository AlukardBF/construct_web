<?php



class SessionController extends \Phalcon\Mvc\Controller
{
	private function _registerSession($user)
    {
        $this->session->set(
            'auth',
            [
                'id'   => $user->user_id,
                'email' => $user->email,
                'role' => $user->type,
            ]
        );
    }
    
    public function indexAction()
    {
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(
                [
                    'controller' => 'index',
                    'action'     => 'index',
                    'params' => [error=>'Пожалйста, не хакайте нас. Оч просим.'],
                ]
            );;
    	}
    	$email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

         //найдем пользователя, подходящего под параметры
        $user = User::findFirst(
                [
                    "email = :email:",
                    'bind' => [
                        'email'    => $email
                    ]
                ]
            );

        if ($user !== false && $this->security->checkHash($password, $user->pass)) {
          	/*если пользователь найден и
          	пароли совпадают
          	авторизуем и переходим на след страницу*/
            $this->_registerSession($user);
            
            if ($user->type == 'admin') {
                return $this->dispatcher->forward(
                    [
                        'controller' => 'user',
                        'action'     => 'index',
                    ]
                );
            } else {
                return $this->dispatcher->forward(
                    [
                        'controller' => 'course',
                        'action'     => 'list',
                    ]
                );
            }
      	    
        }

        return $this->dispatcher->forward(
            [
                'controller' => 'index',
                'action'     => 'index',
                'params' => [error=>'Введен неправильный логин или пароль.'],
            ]
        );
    }

     public function logoutAction()
    {
        $this->session->destroy();
        return $this->response->redirect();
    }

}

