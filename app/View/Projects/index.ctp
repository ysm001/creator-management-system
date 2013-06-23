<div class="row-fluid">
	<div class="span9">
		<h2><?php echo __('List %s', __('Projects'));?></h2>

		<p>
			<?php echo $this->BootstrapPaginator->counter(array('format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')));?>
		</p>

		<table class="table table-striped">
			<tr>
				<th><?php echo $this->BootstrapPaginator->sort('title');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('project_status_id');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('deadline');?></th>
				<th><?php echo $this->BootstrapPaginator->sort('remark');?></th>
				<th class="actions"><?php echo __('Actions');?></th>
			</tr>
		<?php foreach ($projects as $project): ?>
			<tr>
				<td>
					<?php echo $this->Html->link($project['Project']['title'], array('controller' => 'projects', 'action' => 'view', $project['Project']['id'])); ?>
				</td>
				<td>
					<?php echo h($project['ProjectStatus']['name']); ?>
				</td>
				<td><?php echo h($project['Project']['deadline']); ?>&nbsp;</td>
				<td><?php echo h($project['Project']['remark']); ?>&nbsp;</td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('action' => 'view', $project['Project']['id'])); ?>
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $project['Project']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $project['Project']['id']), null, __('Are you sure you want to delete # %s?', $project['Project']['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->BootstrapPaginator->pagination(); ?>
	</div>
	<div class="span3">
		<div class="well" style="padding: 8px 0; margin-top:8px;">
		<ul class="nav nav-list">
			<li class="nav-header"><?php echo __('Actions'); ?></li>
			<li><?php echo $this->Html->link(__('New %s', __('Project')), array('action' => 'add')); ?></li>
		</ul>
		</div>
	</div>
</div>