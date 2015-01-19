<?php

class ControllerShippingSheepla extends Controller {
    
    public function confirmorder() {
        
        // saving order id
        $orderId = $this->session->data['order_id'];
        
        $sheeplaData = array();
        foreach( (array)$this->request->get as $getRowName => $getRow ) {
            
            if(preg_match( '/^sheepla/' , $getRowName ) ) {
                
                $sheeplaData[ $getRowName ] = $getRow;
            }
        }
        
        
        $sheeplaAdditional = serialize( $sheeplaData );
        
        
        
        $this->load->model( 'shipping/sheepla' );
        
        
        
        try {
            
            
            if( $this->cart->hasShipping() ) {
                
                $this->model_shipping_sheepla->addOrder( $orderId , $sheeplaAdditional );
            }
            
        } catch( Exception $e ) {
            
            $this->load->library( 'sheeplaLog' );
            $SheeplaLog = new SheeplaLog();
            
            $SheeplaLog->error( __METHOD__ , "Fail on inserting order ($orderId) to sheepa table. Exception message: " . $e->getMessage() , $this );
        }
        
        return $this->forward( 'payment/cod/confirm' );
    }
    
    
    public function gateway( $forceSyncId = null ) {
        
        $sheeplaAdminKey = $this->config->get( 'sheepla_admin_key' );
        $sheeplaApiUrl = $this->config->get( 'sheepla_api_url' );
        
        
        
        $this->load->model( 'shipping/sheepla' );
        $this->load->library( 'sheeplaLog' );
        $this->load->library( 'opencartSheepla' );

        $SheeplaLog = new SheeplaLog();




        // checking connection with sheepla
        $sheeplaClient = new OpencartSheepla();

        if( !$sheeplaClient->checkAccount( $sheeplaAdminKey , $sheeplaApiUrl ) ) {

            $SheeplaLog->error( __METHOD__ , "Sheepla account is not configures" , $this );
            return;
        }





        $sheeplaClient->init( $sheeplaAdminKey , $sheeplaApiUrl );


        try {

            $orders = $this->model_shipping_sheepla->getOrdersToSync( 2 , 10 , $forceSyncId );

        } catch( Exception $e ) {

            $orders = array();
            $SheeplaLog->critical( __METHOD__ , "Unable to get orders to synchronize. Exception message: " . $e->getMessage() , $this );
            return;
        }


        $syncRequest = $sheeplaClient->createSynchronizeRequest();

        
        
        $convertWeight = false;
        $convertLength = false;
        if( 'g' === $this->weight->getUnit( 2 ) ) {
            
            $convertWeight = true;
        }
        
        if( 'mm' === $this->length->getUnit( 2 ) ) {
            
            $convertLength = true;
        }
        
        
        foreach( $orders as $order ) {
            
            foreach( $order[ 'order_items' ] as $orderItemKey => $orderItem ) {
                
                if( $convertWeight ) {
                    
                    if( 2 !== $orderItem[ 'weight_class_id' ] ) {
                        
                        $orderItem[ 'weight' ] = $this->weight->convert(
                            $orderItem[ 'weight' ],
                            $orderItem[ 'weight_class_id' ],
                            2
                        );
                    }
                }
                
                if( $convertLength ) {
                    
                    if( 2 !== $orderItem[ 'length_class_id' ] ) {
                        
                        $orderItem[ 'length' ] = $this->weight->convert(
                            $orderItem[ 'length' ],
                            $orderItem[ 'length_class_id' ],
                            2
                        );
                        
                        $orderItem[ 'width' ] = $this->weight->convert(
                            $orderItem[ 'width' ],
                            $orderItem[ 'length_class_id' ],
                            2
                        );
                        
                        $orderItem[ 'height' ] = $this->weight->convert(
                            $orderItem[ 'height' ],
                            $orderItem[ 'length_class_id' ],
                            2
                        );
                    }
                }
                
                $order[ 'order_items' ][ $orderItemKey ] = $orderItem;
            }
            
            
            $syncRequest->addOrder( $order );
        }

        try {

            $syncResult = $sheeplaClient->synchronize( $syncRequest );

            $success = $syncResult[ 1 ];

            foreach( $success as $shopOrderId => $sheeplaOrderId ) {

                $this->model_shipping_sheepla->markAsSynchronized( $shopOrderId , $sheeplaOrderId );
            }


            $errors = $syncResult[ 0 ];
            if( count($errors ) ) {

                foreach( $errors as $orderId => $error ) {

                    $SheeplaLog->critical( __METHOD__ , "Unable to synchrnonize order $orderId with Sheepla. Response error message " . print_r( $error , true ) , $this );

                    try {

                        $this->model_shipping_sheepla->incrementFailTryoutCounter( $orderId );

                    } catch (Exception $e ) {}
                }
            }

        } catch (Exception $e ) {}
    }
    
    
    
