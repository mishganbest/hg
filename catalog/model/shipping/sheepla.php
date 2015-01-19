<?php

class ModelShippingSheepla extends Model {
    
    function getQuote( $address ) {
        
        $loader = new Loader( $this->registry );
        $loader->model( 'setting/setting' );
        $loader->model( 'shipping/sheepla' );

            
        $loader->library( 'sheeplaLog' );
        $SheeplaLog = new SheeplaLog();
        
        
        $pricing = $this->registry->get( 'config' )->get( 'sheepla_dynamic_pricing' );
        
        
        $pricingData = null;
        
        
        if( $pricing ) {
            
            
            try {
                
               
                $loader->library( 'opencartSheepla' );
                
                $sheeplaClient = new OpencartSheepla();
                
                
                
                $sheeplaClient->init(
                    $this->registry->get( 'config' )->get( 'sheepla_admin_key' ),
                    $this->registry->get( 'config' )->get( 'sheepla_api_url' )
                );
                
                
                
                // getting shipping address
                $session = $this->registry->get( 'session' );
                if( $this->customer->isLogged() && isset( $session->data[ 'shipping_address_id' ] ) ) {	
                    
                    $shipping_address = $this->model_account_address->getAddress( $session->data[ 'shipping_address_id' ] );	
                    
                } elseif( isset( $session->data['guest'] ) ) {
                    
                    $shipping_address = $session->data['guest']['shipping'];
                }
                
                
                
                $cart = $this->registry->get( 'cart' );
                
                
                $pricingRequest = $sheeplaClient->createPricingRequest();
                
                
                $pricingRequest->setAddress( 
                    @$shipping_address[ 'iso_code_2' ],
                    @$shipping_address[ 'postcode' ], 
                    @$shipping_address[ 'city' ],
                    @$shipping_address[ 'address_1' ]
                );
                
                
                
                $cartProducts = $cart->getProducts();
                
                
                
                foreach( $cartProducts as $cartProduct ) {
                    
                    // tu podana jest suma wag danego produktu, więc dzielę prze ilość danego produktu
                    $cartProduct[ 'weight' ] = $cartProduct[ 'weight' ] / $cartProduct[ 'quantity' ];
                    
                    
                    
                    $cartProduct[ 'weight' ] = $this->weight->convert( 
                        $cartProduct[ 'weight' ],
                        $cartProduct[ 'weight_class_id' ],
                        2
                    );
                    
                    
                    $cartProduct[ 'length' ] = $this->weight->convert( 
                        $cartProduct[ 'length' ],
                        $cartProduct[ 'length_class_id' ],
                        2
                    );
                    
                    
                    $cartProduct[ 'width' ] = $this->weight->convert( 
                        $cartProduct[ 'width' ],
                        $cartProduct[ 'length_class_id' ],
                        2
                    );
                    
                    
                    $cartProduct[ 'height' ] = $this->weight->convert( 
                        $cartProduct[ 'height' ],
                        $cartProduct[ 'length_class_id' ],
                        2
                    );
                    
                    
                    $pricingRequest->addProduct( $cartProduct );
                }
                
                
                
                $shippings = $sheeplaClient->getAvailableShippingMethods( $pricingRequest );
                
                $pricingData = null;
                
                if( count( $shippings[ 0 ] ) ) {
                    
                    
                    $SheeplaLog->critical( __METHOD__ , 'Unable to perform dynamic pricing. errors: ' . print_r( $shippings[ 0 ] , true ) , $this );
                    
                } else {
                    
                    foreach( $shippings[ 1 ] as $sheeplaTemplateId => $shippingMethod ) {
                        
                        $pricingData[ $sheeplaTemplateId ] = $shippingMethod;
                    }
                    
                    
                    if( isset( $shippings[ 2 ] ) ) {
                        
                        $this->session->data[ 'checkoutPricingSessionId' ] = $shippings[ 2 ];
                        
                    } else {
                        
                        $this->session->data[ 'checkoutPricingSessionId' ] = null;
                    }
                }
                
            } catch( Exception $e ) {
                
                
                $SheeplaLog->critical( __METHOD__ , 'Unable to perform dynamic pricing. Excepton: ' . $e->getMessage() , $this );
            }
        }
        
        
        
                
        try {
            
            
            $shippments = $this->db->query( "SELECT * FROM " . DB_PREFIX . "sheepla WHERE active = 1 ORDER BY queue ASC" );
            $shippments = $shippments->rows;
            
            
        } catch (Exception $ex) {
            
            $shippments = array();
        }
        
        $quote_data = array();
        
        
        
        foreach( $shippments as $shippment ) {
            
            if( null !== $pricingData && !isset( $pricingData[ $shippment[ 'sheepla_template_id' ] ] ) ) {
                
                continue;
            }
            
            
            if( null == $pricingData ) {
                
                $shippingCost = $shippment[ 'price' ];
                $shippingCostText = $this->currency->format( $shippingCost );
                
            } else {
                
                
                $shippingCost = $this->currency->convert(
                    $pricingData[ $shippment[ 'sheepla_template_id' ] ][ 'price' ],
                    $pricingData[ $shippment[ 'sheepla_template_id' ] ][ 'currency' ], 
                    $this->currency->getCode()
                );

                $shippingCostText = $this->currency->format( $shippingCost , null , 1 );
            }
            
            
            
            
            
            $quote_data[ $shippment[ 'sheepla_template_id' ] ] = array(
                'code'         => 'sheepla.' . $shippment[ 'sheepla_template_id' ],
                'title'        => $shippment[ 'name' ],
                'cost'         => $shippingCost,
                'tax_class_id' => 0,
                'text'         => $shippingCostText
            );
        }
        
        
        if( !count( $quote_data ) ) {
            
            return array();
        }
        
        
        $method_data = array(
                'code'       => 'sheepla',
                'title'      => 'Sheepla',
                'quote'      => $quote_data,
                'sort_order' => 1,
                'error'      => false
        );
        
        return $method_data;
    }
    
