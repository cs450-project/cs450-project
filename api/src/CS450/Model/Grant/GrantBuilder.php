<?php

namespace CS450\Model;

use CS450\Model;
use CS450\Service\DbService;

final class GrantBuilder {
    private $db;

    public $id;
    public $name;
    public $type;
    /**
     * Annotation combined with phpdoc:
     *
     * @Inject
     * @param DbService $db
     */
    function __construct(DbService $db) {
        $this->db = $db;
    }

    function build() {
        return new User($this, $this->db);
    }

    function id($id) {
        $this->id = $id;
        return $this;
    }

    function name($name) {
        $this->name = $name;
        return $this;
    }

    function type($typel) {
        $this->type = $type;
        return $this;
    }
}
