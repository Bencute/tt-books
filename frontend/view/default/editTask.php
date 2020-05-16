<?php

use frontend\form\FormAddTask;
use frontend\model\Task;
use system\helper\FormHtml;
use system\view\View;

/**
 * @var View $this
 * @var FormAddTask $form
 * @var Task $task
 */
?>
<div>
    <h1 class="text-center mb-3">
        <?=Sys::mId('app', 'editTask')?>
    </h1>

    <p class="lead text-center">
        <?=Sys::mId('app', 'descEditTaskPage', ['id' => $task->id])?>
    </p>

    <form action="/editTask?id=<?=$task->id?>" method="post"
          class="js-needs-validation form-registration px-3" novalidate
          data-validate-rules='<?=FormHtml::generateJSONValidateParams($form)?>'>

        <?=FormHtml::csrfInput()?>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelNameTask')?>
            </label>
            <div class="col-sm-8 px-0">
                <input value="<?=$task->name?>" readonly type="text" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelEmail')?>
            </label>
            <div class="col-sm-8 px-0">
                <input value="<?=$task->email?>" readonly type="text" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label for="inputDesc" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelContent')?>
            </label>
            <div class="col-sm-8 px-0">
                <textarea name="<?=$form->getAttributeFormName('content')?>" rows="3"
                          class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('content'))?>"
                          id="inputDesc" placeholder="<?=Sys::mId('app', 'inputLabelContent')?>"><?=$form->content?></textarea>
                <small class="form-text text-muted">
                    <?=Sys::mId('app', 'inputNoteContent')?>
                </small>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('content'))?>
            </div>
        </div>

        <div class="form-group text-center custom-control custom-checkbox p-0">
            <input name="<?=$form->getAttributeFormName('performed')?>" <?=FormHtml::boolToChecked($form->performed)?>
                   type="checkbox"
                   class="custom-control-input <?=FormHtml::stateInValidClass($form->isAttributeError('performed'))?>"
                   id="inputRemember" value="0">
            <label class="custom-control-label" for="inputRemember">
                <?=Sys::mId('app', 'inputLabelPerformed')?>
            </label>
            <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('performed'))?>
        </div>

        <div class="form-group row  justify-content-center">
            <button type="submit" formnovalidate class="btn btn-primary btn-block col-sm-6">
                <?=Sys::mId('app', 'buttonEditTask')?>
            </button>
        </div>
    </form>
</div>
