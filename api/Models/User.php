<?

namespace Models;

use Model\BaseModel;

class User extends BaseModel
{
    public function login($params)
    {
        $sql = sprintf("select * from users where userName = '%s' and password = '%s'", $params['user'], $params['password']);
        $result = $this->db->query($sql);
        return $result->num_rows;
    }
}
