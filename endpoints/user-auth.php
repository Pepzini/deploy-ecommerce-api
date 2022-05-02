<?php

//new user registration
$app->post('/auth/user/register', function () use ($app) {
    // require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    // var_dump($r);
    verifyRequiredParams(['user_name', 'user_email', 'user_phone', 'user_password'], $r->user);
    // response array
    $response = [];
    // db handler class
    $db = new DbHandler();
    $user = $r->user;
    // extract fields
    $user_name = $db->purify($r->user->user_name);
    $user_email = $db->purify($r->user->user_email);
    $user_phone = $db->purify($r->user->user_phone);
    $user_password = $db->purify($r->user->user_password);
    // check if user already exists
    $user_exists = $db->getOneRecord("SELECT * FROM user WHERE user_email='$user_email' OR user_phone='$user_phone'  ");
    if (!$user_exists) {
        // user does not exist
        // hash password ---
        // $user_password = hash($user_password); //somehow
        // generate signup token
        // $pg = new PasswordGenerator();
       // $user_token = $pg->randomNumericPassword(6, false); -->
        // create user
        $user_id = $db->insertToTable(
            [$user_name, $user_email, $user_phone, $user_password, date('Y-m-d H:i:s')], //values
            ['user_name', 'user_email', 'user_phone', 'user_password', 'user_time_reg'], //columns names
            'user'
        ); 
        // var_dump($user_id); die;
        // user successfully created?
        // useromer successfully created?
        if ($user_id) {
            // return success
            $response['status'] = "success";
            $response['message'] = 'Logged in successfully. Taking you in...';
            $response['user'] = $user;
            echoResponse(200, $response);
        } else {
            // mysql error - useromer creation failed
            $response['status'] = "error";
            $response['message'] = 'Signup failed! Something went wrong while to create your account';
            echoResponse(201, $response);
        }
    } else {
        // useromer email/phone already exists
        $response['status'] = "error";
        $response['message'] = "Sorry, another useromer already registered with this email or phone!";
        echoResponse(201, $response);
    }
});

//allow users to login
$app->post('/auth/user/login', function () use ($app) {
    // require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    // var_dump($r);
    verifyRequiredParams(array('username', 'password'), $r);
    $response = array();
    $db = new DbHandler();
    $password = $db->purify($r->password);
    $email = $db->purify($r->username);
    $user = $db->getOneRecord("SELECT * FROM user WHERE user_email='$email'");
    if ($user) {
        // check if password is correct
        //if(passwordHash::check_password($user['user_password'],$password)){
        if ($user['user_password'] == $password) {
            // create JSON token
            $jh = new JWTHandler();
            $user['user_password'] = '';
            if ($token = $jh->createUserToken($user)) {
                $response['status'] = "success";
                $response['message'] = 'Logged in successfully. Taking you in...';
                $response['user'] = $user;
                $response['user']['token'] = $token;
                echoResponse(200, $response);
            } else {
                $response['status'] = "error";
                $response['message'] = 'Login failed! Could not complete authentication';
                echoResponse(201, $response);
            }
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login failed! Incorrect Email or Password';
            echoResponse(201, $response);
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "Sorry, we didn't find anybody matching your email!";
        echoResponse(201, $response);
    }
});
//allow users to log out
$app->delete('/auth/user/logout', function () {
    $response['status'] = "success";
    echoResponse(200, $response);
});

//allow users to log reset password
$app->post('/auth/user/resetpassword', function () use ($app) {
    // require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    // var_dump($r);
    verifyRequiredParams(['email'], $r);
    // response array
    $response = [];
    // db handler class
    $db = new DbHandler();
    // extract fields
    $email = $db->purify($r->email);
    // check if user already exists
    $user = $db->getOneRecord("SELECT * FROM user WHERE user_email='$email' ");
    if ($user) {
        // user found
        $pg = new PasswordGenerator();
        $password = $pg->randomAlphaNumericPassword(10);
        // hash password ---
        // $user_password = hash($user_password); //somehow
        // update password in db
        $update_user = $db->updateInTable(
            "user", /*table*/
            ['user_password' => $password], /*columns*/
            ['user_id' => $user['user_id']] /*where clause*/
        );
        // password updated?
        if ($update_user > 0) {
            // password updated successfully
            // send password to user
            $sm = new mySwiftMailer();
            $SHORTNAME = SHORTNAME;
            $subject = "Password Reset on {$SHORTNAME}";
            $body = "<p>Hello {$user['user_name']},</p>
            <p>You requested a password reset on {$SHORTNAME}. Your new password is:</p>
            <p><strong>{$password}</strong></p>
            <p>Thank you for using {$SHORTNAME}.</p>
            <p>NOTE: please DO NOT REPLY to this email.</p>
            <p><br><strong>{$SHORTNAME} App</strong></p>";
            $sm->sendmail(FROM_EMAIL, SHORTNAME, [$user['user_email']], $subject, $body);
            // return success
            $response['status'] = "success";
            $response['message'] = 'Password reset successfully. Your new password has been sent to your email address';
            echoResponse(200, $response);
        } else {
            // mysql error - user creation failed
            $response['status'] = "error";
            $response['message'] = 'Password reset failed! Something went wrong while to reset your password';
            echoResponse(201, $response);
        }
    } else {
        // user email/phone already exists
        $response['status'] = "error";
        $response['message'] = "Sorry, that email address is not a registered user!";
        echoResponse(201, $response);
    }
});

//allow users to update password
$app->put('/auth/user/changepassword', function () use ($app) {
    // require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    // var_dump($r);
    verifyRequiredParams(['old', 'new', 'id'], $r->password);
    // response array
    $response = [];
    // db handler class
    $db = new DbHandler();
    // extract fields
    $old = $db->purify($r->password->old);
    $new = $db->purify($r->password->new);
    $id = $db->purify($r->password->id);
    // check if user password is correct
    $user = $db->getOneRecord("SELECT * FROM user WHERE user_id='$id' AND user_password='$old' ");
    if ($user) {
        // user found
        // hash new password ---
        // $user_password = hash($user_password); //somehow
        // update password in db
        $update_user = $db->updateInTable(
            "user", /*table*/
            ['user_password' => $new], /*columns*/
            ['user_id' => $user['user_id']] /*where clause*/
        );
        // password updated?
        if ($update_user > 0) {
            // return success
            $response['status'] = "success";
            $response['message'] = 'Password changed successfully.';
            echoResponse(200, $response);
        } else {
            // mysql error - user creation failed
            $response['status'] = "error";
            $response['message'] = 'Password reset failed!';
            echoResponse(201, $response);
        }
    } else {
        // old password is wrong
        $response['status'] = "error";
        $response['message'] = "Old password you supplied is incorrect!";
        echoResponse(201, $response);
    }
});