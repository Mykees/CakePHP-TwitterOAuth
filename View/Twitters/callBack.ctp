<div class="posts index">
	<h1>Hello <?php echo isset($User['username']) ? $User['username'] : $User['Social']['username']; ?></h1><small>Veuillez entrer un email pour finaliser votre inscription</small>
	<?php $id = isset($User['twitter_id']) ? $User['twitter_id'] : $User['Social']['twitter_id']; ?>

	<?php echo $this->Form->create('callBack'); ?>
	<?php echo $this->Form->input('email',array('label'=>'Email')); ?>
	<?php echo $this->Form->input('twitter_id',array('type'=>'hidden','value'=>$id)); ?>
	<?php echo $this->Form->end('Envoyer'); ?>
</div>