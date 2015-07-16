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
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/plugins/datepicker/jquery-ui.min.css">
    <script src="/js/jquery-1.11.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/underscore-min.js"></script>
    <script src="/plugins/datepicker/jquery-ui.min.js"></script>
</head>
<body>


<div id="task-manager" class="container">

    <!-- Модальное окно для создания заявки -->
    <div class="modal" id="task-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">
            </div>
        </div>
    </div>

    <div class="row">
        <h2 class="text-center col-md-10">Список задач</h2>

        <div class="col-md-2">
            <button id="task-create" class="btn btn-success">Добавить задачу</button>
        </div>
    </div>
    <div id="tasks-global-wrapper">
        <?php for ($i = 0, $len = sizeof($tasks); $i < $len; $i++) {
            $this->render('task', [
                'task' => $tasks[$i]
            ]);
        } ?>
    </div>
</div>


<!-- ----------------------------------------------------------- -->
<!--       Шаблон для контента модального окна  (create/edit)    -->
<!-- ----------------------------------------------------------- -->
<script type="text/template" id="modal-content-template">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="task-modal-title"><%= data.title %></h4>
    </div>
    <div class="modal-body">
        <form id="task-modal-form" class="row">
            <input id="task-id" name="Task[id]" type="hidden" value="<%= data.id %>"/>

            <div class="form-group col-md-12">
                <label for="task-name">Название задачи</label>
                <input id="task-name" name="Task[name]" class="form-control" value="<%= data.name %>"/>
            </div>
            <div class="form-group col-md-12">
                <label for="task-datepicker">Дедлайн задачи</label>
                <input id="task-datepicker" name="Task[deadline]" class="form-control" value="<%= data.deadline %>" readonly/>
            </div>
            <div class="form-group col-md-12">
                <label for="task-description">Описание задачи</label>
                <textarea id="task-description" class="form-control" rows="3"
                          name="Task[description]"><%= data.description %></textarea>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div id="task-modal-error" class="alert alert-danger fade"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" id="task-modal-submit" class="btn btn-primary">Ок</button>
    </div>
</script>


<!-- ------------------------------------------------------ -->
<!--       Шаблон для контента модального окна  (remove)    -->
<!-- ------------------------------------------------------ -->
<script type="text/template" id="modal-remove-template">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="task-modal-title"><%= data.title %></h4>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="button" id="task-modal-submit" class="btn btn-primary">Ок</button>
    </div>
</script>

<script>

    /**
     *  Менеджер задач
     */
    function TaskManager() {

        /**
         *  Устанавливает эвенты
         */
        this.setEvents = function () {
            // @todo  можно было использовать proxy
            var self = this;
            /**
             * Событие на создание задачи
             */
            $('#task-create').click(function () {
                    self.invokeModal({
                            'template':     '#modal-content-template',
                            'templateData': {
                                'data': {
                                    'title': 'Добавить задачу'
                                }
                            },
                            'success':      function () {
                                $.ajax({
                                    'method':  'POST',
                                    'url':     'task/create',
                                    'data':    $('#task-modal-form').serialize(),
                                    'success': function (data) {
                                        $('#tasks-global-wrapper').prepend(data);
                                        $('#task-modal').modal('hide');
                                        $('#task-modal-error').addClass('fade');
                                    },
                                    'error':   function (data) {
                                        console.log('e', data);
                                        $('#task-modal-error').removeClass('fade').html(data.responseJSON.join('<br/>'));

                                    }
                                });
                            }
                        }
                    )
                    ;
                }
            );

            /**
             * Событие на изменение задачи
             */
            $('#task-manager').on('click', '.edit-task', function () {
                var $taskWrapper = $(this).parents('.task-wrapper');
                self.invokeModal({
                    'template':     '#modal-content-template',
                    'templateData': {
                        'data': {
                            'id':          $taskWrapper.data('id'),
                            'title':       'Изменить задачу',
                            'name':        $taskWrapper.find('.task-name').text(),
                            'deadline':    $taskWrapper.find('.task-deadline').text(),
                            'description': $taskWrapper.find('.task-description').text()
                        }
                    },
                    'success':      function () {
                        $.ajax({
                            'method':  'POST',
                            'url':     'task/edit',
                            'data':    $('#task-modal-form').serialize(),
                            'success': function (data) {
                                $taskWrapper.replaceWith(data);
                                $('#task-modal').modal('hide');
                                $('#task-modal-error').addClass('fade');
                            },
                            'error':   function (data) {
                                $('#task-modal-error').removeClass('fade').html(data.responseJSON.join('<br/>'));

                            }
                        });
                    }
                });
            });
            /**
             * Событие на удаление задачи
             */
            $('#task-manager').on('click', '.remove-task', function () {
                var $taskWrapper = $(this).parents('.task-wrapper');
                self.invokeModal({
                    'template':     '#modal-remove-template',
                    'templateData': {
                        'data': {
                            'title': 'Удалить задачу'
                        }
                    },
                    'success':      function () {
                        $.ajax({
                            'method':  'POST',
                            'url':     'task/remove',
                            'data':    {
                                'id': $taskWrapper.data('id')
                            },
                            'success': function () {
                                $taskWrapper.remove();
                                $('#task-modal').modal('hide');
                                $('#task-modal-error').addClass('fade');
                            },
                            'error':   function (data) {
                                $('#task-modal-error').removeClass('fade').html(data.responseJSON.join('<br/>'));

                            }
                        });
                    }
                });
            });
        };

        /**
         * Обновляет datepicker
         */
        this.refreshDatepicker = function () {
            $("#task-datepicker").datepicker({
                dateFormat: 'dd-mm-yy'
            });
        };

        /**
         * Вызывает модальное окно
         *
         * @params string options['template'] Шаблон тела модального окна (selector)
         * @params associative array options['templateData'] Информация для шаблона
         * @params function options['success'] Функция, которая вызывается после утверждения модального окна
         */
        this.invokeModal = function (options) {
            $('#modal-content').html(_.template($(options.template).html(), options.templateData));
            this.refreshDatepicker();
            $('#task-modal-submit').off('click').on('click', options.success);
            $('#task-modal').modal();
        };

        this.setEvents();
    }

    $(function () {
        new TaskManager();
    })
</script>

</body>
</html>