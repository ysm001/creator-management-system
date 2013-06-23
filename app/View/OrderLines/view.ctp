<div class="row-fluid">
	<div class="span9">
          <table class="table table-striped">
                <tr>
                <th>Project</th>
                <th>Status</th>
                <th>DeadLine</th>
                </tr>
		<h2><?php echo h($orderLine['OrderLine']['title']); ?></h2>
			<td>
				<?php echo $this->Html->link($orderLine['Project']['title'], array('controller' => 'projects', 'action' => 'view', $orderLine['Project']['id'])); ?>
				&nbsp;
			</td>
			<td>
				<?php echo $this->Html->link($orderLine['OrderStatus']['name'], array('controller' => 'order_statuses', 'action' => 'view', $orderLine['OrderStatus']['id'])); ?>
				&nbsp;
			</td>
			<td>
				<?php echo h($orderLine['OrderLine']['deadline']); ?>
				&nbsp;
			</td>
          </table>
	</div>
</div>
<div class="row-fluid">
	<div class="span9">
		<h4><?php echo __('Assigned %s', __('Illustrators')); ?></h4>
	<?php if (!empty($orderLine['User'])):?>
		<table class="table table-striped">
			<tr>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Email'); ?></th>
			</tr>
		<?php foreach ($orderLine['User'] as $user): ?>
			<tr>
				<td><?php echo $user['name'];?></td>
				<td><?php echo $user['email'];?></td>
			</tr>
		<?php endforeach; ?>
		</table>
        <?php else: ?>
        <?php echo "No one has been assigned."; ?>
	<?php endif; ?>

	</div>
</div>
<hr>

<div class="row-fluid">
<div class="tabbable">
 <ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">Main</a></li>
<?php
$photo_num = count($orderLine['Attachment']);
for ($i = 0; $i < $photo_num; $i++) { echo <<< _EOT_
	<li><a href="#tab$i" data-toggle="tab">Sub-$i</a></li>
_EOT_;
}
?>
 </ul>
   <div id="photo-tab-content" class="tab-content">
    <div class="tab-pane active" id="home">
   </div>
<?php
// tab contents
for ($i = 0; $i < $photo_num; $i++) { 
    $attachment = $attachments[$i];
    $attachmentId = $attachment['Attachment']['id'];
    $dir = $this->webroot.APP_DIR."/".WEBROOT_DIR."/files/attachment/photo/".$attachment['Attachment']['dir'];
    $org_image = $dir."/".$attachment['Attachment']['photo'];
    $thumb_image = $dir."/thumb560_".$attachment['Attachment']['photo'];
    $comments = $orderLine['Comment'];
    $comment_num = count($comments);
    $orderLineId = $orderLine['OrderLine']['id'];

    echo "<div class=\"tab-pane\" id=\"tab$i\">";
    echo "  <ul class=\"thumbnails\">";

    echo "    <li class=\"span6\">";
    echo "      <a href=\"$org_image\" class=\"thumbnail\">";
    echo "        <img data-src=\"holder.js/560x420\" src=\"$org_image\" alt=\"\">";
    echo "      </a>";
    echo "    </li>";

    echo "    <li class =\"span6\">";
    echo      $this->BootstrapForm->create('OrderLine', array('controller' => 'OrderLine', 'action' => 'update_status'."/".$orderLineId, 'class' => 'form-horizontal'));
    echo "    <fieldset>";
    echo "      <table class=\"table table-striped\">";
    echo "        <td>";
    echo            $this->BootstrapForm->input('order_status_id', array('options' => $orderStatuses, 'div' => false));
    echo            $this->BootstrapForm->submit(__('Update'), array('div' => false));
    echo "        </td>";
    echo "      </table>";
    echo "      </fieldset>";
    echo        $this->BootstrapForm->end();

    echo "      <table class=\"table table-striped\">";
    echo "        <tr><th>User</th><th>Comment</th><th>Date</th></tr>";
                  foreach ($comments as $comment) {
		      if ($comment['attachment_id'] == $attachmentId) {
    echo "          <tr>";
                    $name = $userNames[$comment['user_id']];
		    $content = $comment['content'];
		    $created = $comment['created'];
    echo "          <td>$name</td>";
    echo "          <td>$content</td>";
    echo "          <td>$created</td>";
    echo "          </tr>";
		      }
                  }
    echo "      </table>";
    echo "      <div>";
    echo               $this->BootstrapForm->create('Comment', array('controller' => 'Comment', 'action' => 'add', 'class' => 'form-horizontal', 'align' => 'center'));
    echo "			<fieldset>";
    echo				$this->BootstrapForm->input('content', array('label' => false, 'div' => false));
    echo				$this->BootstrapForm->submit(__('Submit Comment'), array('div' => false));
    echo "			</fieldset>";
    echo                $this->BootstrapForm->hidden('user_id', array('value'=>$authUser['id']));
    echo                $this->BootstrapForm->hidden('attachment_id', array('value'=>$attachmentId));
    echo                $this->BootstrapForm->hidden('order_line_id', array('value'=>$orderLineId));
    echo		$this->BootstrapForm->end();
    echo "      </div>";

    echo "    </li>";

    echo "  </ul>";
    echo "</div>";
}
?>
</ul>

</div>
</div>

  <table class="table table-striped">
    <td>
      <?php
      echo $this->BootstrapForm->create('OrderLine', array('controller' => 'OrderLine','action' => 'upload'."/".$orderLine['OrderLine']['id'], 'type' => 'file', ));
      echo $this->BootstrapForm->input('Attachment.0.photo', array('type' => 'file', 'label' => false, 'div' => false));
      echo $this->BootstrapForm->hidden('Attachment.0.model', array('value'=>'OrderLine'));
      echo $this->BootstrapForm->submit(__('Upload'), array('div' => false));
      echo $this->BootstrapForm->end();
      ?>
    </td>
  </table>
</div>

<hr>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('%s', __('Comments')); ?></h3>
	<?php if (!empty($orderLine['Comment'])):?>
		<table class="table table-striped">
			<tr>
				<th><?php echo __('User'); ?></th>
				<th><?php echo __('Content'); ?></th>
				<th><?php echo __('Created'); ?></th>
			</tr>
		<?php foreach ($orderLine['Comment'] as $comment): ?>
                <?php if (!isset($comment['attachment_id'])) :?>
			<tr>
				<td><?php echo $userNames[$comment['user_id']];?></td>
				<td><?php echo $comment['content'];?></td>
				<td><?php echo $comment['created'];?></td>
			</tr>
                <?php endif; ?>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>

	</div>
</div>
<?php
    echo "      <div class='row-fluid'>";
    echo               $this->BootstrapForm->create('Comment', array('controller' => 'Comment', 'action' => 'add', 'class' => 'form-horizontal'));
    echo "			<fieldset>";
    echo				$this->BootstrapForm->input('content', array('label' => false, 'div' => false));
    echo				$this->BootstrapForm->submit(__('Submit Comment'), array('div' => false));
    echo "			</fieldset>";
    echo                $this->BootstrapForm->hidden('user_id', array('value'=>$authUser['id']));
    echo                $this->BootstrapForm->hidden('order_line_id', array('value'=>$orderLineId));
    echo		$this->BootstrapForm->end();
    echo "      </div>";
?>