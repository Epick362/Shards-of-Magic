<li class="<?=($this->uri->segment(1)=='character')? 'active' : ''; ?>"><a href="<?php echo base_url('character/'); ?>">Character</a></li>
<li class="<?=($this->uri->segment(1)=='world')? 'active' : ''; ?>"><a href="<?php echo base_url('world/'); ?>">Map</a></li>
<li class="<?=($this->uri->segment(1)=='guild')? 'active' : ''; ?>"><a href="<?php echo base_url('guild/'); ?>">Guild</a></li>
<?
if($this->uri->segment(1)=='messages') { $class = 'active'; }else{ $class = ''; }
if(isset($this->cid) && $this->core->countNewMessages($this->cid) > 0) {
	echo '<li class="'.$class.'"><a href="'.base_url('messages/').'">Mailbox <span class="badge badge-important">'.$this->core->countNewMessages($this->cid).'</span></a></li>';
}else{
	echo '<li class="'.$class.'"><a href="'.base_url('messages/').'">Mailbox</a></li>';
} ?>
<li class="<?=($this->uri->segment(1)=='ladder')? 'active' : ''; ?>"><a href="<?php echo base_url('ladder/'); ?>">Ladder</a></li>