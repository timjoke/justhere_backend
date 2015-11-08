<?php

class SiteController extends Controller
{

    public $layout = 'main';

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        echo 'yaohe-api';
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        echo 'error';
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionInfo()
    {
        phpinfo();
    }

    public function actionLog()
    {
        $log = Yii::getPathOfAlias('application.runtime') . '\\application.log';
        $file = file_get_contents($log);
        echo $file;
    }

}
