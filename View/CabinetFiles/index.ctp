<?php echo $this->element('NetCommons.javascript_alert'); ?>
<div ng-controller="Cabinets" ng-init="init(
	 <?php echo Current::read('Block.id') ?>,
	 <?php echo Current::read('Frame.id') ?>
	 )">

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
echo $this->Html->script(
	'/AuthorizationKeys/js/authorization_keys.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);

?>
	<script>
		$(function () {
			$('.cabinets__index__description').popover({html:true})
		})
		// popover外クリックでpopoverを閉じる
		$('body').on('click', function (e) {
			$('[data-toggle="popover"]').each(function () {
				//the 'is' for buttons that trigger popups
				//the 'has' for icons within a button that triggers a popup
				if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
					$(this).popover('hide');
				}
			});
		});

	</script>


	<h1 class="cabinets_cabinetTitle"><?php echo h($cabinet['Cabinet']['name']) ?></h1>
<div class="clearfix">
	<div class="pull-left cabinets__index__file-path">
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
				if (Current::permission('content_editable')){
					echo $this->Button->addLink('<span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>' . __d('cabinets', 'フォルダ') ,
						$addUrl,
						array('tooltip' => __d('cabinets', 'Add folder'), 'escapeTitle' => false, 'escape' => false));
				}
				?>
			</div>
		<?php endif ?>
		<?php if (Current::permission('content_creatable')) : ?>
			<div class="pull-right" ng-controller="CabinetFile.addFile" ng-init="init(<?php echo $parentId?>)">
				<?php
				$addUrl = $this->NetCommonsHtml->url(array(
					'controller' => 'cabinet_files_edit',
					'action' => 'add',
					'frame_id' => Current::read('Frame.id'),
					'parent_id' => $parentId,
				));
				echo $this->Button->addLink('<span class="glyphicon glyphicon-file" aria-hidden="true"></span>' . __d('cabinets', 'ファイル'),
					'#',
					array('tooltip' => __d('cabinets', 'Add file'), 'ng-click' => 'addFile()', 'escapeTitle' => false, 'escape' => false));
				?>
			</div>
		<?php endif ?>
	</div>
</div>


