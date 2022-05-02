<?php
// list products
$app->get('/products', function() use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `product`";
    $query .= " ORDER BY product_name ";
    // run query
    $products = $db->getRecordset($query);
    // return list of products
    if($products) {
    	$response['products'] = $products;
    	$response['status'] = "success";
        $response["message"] =  count($products) . " Product(s) found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "No product found!";
        echoResponse(201, $response);
    }
});
// single product
$app->get('/products/:id', function($id) use ($app) {
    // authAdmin();
    // initialize response array
    $response = [];
    // database handler
    $db = new DbHandler();
    // compose sql query
    $query = "SELECT * FROM `product` WHERE product_id = '$id' ";
    // run query
    $product = $db->getOneRecord($query);
    // get orders if any
    // return product
    if($product) {
        $response['product'] = $product;
    	$response['status'] = "success";
        $response["message"] =  " product found!";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "product not found!";
        echoResponse(201, $response);
    }
});
//create product
$app->post('/products', function() use ($app) {
    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['product_name', 'product_fragrance', 'product_origin', 'product_price', 'product_details'],$r->product);
    // instantiate classes
    $db = new DbHandler();
    $jh = new JWTHandler();
    
    // product name
    $product_name = $db->purify($r->product->product_name);
    $product_fragrance = $db->purify($r->product->product_fragrance);
    $product_origin = $db->purify($r->product->product_origin);
    $product_category = $db->purify($r->product->product_category);
    $product_price = $db->purify($r->product->product_price);
    $product_details = $db->purify($r->product->product_details);
    // check if product name already exists
    $product_check  = $db->getOneRecord("SELECT product_id FROM `product` WHERE product_name='$product_name' ");
    if($product_check) {
        // product already exists
        $response['status'] = "error";
        $response["message"] = "product with same name already Exists!";
        echoResponse(201, $response);
    } else {
        //get fields for insert
        $product_name = $db->purify($r->product->product_name);
        $product_fragrance = $db->purify($r->product->product_fragrance);
        $product_origin = $db->purify($r->product->product_origin);
        $product_category = $db->purify($r->product->product_category);
        $product_price = $db->purify($r->product->product_price);
        $product_details = $db->purify($r->product->product_details);
        //create new product
        $product_id = $db->insertToTable(
            [ $product_name, $product_fragrance, $product_origin, $product_category, $product_price, $product_details], /*values - array*/
            [ 'product_name','product_fragrance','product_origin', 'product_category', 'product_price', 'product_details'], /*column names - array*/
            "product" /*table name - string*/
        );
        // product created successfully
        if($product_id) {
            // log admin action
            $response['product_id'] = $product_id;
            $response['status'] = "success";
            $response["message"] = "product created successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to create the product!";
            echoResponse(201, $response);
        }

    }
});
//edit product
$app->put('/products', function() use ($app) {

    // initialize response array
    $response = [];
    // extract post body
    $r = json_decode($app->request->getBody());
    // check required fields
    verifyRequiredParams(['product_id','product_name', 'product_fragrance', 'product_origin', 'product_price', 'product_details'],$r->product);
    // instantiate classes
    $db = new DbHandler();
    
    // product id
    $product_id = $db->purify($r->product->product_id);
    $product_name = $db->purify($r->product->product_name);
    $product_fragrance = $db->purify($r->product->product_fragrance);
    $product_origin = $db->purify($r->product->product_origin);
    $product_details = $db->purify($r->product->product_details);
    $product_price = $db->purify($r->product->product_price);
     // check if product_name is already used
     $product_check  = $db->getOneRecord("SELECT product_id FROM `product` WHERE product_name = '$product_name' AND product_id <> '$product_id' ");
     if($product_check) {
         // product already exists
         $response['status'] = "error";
         $response["message"] = "product with same name already Exists!";
         echoResponse(201, $response);
     } else {
        //get fields for insert
        $product_id = $db->purify($r->product->product_id);
        $product_name = $db->purify($r->product->product_name);
    $product_fragrance = $db->purify($r->product->product_fragrance);
    $product_origin = $db->purify($r->product->product_origin);
    $product_details = $db->purify($r->product->product_details);
    $product_price = $db->purify($r->product->product_price);
        //update product
        $update_product = $db->updateInTable(
        	"product", /*table*/
            [ 'product_name'=>$product_name, 'product_fragrance' => $product_fragrance, 'product_origin' => $product_origin, 'product_price' => $product_price, 'product_details' => $product_details], /*columns*/
        	[ 'product_id'=>$product_id ] /*where clause*/
        );
        
        // product updated successfully
        if($update_product >= 0) {
            // log admin action
            $response['status'] = "success";
            $response["message"] = "product updated successfully!";
            echoResponse(200, $response);
        } else {
            $response['status'] = "error";
            $response["message"] = "Something went wrong while trying to update the product!";
            echoResponse(201, $response);
        }
    }
});
$app->delete('/products/:id', function($id) use ($app) {
    // // only super admins allowed
    // authAdmin('super');
    // // initialize response array
    // $response = [];
    // database handler
    $db = new DbHandler();
    // delete product
    $product_delete = $db->deleteFromTable("product", "product_id", $id);
    // deleted?
    if($product_delete) {
    	// log admin action
    	$response['status'] = "success";
        $response["message"] =  "product deleted successfully";
        echoResponse(200, $response);
    } else {
    	$response['status'] = "error";
        $response["message"] = "Product DELETE failed!";
        echoResponse(201, $response);
    }
});


