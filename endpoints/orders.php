<?php
// list orders
$app->get('/orders', function() use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `order`";
    $query .= " ORDER BY order_date ";
    // run quer
    $orders = $db->getRecordset($query);
    // return list of orders
    if($orders) {
    	$response['orders'] = $orders;
    	$response['status'] = "success";
        $response["message"] =  count($orders) . "order(s) found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "No order found!";
        echoResponse(201, $response);
    }
});
// single order
$app->get('/orders/:id', function($id) use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `order` WHERE order_id = '$id' ";
    // run query
    $order = $db->getOneRecord($query);
    // return order
    if($order) {
        $response['order'] = $order;
    	$response['status'] = "success";
        $response["message"] =  " order found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "order not found!";
        echoResponse(201, $response);
    }
});
//create order
$app->post('/orders', function() use ($app) {
    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    // var_dump($r->order);die;
    verifyRequiredParams(['order_remarks', 'order_quantity'],$r->order);
    
    // instantiate classes
    $db = new DbHandler();
    $jh = new JWTHandler();

    $order_date = date("Y-m-d");
    $order_remarks = $db->purify($r->order->order_remarks);
    $order_quantity = $db->purify($r->order->order_quantity);
     $order_product = $db->purify($r->order->order_product);
     $order_customer = $db->purify($r->order->order_customer);
    $order_signup_verified = 1;

        //get fields for insert
        $order_date = date("Y-m-d");
        $order_customer = $db->purify($r->order->order_customer);
        $order_product = $db->purify($r->order->order_product);
        $order_quantity = $db->purify($r->order->order_quantity);
        $order_remarks = $db->purify($r->order->order_remarks);
        $order_signup_verified = 1;
        //create new order
        $order_id = $db->insertToTable(
            [$order_date, $order_quantity, $order_remarks, $order_customer, $order_product], /*values - array*/
            ['order_date','order_quantity', 'order_remarks', 'order_customer', 'order_product'], /*column names - array*/
            "order" /*table name - string*/
        );
        // order created successfully
        if($order_id) {
            // log admin action
            $response['order_id'] = $order_id;
            $response['status'] = "success";
            $response["message"] = "order created successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to create the order!";
            echoResponse(201, $response);
        }

    
});
//edit order
$app->put('/orders', function() use ($app) {

    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['order_id','order_customer', 'order_product', 'order_quantity', 'order_remarks'],$r->order);
    // instantiate classes
    $db = new DbHandler();
    
    // order id
    $order_id  = $db->purify($r->order->order_id);
    $order_date = date("Y-m-d");
    $order_remarks = $db->purify($r->order->order_remarks);
    $order_customer = $db->purify($r->order->order_customer);
    $order_quantity = $db->purify($r->order->order_quantity);
    $order_product = $db->purify($r->order->order_product);
     // check if order_name is already used
     $order_check  = $db->getOneRecord("SELECT order_id FROM `order` WHERE order_date = '$order_date' AND order_id <> '$order_id' ");
     if($order_check) {
         // order already exists
         $response['status'] = "error";
         $response["message"] = "order with same name already Exists!";
         echoResponse(201, $response);
     } else {
        //get fields for insert
        $order_id = $db->purify($r->order->order_id);
        $order_customer = $db->purify($r->order->order_customer);
    $order_product = $db->purify($r->order->order_product);
    $order_remarks = $db->purify($r->order->order_remarks);
    $order_quantity = $db->purify($r->order->order_quantity);
        //update order
        $update_order = $db->updateInTable(
        	"order", /*table*/
            [ 'order_customer'=>$order_customer, 'order_product' => $order_product, 'order_quantity' => $order_quantity, 'order_remarks' => $order_remarks], /*columns*/
        	[ 'order_id'=>$order_id ] /*where clause*/
        );
        
        // order updated successfully
        if($update_order >= 0) {
            // log admin action
            $response['status'] = "success";
            $response["message"] = "order updated successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to update the order!";
            echoResponse(201, $response);
        }
    }
});
$app->delete('/orders/:id', function($id) use ($app) {
    // // only super admins allowed
    // authAdmin('super');
    // // initialize response array
    // $response = [];
    // database handler
    $db = new DbHandler();
    // delete order
    $order_delete = $db->deleteFromTable("`order`", "order_id", $id);
    // deleted?
    if($order_delete) {
    	// log admin action
    	$response['status'] = "success";
        $response["message"] =  "order deleted successfully";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "order DELETE failed!";
        echoResponse(201, $response);
    }
});