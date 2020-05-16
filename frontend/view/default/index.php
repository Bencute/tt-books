<?php

use system\helper\Pagination;
use system\view\View;
use system\helper\Html;
use frontend\helper\FormHtmlPagination;

/**
 * @var View $this
 * @var bool $isGuest
 * @var string $username
 * @var string $currentFilterName
 * @var array $tasks type of frontend\model\Tasks
 * @var array $currentFilter
 * @var Pagination $pagination
 */
?>
<div class="text-center">
    <?php if ($isGuest) { ?>
        <h1>
            <?=Sys::mId('app', 'hellowGuest')?>
        </h1>
        <p class="lead">
            <?=Sys::mId('app', 'selectDo')?>
        </p>
        <p>
            <a href="/login">
                <?=Sys::mId('app', 'login')?>
            </a> | <a href="/registration">
                <?=Sys::mId('app', 'registration')?>
            </a>
        </p>
    <?php } else { ?>
        <h1>
            <?=Sys::mId('app', 'hellowUsername', ['username' => Html::encode($username)])?>
        </h1>
        <p class="lead">
            <?=Sys::mId('app', 'selectDo')?>
        </p>
        <p>
            <a href="/profile">
                <?=Sys::mId('app', 'editProfile')?>
            </a>
        </p>
    <?php } ?>

    <p>
        <a href="/addTask" class="btn btn-primary btn-lg">
            <?=Sys::mId('app', 'addTaskLink')?>
        </a>
    </p>
</div>

<?=$this->renderView('_filterSelect', [
    'currentFilterName' => $currentFilterName,
    'currentFilter' => $currentFilter,
    'tasks' => $tasks,
])?>

<?php foreach ($tasks as $task) { ?>
    <div class="card my-3">
        <div class="card-header">
            <div class="row">
                <div class="col-1">
                    #<?=$task->id?>
                </div>
                <div class="col">
                    <?=$task->name?> / <?=$task->email?>
                    <?php if (!$isGuest) { ?>
                        | <a href="/editTask?id=<?=$task->id?>">Изменить</a>
                    <?php } ?>
                </div>
                <?php if ($task->isPerformed() || $task->isUpdated()) { ?>
                    <div class="col-auto">
                        <?php if ($task->isUpdated()) { ?>
                            <small class="text-muted">изменено</small>
                        <?php } ?>
                        <?php if ($task->isPerformed()) { ?>
                            <span class="badge badge-success"><?=Sys::mId('app', 'badgeTaskPerformed')?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="card-body">
            <p class="card-text">
                <?=Html::encode($task->content)?>
            </p>
        </div>
    </div>
<?php } ?>

<?php if ($pagination->getCountPage() > 1) { ?>
    <nav aria-label="Page navigation example" class="my-3">
        <ul class="pagination justify-content-center">
            <?php for ($i = 0; $i < $pagination->getCountPage(); $i++) { ?>
                <li class="page-item">
                    <a class="page-link" href="<?=FormHtmlPagination::getLink($i + 1)?>">
                        <?=$i + 1?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
<?php } ?>
