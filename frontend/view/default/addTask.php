<?php

use frontend\form\FormAddTask;
use system\helper\FormHtml;
use system\view\View;

/**
 * @var View $this
 * @var FormAddTask $form
 */
?>
<div>
    <h1 class="text-center mb-3">
        <?=Sys::mId('app', 'addTask')?>
    </h1>

    <p class="lead text-center">
        <?=Sys::mId('app', 'descAddTaskPage')?>
    </p>

    <form action="/addTask" method="post"
          class="js-needs-validation form-registration px-3" novalidate
          data-validate-rules='<?=FormHtml::generateJSONValidateParams($form)?>'>

        <?=FormHtml::csrfInput()?>

        <div class="form-group row">
            <label for="inputName" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelNameTask')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('name')?>" value="<?=$form->name?>" type="text"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('name'))?>"
                       id="inputName" placeholder="<?=Sys::mId('app', 'inputLabelNameTask')?>" required>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('name'))?>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputEmail" class="col-sm-4 col-form-label">
                <?=Sys::mId('app', 'inputLabelEmail')?>
            </label>
            <div class="col-sm-8 px-0">
                <input name="<?=$form->getAttributeFormName('email')?>" value="<?=$form->email?>" type="email"
                       class="form-control <?=FormHtml::stateInValidClass($form->isAttributeError('email'))?>"
                       id="inputEmail" placeholder="<?=Sys::mId('app', 'placeholderEmail')?>" required>
                <?=FormHtml::getMessageErrors($form->getAttributeMessageErrors('email'))?>
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

        <div class="form-group row  justify-content-center">
            <button type="submit" formnovalidate class="btn btn-primary btn-block col-sm-6">
                <?=Sys::mId('app', 'buttonAddTask')?>
            </button>
        </div>
    </form>
</div>