<div class="row">
	<?php // ============ フォルダツリー ============?>
	<div class="col-md-3 hidden-sm hidden-xs cabinets-folder-tree inline" ng-controller="Cabinets.FolderTree" ng-init="init(<?php echo json_encode($currentFolderTree)?>)" >
		<ul class="list-group" ng-cloak>
			<?php if ($currentTreeId > 0): ?>
			<li class="list-group-item cabinets__folder-tree__folder">
				<?php
				echo '<span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>';
				echo $this->NetCommonsHtml->link( h($cabinet['Cabinet']['name']), NetCommonsUrl::backToIndexUrl(), ['escape' => false]);
				?>
			</li>
			<?php else:?>
			<li class="list-group-item active cabinets__folder-tree__folder">
				<?php
				echo '<span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>' . h($cabinet['Cabinet']['name']);
				?>
			</li>
			<?php endif ?>
			<?php

			$this->CabinetsFolderTree->render($folders, $currentTreeId);

			?>
		</ul>
	</div>


	<div class="col-md-9 inline">
		<table class="table" ng-controller="CabinetFile.index" ng-init="init(<?php echo $currentTreeId ?>)">
			<thead>
			<tr>
				<th>名前</th>
				<th class="cabinets__index__size hidden-sm hidden-xs"><?php echo __d('cabinets', 'サイズ') ?></th>
				<th class="cabinets__index__modified" colspan="2"><?php echo __d('cabinets', '最終更新'); ?></th>

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
				<td colspan="2" style="text-align: right">
					<?php
					echo $this->Html->link(
						__d('cabinets', '圧縮ダウンロード'),
						$this->NetCommonsHtml->url(['action' => 'download_folder', 'key' => $currentFolder['CabinetFile']['key']]), ['class' => 'btn btn-xs btn-default']);
					?>
				</td>
			</tr>
			<?php endif ?>

			<?php foreach ($cabinetFiles as $cabinetFile): ?>
				<tr ng-hide="moved['<?php echo $cabinetFile['CabinetFile']['key']?>']" class="cabinet-file">
				<?php if ($cabinetFile['CabinetFile']['is_folder']) :?>
					<td>

							<?php
							$icon = '<span class="glyphicon glyphicon-folder-close cabinets__file-list-icon" aria-hidden="true"></span>';
							echo $icon;
							echo $this->NetCommonsHtml->link(h($cabinetFile['CabinetFile']['filename']), ['key' => $cabinetFile['CabinetFile']['key']], ['escape' => false]);
							?>
						<div class="cabinets__index__description text-muted small"
							 data-content="<?php echo nl2br(h($cabinetFile['CabinetFile']['description']));?>"
							 data-toggle="popover"
							 data-placement="bottom"
						>
							<?php echo (h($cabinetFile['CabinetFile']['description']));?>
						</div>


					</td>
					<td class="cabinets__index__size  hidden-sm hidden-xs"><?php echo $this->Number->toReadableSize($cabinetFile['CabinetFile']['size']) ?></td>
					<td><?php echo $this->Date->dateFormat($cabinetFile['CabinetFile']['modified']) ?> <?php echo $cabinetFile['TrackableUpdater']['username'] ?></td>

					<td>
						<?php
							// link folder_detail
							$detailUrl = $this->NetCommonsHtml->url(['action' => 'folder_detail', 'key' => $cabinetFile['CabinetFile']['key']]);
							//$detailUrl = NetCommonsUrl::actionUrl(['action' => 'folder_detail', 'key' => $cabinetFile['CabinetFile']['key']]);
						?>

						<div class="dropdown">
							<button class="btn btn-default dropdown-toggle" type="button" id="cabinets__file-<?php echo $cabinetFile['CabinetFile']['id']?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<span class="glyphicon glyphicon-option-vertical aria-hidden="true"></span>
								<!--<span class="caret"></span>-->
							</button>
							<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="cabinets__file-<?php echo $cabinetFile['CabinetFile']['id']?>">
								<li>
									<?php echo $this->NetCommonsHtml->link(__d('cabinets', '詳細'), ['controller' => 'cabinet_files', 'action' => 'folder_detail', 'key' => $cabinetFile['CabinetFile']['key']]);?>
								</li>
								<li><a href="#" ng-click="moveFile('<?php echo $cabinetFile['CabinetFile']['key']?>')"><?php echo __d('cabinets', '移動'); ?></a></li>
								<li>
									<?php echo $this->NetCommonsHtml->link(__d('cabinets', '編集'), ['controller' => 'cabinet_files_edit', 'action' => 'edit_folder', 'key' => $cabinetFile['CabinetFile']['key']]);?>
								</li>
								<li>
									<?php
									echo $this->Html->link(
										__d('cabinets', '圧縮ダウンロード'),
										$this->NetCommonsHtml->url(['action' => 'download_folder', 'key' => $cabinetFile['CabinetFile']['key']]))
									?>
								</li>
							</ul>
						</div>

					</td>
				<?php else:?>
					<?php // File ?>
						<td>
								<?php
								// TODO ビヘイビアに移動？
								//$thumbPath = WWW_ROOT . $cabinetFile['UploadFile']['file']['path'] . $cabinetFile['UploadFile']['file']['id'] . DS . 'thumb_' . $cabinetFile['UploadFile']['file']['real_file_name'];
								//if(file_exists($thumbPath)){
								//	$url = $this->NetCommonsHtml->url([
								//		'action' => 'thumb',
								//		'key' => $cabinetFile['CabinetFile']['key'],
								//	]);
								//	$icon = $this->Html->image($url, ['class' => 'cabinets__thumb']);
								//}else{
								//	$icon = '<span class="glyphicon glyphicon-file text-primary cabinets__file-list-icon" aria-hidden="true"></span>';
								//}
								$icon = '<span class="glyphicon glyphicon-file text-primary cabinets__file-list-icon" aria-hidden="true"></span>';
								echo $icon;
								echo $this->NetCommonsHtml->link(h($cabinetFile['CabinetFile']['filename']), ['action' => 'download', 'key' => $cabinetFile['CabinetFile']['key']], ['escape' => false]);
								?>
							<?php echo $this->Workflow->label($cabinetFile['CabinetFile']['status']); ?>
						<span class="badge ">
						<?php echo $cabinetFile['UploadFile']['file']['download_count'] ?>
						</span>
						<div class="cabinets__index__description text-muted small"
							 data-content="<?php echo nl2br(h($cabinetFile['CabinetFile']['description']));?>"
							 data-toggle="popover"
							 data-placement="bottom"
						>
							<?php echo (h($cabinetFile['CabinetFile']['description']));?>
						</div>


							</td>
						<td class="hidden-sm hidden-xs"><?php echo $this->Number->toReadableSize($cabinetFile['UploadFile']['file']['size']) ?></td>
						<td><?php echo $this->Date->dateFormat($cabinetFile['CabinetFile']['modified']) ?> <?php echo $cabinetFile['TrackableUpdater']['username'] ?></td>

						<td>
							<div class="dropdown">
								<button class="btn btn-default dropdown-toggle" type="button" id="cabinets__file-<?php echo $cabinetFile['CabinetFile']['id']?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									<span class="glyphicon glyphicon-option-vertical aria-hidden="true"></span>
									<!--<span class="caret"></span>-->
								</button>
								<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="cabinets__file-<?php echo $cabinetFile['CabinetFile']['id']?>">
									<li>
										<?php echo $this->NetCommonsHtml->link(__d('cabinets', '詳細'), ['controller' => 'cabinet_files', 'action' => 'view', 'key' => $cabinetFile['CabinetFile']['key']]);?>
									</li>
									<li><a href="#" ng-click="moveFile('<?php echo $cabinetFile['CabinetFile']['key']?>')"><?php echo __d('cabinets', '移動'); ?></a></li>
									<li>
										<?php echo $this->NetCommonsHtml->link(__d('cabinets', '編集'), ['controller' => 'cabinet_files_edit', 'action' => 'edit', 'key' => $cabinetFile['CabinetFile']['key']]);?>
									</li>
									<?php
									$unzipDisabled = '';
									if($cabinetFile['UploadFile']['file']['extension'] !== 'zip'){
										$unzipDisabled = 'class="disabled"';
									}
									?>
									<li <?php echo $unzipDisabled ?>><a href="#"><?php echo __d('cabinets', '解凍'); // TODO zip only ?></a></li>
								</ul>
							</div>

						</td>
				<?php endif ?>
				</tr>
			<?php endforeach ?>

			</tbody>
		</table>

	</div>
</div>

</div>