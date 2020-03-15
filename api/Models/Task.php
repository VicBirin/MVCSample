<?

namespace Models;

use Exception;
use Model\BaseModel;
use PDO;

class Task extends BaseModel
{

    public function get($params)
    {
        $result = $this->db->query(sprintf("select * from tasks where id = %s", $params['id']));
        return $result->rows;
    }

    public function list(&$params)
    {
        $sql = "select * from tasks";

        if (!empty($params)) {

            if(isset($params['page']) && isset($params['perPage'])) {
                $pagination = $this->db->queryPage("tasks", $params['page'], $params['perPage']);
            }

            if(isset($params['sort'])){
                $arr = explode(',', $params['sort']);
                $sort = array();
                foreach ($arr as $item){
                    list($field, $order) = explode(":", $item);
                    if(in_array($field, SORT_FIELDS)){
                        if(isset($order) && (strtolower($order) == "asc" || strtolower($order) == "desc")){
                            array_push($sort, "$field $order");
                        }
                        else{
                            array_push($sort, "$field");
                        }
                    }
                }
            }

            if(!empty($sort)){
                $sql .= " order by " . join(',', $sort);
            }

            if(isset($pagination)){
                $sql .= sprintf(" limit %s, %s", $pagination["offset"], $params['perPage']);
            }

            $result = $this->db->query($sql);

        } else {
            $result = $this->db->query($sql);
        }

        if (isset($pagination)) {
            return [$result->rows, $pagination['totalPages']];
        } else {
            return $result->rows;
        }
    }

    public function add($params)
    {
        $userName = $this->db->escape($params['userName']);
        $email = $this->db->escape($params['email']);
        $body = $this->db->escape($params['body']);

        $result = $this->db->exec("insert into tasks(`userName`, `email`, `body`) values('$userName', '$email', '$body')");

        // catch database errors if any
        if (isset($result->errorInfo)) {
            throw new Exception("Error creating task. Error code: " . $result->getCode());
        }

        return $this->db->getLastId();
    }

    public function edit($params)
    {
        $id = $this->db->escape($params['id']);
        $userName = $this->db->escape($params['userName']);
        $email = $this->db->escape($params['email']);
        $body = $this->db->escape($params['body']);
        $completed = $this->db->escape($params['completed']);
        $adminEdited = 0;

        // check if edited body
        $prevTask = $this->get(['id' => $id]);
        if(count($prevTask) > 0 && strcmp($prevTask[0]['body'], $body) != 0){
            $adminEdited = 1;
            $result = $this->db->exec("update tasks set userName = '$userName', email = '$email', body = '$body', completed = $completed, edited = $adminEdited where id = $id");
        }
        else{
            $result = $this->db->exec("update tasks set userName = '$userName', email = '$email', body = '$body', completed = $completed where id = $id");
        }



        // catch database errors if any
        if (isset($result->errorInfo)) {
            throw new Exception("Error updating task. Error code: " . $result->getCode());
        }

        return true;
    }

    public function delete($params)
    {
        $id = $params['id'];
        $result = $this->db->exec("delete from tasks where id = $id");

        // catch database errors if any
        if (isset($result->errorInfo)) {
            throw new Exception("Error deleting task. Error code: " . $result->getCode());
        }

        return true;
    }
}
