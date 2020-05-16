<?php

namespace frontend\controller;

use Exception;
use frontend\form\FormAddTask;
use frontend\form\FormEditTask;
use frontend\model\Task;
use ReflectionException;
use system\helper\Pagination;
use Throwable;
use Sys;
use frontend\form\FormLogin;
use frontend\form\FormProfile;
use frontend\form\FormRegistration;
use frontend\model\User;
use system\application\WebUser;
use system\controller\Controller;
use system\exception\HttpException404;

/**
 * {@inheritdoc}
 * Class DefaultController
 *
 * @package frontend
 */
class DefaultController extends Controller
{
    const COUNT_TASKS_PER_PAGE = 3;
    const DEFAULT_TASKS_FILTER = 'idAsk';

    /**
     * @param int $page
     * @param string $sort
     * @return string
     * @throws Throwable
     */
    public function actionIndex(int $page = 1, string $sort = self::DEFAULT_TASKS_FILTER): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        $isGuest = $webUser->isGuest();
        $username = '';

        if (!$isGuest && !is_null($webUser->getUser()))
            $username = $webUser->getUser()->name;

        $pagination = new Pagination($page, Task::count(), self::COUNT_TASKS_PER_PAGE);
        $condition['limit']['offset'] = $pagination->getOffset();
        $condition['limit']['count'] = $pagination->getLimit();

        $filters = Task::getFilters();
        $filterName = isset($filters[$sort]) ? $sort : self::DEFAULT_TASKS_FILTER;

        $filter = [];

        if (isset($filters[$filterName])) {
            $filter = $filters[$filterName];
            $condition['order'][$filter['attribute']] = $filter['order'];
        }

        $tasks = $this->getApp()->getTasks($condition);

