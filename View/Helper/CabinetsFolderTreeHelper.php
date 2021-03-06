<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 15/03/06
 * Time: 14:57
 */
App::uses('AppHelper', 'View/Helper');

/**
 * Class CabinetsFormatHelper
 */
class CabinetsFolderTreeHelper extends AppHelper {

/**
 * @var array helpers
 */
	public $helpers = array('NetCommons.Date', 'Html', 'NetCommonsHtml');

/**
 * @var array currentFolderTree
 */
	protected $_currentFolderTree = array();

/**
 * @var int 現在位置のツリーID
 */
	protected $_currentTreeId = 0;

/**
 * @var bool selectTree
 */
	protected $_selectTree = false;

/**
 * ファイル一覧左側に表示するフォルダツリーの出力
 *
 * @param array $folders フォルダツリーデータ
 * @param int $currentTreeId 現在位置のツリーID
 * @return void
 */
	public function render($folders, $currentTreeId) {
		$this->_currentTreeId = $currentTreeId;
		$this->_render($folders);
	}

/**
 * フォルダ選択時のフォルダツリー出力
 *
 * @param array $folders フォルダツリーデータ
 * @param int $currentTreeId 現在位置のツリーID
 * @return void
 */
	public function renderSelectFolderTree($folders, $currentTreeId) {
		$this->_selectTree = true;
		$this->_currentTreeId = $currentTreeId;
		$this->_render($folders);
	}

/**
 * フォルダツリー出力（再帰呼び出しされる）
 *
 * @param array $folders フォルダツリーデータ
 * @param int $nest フォルダのネストレベル
 * @param int $parentFolderId 親フォルダのツリーID
 * @return void
 */
	protected function _render($folders, $nest = -1, $parentFolderId = 0) {
		foreach ($folders as $folder) {
			$treeId = $folder['CabinetFileTree']['id'];
			$isActiveFolder = ($treeId == $this->_currentTreeId);
			$tree = '';
			for ($i = 0; $i < $nest; $i++) {
				$tree .= $this->Html->tag('span', '', ['class' => 'cabinets-nest']);
			}
			// currentフォルダか
			if ($folder['CabinetFileTree']['id'] == $this->_currentTreeId) {
				$active = 'active';
			} else {
				$active = '';
			}

			$folderIcon = $this->_getFolderIcon($nest, $folder, $treeId);

			$options = [
				'escape' => false,
				'class' => 'cabinets__folder-tree__folder list-group-item ' . $active,
			];
			if ($parentFolderId > 0) {
				$options['ng-show'] = 'folder[' . $parentFolderId . ']';
			}

			//  actveだったらリンクしない
			if ($isActiveFolder) {
				echo $this->Html->tag(
					'li',
					$tree . $folderIcon . h($folder['CabinetFile']['filename']),
					$options
				);
			} else {
				if ($this->_selectTree) {
					// フォルダ選択用
					$url = '#';
				} else {
					$url = [
						'action' => 'index',
						'key' => $folder['CabinetFile']['key']
					];
				}
				$link = $this->NetCommonsHtml->link(
					h($folder['CabinetFile']['filename']),
					$url,
					['ng-click' => 'select(' . $folder['CabinetFileTree']['id'] . ')']
				);
				echo $this->Html->tag(
					'li',
					$tree . $folderIcon . $link,
					$options
				);
			}

			if (Hash::get($folder, 'children', false)) {
				//echo '<div id="cabinets-folder-tree-children-'.$folderId.'" class="" __ng-show="folder['.$folderId.']">';
				$this->_render($folder['children'], $nest + 1, $treeId);
				//echo '</div>';
			}

		}
	}

/**
 * フォルダアイコンを返す
 *
 * @param int $nest フォルダのネストレベル
 * @param array $folder CabinetFile フォルダデータ
 * @param int $treeId CabinetFileTree.id
 * @return string アイコン
 */
	protected function _getFolderIcon($nest, $folder, $treeId) {
		if ($nest == -1) {
			// Cabinet
			$arrowIcon = '';
			$folderIcon = '<span class="glyphicon glyphicon-hdd" aria-hidden="true" ></span>';
		} else {
			// 下位のフォルダがなければアローアイコン不要
			if (Hash::get($folder, 'children', false)) {
				$arrowIcon = '<span class="glyphicon cabinets__folder-tree-toggle"' .
					' aria-hidden="true"  style="width: 15px"' .
					' ng-class="{\'glyphicon-menu-down\': folder[' . $treeId . '],' .
					' \'glyphicon-menu-right\': ! folder[' . $treeId . ']}"' .
					' ng-click="toggle(' . $treeId . ')"></span> ';
			} else {
				$arrowIcon = '<span  class="glyphicon" style="width: 15px"></span> ';
			}

			$folderIcon = '<span class="glyphicon " aria-hidden="true"' .
				' ng-class="{\'glyphicon-folder-open\': folder[' . $treeId . '], ' .
				'\'glyphicon-folder-close\': ! folder[' . $treeId . ']}"></span>';
		}

		$folderIcon = $arrowIcon . $folderIcon;
		return $folderIcon;
	}
}
