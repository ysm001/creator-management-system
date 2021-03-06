<div class="row-fluid">
	<div class="span9">
		<?php echo $this->BootstrapForm->create('User', array('url' => array('plugin' => 'usermgmt', 'controller' => 'users', 'action' => 'addUser'), 'class' => 'form-horizontal'));?>
			<fieldset>
				<legend><?php echo __('ユーザーの新規作成'); ?></legend>
				<?php
				echo $this->BootstrapForm->input('username', array(
				        'label' => '名前',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('email', array(
				        'label' => 'Email',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('password', array(
				        'label' => 'パスワード',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('cpassword', array(
				        'label' => 'パスワード(確認)',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				echo $this->BootstrapForm->input('user_group_id', array(
				        'label' => 'グループ',
					'required' => 'required',
					'helpInline' => '<span class="label label-important">' . __('Required') . '</span>&nbsp;')
				);
				?>
				<?php echo $this->BootstrapForm->submit(__('ユーザーの新規作成'));?>
			</fieldset>
		<?php echo $this->BootstrapForm->end();?>
	</div>
</div>
