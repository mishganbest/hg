<?php

require_once __DIR__ . '/sheepla.php';
require_once __DIR__ . '/sheeplaLog.php';

class OpencartSheepla extends Sheepla {
    
    protected $sheeplaObj,
              $sheeplaLog,
              $init = false;
    
    
    public function init( $key , $apiurl ) {
        
        $this->setConfig( array( 'key' => $key , 'url' => $apiurl ) );
        $this->sheeplaLog = new SheeplaLog();
        
        $this->init = true;
    }
    
    
    public function checkAccount( $key , $apiurl ) {
        
        $test = new Sheepla( array( 'key' => $key , 'url' => $apiurl ) );
        
        $result = $test->validAccount();
        
        unset( $test );
        
        return $result;
    }
    
    
    public function getTemplates() {
        
        return $this->getShipmentTemplates(); 
    }
    
    
    public function createSynchronizeRequest() {
        
        return new SynchronizeRequest();
    }
    
    
    public function synchronize( SynchronizeRequest $sr ) {
        
        $synchronized = array();
        $errors = array();
        
        
        if( !count( $sr->getOrders() ) ) {
            
            return array( array() , array() );
        }
        
        
        $res = $this->createOrders( $sr->getOrders() );
        
        $this->sheeplaLog->notice( __METHOD__ , "Synchronization start" , $this );
        
        foreach( $res as $responseRow ) {
            
            if( empty( $responseRow[ 'errors' ] ) ) {
                
                $this->sheeplaLog->notice(
                    __METHOD__,
                    "Order with shop id = {$responseRow[ 'externalOrderId' ]} was synchronized with Sheepla and have id = {$responseRow[ 'orderId' ]} in Sheepla",
                    $this 
                );
                $synchronized[ $responseRow[ 'externalOrderId' ] ] = $responseRow[ 'orderId' ];
                
            } else {
                
                $this->sheeplaLog->critical(
                    __METHOD__,
                    "Order synchronization fail! Shop order id = {$responseRow[ 'externalOrderId' ]}, Response reason " . print_r( $responseRow[ 'errors' ] , true ),
                    $this 
                );
                $errors[ $responseRow[ 'externalOrderId' ] ] = $responseRow[ 'errors' ];
            }
        }
        
        $this->sheeplaLog->notice(__METHOD__ , "Synchronizationa end" , $this );
        
        return array(
            0 => $errors,
            1 => $synchronized
        );
    }
    
    
    public function createPricingRequest() {
        
        $pricingDom = $this->createRequestDom( 'checkoutPricingRequest' , true );
        
        return new PricingRequest( $pricingDom );
    }
    
    
    /**
     * pricing
     * @return array first key is errors second templates third is pricingSessionId
     */
    public function getAvailableShippingMethods( PricingRequest $pr ) {
        
        $errors = array();
        $shippings = array();
        $checkoutPricingHashSession = null;
        
        
        
        
        
        $requestResult = $this->toSimpleXMLElement( $this->send( 'CheckoutPricing' , $pr->makeDocument() ) );
        
        if( isset( $requestResult->errors ) ||
            isset( $requestResult->error ) ) {
            
            
            $ResponseErrors = isset( $requestResult->errors ) ? $requestResult->errors : $requestResult->error;
            
            foreach( $ResponseErrors->children() as $error ) {
                
                $errors[] = $error->__toString();
            }
            
        } else {
            
                    
            foreach( $requestResult as $deliveryMethods ) {
                
                if( 'checkoutPricingHashSession' == $deliveryMethods->getName() ) {
                    
                    $checkoutPricingHashSession = $deliveryMethods->__toString();
                    continue;
                }
                
                
                foreach( $deliveryMethods->children() as $deliveryMethod ) {
                    
                    $shippings[ $deliveryMethod->shipmentTemplateId->__toString() ] = array(
                        "id" => $deliveryMethod->shipmentTemplateId->__toString(),
                        "name" => $deliveryMethod->shipmentTemplateName->__toString(),
                        "currency" => $deliveryMethod->currency->__toString(),
                        "price" => $deliveryMethod->price->__toString()
                    );
                }
            }
        }
        
        return array(
            $errors,
            $shippings,
            $checkoutPricingHashSession
        );
    }
    
    
    public static function translateLangSc( $langSc ) {
        
        $langs = array(
            "af" => 1078,
            "sq" => 1052,
            "am" => 1118,
            "ar" => 9217,
            "hy" => 1067,
            "as" => 1101,
            "az" => 1068,
            "eu" => 1069,
            "be" => 1059,
            "bn" => 1093,
            "bs" => 5146,
            "bg" => 1026,
            "my" => 1109,
            "ca" => 1027,
            "zh" => 1028,
            "hr" => 1050,
            "cs" => 1029,
            "da" => 1030,
            "dv" => 1125,
            "nl" => 1043,
            "en" => 1033,
            "et" => 1061,
            "fo" => 1080,
            "fa" => 1065,
            "fi" => 1035,
            "fr" => 7180,
            "mk" => 1071,
            "gd" => 1084,
            "gl" => 1110,
            "ka" => 1079,
            "de" => 2055,
            "el" => 1032,
            "gn" => 1140,
            "gu" => 1095,
            "he" => 1037,
            "hi" => 1081,
            "hu" => 1038,
            "is" => 1039,
            "id" => 1057,
            "it" => 2064,
            "ja" => 1041,
            "kn" => 1099,
            "ks" => 1120,
            "kk" => 1087,
            "km" => 1107,
            "ko" => 1042,
            "lo" => 1108,
            "la" => 1142,
            "lv" => 1062,
            "lt" => 1063,
            "ms" => 1086,
            "ml" => 1100,
            "mt" => 1082,
            "mi" => 1153,
            "mr" => 1102,
            "mn" => 1104,
            "ne" => 1121,
            "nb" => 1044,
            "nn" => 2068,
            "or" => 1096,
            "pl" => 1045,
            "pt" => 2070,
            "pa" => 1094,
            "rm" => 1047,
            "ro" => 1048,
            "ru" => 1049,
            "sa" => 1103,
            "sr" => 2074,
            "tn" => 1074,
            "sd" => 1113,
            "si" => 1115,
            "sk" => 1051,
            "sl" => 1060,
            "so" => 1143,
            "sb" => 1070,
            "es" => 8202,
            "sw" => 1089,
            "sv" => 1053,
            "tg" => 1064,
            "ta" => 1097,
            "tt" => 1092,
            "te" => 1098,
            "th" => 1054,
            "bo" => 1105,
            "ts" => 1073,
            "tr" => 1055,
            "tk" => 1090,
            "uk" => 1058,
            "ur" => 1056,
            "uz" => 1091,
            "vi" => 1066,
            "cy" => 1106,
            "xh" => 1076,
            "yi" => 1085,
            "zu" => 1077
        );
        
        
        if( is_string( $langSc ) &&
            isset( $langs[ $langSc ] ) ) {
            
            return $langs[ $langSc ];
        }
        
        
        return 1045;
    }
}


