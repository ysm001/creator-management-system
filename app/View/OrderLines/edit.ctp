<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('OrderLine', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('発注イラストの編集'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('project_id', array(
				        'label' => '所属プロジェクト',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('title', array(
				        'label' => 'タイトル',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				/*
				echo $this->BootstrapForm->input('order_status_id', array(
				        'label' => '状態',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				 */
				echo $this->BootstrapForm->input('deadline', array(
					'label' => '締め切り',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;',
					'id'=>'datepicker',
					'type'=>'text'
				    )
				);
				echo $this->BootstrapForm->input('User', array(
				        'label' => '担当イラストレーター'
				));
				?>
				<?php echo $this->BootstrapForm->submit(__('変更を適用'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>
