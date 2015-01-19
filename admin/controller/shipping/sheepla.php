<?php



class ControllerShippingSheepla extends Controller {
    
    protected $errorList = array(),
              $sheeplaClient;
    
    public function index() {
        
        $this->language->load( 'shipping/sheepla' );
        $this->document->setTitle( $this->language->get( 'heading_title' ) );
        
        
        $this->load->model( 'setting/setting' );
        $this->load->model( 'shipping/sheepla' );
        
        
        $this->data[ 'url_helper' ] = $this->url;
        $this->data[ 'token' ] = $this->session->data[ 'token' ];
        
        
        
        $this->data[ 'error_warning' ] = null;
        
        
        
        if( ( $this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() ) {
            
            $this->model_setting_setting->editSetting('sheepla' , $this->request->post );		

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        
        
        $shipmentMetohods = $this->model_shipping_sheepla->getShippingMethods();
        $this->data[ 'sheepla_shipping_methods' ] = $shipmentMetohods;
        
        
        
        
        if ( !isset( $this->data[ 'sheepla_status' ] ) ) {
            
           $this->data[ 'sheepla_status' ] = $this->config->get( 'sheepla_status' );
        }
        
        if ( !isset( $this->data[ 'sheepla_admin_key' ] ) ) {
            
           $this->data[ 'sheepla_admin_key' ] = $this->config->get( 'sheepla_admin_key' );
        }
        
        if ( !isset( $this->data[ 'sheepla_public_key' ] ) ) {
            
           $this->data[ 'sheepla_public_key' ] = $this->config->get( 'sheepla_public_key' );
        }
        
        if ( !isset( $this->data[ 'sheepla_api_url' ] ) ) {
            
           $this->data[ 'sheepla_api_url' ] = $this->config->get( 'sheepla_api_url' );
        }
        
        if ( !isset( $this->data[ 'sheepla_wapi_url_js' ] ) ) {
            
           $this->data[ 'sheepla_wapi_url_js' ] = $this->config->get( 'sheepla_wapi_url_js' );
        }
        
        if ( !isset( $this->data[ 'sheepla_wapi_url_css' ] ) ) {
            
           $this->data[ 'sheepla_wapi_url_css' ] = $this->config->get( 'sheepla_wapi_url_css' );
        }
        
        if ( !isset( $this->data[ 'sheepla_dynamic_pricing' ] ) ) {
            
           $this->data[ 'sheepla_dynamic_pricing' ] = $this->config->get( 'sheepla_dynamic_pricing' );
        }
        
        
        
        
        
        try {
            
            $shippingsList = $this->getSheeplaClient()->getTemplates();
            
        } catch( Exception $e ) {
            
            $shippingsList = array();
            
            $this->load->library( 'sheeplaLog' );
            $SheeplaLog = new SheeplaLog();
            $SheeplaLog->error( __METHOD__ , $e->getMessage() , $this );
        }
        
        $shippings = array();
        foreach( $shippingsList as $shipping ) {
            
            $shippings[ $shipping[ 'id' ] ] = $shipping[ 'name' ];
        }
        $this->data[ 'sheepla_shippings' ] = $shippings;
        
        
        
        
        
        
        
        
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_shipping'),
            'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('shipping/sheepla', 'token=' . $this->session->data['token'], 'SSL' ),
            'separator' => ' :: '
        );
        
        
        
        
        
        $this->load->library( 'opencartSheepla' );
        $this->data[ 'culture_id' ] = (int)OpencartSheepla::translateLangSc( $this->config->get( 'config_admin_language' ) );
        
        
        
        $this->data[ 'main_form_action' ] = $this->url->link( 'shipping/sheepla' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'add_url' ] = $this->url->link( 'shipping/sheepla/add' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'edit_url' ] = $this->url->link( 'shipping/sheepla/edit' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'remove_url' ] = $this->url->link( 'shipping/sheepla/remove' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'shippments_url' ] = $this->url->link( 'shipping/sheepla/shippments' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'manage_payments_url' ] = $this->url->link( 'shipping/sheepla/payments' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'active_url' ] = $this->url->link( 'shipping/sheepla/active' , 'token=' . $this->session->data['token'] , 'SSL' );
        
        $this->data[ 'shipping_move_up' ] = $this->language->get( 'shipping_move_up' );
        $this->data[ 'shipping_move_down' ] = $this->language->get( 'shipping_move_down' );
        $this->data[ 'shipping_method_edit' ] = $this->language->get( 'shipping_method_edit' );
        $this->data[ 'shipping_method_add' ] = $this->language->get( 'shipping_method_add' );
        $this->data[ 'shipping_methods' ] = $this->language->get( 'shipping_methods' );
        $this->data[ 'heading_title' ] = $this->language->get( 'heading_title' );
        $this->data[ 'button_save' ] = $this->language->get( 'button_save' );
        $this->data[ 'button_cancel' ] = $this->language->get( 'button_cancel' );
        $this->data['text_enabled'] = $this->language->get('text_enabled');
	$this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['entry_status'] = $this->language->get('entry_status');
        
        
        $this->data[ 'cancel' ] = $this->url->link( 'extension/shipping' , 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'shipping_method_add_url' ] = $this->url->link( 'shipping/sheepla', 'token=' . $this->session->data['token'] , 'SSL' );
        $this->data[ 'shipping_method_edit_url' ] = $this->url->link( 'shipping/sheepla', 'token=' . $this->session->data['token'] , 'SSL' );
        
        
        
        $this->data[ 'translator' ] = clone $this->language;
        $language = $this->language;
        
        $this->data[ 'translate' ] = function( $name ) use ( $language ) {
            
            return $language->get( $name );
        };
        
        $this->template = 'shipping/sheepla.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        
        $this->response->setOutput( $this->render() );
    }
    
    
    /*
     * Getting only shippings list. Using after changinag sgipping method during ajax
     */
    public function shippments() {
        
        $this->language->load( 'shipping/sheepla' );
        $this->load->model( 'shipping/sheepla' );
        
        $this->data[ 'language' ] = $this->language;
        
        
        
        // checking post data
        if( isset( $this->request->post[ 'action' ] ) ) {
            
            if( 'moveUp' == $this->request->post[ 'action' ] ) {
                
                $id = $this->request->post[ 'id' ];
                
                if( $this->model_shipping_sheepla->moveShipmentUp( $id ) ) {
                    
                    $this->data[ 'shippings_message' ] = $this->language->get( 'sheepla_shippings_queue_changed' );
                    
                } else {
                    
                    $this->data[ 'shippings_error' ] = $this->language->get( 'sheepla_shippings_queue_not_changed' );
                }
                
            } elseif( 'moveDown' == $this->request->post[ 'action' ] ) {
                
                $id = $this->request->post[ 'id' ];
                
                if( $this->model_shipping_sheepla->moveShipmentDown( $id ) ) {
                    
                    $this->data[ 'shippings_message' ] = $this->language->get( 'sheepla_shippings_queue_changed' );
                    
                } else {
                    
                    $this->data[ 'shippings_error' ] = $this->language->get( 'sheepla_shippings_queue_not_changed' );
                }
            }
        }
        
        
        
        try {
            
            $shippingsList = $this->getSheeplaClient()->getTemplates();
            
        } catch( Exception $e ) {
            
            $shippingsList = array();
            
            $this->load->library( 'sheeplaLog' );
            $SheeplaLog = new SheeplaLog();
            
            $SheeplaLog->error( __METHOD__ , $e->getMessage() , $this );
        }
        
        
        
        $shippings = array();
        foreach( $shippingsList as $shipping ) {
            
            $shippings[ $shipping[ 'id' ] ] = $shipping[ 'name' ];
        }
        $this->data[ 'sheepla_shippings' ] = $shippings;
        
        
        $shipmentMetohods = $this->model_shipping_sheepla->getShippingMethods();
        $this->data[ 'sheepla_shipping_methods' ] = $shipmentMetohods;
        $this->data[ 'shippings_only' ] = 1;
        
        $this->template = 'shipping/sheepla.tpl';
        
        exit(json_encode(array(
            'status' => 1,
            'html' => $this->render()
        )));
    }
    
    
    public function add() {
        
        $this->language->load( 'shipping/sheepla' );
        
        
        $status = 1;
        
        
        try {
            
            $shippingsList = $this->getSheeplaClient()->getTemplates();
            
        } catch( Exception $e ) {
            
            $shippingsList = array();
            
            $this->load->library( 'sheeplaLog' );
            $SheeplaLog = new SheeplaLog();
            
            $SheeplaLog->error( __METHOD__ , $e->getMessage() , $this );
        }
        
        
        
        $this->getPayments();
        
        
        $shippings = array();
        foreach( $shippingsList as $shipping ) {
            
            $shippings[ $shipping[ 'id' ] ] = $shipping[ 'name' ];
        }
        $this->data[ 'sheepla_shippings' ] = $shippings;
        
        
        $this->load->model( 'shipping/sheepla' );
        
        
        $currencies = array();
        foreach( $this->model_shipping_sheepla->getShopCurrencies() as $currencyData ) {
            
            $currencies[ $currencyData[ 'currency_id' ] ] = array( 'code' => $currencyData[ 'code' ] , 'value' => $currencyData[ 'value' ] ); //, 'selected' => $this->currency->getCode() == $currencyData[ 'code' ] ? true : false );
        }
        
        
        $this->data[ 'currencies' ] = $currencies;
        $this->data[ 'currency_id' ] = $this->currency->getId();
        $this->data[ 'selected_payments' ] = array();
        
        
        
        
        
        if( isset( $this->request->post[ 'save_shippment' ] ) ) {
            
            if( $this->validateShipping() ) {
                
                $this->load->model( 'shipping/sheepla' );
                
                $shippingMethodData = array(
                    'shipping_name' => $this->data[ 'shipping_name' ],
                    'shipping_price' => $this->data[ 'shipping_price' ] / $currencies[ $this->data[ 'currency_id' ] ][ 'value' ],
                    'sheepla_template_id' => $this->data[ 'sheepla_template_id' ],
                    'payments' => implode( ',' , $this->data[ 'selected_payments' ] )
                );
                
                if( $this->model_shipping_sheepla->saveShipment( $shippingMethodData ) ) {
                    
                    $this->data[ 'success' ] = $this->language->get( 'shipping_method_saved' );
                    
                } else {
                    
                    $this->data[ 'errors' ] = array( 'save' => $this->language->get( 'error_shipping_method_not_saved' ) );
                }
                
            } else {
                
                
                $this->data[ 'errors' ] = $this->errorList;
                $status = 0;
            } 
        }
        
        
        
        $this->data[ 'language' ] = $this->language;
        
        $this->template = 'shipping/sheepla_add_edit.tpl';
        
        exit(json_encode(array(
            'status' => $status,
            'html' => $this->render()
        )));
    }
    
    
    
    
    public function edit() {
        
        $this->language->load( 'shipping/sheepla' );
        
        $status = 1;
        
        $this->load->model( 'setting/setting' );
        $this->load->model( 'shipping/sheepla' );
        
        
        $this->getPayments();
        
        
        try {
            
            $shippingsList = $this->getSheeplaClient()->getTemplates();
            
        } catch( Exception $e ) {
            
            $shippingsList = array();
            
            $this->load->library( 'sheeplaLog' );
            $SheeplaLog = new SheeplaLog();
            
            $SheeplaLog->error( __METHOD__ , $e->getMessage() , $this );
        }
        
        
        
        $shippings = array();
        foreach( $shippingsList as $shipping ) {
            
            $shippings[ $shipping[ 'id' ] ] = $shipping[ 'name' ];
        }
        $this->data[ 'sheepla_shippings' ] = $shippings;
        
        
        $currencies = array();
        foreach( $this->model_shipping_sheepla->getShopCurrencies() as $currencyData ) {
            
            $currencies[ $currencyData[ 'currency_id' ] ] = array( 'code' => $currencyData[ 'code' ] , 'value' => $currencyData[ 'value' ] ); //, 'selected' => $this->currency->getCode() == $currencyData[ 'code' ] ? true : false );
        }
        $this->data[ 'currencies' ] = $currencies;
        $this->data[ 'currency_id' ] = $this->currency->getId();
        
        
        if( 0 < (int)( $shippingId = $this->request->get[ 'id' ] ) ) {
            
            if( isset( $this->request->post[ 'save_shippment' ] ) ) {

                if( $this->validateShipping() ) {

                    $this->load->model( 'shipping/sheepla' );
                    
                    
                    $shippingMethodData = array(
                        'shipping_name' => $this->data[ 'shipping_name' ],
                        'shipping_price' => $this->data[ 'shipping_price' ] / $currencies[ $this->data[ 'currency_id' ] ][ 'value' ],
                        'sheepla_template_id' => $this->data[ 'sheepla_template_id' ],
                        'payments' => implode( ',' , $this->data[ 'selected_payments' ] )
                    );

                    if( $this->model_shipping_sheepla->saveShipment( $shippingMethodData , $shippingId ) ) {

                        $this->data[ 'success' ] = $this->language->get( 'shipping_method_saved' );

                    } else {

                        $this->data[ 'errors' ] = array( 'save' => $this->language->get( 'error_shipping_method_not_saved' ) );
                    }

                } else {


                    $this->data[ 'errors' ] = $this->errorList;
                    $status = 0;
                } 

            } else {

                $this->load->model( 'shipping/sheepla' );
                $shipping = $this->model_shipping_sheepla->getShippingMethods( $shippingId );
                
                
                $selectedPayments = explode( ',' , $shipping[ 'payments' ] );
                
                $this->data[ 'selected_payments' ] = $selectedPayments;
                $this->data[ 'shipping_name' ] = $shipping[ 'name' ];
                $this->data[ 'shipping_price' ] = round( $shipping[ 'price' ] * $currencies[ $this->data[ 'currency_id' ] ][ 'value' ] , 2 );
                $this->data[ 'sheepla_template_id' ] = $shipping[ 'sheepla_template_id' ];
            }
        }
        
        
        $this->data[ 'language' ] = $this->language;
        
        $this->template = 'shipping/sheepla_add_edit.tpl';
        
        exit(json_encode(array(
            'status' => $status,
            'html' => $this->render()
        )));
    }
    
    
    /**
     * Getting installed shipping extensions and pass theme to the view
     */
    protected function getPayments() {

        $this->load->model( 'shipping/sheepla' );
        $this->load->model( 'setting/extension' );
        $this->language->load( 'shipping/sheepla' );
        
        
        $payments = array();
        
        
        foreach ( $this->model_setting_extension->getInstalled( 'payment' ) as $paymentCode ) {

            $this->language->load( "payment/$paymentCode" );

            if($this->config->get( $paymentCode . '_status' ) ) {

                $payments[ $paymentCode ] = $this->language->get( 'heading_title' );
            }
        }



        $this->data[ 'payments' ] = $payments;
    }
    
    
    public function active() {
        $status = 1;
        
        
        if( isset( $this->request->post[ 'id' ] ) &&
            isset( $this->request->post[ 'active' ] ) ) {
            
            
            $id = (int)$this->request->post[ 'id' ];
            $value = (int)$this->request->post[ 'active' ];

            $this->load->model( 'shipping/sheepla' );

            try {

                $this->model_shipping_sheepla->changeShippingActiveState( $id , $value );

            } catch( Exception $e ) {

                $status = 0;
            }
        }
        
        exit(json_encode(array(
            'status' => $status
        )));
    }
    
    
    public function remove() {
        
        $this->language->load( 'shipping/sheepla' );
        $status = 1;
        $responseHtml = "";
        $class = 0;
        
        if( 0 < (int)( $sheepmentId = $this->request->get[ 'id' ] ) ) {
            
            if( isset( $this->request->post[ 'confirmed' ] ) ) {
                
                $this->load->model( 'shipping/sheepla' );
                if( $this->model_shipping_sheepla->removeShippment( $sheepmentId ) ) {
                    
                    $responseHtml = $this->language->get( 'shippment_removed' );
                    $class = 'success';
                    
                } else {
                    
                    $responseHtml = $this->language->get( 'error_shippment_not_removed' );
                    $status = 0;
                    $class = 'warning';
                }
                
            } else {
                
                $responseHtml = $this->language->get( 'remove_shippment_confirm' );
            }   
        }
        
        exit(json_encode(array(
            'status' => $status,
            'html' => "<br/><span class='$class'>" . $responseHtml . "</span>"
        )));
    }
    
    protected function validate() {
        
        $this->errorList = array();
        
        
        if( isset( $this->request->post[ 'sheepla_admin_key' ] ) &&
            strlen( $this->request->post[ 'sheepla_admin_key' ] ) ) {
            
            $this->data[ 'sheepla_admin_key' ] = $this->request->post[ 'sheepla_admin_key' ];
        } else {
            
            $this->errorList[ 'sheepla_admin_key' ] = $this->language->get( 'error_value_is_required' );
        }
        
        if( isset( $this->request->post[ 'sheepla_public_key' ] ) &&
            strlen( $this->request->post[ 'sheepla_public_key' ] ) ) {
            
            $this->data[ 'sheepla_public_key' ] = $this->request->post[ 'sheepla_public_key' ];
        } else {
            $this->errorList[ 'sheepla_public_key' ] = $this->language->get( 'error_value_is_required' );
        }
        
        if( isset( $this->request->post[ 'sheepla_api_url' ] ) &&
            strlen( $this->request->post[ 'sheepla_api_url' ] ) ) {
            $this->data[ 'sheepla_api_url' ] = $this->request->post[ 'sheepla_api_url' ];
        } else {
            $this->errorList[ 'sheepla_api_url' ] = $this->language->get( 'error_value_is_required' );
        }
        
        if( isset( $this->request->post[ 'sheepla_wapi_url_js' ] ) &&
            strlen( $this->request->post[ 'sheepla_wapi_url_js' ] ) ) {
            
            $this->data[ 'sheepla_wapi_url_js' ] = $this->request->post[ 'sheepla_wapi_url_js' ];
        } else {
            $this->errorList[ 'sheepla_wapi_url_js' ] = $this->language->get( 'error_value_is_required' );
        }
        
        if( isset( $this->request->post[ 'sheepla_wapi_url_css' ] ) &&
            strlen( $this->request->post[ 'sheepla_wapi_url_css' ] ) ) {
            $this->data[ 'sheepla_wapi_url_css' ] = $this->request->post[ 'sheepla_wapi_url_css' ];
        } else {
            $this->errorList[ 'sheepla_wapi_url_css' ] = $this->language->get( 'error_value_is_required' );
        }
        
        
        if( isset( $this->data[ 'sheepla_admin_key' ] ) &&
            isset( $this->data[ 'sheepla_api_url' ] ) ) {
            
            
            if( !$this->getSheeplaClient()->checkAccount( $this->data[ 'sheepla_admin_key' ] , $this->data[ 'sheepla_api_url' ] ) ) {
                
                $this->errorList[ 'sheepla_account' ] = $this->language->get( 'sheepla_account_is_invalid' );
            }
        }
        
        if( count( $this->errorList ) ) {
            
            
            $this->data[ 'errors' ] = $this->errorList;
            return false;
        }
        
        return true;
    }
            
    
    protected function validateShipping() {
        
        $this->errorList = array();
        
        if( empty( $this->request->post[ 'shipping_name' ] ) ) {
                
            $this->errorList[ 'shipping_name' ] = $this->language->get( 'error_shipping_name_required' );
            
        } else {
            
            $this->data[ 'shipping_name' ] = $this->request->post[ 'shipping_name' ]; 
        }
        
        
        if( !empty( $this->request->post[ 'sheepla_template_id' ] ) ) {
            
            $sheeplaTemplateId = $this->request->post[ 'sheepla_template_id' ];
            
            if( empty( $this->data[ 'sheepla_shippings' ][ $sheeplaTemplateId ] ) ) {
                
                $this->errorList[ 'sheepla_template_id' ] = $this->language->get( 'error_sheepla' );
                
            } else {
                
                $this->data[ 'sheepla_template_id' ] = $this->request->post[ 'sheepla_template_id' ];
            }
        } else {
            
            $this->data[ 'sheepla_template_id' ] = 0;
        }
        
        
        if( empty( $this->request->post[ 'shipping_price' ] ) ) {
                
            $this->errorList['shipping_price'] = $this->language->get('error_shipping_price_required');
            
        } else {
            
            $price = preg_replace( '/,/' , '.' , $this->request->post[ 'shipping_price' ] );
            
            if( !preg_match( '/[0-9]+(,[0-9]{0,2})?/' , $price ) ) {
                
                $this->errorList[ 'shipping_price' ] = $this->language->get( 'error_shipping_price_bad_format' );  
            } 
            
            if( empty( $this->request->post[ 'currency_id' ] ) ||
                !array_key_exists( $this->request->post[ 'currency_id' ] , $this->data[ 'currencies' ] ) ) {
                
                if( isset( $this->errorList[ 'shipping_price' ] ) ) {
                    $this->errorList[ 'shipping_price' ] .= '<br/>';
                } else {
                    $this->errorList[ 'shipping_price' ] = '';
                }
                
                $this->errorList[ 'shipping_price' ] .= $this->language->get( 'error_shipping_price_bad_currency' );  
                
            } else {
                
                $this->data[ 'currency_id' ] = $this->request->post[ 'currency_id' ];
            }
            
            $this->data[ 'shipping_price' ] = $price;
        }
        
        
        if( empty( $this->request->post[ 'payments' ] ) ||
            !is_array( $this->request->post[ 'payments' ] ) ||
            !count( $this->request->post[ 'payments' ] ) ) {
            
            $this->errorList[ 'payments' ] = $this->language->get( 'error_shipping_payments_required' );  
            $this->data[ 'selected_payments' ] = array();
            
        } else {
            
            $seelctedPayments = array();
            foreach( $this->request->post[ 'payments' ] as $payment ) {
                
                if( array_key_exists( $payment , $this->data[ 'payments' ] ) ) {
                    
                    $seelctedPayments[] = $payment;
                }
            } 
            
            if( !count( $seelctedPayments ) ) {
                
                $this->errorList[ 'payments' ] = $this->language->get( 'error_shipping_payments_required' );
                $this->data[ 'selected_payments' ] = array();
                
            } else {
                
                $this->data[ 'selected_payments' ] = $seelctedPayments;
            }
        }
        
        
        
        return !count( $this->errorList );
    }
    
    public function install() {
        
        $this->load->model( 'shipping/sheepla' );
        $this->model_shipping_sheepla->createSheeplaTable();
        
        $this->load->model( 'setting/setting' );
        
        $initData = array(
            'sheepla_api_url' => 'https://api.sheepla.com/',
            'sheepla_wapi_url_js' => 'https://panel.sheepla.pl/Content/GetWidgetAPIJavaScript',
            'sheepla_wapi_url_css' => 'https://panel.sheepla.pl/Content/GetWidgetAPICss'
        );
        
        $this->model_setting_setting->editSetting( 'sheepla' , $initData );
        
        $this->load->library( 'sheeplaLog' );
        $sheeplaLogger = new SheeplaLog();
        
        $sheeplaLogger->service( __METHOD__ , "New instalation" , $this );
    }
    
    
    public function uninstall() {
        
    }
    
    
    protected function getSheeplaClient() { 
        
        if( null == $this->sheeplaClient ) {
            
            $this->load->model( 'setting/setting' );
            $lib = $this->load->library( 'opencartSheepla' );
            
            $key = $this->config->get( 'sheepla_admin_key' );
            $apiurl = $this->config->get( 'sheepla_api_url' );
            
            
            $this->sheeplaClient = new OpencartSheepla();
            $this->sheeplaClient->init( $key , $apiurl );
        }
        
        return $this->sheeplaClient;
    }
}