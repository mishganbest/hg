<?php 

if( isset( $success ) ) {

echo "<br/><span class='success'>{$success}</span>";

} else {

?>

<table>
    
    <?php
        
        if( isset( $errors[ 'save' ] ) ) { 
        
            echo "<span class='error'>{$errors[ 'save' ]}</span>";
        }
        
    ?>
    
    <tr>
        <td>
            <?php echo $language->get( 'shipping_name' ) ?>
        </td>
        <td>
            <input style="width:98%;margin:2px 0;" type="text" name="sheepla_new_shipping_name" value="<?php echo isset( $shipping_name ) ? $shipping_name : ''; ?>">
            <?php if( isset( $errors[ 'shipping_name' ] ) ) { ?>
                <span class="error"><?php echo $errors[ 'shipping_name' ] ?></span>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $language->get( 'shipping_price' ) ?>
        </td>
        <td>
            <input style="width:140px;" type="text" name="sheepla_new_shipping_price" value="<?php echo isset( $shipping_price ) ? $shipping_price : ''; ?>">
            
            <select name="sheepla_new_shipping_price_currency" style="width:60px">
                
                <?php 
                    
                    foreach( (array)$currencies as $c_id => $c_data ) {
                        
                        echo "<option value='$c_id' " . ( $currency_id == $c_id ? 'selected="selected"' : '' ) . " >{$c_data[ 'code' ]}</option>";
                    }
                ?>
                
            </select>
            
            <?php if( isset( $errors[ 'shipping_price' ] ) ) { ?>
                <span class="error"><?php echo $errors[ 'shipping_price' ] ?></span>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->language->get( 'sheepla_template' ) ?>
        </td>
        <td>
            
            <select style="width:100%;" name="sheepla_template_id">
            <option value="0"> - </option>
            <?php
                
                foreach( $sheepla_shippings as $shippingId => $shipping ) {
                    
                    $selected = '';
                    if( isset( $sheepla_template_id ) ) {
                    
                        if( $sheepla_template_id == $shippingId ) {
                            
                            $selected = 'selected="selected"';
                        }
                    }
                    
                    echo "<option value='{$shippingId}' {$selected}>{$shipping}</option>";
                }
                
            ?>
            </select>
            
        </td>
    </tr>
    <tr>
        <td>
            <?php echo $this->language->get( 'sheepla_payment_methods' ) ?>
        </td>
        <td>
            <table>
            <?php

            foreach( (array)$payments as $payment_code => $payment ) {
                
                $checked = in_array( $payment_code , (array)$selected_payments ) ? 'checked="checked"' : '';
            
                echo "<tr><td>{$payment}</td><td><input type='checkbox' name='payments[]' {$checked} value='{$payment_code}'></td></tr>";
            }
            
            
            if( isset( $errors[ 'payments' ] ) ) {
                echo "<span class='error'>{$errors[ 'payments' ]}</span>";
            }
            
            ?>
            </table>
        </td>
    </tr>
</table>

<?php 

}

?>