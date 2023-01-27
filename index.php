<?php

require 'vendor/autoload.php';

use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
const APP_ID = 'APP_ID';
const TRANSACTION_KEY = 'TRANSACTION_KEY';
function chargeCreditCard($options)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName(APP_ID);
    $merchantAuthentication->setTransactionKey(TRANSACTION_KEY);

    // Set the transaction's refId
    $refId = 'ref' . time();

    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber($options['cc_number']);
    $expiration = strlen($options['exp_year']) == 2 ? ('20' . $options['exp_year']) : $options['exp_year'];
    $expiration .= '-' . (str_pad($options['exp_month'], 2, 0, STR_PAD_LEFT));
    $creditCard->setExpirationDate($expiration);
    $creditCard->setCardCode($options['card_code']);

    // Add the payment data to a paymentType object
    $paymentOne = new AnetAPI\PaymentType();
    $paymentOne->setCreditCard($creditCard);

    // Create order information
    $order = new AnetAPI\OrderType();
    $order->setInvoiceNumber($options['invoice_number']);
    $order->setDescription($options['description']);

    // Set the customer's Bill To address
    $customerAddress = new AnetAPI\CustomerAddressType();
    $customerAddress->setFirstName($options['first_name']);
    $customerAddress->setLastName($options['last_name']);
    $customerAddress->setCompany($options['company']);
    $customerAddress->setAddress($options['address']);
    $customerAddress->setCity($options['city']);
    $customerAddress->setState($options['state']);
    $customerAddress->setZip($options['zip']);
    $customerAddress->setCountry("USA");

    // Set the customer's identifying information
    $customerData = new AnetAPI\CustomerDataType();
    $customerData->setType("individual");
    $customerData->setEmail($options['email']);

    // Create a customer shipping address
    $customerShippingAddress = new AnetAPI\CustomerAddressType();
    $customerShippingAddress->setFirstName($options['customer_first_name']);
    $customerShippingAddress->setLastName($options['customer_last_name']);
    $merchantDefinedField1 = new AnetAPI\UserFieldType();
    $merchantDefinedField1->setName("Patient First Name");
    $merchantDefinedField1->setValue($options['customer_first_name']);

    $merchantDefinedField2 = new AnetAPI\UserFieldType();
    $merchantDefinedField2->setName("Patient Last Name");
    $merchantDefinedField2->setValue($options['customer_last_name']);

    // Add values for transaction settings
    $duplicateWindowSetting = new AnetAPI\SettingType();
    $duplicateWindowSetting->setSettingName("duplicateWindow");
    $duplicateWindowSetting->setSettingValue("60");


    // Create a TransactionRequestType object and add the previous objects to it
    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType("authCaptureTransaction");
    $transactionRequestType->setAmount($options['amount']);
    $transactionRequestType->setOrder($order);
    $transactionRequestType->setPayment($paymentOne);
    $transactionRequestType->setBillTo($customerAddress);
    $transactionRequestType->setShipTo($customerShippingAddress);
    $transactionRequestType->setUserFields([$merchantDefinedField1, $merchantDefinedField2]);
    $transactionRequestType->setCustomer($customerData);
    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

    // Assemble the complete transaction request
    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setTransactionRequest($transactionRequestType);

    // Create the controller and get the response
    $controller = new AnetController\CreateTransactionController($request);
    $response = $controller->executeWithApiResponse(ANetEnvironment::PRODUCTION);

    $tresponse = $response->getTransactionResponse();
    if ($tresponse != null) {
        // Check to see if the API request was successfully received and acted upon
        if ($response->getMessages()->getResultCode() == "Ok") {
            // Since the API request was successful, look for a transaction response
            // and parse it to display the results of authorizing the card

            if ($tresponse->getMessages() != null) {
                return [
                    'transaction_id' => $tresponse->getTransId(),
                    'auth_code' => $tresponse->getAuthCode(),
                ];
            }
        }
    }
    return [
        'error_message' => $response->getMessages()->getMessage()[0]->getText(),
    ];
}

if (!empty($_POST['amount']) &&
    !empty($_POST['cc_number']) &&
    !empty($_POST['exp_year']) &&
    !empty($_POST['exp_month']) &&
    !empty($_POST['card_code'])
) {
    try {
        $return = @chargeCreditCard([
            'amount' => $_POST['amount'],
            'cc_number' => str_replace(' ', '', $_POST['cc_number']),
            'exp_year' => $_POST['exp_year'],
            'exp_month' => $_POST['exp_month'],
            'card_code' => $_POST['card_code'],
            'invoice_number' => time() . rand(1000, 9999),
            'description' => 'Medical Payment',
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'company' => '',
            'address' => $_POST['street'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zip' => $_POST['zip'],
            'email' => $_POST['email'],
            'customer_first_name' => $_POST['customer_first_name'],
            'customer_last_name' => $_POST['customer_last_name'],
        ]);

        echo json_encode($return);
        exit;
    } catch (\Exception $exception) {
        echo json_encode([
            'error_message' => $exception->getMessage(),
        ]);
        exit;
    }
}
echo 1;