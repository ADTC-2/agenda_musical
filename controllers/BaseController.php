<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class BaseController {
    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function index() {
        return $this->model->getAll();
    }

    public function show($id) {
        return $this->model->getById($id);
    }

    public function store($data) {
        return $this->model->create($data);
    }

    public function update($id, $data) {
        return $this->model->update($id, $data);
    }

    public function destroy($id) {
        return $this->model->delete($id);
    }

    protected function render($view, $data = []) {
        extract($data);
        include "../views/$view";
    }
}
?>