class SynchronizeRequest {
    
    protected $orders = array(),
              $errors = array(),
              $synchronized = array();
    
    
    public function getOrders() {
    
        return $this->orders;
    }    
    
    public function addOrder( $orderData ) {
        
        $products = array();
         
        foreach( $orderData[ 'order_items' ] as $orderItem ) {
            
            $products[] = array(
                'name' => $orderItem[ 'name' ],
                'sku' => $orderItem[ 'product_id' ],
                'qty' => $orderItem[ 'quantity' ],
                'unit' => null,
                'weight' => round( $orderItem[ 'weight' ] , 2 ),
                'height' => round( $orderItem[ 'height' ] , 2 ),
                'length' => round( $orderItem[ 'length' ] , 2 ),
                'priceGross' => round( ( $orderItem[ 'price' ] + $orderItem[ 'tax' ] ) * $orderData[ 'currency_value' ] , 2 ),
                'ean13' => null,
                'ean8' => null,
                'issn' => null
            );
        }
        
        
        // for inpost and other additional carrier data
        $specials = unserialize( $orderData[ 'sheepla_additional' ] );
        
        $delivery_option = array();
        
        
        
        
        if ( isset( $specials[ 'sheepla-widget-plpointpack-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plpointpack-paczkomat' ] ) ) {
            $delivery_option['plPointPack']['popName'] = $specials[ 'sheepla-widget-plpointpack-paczkomat' ];
                    
        } elseif ( isset( $specials[ 'sheepla-widget-plruch-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plruch-paczkomat' ] ) ) {
            $delivery_option['plRuch']['popName'] = $specials[ 'sheepla-widget-plruch-paczkomat' ];
                    
        } else if ( isset( $specials[ 'sheepla-widget-plxpress-deliveryframetime' ] ) && !empty( $specials[ 'sheepla-widget-plxpress-deliveryframetime' ] ) ) {
            $delivery_option['plXPress']['deliveryTimeFrameId'] = $specials[ 'sheepla-widget-plxpress-deliveryframetime' ];
            
        } elseif( isset( $specials[ 'sheepla-widget-plinpost-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plinpost-paczkomat' ] ) ) {
            $delivery_option[ 'plInPost' ][ 'popName' ] = $specials[ 'sheepla-widget-plinpost-paczkomat' ];

        } elseif( isset( $specials[ 'sheepla-widget-rushoplogistics-metro-station' ] ) && !empty( $specials[ 'sheepla-widget-rushoplogistics-metro-station' ] ) ) {
            $delivery_option[ 'ruShopLogistics' ][ 'metroStationId' ] = $specials[ 'sheepla-widget-rushoplogistics-metro-station' ];         
            $delivery_option[ 'ruShopLogistics' ][ 'popId' ] = $specials[ 'sheepla-widget-rushoplogistics-paczkomat' ];    
            
        } elseif( isset( $specials[ 'sheepla-widget-plinpost-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plinpost-paczkomat' ] ) ) {
            $delivery_option[ 'ruIMLogistics' ][ 'pickupPointCarrierCode' ] = $specials[ 'sheepla-widget-plinpost-paczkomat' ];                

        } elseif( isset( $specials[ 'sheepla-widget-plinpost-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plinpost-paczkomat' ] ) ) {
            $delivery_option[ 'ruPickPoint' ][ 'pickupPointCarrierCode' ] = $specials[ 'sheepla-widget-plinpost-paczkomat' ];
            
        } elseif( isset( $specials[ 'sheepla-widget-ruqiwipost-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-ruqiwipost-paczkomat' ] ) ) {
            $delivery_option[ 'ruQiwiPost' ][ 'popName' ] = $specials[ 'sheepla-widget-ruqiwipost-paczkomat' ];
            
        } elseif( isset( $specials[ 'sheepla-widget-rucdek-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-rucdek-paczkomat' ] ) ) {
            $delivery_option[ 'ruCdek' ][ 'popName' ] = $specials[ 'sheepla-widget-rucdek-paczkomat' ];
            
         } elseif( isset( $specials[ 'sheepla-widget-rulogibox-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-rulogibox-paczkomat' ] ) ) {
            $delivery_option[ 'ruLogiBox' ][ 'popName' ] = $specials[ 'sheepla-widget-rulogibox-paczkomat' ];
            
        } elseif( isset( $specials[ 'sheepla-widget-ruboxberry-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-ruboxberry-paczkomat' ] ) ) {
            $delivery_option[ 'ruBoxBerry' ][ 'popId' ] = $specials[ 'sheepla-widget-ruboxberry-paczkomat' ];
            $delivery_option[ 'ruBoxBerry' ][ 'popName' ] = $specials[ 'sheepla-widget-ruboxberry-paczkomat' ];
            
         } elseif( isset( $specials[ 'sheepla-widget-rutopdelivery-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-rutopdelivery-paczkomat' ] ) ) {
            $delivery_option[ 'ruTopDelivery' ][ 'popName' ] = $specials[ 'sheepla-widget-rutopdelivery-paczkomat' ];
            
        } elseif( isset( $specials[ 'sheepla-widget-plpolishpost-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plpolishpost-paczkomat' ] ) ) {
            $delivery_option[ 'plPolishPost' ][ 'pickupPointCarrierCode' ] = $specials[ 'sheepla-widget-plpolishpost-paczkomat' ];
           
        } elseif( isset( $specials[ 'sheepla-widget-plowncarrier-paczkomat' ] ) && !empty( $specials[ 'sheepla-widget-plowncarrier-paczkomat' ] ) ) {
            $delivery_option[ 'plOwnCarrier' ][ 'deliveryPointId' ] = $specials[ 'sheepla-widget-plowncarrier-paczkomat' ];
        }
        
        
        
        
        $templateId = null;
        if( preg_match( '/sheepla\.([0-9]+)/' , $orderData[ 'shipping_code' ] , $matches ) ) {
            
            $templateId = $matches[ 1 ];
        }
        
        
        
        
        $shippingAddress = empty( $orderData[ 'shipping_address_1' ] ) && isset( $orderData[ 'shipping_address_2' ] ) 
                                ? 
                                    $orderData[ 'shipping_address_2' ] 
                                : 
                                    $orderData[ 'shipping_address_1' ];
        
        if( preg_match( '/([0-9]{0,}[.]*[^0-9]+) ([0-9\/a-z\. ]+)/' , $shippingAddress , $matches ) ) {
            
            
            $street = $matches[ 1 ];
            $buildingNumber = $matches[ 2 ];
            
        } else { 
            
            $street = $shippingAddress;
            $buildingNumber = '';
        }
        
        $this->orders[] = array(
            'orderValue' => $orderData[ 'total' ],
            'orderValueCurrency' => $orderData[ 'currency_code' ],
            'externalDeliveryTypeId' => $orderData[ 'shipping_code' ],
            'externalDeliveryTypeName' => $orderData[ 'shipping_method' ],
            'externalPaymentTypeId' => $orderData[ 'payment_code' ],
            'externalPaymentTypeName' => $orderData[ 'payment_method' ],
            'externalBuyerId' => $orderData[ 'customer_id' ],
            'externalOrderId' => $orderData[ 'order_id' ],
            'shipmentTemplate' => $templateId,          
            'comments' => $orderData[ 'comment' ],
            'createdOn' => date( 'c' , strtotime( $orderData[ 'date_added' ] ) ),
            'deliveryPrice' => round( $orderData[ 'oc_total' ][ 'shipping' ][ 'value' ] * $orderData[ 'currency_value' ] , 2 ),
            'deliveryPriceCurrency' => $orderData[ 'currency_code' ],
            'deliveryOptions' => $delivery_option,
            'deliveryAddress' => array(
                'street' => $street,
                'buildingNumber' => $buildingNumber,
                'zipCode' => $orderData[ 'shipping_postcode' ],
                'city' => $orderData[ 'shipping_city' ],
                'countryAlpha2Code' => $orderData[ 'shipping_country_iso_code_2' ],
                'firstName' => $orderData[ 'shipping_firstname' ],
                'lastName' => $orderData[ 'shipping_lastname' ],
                'companyName' => $orderData[ 'shipping_company' ],
                'phone' => $orderData[ 'telephone' ],
                'email' => $orderData[ 'email' ],
            ),
            'orderItems' => $products
        );
    }
}


