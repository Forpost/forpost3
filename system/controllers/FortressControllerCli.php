<?php
/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 20.07.14
 * Time: 2:08
 */

class FortressControllerCli extends CliController
{
    public function actionCreateController($controller_name)
    {
        $controller_name = str_replace('controller', '', strtolower($controller_name));

        if (empty($controller_name)) {
            Output::println('Usage: ./fortress CreateController <controller_name>');
            exit(0);
        }

        $controller_file_name = ucfirst($controller_name) . 'Controller.php';
        $controller_file_path = CONTROLLERS_DIR . '/' . $controller_file_name;

        if (!Lib::chkFile($controller_file_path)) {
            $content = Lib::text2html($this->FortressView->render(
                'cli/default_controller',
                array(
                    'controller_name' => $controller_name,
                )
            ));
            file_put_contents($controller_file_path, $content);
            chmod($controller_file_path, octdec(Config::get('sys.file_chmod')));

            if (Config::get('sys.default_uid')) {
                chown($controller_file_path, Config::get('sys.default_uid'));
            }

            if (Config::get('sys.default_gid')) {
                chgrp($controller_file_path, Config::get('sys.default_gid'));
            }

            Output::println('Successfully created controller ' . $controller_file_name);
        } else {
            Output::println('Error! File ' . $controller_file_path . ' already exists.');
        }

    }

    public function actionCreateModel($model_name)
    {
        $model_name = str_replace('model', '', strtolower($model_name));

        if (empty($model_name)) {
            Output::println('Usage: ./fortress CreateModel <model_name>');
            exit(0);
        }

        $model_file_name = ucfirst($model_name) . 'Model.php';
        $model_file_path = MODELS_DIR . '/' . $model_file_name;

        if (!Lib::chkFile($model_file_path)) {
            $content = Lib::text2html($this->FortressView->render(
                'cli/default_model',
                array(
                    'model_name' => $model_name,
                )
            ));
            file_put_contents($model_file_path, $content);
            chmod($model_file_path, octdec(Config::get('sys.file_chmod')));

            if (Config::get('sys.default_uid')) {
                chown($model_file_path, Config::get('sys.default_uid'));
            }

            if (Config::get('sys.default_gid')) {
                chgrp($model_file_path, Config::get('sys.default_gid'));
            }


            Output::println('Successfully created model ' . $model_file_name);
        } else {
            Output::println('Error! File ' . $model_file_path . ' already exists.');
        }
    }

    public function actionCreateView($view_name)
    {
        $view_name = str_replace('view', '', strtolower($view_name));

        if (empty($view_name)) {
            Output::println('Usage: ./fortress CreateView <view_name>');
            exit(0);
        }

        $view_file_name = ucfirst($view_name) . 'View.php';
        $view_file_path = VIEWS_DIR . '/' . $view_file_name;

        if (!Lib::chkFile($view_file_path)) {
            $content = Lib::text2html($this->FortressView->render(
                'cli/default_view',
                array(
                    'view_name' => $view_name,
                )
            ));
            file_put_contents($view_file_path, $content);
            chmod($view_file_path, octdec(Config::get('sys.file_chmod')));

            if (Config::get('sys.default_uid')) {
                chown($view_file_path, Config::get('sys.default_uid'));
            }

            if (Config::get('sys.default_gid')) {
                chgrp($view_file_path, Config::get('sys.default_gid'));
            }

            Output::println('Successfully created view ' . $view_file_name);
        } else {
            Output::println('Error! File ' . $view_file_path . ' already exists.');
        }

    }

    public function actionCreateMVCTriad($controller_name, $model_name, $view_name)
    {
        $controller_name = str_replace('controller', '', strtolower($controller_name));
        $model_name = str_replace('model', '', strtolower($model_name));
        $view_name = str_replace('view', '', strtolower($view_name));

        if (empty($controller_name) || empty($model_name) || empty($view_name)) {
            Output::println('Usage: ./fortress CreateMVCTriad <controller_name> <model_name> <view_name>');
            exit(0);
        }

        $this->actionCreateController($controller_name);
        $this->actionCreateModel($model_name);
        $this->actionCreateView($view_name);
    }

    public function actionCreateComponent($component_name)
    {
        $flg = false;
        $component_name = str_replace('com', '', strtolower($component_name));

        if (empty($component_name)) {
            Output::println('Usage: ./fortress CreateComponent <component_name>');
            exit(0);
        }

        $component_name = ucfirst($component_name) . 'Com';
        $component_path = COM_DIR . '/' . $component_name;

        if (Lib::chkDir($component_path)) {
            Output::println('Component ' . $component_name . ' already exists.');
            exit(0);
        }

        $flg = mkdir($component_path);
        $flg = mkdir($component_path . '/classes');
        $flg = mkdir($component_path . '/includes');
        $flg = mkdir($component_path . '/templates');

        if (!$flg) {
            Output::println('Can`t create directories for component. Check permissions on application/components directory.');
            exit(0);
        }

        chmod($component_path, octdec(Config::get('sys.dir_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path, Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
            chgrp($component_path, Config::get('sys.default_gid'));
        }

        chmod($component_path . '/classes', octdec(Config::get('sys.dir_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path . '/classes', Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
            chgrp($component_path . '/classes', Config::get('sys.default_gid'));
        }

        chmod($component_path . '/includes', octdec(Config::get('sys.dir_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path . '/includes', Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
            chgrp($component_path . '/includes', Config::get('sys.default_gid'));
        }

        chmod($component_path . '/templates', octdec(Config::get('sys.dir_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path . '/templates', Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
            chgrp($component_path . '/templates', Config::get('sys.default_gid'));
        }

        $content = Lib::text2html($this->FortressView->render(
            'cli/component/component',
            array(
                'component_name' => $component_name,
            )
        ));
        file_put_contents($component_path . '/component.php', $content);
        chmod($component_path . '/component.php', octdec(Config::get('sys.file_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path . '/component.php', Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
           chgrp($component_path . '/component.php', Config::get('sys.default_gid'));
        }

        $content = Lib::text2html($this->FortressView->render(
            'cli/component/index',
            array(
                'component_name' => $component_name,
            )
        ));
        file_put_contents($component_path . '/index.php', $content);
        chmod($component_path . '/index.php', octdec(Config::get('sys.file_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path . '/index.php', Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
            chgrp($component_path . '/index.php', Config::get('sys.default_gid'));
        }

        $content = Lib::text2html($this->FortressView->render('cli/component/init'));
        file_put_contents($component_path . '/init.php', $content);
        chmod($component_path . '/init.php', octdec(Config::get('sys.file_chmod')));

        if (Config::get('sys.default_uid')) {
            chown($component_path . '/init.php', Config::get('sys.default_uid'));
        }

        if (Config::get('sys.default_gid')) {
            chgrp($component_path . '/init.php', Config::get('sys.default_gid'));
        }

        Output::println('Successfully created component ' . $component_name);
    }

    public function defaultAction()
    {
        Output::println('Usage: ./fortress [command] [param1, param2, ... paramN]');
    }
}