        return $this->render('index', [
            'isGuest' => $isGuest,
            'username' => $username,
            'tasks'=> $tasks,
            'pagination' => $pagination,
            'currentFilterName' => $filterName,
            'currentFilter' => $filter,
        ]);
    }

    /**
     * @throws Throwable
     * @return string
     */
    public function actionLogin(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if (!$webUser->isGuest())
            return $this->redirect('/');

        $form = new FormLogin();
        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                /** @var User $user */
//                $user = $this->getApp()->getUser($form->email);
                $user = $this->getApp()->getUserByName($form->name);
                if (!is_null($user) && $user->verifyPassword($form->password)) {
                    if ($this->getApp()->login($user, $form->remember ? null : 0)) {
                        return $this->redirect('/');
                    } else {
                        $form->addError('email', Sys::mId('app', 'errorActionLoginFailedLogin'));
                    }
                } else {
//                    $form->addError('email', Sys::mId('app', 'errorFormLoginInValidEmail'));
                    $form->addError('name', Sys::mId('app', 'errorFormLoginInValidName'));
                    $form->addError('password', Sys::mId('app', 'errorFormLoginInValidPassword'));
                }
            }
        }

        $form->password = null;

        return $this->render('login', ['form' => $form]);
    }

    /**
     * @throws Throwable
     * @return string
     */
    public function actionRegistration(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if (!$webUser->isGuest())
            return $this->redirect('/index');

        $form = new FormRegistration();
        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                $user = new User();

                $user->loadAttributes($form);
                $user->setPassword($form->password);
                if (isset($form->avatar->files[0]))
                    $user->setAvatar($form->avatar->files[0]);

                if ($this->getApp()->addUser($user)) {
                    $webUser->addFlash(Sys::mId('app', 'messageSuccessRegistration'), 'messageSuccessRegistration', 'successes');
                    return $this->redirect('/profile');
                } else {
                    $webUser->addFlash(Sys::mId('app', 'messageErrorCreateUser'), 'messageErrorCreateUser', 'errors');
                }
            }
        }
        return $this->render('registration', ['form' => $form, 'countries' => $this->getApp()->getCountries()]);
    }

    /**
     * @throws HttpException404
     * @throws Throwable
     * @throws ReflectionException
     * @return string
     */
    public function actionProfile(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            $this->redirect('/login');

        $form = new FormProfile();

        /** @var User $user */
        $user = $webUser->getUser();

        if (is_null($user))
            throw new HttpException404(Sys::mId('app', 'notFoundUser'));

        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                if ($user->verifyPassword($form->password)) {
                    $user->loadAttributes($form);
                    if (isset($form->avatar->files[0]))
                        $user->setAvatar($form->avatar->files[0]);

                    if ($user->save()) {
                        $form->flushValidate();
                        $webUser->addFlash(Sys::mId('app', 'messageSuccessUpdateProfile'), 'messageSuccessUpdateProfile', 'successes');
                        return $this->redirect('/profile');
                    } else {
                        $webUser->addFlash(Sys::mId('app', 'messageErrorUpdateProfile'), 'messageErrorUpdateProfile', 'errors');
                    }
                } else {
                    $form->addError('password', Sys::mId('app', 'errorFormProfileInValidPassword'));
                }
            }
        } else {
            $form->loadAttributes($user);
        }

        $form->password = null;

        return $this->render('profile', ['user' => $user, 'form' => $form, 'countries' => $this->getApp()->getCountries()]);
    }

    /**
     * @throws Exception
     * @return string
     */
    public function actionLogout(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            return $this->redirect('/');

        if ($webUser->isValidCsrf() &&
            $this->getApp()->getRequest()->isPost() &&
            $webUser->logout()
        )
            return $this->redirect('/');

        return $this->redirect('/profile');
    }

    /**
     * @throws Exception
     * @return string
     */
    public function actionDeleteAvatar(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            return $this->redirect('/index');

        $user = $webUser->getUser();

        if (is_null($user))
            return $this->redirect('/profile');

        if ($this->getApp()->getRequest()->isPost() &&
            $webUser->isValidCsrf() &&
            $user->deleteAvatar() &&
            $user->save()
        ) {
            $webUser->addFlash(Sys::mId('app', 'messageSuccessUpdateProfile'), 'messageSuccessUpdateProfile', 'successes');
            return $this->redirect('/profile');
        } else {
            $webUser->addFlash(Sys::mId('app', 'messageErrorUpdateProfile'), 'messageErrorUpdateProfile', 'errors');
        }

        return $this->redirect('/profile');
    }

    /**
     * @return string
     * @throws Exception
     */
    public function actionDeleteProfile(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            return $this->redirect('/login');

        $user = $webUser->getUser();

        if (is_null($user))
            return $this->redirect('/profile');

        if ($webUser->isValidCsrf() &&
            $this->getApp()->getRequest()->isPost() &&
            $user->delete()
        ) {
            $webUser->logout();
            $webUser->addFlash(Sys::mId('app', 'messageSuccessDeleteProfile'), 'messageSuccessDeleteProfile', 'successes');
            return $this->redirect('/index');
        }

        $webUser->addFlash(Sys::mId('app', 'messageErrorDeleteProfile'), 'messageErrorDeleteProfile', 'errors');

        return $this->redirect('/index');
    }

    public function actionAddTask(): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        $form = new FormAddTask();
        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                $task = new Task();

                $task->loadAttributes($form);

                if ($this->getApp()->addTask($task)) {
                    $webUser->addFlash(Sys::mId('app', 'messageSuccessAddTask'), 'messageSuccessAddTask', 'successes');
                    return $this->redirect('/');
                } else {
                    $webUser->addFlash(Sys::mId('app', 'messageErrorAddTask'), 'messageErrorAddTask', 'errors');
                }
            }
        }
        return $this->render('addTask', ['form' => $form]);
    }

    public function actionEditTask(int $id): string
    {
        /** @var WebUser $webUser */
        $webUser = $this->getApp()->getWebUser();

        if ($webUser->isGuest())
            $this->redirect('/login');

        /** @var Task $task */
        $task = $this->getApp()->getTask($id);

        if (is_null($task))
            throw new HttpException404(Sys::mId('app', 'notFoundTask'));

        $form = new FormEditTask();
        if (!empty($_POST) && $webUser->isValidCsrf()) {
            $form->load($_POST);
            if ($form->validate()) {
                $oldContent = $task->content;
                $task->loadAttributes($form);

                if (strcmp($oldContent, $task->content) !== 0)
                    $task->setIsUpdated();

                if ($task->save()) {
                    $webUser->addFlash(Sys::mId('app', 'messageSuccessUpdateTask'), 'messageSuccessUpdateTask', 'successes');
                    return $this->redirect('/');
                } else {
                    $webUser->addFlash(Sys::mId('app', 'messageErrorUpdateTask'), 'messageErrorUpdateTask', 'errors');
                }
            }
        } else {
            $form->loadAttributes($task);
        }

        return $this->render('editTask', ['form' => $form, 'task' => $task]);
    }
}