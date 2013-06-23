<?php 
$this->Html->script('jquery-cookie/jquery.cookie', array('inline' => false));
$script = <<< EOT
$(function() {
    if($.cookie("openTag")){
	$('a[data-toggle="tab"]').parent().removeClass('active');
	$('a[href=#' + $.cookie("openTag") +']').click();
  }

  $('a[data-toggle="tab"]').on('shown', function (e) {
      var tabName = e.target.href;
      var items = tabName.split("#");
      $.cookie("openTag",items[1], { expires: 700 });
  });
});
EOT;
echo $this->html->scriptBlock($script, array('inline' => false,'safe' => true));
?>

<div class="row-fluid">
	<div class="span9">
          <table class="table table-striped">
                <tr>
                <th>所属プロジェクト</th>
                <th>状態</th>
                <th>締め切り</th>
                </tr>
		<h2><?php echo h($orderLine['OrderLine']['title']); ?></h2>
			<td>
<?php if (isAdminUser($authUser) || isClientUser($authUser)): ?>
				<?php echo $this->Html->link($orderLine['Project']['title'], array('controller' => 'projects', 'action' => 'view', $orderLine['Project']['id'])); ?>
<?php else: ?>
				<?php echo h($orderLine['Project']['title']); ?>
<?php endif; ?>
				&nbsp;
			</td>
			<td>
				<?php echo h($orderLine['OrderStatus']['name']); ?>
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
		<h4><?php echo __('担当イラストレーター'); ?></h4>
	<?php if (!empty($orderLine['User'])):?>
		<table class="table table-striped">
			<tr>
				<th><?php echo __('名前'); ?></th>
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
        <?php echo "担当イラストレーターがいません。"; ?>
	<?php endif; ?>

	</div>
</div>
<hr>

<div class="row-fluid">
<div class="tabbable">
 <ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">Main</a></li>
<?php
$orderLineId = $orderLine['OrderLine']['id'];
$photo_num = count($orderLine['Attachment']);
for ($i = 0; $i < $photo_num; $i++) { echo <<< _EOT_
	<li><a href="#tab$i" data-toggle="tab">Image-$i</a></li>
_EOT_;
}
?>
 </ul>
   <div id="photo-tab-content" class="tab-content">
    <div class="tab-pane active" id="home">
<?php if (isset($orderLine['OrderLine']['main_attachment_id'])): ?>
<?php 
      $main_attachment_id = $orderLine['OrderLine']['main_attachment_id'];
      $main_attachment = null; 
      $status = $orderStatuses[$orderLine['OrderLine']['order_status_id']];
      foreach ($attachments as $attachment) {
	  if ($attachment['Attachment']['id'] == $main_attachment_id) {
	      $main_attachment = $attachment;
	      break;
	  }
      }
      $modified = $main_attachment['Attachment']['modified'];
      $dir = $this->webroot.APP_DIR."/".WEBROOT_DIR."/files/attachment/photo/".$main_attachment['Attachment']['dir'];
      $main_image = $dir."/".$main_attachment['Attachment']['photo'];
?>
       <ul class="thumbnails">
         <li class="span6">
	   <?php echo "<a href=\"$main_image\" class=\"thumbnail\">" ?>
	     <?php echo "<img data-src=\"holder.js/560x420\" src=\"$main_image\" alt=\"\">" ?>
           </a>
         </li>
         <li class="span6">
           <table class="table table-striped">
             <tr>
               <td>状態</td>
               <?php echo "<td>$status</td>"; ?>
             </tr>
	     <tr> 
               <td>更新日時</td>
               <?php echo "<td>$modified</td>"; ?>
             </tr>
           </table>
         </li>
       </ul>
<?php else: ?>
     <p>承認された画像がありません。</p>
