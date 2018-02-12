<?php
require_once CONTROLLER_DIR . 'ResultController.php';

$controller = new ResultController();
$data = $controller->getResult(8, 5);
var_dump($data); die;
?>
<div class="row">
    <div class="col-12 col-md-12">
        <h3 class="text-center page-heading"><b><i>Result</i></b></h3>
        <table class="table table-hover table-striped">
            <tbody>
                <?php
                foreach ($data['result'] as $result):
                    $dateTime = $controller->transformDateTime($result->created_at);
                ?>
                <tr>
                    <td>
                        <h4>
                            <b>Date of Examination</b>
                        </h4>
                        <p><?= $dateTime['date'] ?> @ <?= $dateTime['time'] ?></p>
                    </td>
                    <td>
                        <img src="http://pingendo.github.io/pingendo-bootstrap/assets/user_placeholder.png"
                             class="img-circle" width="60">
                    </td>
                    <td>
                        <h4>
                            <b><?= $result->username ?></b>
                        </h4>
                        <a href="mailto:ramonvillaw@gmail.com"><?= $result->name ?></a>
                    </td>

                    <td class="float-right">
                        <div class="btn-group">
                            <button class="btn btn-default pointer-on-hover" type="button">
                                <i class="fa fa-fw fa-info-circle"></i><span class="mr-2 ml-1">View detail</span>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
    </div>
</div>
