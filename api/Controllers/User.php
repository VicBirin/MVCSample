<?

namespace Controllers;

use Controller\BaseController;

class User extends BaseController {

    public function get() {
        if ($this->request->getMethod() == "GET") {

            // Check user session
            if(isset($_SESSION['user'])){
                $data = ['user' => $_SESSION['user']];
            }
            else{
                $data = ['user' => null];
            }

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($data);
        }
    }

    public function login($params) {
        if ($this->request->getMethod() == "POST") {

            $params = $this->request->post();

            // check get params
            if (empty($params) || !isset($params['user'])) {
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => 'Incorrect parameters.']);
                return;
            }

            // Connect to database
            $model = $this->model('user');

            // Read single record
            $user = $model->login($params);

            // Prepare data
            if($user > 0){
                $_SESSION['user'] = $params['user'];
                $data = ['data' => $_SESSION['user']];
            }
            else{
                $data = ['user' => null];
            }

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($data);
        }
    }

    public function logout($params) {
        if ($this->request->getMethod() == "POST") {

            unset($_SESSION['user']);
            $data = ['user' => null];

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($data);
        }
    }
}