    public function addOrder( $ocOrderId , $sheeplaAdditional ) {
        
        $this->db->query("
            INSERT INTO " . DB_PREFIX . "sheepla_order ( order_id , additional ) VALUES
                                                       ( $ocOrderId , '$sheeplaAdditional' )
        ");
        
        return true;
    }
    
    
    /**
     * Getting orders to synchronize
     */
    public function getOrdersToSync( $maxAttempt = 2 , $limit = 10 , $forceSyncId = null ) {
        
        // getting orders data
        $res = $this->db->query( "SELECT so.id as sheepla_order_id, 
                                         so.additional as sheepla_additional,
                                         so.sync_fail_attempt as sheepla_sync_fail_attempt,
                                         co.*,
                                         cc.iso_code_2 as shipping_country_iso_code_2
                                  FROM " . DB_PREFIX . "sheepla_order so, " .   // sheepla data table
                                           DB_PREFIX . "order co, " .           // main order table
                                           DB_PREFIX . "country cc " .
                                 "WHERE so.order_id = co.order_id AND
                                        " . ( null !== $forceSyncId ? 
                                               "so.order_id = $forceSyncId" : 
                                               "( so.synchronized = 0 AND ( so.sync_fail_attempt < $maxAttempt OR so.wait_until <= CURRENT_TIMESTAMP ) ) " ) . "
                                        AND co.shipping_country_id = cc.country_id
                                  ORDER BY co.order_id DESC 
                                  LIMIT $limit" );
        
        
        $result = array();
        
        
        if( 0 < $res->num_rows ) {
            
            
            $ordersIdList = array();
            
            
            foreach( $res->rows as $row ) {
                
                $row[ 'order_items' ] = array();
                
                $ocTotal = array();
                foreach( $this->db->query( "SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = {$row[ 'order_id' ]}" )->rows as $ocTotalRow ) {
                    
                    $ocTotal[ $ocTotalRow[ 'code' ] ] = $ocTotalRow;
                }
                
                
                
                $row[ 'oc_total' ] = $ocTotal;
                $result[ $row[ 'order_id' ] ] = $row;
                
                
                
                
                $ordersIdList[] = $row[ 'order_id' ];
            }
            
            
            // collecting order items fot each of order
            $ordersIdList = implode( ',' , $ordersIdList );
            
            $orderItems = $this->db->query("SELECT oop.*,
                                                   op.weight_class_id,
                                                   op.length_class_id,
                                                   op.weight,
                                                   op.length,
                                                   op.width,
                                                   op.height
                                            FROM " . DB_PREFIX . "order_product oop,
                                                 " . DB_PREFIX . "product op
                                            WHERE oop.order_id in( $ordersIdList ) AND 
                                                  oop.product_id = op.product_id
                                           ")->rows;
            
            foreach( $orderItems as $orderItem ) {
                
                $result[ $orderItem[ 'order_id' ] ][ 'order_items' ][] = $orderItem;
            }
            
        }
        return $result;
    }
    
    
    public function getProduct( $productId ) {
        
        if(is_numeric( $productId ) ) {
            
            $single = true;
            $productId = array( $productId );
            
        } else {
            
            $single = false;
        }
        
        
        $productId = implode( ',' , (array)$productId );
        
        
        $res = $this->db->select( "SELECT * FROM " . DB_PREFIX . "product WHERE product_id in ( $productId )" );
        
        
        if( $single ) {
            
            return $res->row;
            
        } else {
            
            return $res->rows;
        }
    }
    
    
    
    /**
     * Marking row as sent
     * @param type $shopId
     * @param type $sheeplaId
     */
    public function markAsSynchronized( $shopId , $sheeplaId ) {
        
        $this->db->query( "UPDATE " . DB_PREFIX . "sheepla_order SET synchronized = 1, sheepla_order_id = $sheeplaId, synchronized_date = CURRENT_TIMESTAMP WHERE order_id = $shopId" );
    }
    
    
    /**
     * Increment fail addempt counter and set next synchronization date if maxAttempt is reached
     * @param integer $orderId order id
     * @param integer $maxAttempt max attempt value
     * @param string $timeout value to use with strtotime function
     */
    public function incrementFailTryoutCounter( $orderId , $maxAttempt = 2 , $timeout = 'days midnight' ) {
        
        $orderId = (int)$orderId;
        
        $orderRow = $this->db->query( "SELECT sync_fail_attempt FROM " . DB_PREFIX . "sheepla_order WHERE order_id = $orderId" );
        
        if( 1 == $orderRow->num_rows ) {
            
            $attemptNumber = $orderRow->row[ 'sync_fail_attempt' ];
            $attemptNumber++;
            
            $waitUntil = null;
            
            if( $attemptNumber > $maxAttempt ) {
                
                $waitUntil = date( 'Y-m-d H:i:s' , strtotime( '+' . $maxAttempt - $attemptNumber . ' ' . $timeout ) );
                
                $this->db->query("UPDATE " . DB_PREFIX . "sheepla_order SET sync_fail_attempt = $attemptNumber, wait_until = '$waitUntil' WHERE order_id = $orderId" );
                
            } else {
                
                $this->db->query("UPDATE " . DB_PREFIX . "sheepla_order SET sync_fail_attempt = $attemptNumber WHERE order_id = $orderId" );
            }
        }
    }
    
    
    public function getSheeplaTable( $where , $limit ) {
        
        if( 0 < $limit ) {
            
            $limit = " LIMIT " . (int)$limit;
            
        } else { $limit = ''; }
        
        $whereConstrint = "";
        
        foreach( $where as $request => $constraint ) {
            
            if( 'since_id' == $request ) {
                
                $whereConstrint .= " AND so.order_id >= $constraint";
            } elseif( 'id' == $request ) {
                
                $whereConstrint .= " AND so.order_id = $constraint";
            } elseif( 'since_date' == $request ) {
                
                $whereConstrint .= " AND oo.date_added >= '$constraint'";
            } elseif( 'to_date' == $request ) {
                
                $whereConstrint .= " AND oo.date_added <= '$constraint'";
            }
        }
        
        return $this->db->query( "SELECT so.*, oo.date_added as order_date FROM " . DB_PREFIX . "sheepla_order so, " . DB_PREFIX . "order oo WHERE so.order_id = oo.order_id $whereConstrint ORDER BY so.id DESC $limit " )->rows;
    }
    
    
    public function getSheeplaOrder( $id ) {
        
        $sheeplaOrder = $this->db->query( "SELECT * FROM " . DB_PREFIX . "sheepla_order WHERE order_id = $id" );
        
        if( 0 == $sheeplaOrder->num_rows )
            return false;
        
        return $sheeplaOrder->row;
    }
    
    
    public function getShopOrder( $id ) {
        
        $order = $this->db->query( "SELECT * FROM " . DB_PREFIX . "order WHERE order_id = $id" );
        
        if( 0 == $order->num_rows )
            return false;
        
        return $order->row;
    }
    
    
    /**
     * Getting sheepla shipping
     * 
     * @param int|null $shippingId id to search if null pass then method return all shippings
     * @return array
     */
    public function getShippingMethods( $shippingId = null) {
        
        if( null == $shippingId ) {
            
            $res = $this->db->query( "SELECT * from " . DB_PREFIX . "sheepla ORDER BY queue ASC" );
            return $res->rows;
            
        } else {
            
            $res = $this->db->query( "SELECT * from " . DB_PREFIX . "sheepla WHERE sheepla_template_id = $shippingId" );
            
            if( $res->num_rows )
                return $res->row;
            return null;
        }
    }
}