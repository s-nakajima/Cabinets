<?php
/**
 * CabinetFileTreeFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Your Name <yourname@domain.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for CabinetFileTreeFixture
 */
class CabinetFileTreeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'unsigned' => false,
			'key' => 'primary',
			'comment' => 'ID | | | '
		),
		'cabinet_key' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'bbs key | キャビネットキー | Hash値 | ',
			'charset' => 'utf8'
		),
		'cabinet_file_key' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'bbs articles key | ファイルキー | Hash値 | ',
			'charset' => 'utf8'
		),
		'cabinet_file_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'parent_id' => array(
			'type' => 'integer',
			'null' => true,
			'default' => null,
			'unsigned' => false,
			'comment' => 'parent id | 親フォルダのID treeビヘイビア必須カラム | | '
		),
		'lft' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'unsigned' => false,
			'comment' => 'lft | treeビヘイビア必須カラム | | '
		),
		'rght' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'unsigned' => false,
			'comment' => 'rght | treeビヘイビア必須カラム | | '
		),
		'created_user' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '0',
			'unsigned' => false,
			'comment' => 'created user | 作成者 | users.id | '
		),
		'created' => array(
			'type' => 'datetime',
			'null' => true,
			'default' => null,
			'comment' => 'created datetime | 作成日時 | | '
		),
		'modified_user' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '0',
			'unsigned' => false,
			'comment' => 'modified user | 更新者 | users.id | '
		),
		'modified' => array(
			'type' => 'datetime',
			'null' => true,
			'default' => null,
			'comment' => 'modified datetime | 更新日時 | | '
		),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array(
			'charset' => 'utf8',
			'collate' => 'utf8_general_ci',
			'engine' => 'InnoDB'
		)
	);

/**
 * Records CabinetFile.id 1-8に対応するレコードは予約
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'cabinet_key' => 'Lorem ipsum dolor sit amet',
			'cabinet_file_key' => 'Lorem ipsum dolor sit amet',
			'parent_id' => 1,
			'lft' => 1,
			'rght' => 1,
			'created_user' => 1,
			'created' => '2016-04-14 02:48:19',
			'modified_user' => 1,
			'modified' => '2016-04-14 02:48:19'
		),
		array(
			'id' => 10,
			'cabinet_key' => 'cabinet_3',
			'cabinet_file_key' => 'content_key_10',
			'parent_id' => null,
			'lft' => 1,
			'rght' => 2,
			'created_user' => 1,
			'created' => '2016-04-14 02:48:19',
			'modified_user' => 1,
			'modified' => '2016-04-14 02:48:19'
		),
	);

}
