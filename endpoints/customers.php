<?php
// list customers
$app->get('/customers', function() use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `customer`";
    $query .= " ORDER BY cust_name ";
    // run query
    $customers = $db->getRecordset($query);
    // return list of customers
    if($customers) {
    	$response['customers'] = $customers;
    	$response['status'] = "success";
        $response["message"] =  count($customers) . " Customer(s) found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "No user found!";
        echoResponse(201, $response);
    }
});
// single customer
$app->get('/customers/:id', function($id) use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `customer` WHERE cust_id = '$id' ";
    // run query
    $customer = $db->getOneRecord($query);
    // get orders if any
    $customer_requests = $db->getRecordset("SELECT * FROM `request` LEFT JOIN customer_location ON req_location_id=cl_id WHERE cl_customer_id = '$id' ORDER BY req_time_initiated ");
    // return customer
    if($customer) {
        $response['customer'] = $customer;
        $response['customer_requests'] = $customer_requests;
    	$response['status'] = "success";
        $response["message"] =  " Customer found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "Customer not found!";
        echoResponse(201, $response);
    }
});
// create Customer
$app->post('/customers', function() use ($app) {
    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['cust_name', 'cust_email', 'cust_phone','cust_address'],$r->customer);
    // instantiate classes
    $db = new DbHandler();
    $jh = new JWTHandler();
    
    // customer name
    $cust_email = $db->purify($r->customer->cust_email);
    $cust_phone = $db->purify($r->customer->cust_phone);
    $cust_address = $db->purify($r->customer->cust_address);
    
    $cust_signup_verified = 1;
    // check if cust_email is already used
    $cust_check  = $db->getOneRecord("SELECT cust_id FROM `customer` WHERE cust_email='$cust_email' ");
    if($cust_check) {
        // customer already exists
        $response['status'] = "error";
        $response["message"] = "Customer with same email already Exists!";
        echoResponse(201, $response);
    } else {
        //get fields for insert
        $cust_name = $db->purify($r->customer->cust_name);
        $cust_address = $db->purify($r->customer->cust_address);
        $cust_phone = $db->purify($r->customer->cust_phone);
     
        //create new customer
        $cust_id = $db->insertToTable(
            [ $cust_name, $cust_email, $cust_address,$cust_phone], /*values - array*/
            [ 'cust_name','cust_email','cust_address','cust_phone'], /*column names - array*/
            "customer" /*table name - string*/
        );
        // customer created successfully
        if($cust_id) {
            // log admin action
            $response['cust_id'] = $cust_id;
            $response['status'] = "success";
            $response["message"] = "Customer created successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to create the Customer!";
            echoResponse(201, $response);
        }
    }
});
// edit Customer
$app->put('/customers', function() use ($app) {
    // only super admins allowed
    // authAdmin('super');
    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['cust_id','cust_name', 'cust_email',  'cust_phone','cust_address'],$r->customer);
    // instantiate classes
    $db = new DbHandler();
    
    // customer id
    $cust_id = $db->purify($r->customer->cust_id);
    $cust_name = $db->purify($r->customer->cust_name);
    $cust_email = $db->purify($r->customer->cust_email);
    $cust_address = $db->purify($r->customer->cust_address);
    $cust_phone = $db->purify($r->customer->cust_phone);
  
     // check if cust_name is already used
     $cust_check  = $db->getOneRecord("SELECT cust_id FROM `customer` WHERE cust_name = '$cust_name' AND cust_email='$cust_email' AND cust_id <> '$cust_id' ");
     if($cust_check) {
         // customer already exists
         $response['status'] = "error";
         $response["message"] = "Customer with same email already Exists!";
         echoResponse(201, $response);
     } else {
        //get fields for insert
        $cust_address = $db->purify($r->customer->cust_address);
        $cust_phone = $db->purify($r->customer->cust_phone);
        //update customer
        $update_customer = $db->updateInTable(
        	"customer", /*table*/
            [ 'cust_name'=>$cust_name, 'cust_email' => $cust_email, 'cust_address' => $cust_address,'cust_phone' => $cust_phone], /*columns*/
        	[ 'cust_id'=>$cust_id ] /*where clause*/
        );
        
        // customer updated successfully
        if($update_customer >= 0) {
            // log admin action
            $response['status'] = "success";
            $response["message"] = "Customer updated successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to update the customer!";
            echoResponse(201, $response);
        }
    }
});
// delete customer
$app->delete('/customers/:id', function($id) use ($app) {
    // // only super admins allowed
    // authAdmin('super');
    // // initialize response array
    // $response = [];
    // database handler
    $db = new DbHandler();
    // delete customer
    $customer_delete = $db->deleteFromTable("customer", "cust_id", $id);
    // deleted?
    if($customer_delete) {
    	// log admin action
    	$response['status'] = "success";
        $response["message"] =  "Customer deleted successfully";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "Customer DELETE failed!";
        echoResponse(201, $response);
    }
});