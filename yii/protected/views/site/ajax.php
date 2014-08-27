<?php

/*
	Just stubs for login and register
*/

function doLogin()
{
	if ( key_exists('username',$_POST) && key_exists('password',$_POST) )
	{
		if (strcmp($_POST['password'],'password')==0) return array('response_text'=>1);
		else return array('response_text'=>-2);
	} else
	{
		return array('response_text'=>-3,'response_message'=>"username and/or password not specified"); 
	}
}

function doRegister()
{
	return array('response_text'=>1);
}

/* 
 * Controller
 */
if (key_exists('action', $_POST))
{
        if (strcmp($_POST['action'], 'doLogin')==0)
        {
                echo json_encode(doLogin());
        } else if (strcmp($_POST['action'], 'doRegister')==0) 
        {
                echo json_encode(doRegister());
        } else
        {
                //Parse sort parameters
                echo json_encode(array('response_text'=>-999,'response_message'=>'Invalid action specified'));
        }
} else
{
        echo json_encode(setError(-999,'No action specified'));
}

exit;


?>
