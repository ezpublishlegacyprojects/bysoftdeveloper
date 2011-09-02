<?php

class GetUserGroups
{
	
    protected $Operators;
    
    
    public function __construct()
    {
        $this->Operators = array( 'getusergroups');
    }
    
    public function &operatorList()
    {
        return $this->Operators;        
    }
    
    public function namedParameterPerOperator()
    {
        return true;
    }
    
    public function namedParameterList()
    {
        return array(
            'getusergroups' => array(
                
            )
        );
        
    }
	
	public function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, 
                            &$operatorValue, $namedParameters)
    {
    	switch ($operatorName) {
    		case 'getusergroups' :
    			$user = $operatorValue;
    			if($user instanceof eZUser)
    			{
    				$group = $user->groups(true);
    				$operatorValue = $group;
    		//		$group = eZContentObject::fetchIDArray($groupIDs);
    				
    			}
			   
    	}	
    }

    
}
