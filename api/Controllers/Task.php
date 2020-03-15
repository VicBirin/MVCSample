<?

namespace Controllers;

use Controller\BaseController;
use Exception;

class Task extends BaseController {

    public function get($params) {
        if ($this->request->getMethod() == "GET") {
            // check get params
            if (empty($params) || !is_numeric($params['id'])) {
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => 'Incorrect parameters.']);
                return;
            }

            // Connect to database
            $model = $this->model('task');

            // Read single record
            $task = $model->get($params);

            // Prepare data
            $data = ['data' => $task];

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($data);
        }
    }

    public function list($params) {

        if ($this->request->getMethod() == "GET") {

            // check get params
            if(!empty($params)) {
                // if set pagination
                if(isset($params['page']) && isset($params['perPage'])) {
                    if ((!is_numeric($params['page']) || !is_numeric($params['perPage']))) {
                        $this->response->sendStatus(400);
                        $this->response->setContent(['error' => 'Incorrect parameters.']);
                        return;
                    }
                }

                // check and save las loaded page in session
                if(isset($params['page']) && !empty($params['page']) && $params['page'] > 0){
                    $_SESSION['page'] = $params['page'];
                }
                else if(isset($_SESSION['page'])){
                    $params['page'] = $_SESSION['page'];
                }
                else{
                    $_SESSION['page'] = 1;
                    $params['page'] = $_SESSION['page'];
                }

                // check and save sorting in session
                if(isset($params['sort']) && !empty($params['sort'])){

                    // check sort
                    $sortCommands = explode( ',', $params['sort']);
                    $sortParam = array();
                    foreach ($sortCommands as $item){
                        if (strpos($item, 'clear') == false) {
                            array_push($sortParam, $item);
                        }
                    }

                    $_SESSION['sort'] = join(',', $sortParam);
                    $params['sort'] = $_SESSION['sort'];
                }
                else if(isset($_SESSION['sort'])){
                    $params['sort'] = $_SESSION['sort'];
                }
            }

            // Connect to database
            $model = $this->model('task');

            // Read All Task
            $tasks = $model->list($params);

            // Prepare data
            if(!isset($_SESSION['sort'])) $_SESSION['sort'] = null;
            if (isset($params['page']) && isset($params['perPage'])) {
                $data = ['data' => $tasks[0]];
                $data['pagination'] = ['page' => $params['page'],
                      'perPage' => $params['perPage'],
                      'totalPages' => $tasks[1],
                      'sort' => $_SESSION['sort']];
            } else {
                $data = ['data' => $tasks];
            }

            // Send Response
            $this->response->sendStatus(200);
            $this->response->setContent($data);
        }
    }

    public function add() {

        if ($this->request->getMethod() == "POST") {

            // Connect to database
            $model = $this->model('task');

            $data = $this->request->post();
            // check data
            if(empty($data) || (!isset($data['userName']) || !isset($data['email']) || !isset($data['body']))){
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => 'Incorrect parameters.']);
                return;
            }

            try {
                // add to database
                $result = $model->add($data);

                // Send Response
                $this->response->sendStatus(200);
                $this->response->setContent(['data' => $result]);
            }
            catch (Exception $e){
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => $e->getMessage()]);
            }
        }
    }

    public function edit() {
        if ($this->request->getMethod() == "POST") {

            // check admin session before edit
            if(!isset($_SESSION['user']) || $_SESSION['user'] != 'admin'){
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => "Admin rights required to edit task"]);
                return;
            }

            // Connect to database
            $model = $this->model('task');

            $data = $this->request->post();
            // check data
            if(empty($data) || (!isset($data['id']) || !isset($data['userName']) || !isset($data['email']) || !isset($data['body']))){
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => 'Incorrect parameters.']);
                return;
            }

            try {
                $result = $model->edit($data);

                // Send Response
                $this->response->sendStatus(200);
                $this->response->setContent(['data' => $result]);
            }
            catch (Exception $e){
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => $e->getMessage()]);
            }
        }
    }

    public function delete($params)
    {
        if ($this->request->getMethod() == "DELETE") {

            try {
                // check get params
                if (empty($params) || !is_numeric($params['id'])) {
                    $this->response->sendStatus(400);
                    $this->response->setContent(['error' => 'Incorrect parameters.']);
                    return;
                }

                // Connect to database
                $model = $this->model('task');

                // delete single record
                $result = $model->delete($params);

                // Prepare data
                $data = ['data' => $result];

                // Send Response
                $this->response->sendStatus(200);
                $this->response->setContent($data);
            }
            catch (Exception $e){
                $this->response->sendStatus(400);
                $this->response->setContent(['error' => $e->getMessage()]);
            }
        }
    }
}