    public function readLog() {
        $this->secure();
        
        
        if( !is_file( DIR_LOGS . 'sheepla.log') ) {
            
            exit( "FIle " . DIR_LOGS . "sheepla.log does'nt exists" );
        } 
        
        exit( nl2br( file_get_contents( DIR_LOGS . "sheepla.log" ) ) );
    }
    
    
    public function sheeplaTable() {
        $this->secure();
        
        $response = '';
        
        $this->load->model( 'shipping/sheepla' );
        
        try {
            
            $where = array();
            $limit = 0;
            
            //limit/{int}, id/{int}, since_id/{int}, since_date/{'2013-01-12' | 1300000000 }, to_date/{'2013-01-12' | 1300000000 }
            if( isset( $this->request->get[ 'limit' ] ) && 0 < (int)$this->request->get[ 'limit' ] ) {
                
                $limit = (int)$this->request->get[ 'limit' ];
            }
            
            if( isset( $this->request->get[ 'since_id' ] ) && 0 < (int)$this->request->get[ 'since_id' ] ) {
                
                $where[ 'since_id' ] = (int)$this->request->get[ 'since_id' ];
            }
            
            if( isset( $this->request->get[ 'id' ] ) && 0 < (int)$this->request->get[ 'id' ] ) {
                
                $where[ 'id' ] = (int)$this->request->get[ 'id' ];
            }
            
            if( isset( $this->request->get[ 'since_date' ] ) ) {
                
                if( preg_match( '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/' , $this->request->get[ 'since_date' ] ) ) {
                    
                    $where[ 'since_date' ] = $this->request->get[ 'since_date' ];
                    
                } elseif( preg_match( '/^[0-9]{10}$/' , $this->request->get[ 'since_date' ] ) ) {
                    
                    $where[ 'since_date' ] = date( 'Y-m-d' , $this->request->get[ 'since_date' ] );
                }
            }
            
            if( isset( $this->request->get[ 'to_date' ] ) ) {
                
                if( preg_match( '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/' , $this->request->get[ 'to_date' ] ) ) {
                    
                    $where[ 'to_date' ] = $this->request->get[ 'to_date' ];
                    
                } elseif( preg_match( '/^[0-9]{10}$/' , $this->request->get[ 'to_date' ] ) ) {
                    
                    $where[ 'to_date' ] = date( 'Y-m-d' , $this->request->get[ 'to_date' ] );
                }
            }
            
            
            
            $sheeplaTableContent = $this->model_shipping_sheepla->getSheeplaTable( $where , $limit );
            
            $response .= "<html><head><meta http-equiv='content-type' content='text\html;charset=utf-8'>{$this->getTableCss()}</head><body>
                            <div class='CSSTableGenerator'>
                            <table>
                                <tr>
                                    <td>Id</td>
                                    <td>Shop order id</td>
                                    <td>Sheepla order id</td>
                                    <td>Additional</td>
                                    <td>Synchronized</td>
                                    <td>Order date</td>
                                    <td>Synchronized date</td>
                                    <td>Synchronization fail attempt</td>
                                    <td>Wait until</td>
                                </tr>";
            
            
            foreach( $sheeplaTableContent as $sheeplaTableRow ) {
                
                $response .=  "<tr>
                        <td>{$sheeplaTableRow[ 'id' ]}</td>
                        <td>{$sheeplaTableRow[ 'order_id' ]}</td>
                        <td>{$sheeplaTableRow[ 'sheepla_order_id' ]}</td>
                        <td>{$sheeplaTableRow[ 'additional' ]}</td>
                        <td>{$sheeplaTableRow[ 'synchronized' ]}</td>
                        <td>{$sheeplaTableRow[ 'order_date' ]}</td>
                        <td>{$sheeplaTableRow[ 'synchronized_date' ]}</td>
                        <td>{$sheeplaTableRow[ 'sync_fail_attempt' ]}</td>
                        <td>{$sheeplaTableRow[ 'wait_until' ]}</td>
                      </tr>";
            }
            
            
            $response .= "</table></div></body></html>";
            
        } catch( Exception $e ) {
            
            $response = "En exception occured on read sheepla table. " . $e->getMessage();
        }
        
        exit( $response );
    }
    
    
    public function forceSyncOrder() {
        $this->secure();
        
        
        $this->load->model( 'shipping/sheepla' );
        
        
        try {
            
            if( !isset( $this->request->get[ 'id' ] ) ||
                !( $order = $this->model_shipping_sheepla->getSheeplaOrder( $this->request->get[ 'id' ] ) ) ) {

                exit( "No order with given id( shop_id ) found in sheepla table" );

            } elseif( $order[ 'synchronized' ] == 1 ) {

                exit( "This order is allready synchronized" );
            }
        
        } catch (Exception $e) {
            
            exit( "En exception occured. " . $e->getMessage() );
        }
        
        
        $this->gateway( $order[ 'order_id' ] );
        
        
        exit( 'Done' );
    }
    
    
    public function addToSheeplaTable() {
        $this->secure();
        
        
        if( !isset( $this->request->get[ 'id' ] ) ||
            1 > (int)$this->request->get[ 'id' ] ) {
            
            exit( "No id param" );
        }
        
        
        if( isset( $this->request->get[ 'additional' ] ) &&
            !unserialize( trim( $this->request->get[ 'additional' ] ) ) ) {
            
            exit( "Additional parameter must be en serialized PHP array!" );
            
        } elseif( isset( $this->request->get[ 'additional' ] ) ) {
            
            $additional = $this->request->get[ 'additional' ];
                    
        } else {
            
            $additional = serialize( array() );
        }
        
        
        $id = (int)$this->request->get[ 'id' ];
        
        $this->load->model( 'shipping/sheepla' );
        
        try {
            
            if( $this->model_shipping_sheepla->getSheeplaOrder( $id ) ) {

                exit( "Order with this is is allready in sheepla table" );
            }
            
        } catch( Exception $e ) {
            
            exit( "En exception occured. " . $e->getMessage() );
        }
        
        try {
            
            if( !( $order = $this->model_shipping_sheepla->getShopOrder( $id ) ) ) {

                exit( "Order with this id is not found in shop database" );
            }
            
        } catch( Exception $e ) {
            
            exit( "En exception occured. " . $e->getMessage() );
        }
        
        
        try {
            
            if( !$this->model_shipping_sheepla->addOrder( $id , $additional) ) {

                exit( "Inserting to sheepla table fail" );
            }
            
        } catch( Exception $e ) {
            
            exit( "En exception occured. " . $e->getMessage() );
        }
        
        
        exit( 'Done' );
    }
    
    
    public function moduleInfo() {
        $this->secure();
        
        ob_start();
        phpinfo();
        $phpInfo = ob_get_clean();
        
        exit("{$this->getTableCss()}<center><div class='CSSTableGenerator' style='width:600px;margin-bottom: 50px;'>
                <table width='100%'>
                    <tr>
                        <td colspan='2'>
                            Module Info
                        </td>
                    </tr>
                    <tr>
                        <td>Opencart version</td>
                        <td>" . VERSION .  "</td>
                    </tr>
                    <tr>
                        <td>Sheepla version</td>
                        <td>" . SHEEPLA_VERSION .  "</td>
                    </tr>
                    <tr>
                        <td>Admin key</td>
                        <td>" . $this->config->get( 'sheepla_admin_key' ) .  "</td>
                    </tr>
                    <tr>
                        <td>Public key</td>
                        <td>" . $this->config->get( 'sheepla_public_key' ) .  "</td>
                    </tr>
                    <tr>
                        <td>Api url</td>
                        <td>" . $this->config->get( 'sheepla_api_url' ) .  "</td>
                    </tr>
                    <tr>
                        <td>Widget api url js</td>
                        <td>" . $this->config->get( 'sheepla_wapi_url_js' ) .  "</td>
                    </tr>
                    <tr>
                        <td>Widget api url css</td>
                        <td>" . $this->config->get( 'sheepla_wapi_url_css' ) .  "</td>
                    </tr>
                    <tr>
                        <td>Dynamic pricing</td>
                        <td>" . (int)$this->config->get( 'sheepla_dynamic_pricing' ) .  "</td>
                    </tr>
                    <tr>
                        <td>Is module enabled</td>
                        <td>" . (int)$this->config->get( 'sheepla_status' ) .  "</td>
                    </tr>
                </table>
            </div></center>
        " . $phpInfo );
    }
    
    
    protected function secure() {
        
        if( !isset( $this->request->get[ 'key' ] ) ||
             $this->request->get[ 'key' ] !== $this->config->get( 'sheepla_admin_key' ) ) {
            
            header( 'Location: /' );
        }
    }
    
    
    public function specials() {
        
        /*
            'pl' => 1045,
            'de' => 1031,
            'en' => 1033,
            'ru' => 1049
         */
        $this->load->library( 'opencartSheepla' );
        $this->data[ 'culture_id' ] = (int)OpencartSheepla::translateLangSc( $this->config->get( 'config_admin_language' ) );
        
        
        // getting shipping address
        $this->load->model('account/address');

        if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {			
            
            $shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);	
            
        } elseif (isset($this->session->data['guest'])) {
            
           $shipping_address = $this->session->data['guest']['shipping'];
        }
        
        
        $this->data[ 'city' ] = isset( $shipping_address[ 'city' ] ) ? $shipping_address[ 'city' ] : null;
        
        
        $this->data[ 'sheepla_template_id' ] = (int)@$this->request->post[ 'sheepla_template_id' ];
        
        $this->data[ 'checkoutPricingSessionId' ] = isset( $this->session->data[ 'checkoutPricingSessionId' ] ) ? $this->session->data[ 'checkoutPricingSessionId' ] : '';
        
        $this->template = 'default/template/shipping/sheepla_special.tpl';
        
        
        exit(json_encode(array(
            'status' => 1,
            'html' => $this->render()
        )));
    }
    
    
    public function script() {
        
        $this->load->library( 'sheepla' );
        
        
        $api_key  = $this->config->get( 'sheepla_public_key' );
        
        
        $wapi_js  = $this->config->get( 'sheepla_wapi_url_js' );
        $wapi_css = $this->config->get( 'sheepla_wapi_url_css' );
        $this->load->library( 'opencartSheepla' );
        $culture_id = (int)OpencartSheepla::translateLangSc( $this->config->get( 'config_language' ) );
        
        
        $script = "  
        $( document ).ready( function() {
            
            $( 'body' ).append( '<link rel=\"stylesheet\" href=\"{$wapi_css}\" />' );
            
            $.getScript( '{$wapi_js}' , function() {
                
                sheepla.init({
                    apikey : '{$api_key}',
                    cultureId : '{$culture_id}'
                });
                
                g_sheeplaScripts = true;
            });
            
            /** checking is ajax request is getting shipping methods if yes then set variable to inform 'complete' callback to load sheepla specials **/
            var sheepla_autoLoadSpecials = false;
            
            $.ajaxSetup({
                beforeSend: function( xhr , settings ) {
                    
                    var re = /payment\/.*\/confirm/;
                    
                    
                    if( re.test( this.url ) ) {
                        
                        try {

                            var sheeplaVars = $( '[name^=\"sheepla\"]' );

                            var sheeplaGetData = '';
                            for( var i = 0; i < sheeplaVars.length; i++ ) {

                                sheeplaGetData += '&' + ( sheeplaVars ).eq( i ).attr( 'name' ) + '=' + $( sheeplaVars ).eq( i ).val();
                            }

                        } catch( e ) {}

                        settings.url = 'index.php?route=shipping/sheepla/confirmorder' + sheeplaGetData;

                    } else if( this.url.match( /index\.php\?route=checkout\/payment_method/ )) {

                        if( 'undefined' !== typeof sheepla ) {

                            if( !sheepla.valid_special( false , true ) )
                                return false;
                        }
                        
                    } else if( this.url.match( /checkout\/shipping_method$/ ) ) {
                        
                        sheepla_autoLoadSpecials = true;
                    }
                },
                complete: function() {
                
                    if( sheepla_autoLoadSpecials ) {
                        
                        var checkedInput = $( 'input[type=\"radio\"][name=\"shipping_method\"]:checked' );
                        
                        if( 1 == checkedInput.length ) {
                            
                            sheepla_loadSpecials( checkedInput );
                            sheepla_autoLoadSpecials = false;
                        }
                    }
                }
            });
            
            
            function sheepla_lockInputs( target ) {

                $( target ).closest( 'table' ).find( 'input' ).attr( 'disabled' , 'disabled' );
            }
            
            
            function sheepla_unlockInputs( target ) {

                $( target ).closest( 'table' ).find( 'input' ).removeAttr( 'disabled' );
            }
            
            
            function sheepla_loadSpecials( targetRadio ) {
                
                var radioButton = targetRadio;

                    if( $( radioButton ).attr( 'id' ).match( /^sheepla\.[0-9]+$/ ) ) {

                        var data = $( radioButton ).attr( 'id' ).split( '.' );
                        var sheeplaTemplateId = data[ 1 ];


                        $.ajax({
                            url: 'index.php?route=shipping/sheepla/specials',
                            type: 'post',
                            data: {
                                sheepla_template_id: sheeplaTemplateId
                            },
                            beforeSend: function() {

                                $( '.sheepla_workarea' ).remove();
                                sheepla_lockInputs( radioButton );
                            },
                            success: function( data ) {

                                try {

                                    data = $.trim( data );
                                    data = $.parseJSON( data );

                                    if( undefined !== typeof data.status &&
                                        1 === data.status ) {

                                        $( radioButton ).parent( 'td' ).next().append( '<div class=\"sheepla_workarea\">' + data.html + '</div>' );
                                    }

                                 } catch( e ) {}
                            },
                            complete: function() {

                                sheepla_unlockInputs( radioButton );
                            }
                        });

                    } else {

                        $( '.sheepla_workarea' ).remove();
                    }
            }

            $( document ).on( 'change' , '[name=\"shipping_method\"]' , function() {

                sheepla_loadSpecials( this );
            });
        });";
        
        exit( $script );
    }
    
