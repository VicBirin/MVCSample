<?php

namespace Model;

use Database\DatabaseAdapter;

class BaseModel {

    /**
     * @var
     */
    public $db;

    /**
     *  Constructor
     */
    public function __construct() {
        $this->db = new DatabaseAdapter(
            DATABASE['Driver'],
            DATABASE['Host'],
            DATABASE['User'],
            DATABASE['Pass'],
            DATABASE['Name'],
            DATABASE['Port']
        );
    }
}
