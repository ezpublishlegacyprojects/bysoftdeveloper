<?php 
class SwitchUser
{
	private $original;
	private $target;
	
	public function __construct($original, $target)
	{
		$this->original = $original;
		$this->target = $target;
	}
	
	public function change()
	{
		self::switchTo($this->target);
	}
	
	public function changeBack()
	{
		self::switchTo($this->original);
	}
	
	public static function switchTo($userobject)
	{
		if($userobject instanceof eZUser)
		{
			eZUser::logoutCurrent();
			$userobject->loginCurrent();
		}
	}
	
}