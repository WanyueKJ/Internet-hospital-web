<?php

/* 内容管理 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use app\portal\service\PostService;
use think\Db;

class PageController extends HomebaseController{

	public function lists() {

        $postService = new PostService();
        $pageId      = 4;
        $page        = $postService->publishedPage($pageId);

        if (empty($page)) {
            $this->assign('reason', lang('页面不存在'));
            return $this->fetch(':error');
        }

        $this->assign('uid', '');
        $this->assign('token', '');
        $this->assign('page', $page);


        return $this->fetch('detail');
	}

    public function detail() {
        $postService = new PostService();
        $pageId      = $this->request->param('id', 0, 'intval');
        $page        = $postService->publishedPage($pageId);
        if (empty($page)) {
            $this->assign('uid', '');
            $this->assign('token', '');
            $this->assign('page', $page);

            $this->assign('reason', lang('页面不存在'));
            return $this->fetch(':error');
        }

        $this->assign('uid', '');
        $this->assign('token', '');
        $this->assign('page', $page);


        return $this->fetch();
	}


}