    protected function getTableCss() {
        
        return "<style>
        .CSSTableGenerator {display:inline-block;margin:0px;padding:0px;border:1px solid #000000;-moz-border-radius-bottomleft:0px;-webkit-border-bottom-left-radius:0px;border-bottom-left-radius:0px;-moz-border-radius-bottomright:0px;-webkit-border-bottom-right-radius:0px;border-bottom-right-radius:0px;-moz-border-radius-topright:0px;-webkit-border-top-right-radius:0px;border-top-right-radius:0px;-moz-border-radius-topleft:0px;-webkit-border-top-left-radius:0px;border-top-left-radius:0px;}
        .CSSTableGenerator table{border-collapse: collapse;border-spacing: 0;px;}
        .CSSTableGenerator tr:last-child td:last-child {-moz-border-radius-bottomright:0px;-webkit-border-bottom-right-radius:0px;border-bottom-right-radius:0px;}
        .CSSTableGenerator table tr:first-child td:first-child {-moz-border-radius-topleft:0px;-webkit-border-top-left-radius:0px;border-top-left-radius:0px;}
        .CSSTableGenerator table tr:first-child td:last-child {-moz-border-radius-topright:0px;-webkit-border-top-right-radius:0px;border-top-right-radius:0px;}
        .CSSTableGenerator tr:last-child td:first-child{-moz-border-radius-bottomleft:0px;-webkit-border-bottom-left-radius:0px;border-bottom-left-radius:0px;}
        .CSSTableGenerator tr:hover td{}
        .CSSTableGenerator tr:nth-child(odd){ background-color:#ededed; }
        .CSSTableGenerator tr:nth-child(even) { background-color:#ffffff; }.CSSTableGenerator td{vertical-align:middle;border:1px solid #000000;border-width:0px 1px 1px 0px;text-align:center;padding:7px;font-size:12px;font-family:Arial;font-weight:normal;color:#000000;}
        .CSSTableGenerator tr:last-child td{border-width:0px 1px 0px 0px;}
        .CSSTableGenerator tr td:last-child{border-width:0px 0px 1px 0px;}
        .CSSTableGenerator tr:last-child td:last-child{border-width:0px 0px 0px 0px;}
        .CSSTableGenerator tr:first-child td{background:-o-linear-gradient(bottom, #357fff 5%, #357fff 100%);background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #357fff), color-stop(1, #357fff) );background:-moz-linear-gradient( center top, #357fff 5%, #357fff 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\"#357fff\", endColorstr=\"#357fff\"); background: -o-linear-gradient(top,#357fff,357fff);background-color:#357fff;border:0px solid #000000;text-align:center;border-width:0px 0px 1px 1px;font-size:14px;font-family:Arial;font-weight:bold;color:#ffffff;}
        .CSSTableGenerator tr:first-child:hover td{background:-o-linear-gradient(bottom, #357fff 5%, #357fff 100%);background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #357fff), color-stop(1, #357fff) );background:-moz-linear-gradient( center top, #357fff 5%, #357fff 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\"#357fff\", endColorstr=\"#357fff\");	background: -o-linear-gradient(top,#357fff,357fff);background-color:#357fff;}
        .CSSTableGenerator tr:first-child td:first-child{border-width:0px 0px 1px 0px;}
        .CSSTableGenerator tr:first-child td:last-child{border-width:0px 0px 1px 1px;}
        </style>";
    }
}
