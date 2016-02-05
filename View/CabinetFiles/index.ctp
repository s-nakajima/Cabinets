<?php

$currentFolderTree = Hash::extract($folderPath, '{n}.CabinetFileTree.id');
$currentFolderTree = array_map('intval', $currentFolderTree);

?>

<?php
echo $this->Html->css(
	'/cabinets/css/cabinets.css',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
); ?>
<?php
echo $this->Html->script(
	'/cabinets/js/cabinets.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>
<?php
// Like
echo $this->Html->script(
	'/likes/js/likes.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
echo $this->Html->css(
	'/likes/css/style.css',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
echo $this->Html->script(
	'/AuthorizationKeys/js/authorization_keys.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);

?>
<h1 class="cabinets_cabinetTitle"><?php echo h($cabinet['Cabinet']['name']) ?></h1>
<div class="clearfix">
	<div class="pull-left">
		<?php echo $this->element('file_path'); ?>
	</div>

	<div class="pull-right">
		<?php if (Current::permission('content_creatable')) : ?>
			<div class="pull-right" style="margin-left: 10px;">
				<?php
				if(count($folderPath) > 0){
					// フォルダ
					$parentId = $folderPath[count($folderPath) - 1]['CabinetFileTree']['id'];
				}else{
					$parentId = null;
				}

				$addUrl = $this->NetCommonsHtml->url(array(
					'controller' => 'cabinet_files_edit',
					'action' => 'add_folder',
					'frame_id' => Current::read('Frame.id'),
					'parent_id' => $parentId,
				));
				echo $this->Button->addLink('<span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>' . __d('cabinets', 'フォルダ') ,
					$addUrl,
					array('tooltip' => __d('cabinets', 'Add folder'), 'escapeTitle' => false));
				?>
			</div>
		<?php endif ?>
		<?php if (Current::permission('content_creatable')) : ?>
			<div class="pull-right">
				<?php
				$addUrl = $this->NetCommonsHtml->url(array(
					'controller' => 'cabinet_files_edit',
					'action' => 'add',
					'frame_id' => Current::read('Frame.id')
				));
				echo $this->Button->addLink('<span class="glyphicon glyphicon-file" aria-hidden="true"></span>' . __d('cabinets', 'ファイル'),
					$addUrl,
					array('tooltip' => __d('cabinets', 'Add file')));
				?>
			</div>
		<?php endif ?>
	</div>
</div>


<style>
	.cabinets__folder-tree__folder{
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	.cabinets-folder-tree li.list-group-item{
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	/*.cabinets__folder-tree__folder:hover{*/
		/*background: #ccc;*/
	/*}*/
	.cabinets__folder-tree-toggle{
		cursor: pointer;
	}
	span.cabinets-nest{
		margin-left: 15px;
	}
</style>
<div class="row">
	<div class="col-md-3 hidden-sm hidden-xs cabinets-folder-tree inline" ng-controller="Cabinets.FolderTree" ng-init="init(<?php echo json_encode($currentFolderTree)?>)">
		<ul class="list-group">
			<li class="list-group-item ">
				<?php
				echo $this->NetCommonsHtml->link('<span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>' . h($cabinet['Cabinet']['name']), NetCommonsUrl::backToIndexUrl(), ['escape' => false]);
				?>
			</li>
			<?php


			$this->CabinetsFolderTree->render($folders, $currentTreeId ,$currentFolderTree);

			?>
		</ul>



	</div>
	<div class="col-md-9 inline">
		<table class="table">
			<thead>
			<tr>
				<th>名前</th>
				<th class="hidden-sm hidden-xs"><?php echo __d('cabinets', 'サイズ') ?></th>
				<th>最終更新</th>
				<th class="hidden-md hidden-sm hidden-xs"><?php echo __d('cabinets', 'ダウンロード回数'); ?></th>
				<th></th>

			</tr>
			</thead>
			<tbody>

			<?php if ($parentUrl): ?>
			<tr>
				<td>
					<?php
					echo $this->Html->link('<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true"></span>' . __d(
							'cabinets',
							'一つ上へ'
						), $parentUrl, ['escape' => false]);
					?>
				</td>
				<td class="hidden-sm hidden-xs"></td>
				<td></td>
				<td class="hidden-md hidden-sm hidden-xs"></td>
				<td></td>
			</tr>
			<?php endif ?>

			<?php foreach ($cabinetFiles as $cabinetFile): ?>
				<tr>
					<td>
						<?php if ($cabinetFile['CabinetFile']['is_folder']) :?>

							<?php
							$icon = '<span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>';
							echo $this->NetCommonsHtml->link($icon . h($cabinetFile['CabinetFile']['filename']), ['key' => $cabinetFile['CabinetFile']['key']], ['escape' => false]);
							?>

						<?php else: ?>
							<?php
							$icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';
							echo $this->NetCommonsHtml->link($icon . h($cabinetFile['CabinetFile']['filename']), ['action' => 'download', 'key' => $cabinetFile['CabinetFile']['key']], ['escape' => false]);
							?>
						<?php endif ?>

					</td>
					<td class="hidden-sm hidden-xs"><?php echo $this->Number->toReadableSize($cabinetFile['CabinetFile']['size']) ?></td>
					<td><?php echo $this->Date->dateFormat($cabinetFile['CabinetFile']['modified']) ?> <?php echo $cabinetFile['TrackableUpdater']['username'] ?></td>
					<td class="hidden-md hidden-sm hidden-xs"><?php echo $cabinetFile['CabinetFile']['download_count'] ?></td>
					<td>
						<?php
						if ($cabinetFile['CabinetFile']['is_folder']) {
							// link folder_detail
							$detailUrl = $this->NetCommonsHtml->url(['action' => 'folder_detail', 'key' => $cabinetFile['CabinetFile']['key']]);
							//$detailUrl = NetCommonsUrl::actionUrl(['action' => 'folder_detail', 'key' => $cabinetFile['CabinetFile']['key']]);
						}else{
							// link file_detail
							$detailUrl = $this->NetCommonsHtml->url(['action' => 'file_detail', 'key' => $cabinetFile['CabinetFile']['key']]);
						}
						?>

						<a href="<?php echo $detailUrl ?>" class="btn btn-default">
							<span class="glyphicon glyphicon-info-sign aria-hidden="true"></span>
						</a>

					</td>
				</tr>
			<?php endforeach ?>

			</tbody>
		</table>

	</div>
</div>

