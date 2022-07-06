<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;
use App\Libraries\SMS;
$session = \Config\Services::session();

class Products extends ResourceController
{
    use ResponseTrait;
    

    // get all product
    public function index()
    {
        echo ("Called");
        $model = new ProductModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    // get single product
    public function show($id = null)
    {
        $model = new ProductModel();
        $data = $model->getWhere(['product_id' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    // login
    public function login()
    {
        $model = new ProductModel();

        $product_name = $this->request->getVar('product_name');
        $product_price = $this->request->getVar('product_price');

        $response = array(
            "success" => true,
            "type" => "Field_Officer",
            "session_id" => "dfhdhdh",
            "message" => "Login Successful",
            "token" => "price " . $product_price . " name " . $product_name
        );

        $response1 = array(
            "success" => false,
            "type" => "Field_Officer",
            "session_id" => "dfhdhdh",
            "message" => "Wrong username and/or password!",
            "token" => "price " . $product_price . " name " . $product_name
        );
        $response2 = array(
            "success" => false,
            "type" => "Field_Officer",
            "session_id" => "dfhdhdh",
            "message" => "Contact the System Administrator!",
            "token" => "price " . $product_price . " name " . $product_name
        );

        $data = $model->getWhere(['product_name' => $product_name, 'product_id' => $product_price])->getResult();
        if ($data) {
            if($this->sendOTP($data[0]->mobile, $product_name)){
                return $this->respond($response);
            }
            else{
                return $this->respond($response2);
            }
        } else {
            return $this->respond($response1);
        }
    }

    public function sendOTP($mobile, $username)
    {
        $sms = new SMS();
        $model = new ProductModel();
        $session = session();

        $otp = $this->generate_otp();
        $datao = [
            'otp' => $otp,
        ];
        $message = $otp . " is your OTP. Do not share with anyone!\n";
        //echo ("Mobile " . $mobile . " " . $message);

        $session->setTempdata('otp', $otp, 30);

        if ($model->update($username, $datao) === true) {
            //echo ("Success");
            $sms->sendSMS($mobile, $message);
            return true;
        } else {
            //echo ("Failure");
            $message = "Sorry! We are unable to send OTP at this time! Please try again later.";
            $sms->sendSMS($mobile, $message);
            return false;
        }
        return false;
    }

    // otp
    public function otp()
    {
        if ($this->request->getMethod() == 'post') {
            $model = new ProductModel();
            $sms = new SMS();
            $session = session();//$session->removeTempdata('item');

            $requestBody = json_decode($this->request->getBody());
            $user = $requestBody->product_name;
            $userOTP = $requestBody->user_otp;
            $data = $model->getWhere(['product_name' => $user])->getResult();
            $mobile = $data[0]->mobile;

            //$session->setTempdata('otp', "otp", 30);

            $sessOTP = /* $session->otp; */ $session->getTempdata('otp');

            //echo("Session otp ".$sessOTP);

            $response = array(
                "success" => true,
                "type" => "Field_Officer",
                "session_id" => "dfhdhdh",
                "message" => "Login Successful",
                "token" => "edrhegwd6"
            );
            $response1 = array(
                "success" => false,
                "type" => "Field_Officer",
                "session_id" => "dfhdhdh",
                "message" => "Wrong OTP or OTP expired.",
                "token" => "edrhegwd6"
            );
            $dataOTP = $model->getWhere(['product_name' => $user])->getResult();

            $dbOTP = $dataOTP[0]->otp;
            //echo ($dataOTP[0]->otp . " % " . $userOTP);

            if (strval($sessOTP) === strval($userOTP)) {
                $session->removeTempdata('otp');
                return $this->respond($response);
            } else {
                return $this->respond($response1);
            }
        }
    }

    // create a product
    public function create()
    {
        $model = new ProductModel();
        $data = [
            'product_name' => $this->request->getVar('product_name'),
            'product_price' => $this->request->getVar('product_price')
        ];
        $model->insert($data);
        $response = [
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Data Saved'
            ]
        ];
        return $this->respondCreated($response);
    }

    // update product
    public function update($id = null)
    {
        $model = new ProductModel();
        $input = $this->request->getRawInput();
        $data = [
            'product_name' => $input['product_name'],
            'product_price' => $input['product_price']
        ];
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    // delete product
    public function delete($id = null)
    {
        $model = new ProductModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }


    // generate OTP
    public function generate_otp()
    {
        $OTP     =    rand(1, 9);
        $OTP     .=    rand(0, 9);
        $OTP     .=    rand(0, 9);
        $OTP     .=    rand(0, 9);
        $OTP     .=    rand(0, 9);
        $OTP     .=    rand(0, 9);
        return $OTP;
    }
}
