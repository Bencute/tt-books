<?php

use frontend\helper\IsbnProcessReport;
use system\helper\Pagination;
use system\view\View;

/**
 * @var View $this
 * @var array $lines
 * @var Pagination $pagination
 * @var IsbnProcessReport $report
 */
?>
<div class="text-center my-3">
    <a href="/process" class="btn btn-primary btn-lg"><?=Sys::mId('app', 'linkNameProcessDB')?></a>
</div>

<?php // TODO Перенести в отдельный файл стилей ?>
<style type="text/css">
    pre {
        white-space: pre-wrap;
    }
</style>

<div class="js-containerReport">
    <?php if (empty($lines)) { ?>
        <p class="lead text-center">Отчет не найден</p>
    <?php } else { ?>
        <?=$this->renderView('table', [
            'report' => $report,
            'lines' => $lines,
            'pagination' => $pagination,
        ])?>
    <?php } ?>
</div>

<?php // TODO Перенести в более подходящее место ?>
<script>
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            initAjaxPaginator()
        }, false);
    })();

    function initAjaxPaginator(){
        let links = document.querySelectorAll('.js-pageLink');
        for (let item of links) {
            item.addEventListener('click', function (e) {
                loadTableReport(this.href);

                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
            }, false);
        }
    }

    function loadTableReport(link){
        let httpRequest = new XMLHttpRequest();
        httpRequest.open('GET', link, true);
        httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        httpRequest.onload = function (e) {
            let container = document.querySelector('.js-containerReport');
            container.innerHTML = this.responseText;
            initAjaxPaginator();
        };
        httpRequest.send();
    }
</script>
