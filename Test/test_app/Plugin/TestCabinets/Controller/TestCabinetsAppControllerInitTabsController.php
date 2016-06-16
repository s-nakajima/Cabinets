<?php
/**
 * CabinetsAppController::initTabs()テスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('CabinetsAppController', 'Cabinets.Controller');

/**
 * CabinetsAppController::initTabs()テスト用Controller
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Cabinets\Test\test_app\Plugin\TestCabinets\Controller
 */
class TestCabinetsAppControllerInitTabsController extends CabinetsAppController {

/**
 * initTabs
 *
 * @return void
 */
	public function initTabs() {
		$this->autoRender = true;
	}

}
