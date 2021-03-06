<?php

class IndexController extends Pix_Controller
{
    public function indexAction()
    {
    }

    public function boardAction()
    {
	list(, /*board*/, $board) = explode('/', $this->getURI());

        if (!RankData::search(array('board' => $board))->first()) {
            return $this->redirect('/');
	}
	$this->view->board = $board;
    }

    public function searchAction()
    {
        return $this->redirect('/board/' . urlencode($_GET['q']));
    }
}
