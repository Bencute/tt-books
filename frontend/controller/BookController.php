<?php

namespace frontend\controller;

use frontend\exceptions\ExceptionEmptyLines;
use frontend\exceptions\ExceptionNotFoundReport;
use frontend\helper\IsbnProcessReport;
use frontend\model\Task;
use frontend\services\ServiceProcessIsbn;
use Sys;
use system\helper\Pagination;
use Throwable;
use system\controller\Controller;

/**
 * {@inheritdoc}
 * Class DefaultController
 *
 * @package frontend
 */
class BookController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public string $layout = 'book';

    /**
     * @param int $page
     * @return string
     * @throws Throwable
     */
    public function actionIndex(int $page = 1): string
    {
        try {
            $report = IsbnProcessReport::load(ServiceProcessIsbn::getPathReport());

            $pagination = new Pagination($page, $report->getCountLines(), 10);
            $offset = $pagination->getOffset();
            $count = $pagination->getLimit();

            $lines = $report->getLinesIterator($offset, $count);
        } catch (ExceptionNotFoundReport $e) {
            $report = null;
            $pagination = null;
            $lines = [];
        }

        if ($this->getApp()->getRequest()->isAjax()) {
            return $this->renderView('table', [
                'report' => $report,
                'lines' => $lines,
                'pagination' => $pagination,
            ]);
        } else {
            return $this->render('index', [
                'report' => $report,
                'lines' => $lines,
                'pagination' => $pagination,
            ]);
        }
    }

    /**
     * @return string
     */
    public function actionProcess(): string
    {
        Sys::getApp()->getServiceProcessIsbn()->process();

        return $this->redirect('/');
    }
}