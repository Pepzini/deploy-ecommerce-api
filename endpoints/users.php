<?php
// list users
$app->get('/users', function() use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `user`";
    $query .= " ORDER BY user_name ";
    // run query
    $users = $db->getRecordset($query);
    // return list of users
    if($users) {
    	$response['users'] = $users;
    	$response['status'] = "success";
        $response["message"] =  count($users) . " user(s) found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "No user found!";
        echoResponse(201, $response);
    }
});
// single user
$app->get('/users/:id', function($id) use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `user` WHERE user_id = '$id' ";
    // run query
    $user = $db->getOneRecord($query);
    // get orders if any
    //$user_requests = $db->getRecordset("SELECT * FROM `request` LEFT JOIN user_location ON req_location_id=cl_id WHERE cl_user_id = '$id' ORDER BY req_time_initiated ");
    // return user
    if($user) {
        $response['user'] = $user;
        //$response['user_requests'] = $user_requests;
    	$response['status'] = "success";
        $response["message"] =  " user found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "user not found!";
        echoResponse(201, $response);
    }
});
//logged in user
$app->get('/users/:id', function() use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `user` WHERE user_id = '$_SESSION[user_id]' ";
    // run query
    $user = $db->getOneRecord($query);
    // get orders if any
    //$user_requests = $db->getRecordset("SELECT * FROM `request` LEFT JOIN user_location ON req_location_id=cl_id WHERE cl_user_id = '$id' ORDER BY req_time_initiated ");
    // return user
    if($user) {
        $response['user'] = $user;
        //$response['user_requests'] = $user_requests;
    	$response['status'] = "success";
        $response["message"] =  " user found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "user not found!";
        echoResponse(201, $response);
    }
});
// create user
$app->post('/users', function() use ($app) {
    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['user_name', 'user_email', 'user_phone','user_address'],$r->user);
    // instantiate classes
    $db = new DbHandler();
    $jh = new JWTHandler();
    
    // user name
    $user_email = $db->purify($r->user->user_email);
    $user_phone = $db->purify($r->user->user_phone);
    $user_address = $db->purify($r->user->user_address);
    
    $user_signup_verified = 1;
    // check if user_email is already used
    $user_check  = $db->getOneRecord("SELECT user_id FROM `user` WHERE user_email='$user_email' ");
    if($user_check) {
        // user already exists
        $response['status'] = "error";
        $response["message"] = "user with same email already Exists!";
        echoResponse(201, $response);
    } else {
        //get fields for insert
        $user_name = $db->purify($r->user->user_name);
        $user_address = $db->purify($r->user->user_address);
        $user_phone = $db->purify($r->user->user_phone);
     
        //create new user
        $user_id = $db->insertToTable(
            [ $user_name, $user_email, $user_address,$user_phone], /*values - array*/
            [ 'user_name','user_email','user_address','user_phone'], /*column names - array*/
            "user" /*table name - string*/
        );
        // user created successfully
        if($user_id) {
            // log admin action
            $response['user_id'] = $user_id;
            $response['status'] = "success";
            $response["message"] = "user created successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to create the user!";
            echoResponse(201, $response);
        }
    }
});
// edit user
$app->put('/users', function() use ($app) {
    // only super admins allowed
    // authAdmin('super');
    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['user_id','user_name', 'user_email',  'user_phone','user_address'],$r->user);
    // instantiate classes
    $db = new DbHandler();
    
    // user id
    $user_id = $db->purify($r->user->user_id);
    $user_name = $db->purify($r->user->user_name);
    $user_email = $db->purify($r->user->user_email);
    $user_address = $db->purify($r->user->user_address);
    $user_phone = $db->purify($r->user->user_phone);
  
     // check if user_name is already used
     $user_check  = $db->getOneRecord("SELECT user_id FROM `user` WHERE user_name = '$user_name' AND user_email='$user_email' AND user_id <> '$user_id' ");
     if($user_check) {
         // user already exists
         $response['status'] = "error";
         $response["message"] = "user with same email already Exists!";
         echoResponse(201, $response);
     } else {
        //get fields for insert
        $user_address = $db->purify($r->user->user_address);
        $user_phone = $db->purify($r->user->user_phone);
        //update user
        $update_user = $db->updateInTable(
        	"user", /*table*/
            [ 'user_name'=>$user_name, 'user_email' => $user_email, 'user_address' => $user_address,'user_phone' => $user_phone], /*columns*/
        	[ 'user_id'=>$user_id ] /*where clause*/
        );
        
        // user updated successfully
        if($update_user >= 0) {
            // log admin action
            $response['status'] = "success";
            $response["message"] = "user updated successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to update the user!";
            echoResponse(201, $response);
        }
    }
});
