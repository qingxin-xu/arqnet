<?php

/*
	Just stubs for login and register
*/

function doLogin()
{
    $redirect = 'test.php';
    $error_msg = 'Invalid login';
    
	if ( key_exists('username',$_POST) && key_exists('password',$_POST) )
	{
		if (strcmp($_POST['password'],'password')==0) return array('success'=>1,redirect=>$redirect);
		else return array('success'=>0,'error'=>$error_msg);
	} else
	{
		return array('response_text'=>-3,'response_message'=>"username and/or password not specified"); 
	}
}

function doRegister()
{
	$redirect = 'test.php';
	return array('success'=>1,'redirect'=>$redirect);
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
                echo json_encode(array('success'=>0,'error'=>'Invalid action specified'));
        }
} else
{
        echo json_encode(array('success'=>0,'error'=>'No action specified'));
}

exit;


?>
