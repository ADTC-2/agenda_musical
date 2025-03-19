<?php
require_once 'BaseController.php';
require_once '../models/Culto.php';

class CultoController extends BaseController {
    public function __construct() {
        parent::__construct(new Culto());
    }
}
?>

