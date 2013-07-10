<li><a href="<?php echo base_url('character/'); ?>">Character</a></li>
<li><a href="<?php echo base_url('world/'); ?>">Map</a></li>
<li><a href="<?php echo base_url('guild/'); ?>">Guild</a></li>
<? if(isset($this->cid) && $this->core->countNewMessages($this->cid) > 0) {
	echo '<li><a href="'.base_url('messages/').'">Mailbox <span class="badge badge-important">'.$this->core->countNewMessages($this->cid).'</span></a></li>';
}else{
	echo '<li><a href="'.base_url('messages/').'">Mailbox</a></li>';
} ?>
<li><a href="<?php echo base_url('ladder/'); ?>">Ladder</a></li>