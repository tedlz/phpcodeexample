<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use SimpleXMLElement;

class SoapController extends Controller
{
    private $_password = 'bmeB400!';

    public function inputAction()
    {
        $xml = file_get_contents('php://input');
        $xml = str_replace('<?xml version=\"1.0\" encoding=\"utf-8\" ?>', '', $xml);
        $preg = preg_replace(['/soapenv:/', '/ns\d+:/', '/(\sxmlns:.*?\".*?\")/'], '', $xml);

        $root = new SimpleXMLElement($preg);
        $header = $root->Header->RequestSOAPHeader;
        $body = $root->Body->syncOrderRelation;

        $result = false;

        print_r($header);
        print_r($body);
        die;

        $webId = (string) $header->webID;
        $updateType = $body->updateType; // 1 订购，2 退订
        $spRevpassword = (string) $header->spRevpassword;
        $spId = (string) $header->spId;
        $serviceId = (string) $header->serviceId;
        $timestamp = (string) $header->timeStamp;
        $userId = (string) $body->userID->ID;
        $endTime = date('Y-m-d H:i:s', strtotime($body->expiryTime));

        // 鉴权
        if ($spRevpassword !== strtoupper(md5($spId . $this->_password . $timestamp . $userId . $webId . $serviceId))) {
            exit('9999');
        }

        // 判断是订购通知还是退订通知
        if ($updateType == 2) {

        } else {

        }
    }
}
