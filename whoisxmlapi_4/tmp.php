<?php
class Test{
	static $a = array('b'=>array(
		array('c'=>'d'),
		array('e'=> 'f')
	));
	public static function init(){
		//print_r(self::$a);
		self::$a['b'][0]['c']='x';
		print_r(self::$a);
	}
}

Test::init();
?>