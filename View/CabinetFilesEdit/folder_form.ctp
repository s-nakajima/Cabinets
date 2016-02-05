<div class="cabinetFiles form">
	<article>
		<h1>CABINET</h1>
		<div class="panel panel-default">

			<?php echo $this->NetCommonsForm->create(
				'CabinetFile',
				array(
					'inputDefaults' => array(
						'div' => 'form-group',
						'class' => 'form-control',
						'error' => false,
					),
					'div' => 'form-control',
					'novalidate' => true,
					'type' => 'file',
				)
			);
			?>
			<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>
			<?php echo $this->NetCommonsForm->input('is_folder', array('type' => 'hidden')); ?>

			<div class="panel-body">

				<fieldset>

					<?php
					echo $this->NetCommonsForm->input(
						'filename',
						array(
							'label' => __d('cabinets', 'フォルダ名'),
							'required' => 'required',
						)
					);
					?>
					<div class="form-group">
						<?php echo $this->NetCommonsForm->label('parent_id', __d('cabinets', 'パス')); ?>
						<div>
							<?php echo $this->element('file_path'); ?>
							<?php //TODO フォルダ移動画面ポップアップへ ?>
						</div>
					</div>
					<?php
					echo $this->NetCommonsForm->input(
						'description',
						array(
							'label' => __d('cabinets', '説明'),
							'required' => 'required',
						)
					);
					?>

				</fieldset>

			</div>

			<div class="panel-footer text-center" >
				<?php echo $this->Button->cancelAndSave(__d('net_commons', 'Cancel'), __d('net_commons', 'OK'), $this->NetCommonsHtml->url(['controller' => 'cabinet_files', 'action' => 'folder_detail', 'key' => $this->request->data['CabinetFile']['key']])) ?>
			</div>

			<?php echo $this->NetCommonsForm->end() ?>
			<?php if ($isEdit && $isDeletable) : ?>
				<div  class="panel-footer" style="text-align: right;">
					<?php echo $this->NetCommonsForm->create('CabinetFile',
						array(
							'type' => 'delete',
							'url' => $this->NetCommonsHtml->url(
								array('controller' => 'cabinet_files_edit', 'action' => 'delete', 'frame_id' => Current::read('Frame.id')))
						)
					) ?>
					<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>

					<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Delete'); ?>">
						<button class="btn btn-danger" onClick="return confirm('<?php echo __d('net_commons', 'Deleting the %s. Are you sure to proceed?', __d('cabinets', 'CabinetFile')) ?>')"><span class="glyphicon glyphicon-trash"> </span></button>


					</span>
					<?php echo $this->NetCommonsForm->end() ?>
				</div>
			<?php endif ?>

		</div>
	</article>

</div>

