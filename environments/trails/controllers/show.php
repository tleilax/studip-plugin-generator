<?php
class ShowController extends StudipController {

    public function before_filter(&$action, &$args) {

		$this->set_layout($GLOBALS['template_factory']->open('layouts/base_without_infobox'));
//		PageLayout::setTitle('');

    }

    public function index_action() {

		$this->answer = 'Yes';

    }

}
