<?php
use TD\Models\Task;

/**
 * @var Task[] $tasks
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/plugins/datepicker/jquery-ui.min.css">
    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/underscore-min.js"></script>
    <script src="/plugins/datepicker/jquery-ui.min.js"></script>
</head>
<body>
<div id="task-manager" class="container">
    <div class="row">
        <h1 class="text-center col-md-12">Список задач</h1>
    </div>
    <?php for ($i = 0, $len = sizeof($tasks); $i < $len; $i++) : ?>

        <div class="row">
            <h3 class="col-md-12 task-name"><?= $tasks[$i]->name; ?></h3>
            <span class="col-md-12"><?= $tasks[$i]->getDeadline(); ?></span>

            <div class="col-md-12">
                <blockquote class="task-description">
                    <?= $tasks[$i]->description; ?>
                </blockquote>
            </div>
        </div>
        <div class="col-md-5">
            <button class="btn btn-default btn-sm create-task">Добавить задачу</button>
            <button class="btn btn-default btn-sm edit-task">Изменить</button>
            <button class="btn btn-default btn-sm remove-task">Удалить</button>
        </div>
    <?php endfor; ?>
    <!-- Модальное окно для создания заявки -->
    <div class="modal fade" id="task-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">
            </div>
        </div>
    </div>
</div>


<!-- ------------------------------------------------------ -->
<!--       Шаблон для контента модального окна              -->
<!-- ------------------------------------------------------ -->
<script type="text/template" id="modal-content-template">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Добавить задачу</h4>
    </div>
    <div class="modal-body">
        <form id="task-modal-form" class="row">
            <input class="col-md-offset-1 col-md-10" placeholder="Название задачи" name="Task[name]"/>
            <input id="datepicker" class="col-md-offset-1 col-md-10" placeholder="Дедлайн задачи" name="Task[deadline]"/>
            <textarea placeholder="Описание задачи" class="col-md-offset-1 col-md-10" name="Task[description]"></textarea>
        </form>
        <div class="row">
            <div id="task-model-error" class="col-md-offset-1 col-md-10 alert alert-danger fade"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" id="task-modal-submit" class="btn btn-primary">Сохранить</button>
    </div>
</script>
</body>
<script>
    $(function () {
        var $modalContent = $('#modal-content');
        $('.create-task').click(function () {
            $modalContent.html(_.template($('#modal-content-template').html()));
            $("#datepicker").datepicker({
                dateFormat: '@'
            });
            $('#task-modal').modal();
        });
        $('.edit-task').click(function () {
            $modalContent.html(_.template($('#modal-content-template').html(), {
                'a': 1111
            }));
            $('#task-modal').modal();
        });

        $('#task-manager').on('click', '#task-modal-submit', function () {
            $.ajax({
                'dataType': 'json',
                'method':   'POST',
                'url':      'task/create',
                'data':     $('#task-modal-form').serialize(),
                'success':  function (data) {
                    console.log(data);
                    $('#task-model-error').addClass('fade');
                },
                'error':    function (data) {
                    $('#task-model-error').removeClass('fade').html(data.responseJSON.join('<br/>'));

                }
            });
        });
    })
</script>
</html>