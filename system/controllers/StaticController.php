<?php
/**
 * Created by JetBrains PhpStorm.
 * User: CoolKid
 * Date: 05.11.13
 * Time: 22:40
 * To change this template use File | Settings | File Templates.
 */

class StaticController extends WebController
{
    public function init()
    {
        Config::set('app.show_debug_panel',true);
    }

    public function actionStatic()
    {
        $page_id=Registry::get('app.static_page_id');
        $this->StaticModel->loadStaticPage($page_id);
        $this->StaticView->setData($this->StaticModel->getData());
        $content=$this->StaticView->render();
        Output::setContent($content);
        Output::showPage();
    }
}
