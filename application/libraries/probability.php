<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * NPC class
 *
 */
class Probability
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	private $events = array();
	
	public function addEvent( $value, $probability ) {
			$this->events[$value] = $probability;
	}
	
	public function removeEvent( $value ) {
		if( array_key_exists($value, $this->events) )
			unset( $this->events[$value] );
	}
	
	public function randomEvent( $num = 1 ) {
		$events = array_reverse( $this->buildTable(), TRUE );
		$sum = array_sum($this->events);
		if( $num == 1 ) {
			$rand = mt_rand( 1, $sum );
			foreach( $events as $key => $event )
				if( $event <= $rand ) return $key;
		} else {
			$return = array();
			for( $i = 0; $i < $num; $i++ ) {
				$rand = mt_rand( 1, $sum );
				foreach( $events as $key => $event )
					if( $event <= $rand ) {
						$return[] = $key; break;
					}
			}
			return $return;
		}
	}
	
	private function buildTable() {
		$events = $this->events;
		$total = 0;
		foreach( $events as &$event ) {
			$prev = $event;
			$event = $total;
			$total += $prev;
		}
		return $events;
	}
}
?>