class PricingRequest {
    
    protected $pricingDom,
              $address,
              $products = array();
    
    
    public function __construct( DOMDocument $pricingDom ) {
        
        $this->pricingDom = $pricingDom;
    }
    
    
    public function addProduct( array $product ) {
        
        $this->products[] = $product;
    }
    
    
    public function setAddress( $country , $zip , $city , $street ) {
        
        $this->address = array(
            'country' => $country,
            'zip' => $zip,
            'city' => $city,
            'streey' => $street
        );
    }
    
    
    public function makeDocument() {
        
        if( null == $this->address ) 
            throw new Exception( 'No address data set in PricingRequest' );
        
        
        $orderDate = $this->pricingDom->createElement( 'orderDate' , date( 'c' ) );
        $this->pricingDom->documentElement->appendChild( $orderDate );
        
        
        $deliveryAddress = $this->pricingDom->createElement( 'deliveryAddress' );
        $this->pricingDom->documentElement->appendChild( $deliveryAddress );
        
        
        $deliveryAddress->appendChild( $this->pricingDom->createElement( 'city' , $this->address[ 'city' ] ) );
        $deliveryAddress->appendChild( $this->pricingDom->createElement( 'zipCode' , $this->address[ 'zip' ] ) );
        $deliveryAddress->appendChild( $this->pricingDom->createElement( 'countryCode' , strtoupper( $this->address[ 'country' ] ) ) );
        $deliveryAddress->appendChild( $this->pricingDom->createElement( 'street' , $this->address[ 'streey' ] ) );
        
        
        $products = $this->pricingDom->createElement( 'products' );
        $this->pricingDom->documentElement->appendChild( $products );
        
        foreach( $this->products as $product ) {
            
            
            $productElement = $this->pricingDom->createElement( 'product' );
            $products->appendChild( $productElement );
            
            
            foreach( $product as $attributeName => $attributeValue ) {
                
                if( is_array( $attributeValue ) ||
                    null == $attributeValue ||
                    !strlen( $attributeValue ) ||
                    !preg_match( '/^[a-zA-Z0-9]+$/' , $attributeName ) ) {
                    
                    continue;
                }
                
                
                $attribute = $this->pricingDom->createElement( 'attribute' );
                
                $attribute->setAttribute( 'name' , $attributeName );
                $attribute->setAttribute( 'value' , $attributeValue );
                
                $productElement->appendChild( $attribute ); 
            }
        }
        
        
        return $this->pricingDom->saveXml();
    }
    
    
    public function __toString() {
        
        return $this->makeDocument();
    }
}