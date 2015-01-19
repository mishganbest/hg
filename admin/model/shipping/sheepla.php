<?php

class ModelShippingSheepla extends Model {
    
    /**
     * Createing table on module install
     */
    public function createSheeplaTable() {
        
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "sheepla (id int primary key auto_increment, name varchar( 100 ) not null, price float not null, sheepla_template_id int default null, queue int unique, active int not null default 1, payments text ) DEFAULT CHARSET=utf8");
        $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "sheepla_order( id int PRIMARY KEY AUTO_INCREMENT , order_id int NOT NULL UNIQUE , sheepla_order_id int , synchronized_date timestamp default '0000-00-00 00:00:00' , additional text , synchronized int default 0 , sync_fail_attempt int default 0 , wait_until timestamp default '0000-00-00 00:00:00' ) DEFAULT CHARSET=utf8");
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
            
            $res = $this->db->query( "SELECT * from " . DB_PREFIX . "sheepla WHERE id = $shippingId" );
            
            if( $res->num_rows )
                return $res->row;
            return null;
        }
    }
    
    
    /**
     * Changing shipping method active state
     * @param int $shippingId
     * @param int $isActive
     */
    public function changeShippingActiveState( $shippingId , $isActive ) {
        
        $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET active = " . ( (int)$isActive ) ." WHERE id = " . ( (int)$shippingId ) );
    }
    
    
    /**
     * if $shipmentId is set saveShipment will update row with this id, if not it will insert new row
     * 
     * @param array $shipmentData
     * @param int $shipmentId
     * @return boolean
     */
    public function saveShipment( $shipmentData , $shipmentId = null ) {
        
        if( null !== $shipmentId ) {
            
            try {
                
                $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET name = '{$shipmentData[ 'shipping_name' ]}' , price = {$shipmentData[ 'shipping_price' ]} , sheepla_template_id = {$shipmentData[ 'sheepla_template_id' ]}, payments = '{$shipmentData[ 'payments' ]}' WHERE id = $shipmentId" );
                return true;
                
            } catch( Exception $e ) {
                
                return false;
            }
            
        } else {
            
            try {
                
                $this->db->query( "INSERT INTO " . DB_PREFIX . "sheepla ( name , price , sheepla_template_id , queue , payments ) VALUES ( '{$shipmentData[ 'shipping_name' ]}' , '{$shipmentData[ 'shipping_price' ]}' , {$shipmentData[ 'sheepla_template_id' ]} , {$this->getNexQueue()} , '{$shipmentData[ 'payments' ]}' )" );
                return true;
                
            } catch( Exception $e ) {
                
                return false;
            }
        }
    }
    
    
    
    public function getShopCurrencies() {
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency");
        return $query->rows;
    }
    
    
    
    public function moveShipmentUp( $shipmentId ) {
        
        try {
            
            $current = $this->db->query( "SELECT id, queue FROM " . DB_PREFIX . "sheepla WHERE id = $shipmentId" );
            
            if( 1 !== $current->num_rows ) {
                
                return false;
            }
            
            
            $prev = $this->db->query( "SELECT id, queue FROM " . DB_PREFIX . "sheepla WHERE queue < {$current->row[ 'queue' ]} ORDER BY queue DESC LIMIT 1" );
            
            
            if( 1 !== $prev->num_rows ) {
                
                return false;
            }
            
            
            $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = 0 WHERE id = {$prev->row[ 'id' ]}" );
            $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = {$prev->row[ 'queue' ]} WHERE id = $shipmentId" );
            $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = {$current->row[ 'queue' ]} WHERE id = {$prev->row[ 'id' ]}" );
            
            
            return true;

        } catch( Exception $e ) {
            
            echo $e->getMEssage();
            return false;
        }
    }
    
    
    public function moveShipmentDown( $shipmentId ) {
        
        try {
            
            $current = $this->db->query( "SELECT id, queue FROM " . DB_PREFIX . "sheepla WHERE id = $shipmentId" );
            
            if( 1 !== $current->num_rows ) {
                
                return false;
            }
            
            
            $next = $this->db->query( "SELECT id, queue FROM " . DB_PREFIX . "sheepla WHERE queue > {$current->row[ 'queue' ]} ORDER BY queue ASC LIMIT 1" );
            
            
            if( 1 !== $next->num_rows ) {
                
                return false;
            }
            
            $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = 0 WHERE id = {$next->row[ 'id' ]}" );
            $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = {$next->row[ 'queue' ]} WHERE id = $shipmentId" );
            $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = {$current->row[ 'queue' ]} WHERE id = {$next->row[ 'id' ]}" );
            
            
            return true;

        } catch( Exception $e ) {
            
            return false;
        }
    }
    
    
    /**
     * Remove shipment with id = $shipmentId and decrement queue of gigher queue values
     * 
     * @param int $shipmentId
     * @return boolean
     */
    public function removeShippment( $shipmentId ) {
        
        try {
            
            $res = $this->db->query( "SELECT queue FROM " . DB_PREFIX . "sheepla WHERE id = $shipmentId" );
            if( $res->num_rows ) {
                
                $oldQueue = $res->row[ 'queue' ];
                
                $this->db->query( "DELETE FROM " . DB_PREFIX . "sheepla WHERE id = $shipmentId" );
                $this->db->query( "UPDATE " . DB_PREFIX . "sheepla SET queue = queue - 1 WHERE queue > $oldQueue" );
            }
            
            return true;
            
        } catch (Exception $ex) {
            
            return false;
        }
    }
    
    
    /**
     * Getting next queue value
     * @return int
     */
    protected function getNexQueue() {
            
        $res = $this->db->query( "SELECT max( queue ) as queue FROM " . DB_PREFIX . "sheepla" );
        
        if( 0 === $res->num_rows ) {
            
            return 1;
        }
        
        
        return (int)$res->row[ 'queue' ] + 1;
    }
    
    
    /**
     * Returns all activer payment methods
     * @return array payment methods list
     */
    public function getAllPaymentMethods() {
        
        
    }
}