<?php endif; ?>
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
    echo            $this->BootstrapForm->input('order_status_id', array('options' => $orderStatuses, 'div' => false, 'label' => false));
    echo            $this->BootstrapForm->hidden('main_attachment_id', array('value'=>$attachmentId));
    echo            $this->BootstrapForm->submit(__('左記の状態でイラストを承認'), array('div' => false));
    echo "        </td>";
    echo "      </table>";
    echo "      </fieldset>";
    echo        $this->BootstrapForm->end();
    echo      $this->BootstrapForm->create('OrderLine', array('controller' => 'OrderLine', 'action' => 'delete_attachment'."/".$orderLineId, 'class' => 'form-horizontal'));
    echo "    <fieldset>";
    echo "      <table class=\"table table-striped\">";
    echo "        <td>";
    if (!isset($main_attachment_id) || $attachmentId != $main_attachment_id) {
    echo            $this->BootstrapForm->hidden('attachment_id', array('value'=>$attachmentId));
    echo            $this->BootstrapForm->submit(__('イラストを削除'), array('div' => false, 'class' => 'btn btn-danger'));
    }
    else {
    echo            $this->BootstrapForm->hidden('attachment_id', array('value'=>$attachmentId));
    echo            "<div class=\"btn disabled\">イラストを削除</div>";
    echo            "<span class=\"help-inline\">承認済みのイラストは削除できません。</span>";
    }
    echo "        </td>";
    echo "      </table>";
    echo "      </fieldset>";
    echo "<hr>";
    echo        $this->BootstrapForm->end();

    echo "      <table class=\"table table-striped\">";
    echo "        <tr><th nowrap>ユーザー</th><th>コメント</th><th>発言日</th></tr>";
                  foreach ($comments as $comment) {
		      if ($comment['attachment_id'] == $attachmentId) {
    echo "          <tr>";
                    $name = $userNames[$comment['user_id']];
		    $content = $comment['content'];
		    $created = $comment['created'];
    echo "          <td nowrap>$name</td>";
    echo "          <td>$content</td>";
    echo "          <td nowrap>$created</td>";
    echo "          </tr>";
		      }
                  }
    echo "      </table>";
    echo "      <div>";
    echo               $this->BootstrapForm->create('Comment', array('controller' => 'Comment', 'action' => 'add', 'class' => 'form-horizontal', 'align' => 'center'));
    echo "			<fieldset>";
    echo				$this->BootstrapForm->input('content', array('label' => false, 'div' => false, 'required' => 'required', 'class' => 'input-xxlarge'));
    echo				$this->BootstrapForm->submit(__('コメントを送信'), array('div' => false));
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
      echo $this->BootstrapForm->input('Attachment.0.photo', array('type' => 'file', 'label' => false, 'div' => false, 'required' => 'required'));
      echo $this->BootstrapForm->hidden('Attachment.0.model', array('value'=>'OrderLine'));
      echo $this->BootstrapForm->submit(__('アップロード'), array('div' => false));
      echo $this->BootstrapForm->end();
      ?>
    </td>
  </table>
</div>

<hr>
<div class="row-fluid">
	<div class="span9">
		<h3><?php echo __('%s', __('コメント')); ?></h3>
	<?php if (!empty($orderLine['Comment'])):?>
		<table class="table table-striped">
			<tr>
				<th nowrap><?php echo __('ユーザー'); ?></th>
				<th><?php echo __('コメント'); ?></th>
				<th><?php echo __('発言日'); ?></th>
			</tr>
		<?php foreach ($orderLine['Comment'] as $comment): ?>
                <?php if (!isset($comment['attachment_id'])) :?>
			<tr>
				<td nowrap><?php echo $userNames[$comment['user_id']];?></td>
				<td><?php echo $comment['content'];?></td>
				<td nowrap><?php echo $comment['created'];?></td>
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
    echo				$this->BootstrapForm->input('content', array('label' => false, 'div' => false, 'required' => 'required', 'class' => 'input-xxlarge'));
    echo				$this->BootstrapForm->submit(__('コメントを送信'), array('div' => false));
    echo "			</fieldset>";
    echo                $this->BootstrapForm->hidden('user_id', array('value'=>$authUser['id']));
    echo                $this->BootstrapForm->hidden('order_line_id', array('value'=>$orderLineId));
    echo		$this->BootstrapForm->end();
    echo "      </div>";
?>
