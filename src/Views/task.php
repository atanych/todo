<?php
/**
 * @var \TD\Models\Task $task
 */
?>
<div class="task-wrapper clearfix" data-id="<?= $task->id; ?>">
    <div class="row">
        <h3 class="col-md-12 task-name"><?= $task->name; ?></h3>
        <span class="col-md-12">дедлайн:
            <?php if ($task->deadline < time()) : ?>
                <del class="task-deadline"><?= $task->getDeadline(); ?></del>
            <?php else: ?>
                <span class="task-deadline"><?= $task->getDeadline(); ?><span>
            <?php endif; ?>
        </span>
        <div class="col-md-12">
            <blockquote class="task-description"><?= $task->description; ?></blockquote>
        </div>
    </div>
    <div class="col-md-5">
        <button class="btn btn-default btn-sm edit-task">Изменить</button>
        <button class="btn btn-default btn-sm remove-task">Удалить</button>
    </div>
</div>