<?php

// if not ajax realoading delivery mathods
if( empty( $shippings_only ) ) {

?>


<?php echo $header; ?>
<div id="content">
    
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    
    
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    
    
    <div class="box">
        <div class="heading">
            <h1>
                <img src="view/image/shipping.png" alt="" /> 
                <?php echo $heading_title ?>
            </h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button">
                    <?php echo $button_save; ?>
                </a>
                <a href="<?php echo $cancel; ?>" class="button">
                    <?php echo $button_cancel; ?>
                </a>
            </div>
        </div>
        <div class="content">
            
            
            <div class="vtabs">
                <a class="selected" href="#tab-general"><?php echo $this->language->get( 'sheepla_general_config' ) ?></a>
                <a href="#tab-shippings"><?php echo $this->language->get( 'sheepla_shippings' ) ?></a>
            </div>
            
            <div id="tab-general" class="vtabs-content">
                <form action="<?php echo $main_form_action; ?>" method="post" enctype="multipart/form-data" id="form">

                    <h2> <?php echo $this->language->get( 'sheepla_api_config' ) ?> </h2>

                    <table class="form">
                        <tr>
                            <td>
                                <?php echo $this->language->get( 'sheepla_admin_key' ) ?>
                            </td>
                            <td>
                                <input type="text" size="60" name="sheepla_admin_key" value="<?php echo isset( $sheepla_admin_key ) ? $sheepla_admin_key : ''; ?>">
                                <?php

                                if( isset( $errors[ 'sheepla_admin_key' ] ) ) {

                                    echo "<span class='error'>{$errors[ 'sheepla_admin_key' ]}</span>";
                                }

                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $this->language->get( 'sheepla_public_key' ) ?>
                            </td>
                            <td>
                                <input type="text" size="60" name="sheepla_public_key" value="<?php echo isset( $sheepla_public_key ) ? $sheepla_public_key : ''; ?>">
                                <?php

                                if( isset( $errors[ 'sheepla_public_key' ] ) ) {

                                    echo "<span class='error'>{$errors[ 'sheepla_public_key' ]}</span>";
                                }

                                if( isset( $errors[ 'sheepla_account' ] ) ) {

                                    echo "<span class='error'>{$errors[ 'sheepla_account' ]}</span>";
                                }



                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $this->language->get( 'sheepla_api_url' ) ?>
                            </td>
                            <td>
                                <input type="text" size="60" name="sheepla_api_url" value="<?php echo isset( $sheepla_api_url ) ? $sheepla_api_url : ''; ?>">
                                <?php

                                if( isset( $errors[ 'sheepla_api_url' ] ) ) {

                                    echo "<span class='error'>{$errors[ 'sheepla_api_url' ]}</span>";
                                }

                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $this->language->get( 'sheepla_wapi_url_js' ) ?>
                            </td>
                            <td>
                                <input type="text" size="60" name="sheepla_wapi_url_js" value="<?php echo isset( $sheepla_wapi_url_js ) ? $sheepla_wapi_url_js : ''; ?>">
                                <?php

                                if( isset( $errors[ 'sheepla_wapi_url_js' ] ) ) {

                                    echo "<span class='error'>{$errors[ 'sheepla_wapi_url_js' ]}</span>";
                                }

                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $this->language->get( 'sheepla_wapi_url_css' ) ?>
                            </td>
                            <td>
                                <input type="text" size="60" name="sheepla_wapi_url_css" value="<?php echo isset( $sheepla_wapi_url_css ) ? $sheepla_wapi_url_css : ''; ?>">
                                <?php

                                if( isset( $errors[ 'sheepla_wapi_url_css' ] ) ) {

                                    echo "<span class='error'>{$errors[ 'sheepla_wapi_url_css' ]}</span>";
                                }

                                ?>
                            </td>
                        </tr>

                    </table>

                    <div id="shippments_list">

    <?php

    }

    ?>                    

                    <h2>  <?php echo $this->language->get( 'shipping_methods_config' ) ?> </h2>
                        
                        <?php
                            
                            $id = 'id_' . time();
                        
                            if( isset( $shippings_message ) ) {
                            
                                echo "<div id='$id' class='success'>{$shippings_message}</div>";
                            
                            } elseif( isset( $shippings_error ) ) {
                                
                                echo "<div id='$id' class='warning'>{$shippings_error}</div>";
                                
                            }
                            
                            echo "<script type='text/javascript'>window.setTimeout( function() { $( '#$id' ).fadeOut(); } , 3000 )</script>";
                        ?>
                    
                        <table class="form">
                            <tr>
                                <td>
                                    <b><?php echo $this->language->get( 'shipping_name' ) ?></b>
                                </td>
                                <td>
                                    <b><?php echo $this->language->get( 'shipping_price' ) ?></b>
                                </td>
                                <td>
                                    <b><?php echo $this->language->get( 'shipping_active' ) ?></b>
                                </td>
                                <td>
                                    <b><?php echo $this->language->get( 'shipping_sheepla_remplate' ) ?></b>
                                </td>
                                <td>
                                    <b><?php echo $this->language->get( 'shipping_actions' ) ?></b>
                                </td>
                            </tr>
                            <?php 
                                
                                $max = count( $sheepla_shipping_methods ) - 1;
                                foreach( $sheepla_shipping_methods as $shippingMethodKey => $shippingMethod ) {

                                    echo "<tr>
                                            <td>
                                                {$shippingMethod[ 'name' ]}
                                            </td>
                                            <td>
                                                {$this->currency->format( $shippingMethod[ 'price' ] )}
                                            </td>
                                            <td>
                                                <span class='admin_active_edit' style='cursor:pointer;' id='{$shippingMethod[ 'id' ]}'>" . ( 1 == $shippingMethod[ 'active' ] ? 
                                                        $this->language->get( 'sheepla_yes' ) : 
                                                        $this->language->get( 'sheepla_no' ) ) . "
                                                </span>
                                                <input type='hidden' name='value' value='{$shippingMethod[ 'active' ]}' />
                                            </td>
                                            <td>";


                                        if( isset( $sheepla_shippings[ (int)$shippingMethod[ 'sheepla_template_id' ] ] ) ) {

                                            echo $sheepla_shippings[ $shippingMethod[ 'sheepla_template_id' ] ];

                                        } else {

                                            echo ' - ';
                                        }
                                        
                                        
                                        echo "
                                            </td>
                                            <td>";
                                        
                                        if( 0 == $shippingMethodKey ) {
                                                echo "<a style='color:#AFAFAF;cursor:default;text-decoration:none;'>{$this->language->get( 'shipping_move_up' )}</a> | ";
                                        } else {
                                                echo "<a id='{$shippingMethod[ 'id' ]}' class='sheepla_shipping_move_up'>{$this->language->get( 'shipping_move_up' )}</a> | ";
                                        }
                                        
                                        if( $max == $shippingMethodKey ) {
                                                echo "<a style='color:#AFAFAF;cursor:default;text-decoration:none;'>{$this->language->get( 'shipping_move_down' )}</a> | ";
                                        } else {
                                                echo "<a id='{$shippingMethod[ 'id' ]}' class='sheepla_shipping_move_down'>{$this->language->get( 'shipping_move_down' )}</a> |";
                                        }
                                                
                                        echo "
                                                <a id='{$shippingMethod[ 'id' ]}' class='shipping_edit'>{$this->language->get( 'shipping_method_edit' )}</a> |
                                                <a id='{$shippingMethod[ 'id' ]}' class='shipping_remove'>{$this->language->get( 'shipping_method_remove' )}</a>
                                            </td>
                                          </tr>";
                                }

                            ?>
                            <tr>
                                <td>
                                    <a class="shipping_add">
                                        <?php echo $this->language->get( 'shipping_method_add' ) ?>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>

    <?php

    // if not ajax realoading delivery mathods
    if( empty( $shippings_only ) ) {

    ?>

                    <h2> <?php echo $this->language->get( 'sheepla_shipping_module_config' ) ?> </h2>

                    <table class="form">
                        <tr>
                            <td>
                                <?php echo $this->language->get( 'sheepla_dynamic_pricing' ) ?>
                            </td>
                            <td>
                                <input type="checkbox" name="sheepla_dynamic_pricing" value="1" <?php echo isset( $sheepla_dynamic_pricing ) ? 'checked="checked"' : ''; ?>>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $entry_status; ?>
                            </td>
                            <td>
                                <select name="sheepla_status">

                                    <?php if ($sheepla_status) { ?>

                                        <option value="1" selected="selected">
                                            <?php echo $text_enabled; ?>
                                        </option>
                                        <option value="0">
                                            <?php echo $text_disabled; ?>
                                        </option>

                                    <?php } else { ?>

                                        <option value="1">
                                            <?php echo $text_enabled; ?>
                                        </option>
                                        <option value="0" selected="selected">
                                            <?php echo $text_disabled; ?>
                                        </option>

                                    <?php } ?>

                                </select>

                            </td>
                        </tr>
                    </table>
                </form>
            </div>    
            <div id="tab-shippings" class="vtabs-content">
                
                <div style="min-width:1000px;margin:10px" id="admin_aheepla_workarea"></div>
                
            </div>
        </div>
    </div>
</div>

                                                
                                                
<?php echo $footer; ?> 


<input type="hidden" value="<?php echo $add_url ?>" class="add_url">
<input type="hidden" value="<?php echo $edit_url ?>" class="edit_url">
<input type="hidden" value="<?php echo $manage_payments_url ?>" class="payments_url">
<input type="hidden" value="<?php echo $remove_url ?>" class="remove_url">
<input type="hidden" value="<?php echo $shippments_url ?>" class="shippments_url">
<input type="hidden" value="<?php echo $active_url ?>" class="active_url">

<script>
    $('.vtabs a').tabs();
    
    if( 'undefined' == typeof g_sheeplaScripts ) { 
        
        $( document ).ready( function() {
            
            if( 'undefined' !== typeof sheepla ) {
                
                sheepla.get_shipments( '#admin_aheepla_workarea' , 1 , 25 );
            }
        });
        
        $( document ).on( 'click' , '.admin_active_edit' ,  function() {
            
            var activeLabel = this;
            var hiddenInput = $( this ).siblings( 'input[name="value"]' );
            
            var select = $( '<select>\n\\n\
                                <option value="1" ' + ( '1' === $( hiddenInput ).val() ? 'selected="selected"' : '' ) + ' ><?php echo $this->language->get( 'sheepla_yes' )?></option>\n\
                                <option value="0" ' + ( '0' === $( hiddenInput ).val() ? 'selected="selected"' : '' ) + '><?php echo $this->language->get( 'sheepla_no' )?></option>\n\
                            </select>' );
            
            $( select ).change(function() {
                
                var elem = this;
                var loading = $( '<span>Loading</span>' );
                
                
                $.ajax({
                    type: 'post',
                    url: $( '.active_url' ).val(),
                    dataType: 'json',
                    data: {
                        id: $( activeLabel ).attr( 'id' ),
                        active: $( select ).val()
                    },
                    beforeSend: function() {
                        
                        $( elem ).hide().after( loading );
                        $( close ).remove();
                    },
                    success: function( data ) {
                        
                        if( 1 === data.status ) {
                            
                            $( activeLabel ).html( $( select ).find( 'option:selected' ).html() );
                            $( hiddenInput ).val( $( select ).val() );
                        }
                    },
                    complete: function() {
                        
                        $( select ).remove();
                        $( loading ).remove();
                        $( activeLabel ).show();
                    }
                });
            });
            $( this ).hide().after( select );
              
              
            var close = $( '<a style="margin-left:10px;"><?php echo $this->language->get( "sheepla_close" ) ?></a>' ).click(function() {
                
                $( select ).remove();
                $( this ).remove();
                $( activeLabel ).show();
            });
            $( select ).after( close ); 
        });
        
        
        
        $( document ).on( 'click' , '.sheepla_shipping_move_up' , function() {
            
            sheepla_reloadShippments({ action: 'moveUp' , id: $( this ).prop( 'id' ) });
        });
        
        
        $( document ).on( 'click' , '.sheepla_shipping_move_down' , function() {
            
            sheepla_reloadShippments({ action: 'moveDown' , id: $( this ).prop( 'id' ) });
        });
        
        
        g_sheeplaScripts = true;

        var g_ajaxLoading = 'Loading';
        
        $( document ).on( 'click' , '.shipping_add' , function() {
            
            sheepla_dialog( 
                $( '.add_url' ).val(), 
                null,
                { 
                    title : "<?php echo $translator->get( 'shipping_method_add_dialog_title' ) ?>",
                    buttons: [ 
                        { name : 'save' },
                        { name : 'cancel' } ] 
                }
            );
        });        
        
        $( document ).on( 'click' , '.shipping_edit' , function() {

            sheepla_dialog( 
                $( '.edit_url' ).val() + '&id=' + $( this ).attr( 'id' ) , 
                null , 
                { 
                    title : "<?php echo $translator->get( 'shipping_method_edit_dialog_title' ) ?>" , 
                    buttons: [ 
                        { name : 'save' } , 
                        { name : 'cancel' } 
                    ] 
                } 
             );
        });
        
        
        $( document ).on( 'click' , '.shipping_remove' , function() {

            sheepla_dialog( 
                $( '.remove_url' ).val() + '&id=' + $( this ).attr( 'id' ), 
                null , 
                { 
                    title : "<?php echo $translator->get( 'shipping_method_remove_dialog_title' ) ?>", 
                    buttons: [ 
                        { name : 'remove' }, 
                        { name : 'cancel' } 
                    ] 
                } 
            );
        });
        
        
        function sheepla_dialog( url , data , options , className ) {
            
            if( 'undefined' === typeof className )
                className = '';
            
            
            var dialog = $( '<div class="' + className + '">' ); 
            var buttons = [];
            
            
            if( 'undefined' === typeof options ) {
                
                options = {};
            }
            
            
            if( options.hasOwnProperty( 'buttons' ) ) {
                
                for( var key in options.buttons ) {
                    
                    var btn = options.buttons[ key ];
                    
                    switch( btn.name ) {
                        case 'save':
                            
                            buttons.push({
                                text: '<?php echo $this->language->get( "shipping_method_save" ) ?>',
                                class: 'dialog_button',
                                click: function() {
                                    
                                    
                                    var payments = [];
                                    $( dialog ).find( '[name="payments[]"]:checked' ).each(function() {
                                        
                                        payments.push( $( this ).val() );
                                    });
                                    
                                    $.ajax({
                                        type: 'post',
                                        url: url,
                                        data: {
                                            shipping_name: $( dialog ).find( '[name="sheepla_new_shipping_name"]' ).val(),
                                            shipping_price: $( dialog ).find( '[name="sheepla_new_shipping_price"]' ).val(),
                                            sheepla_template_id: $( dialog ).find( '[name="sheepla_template_id"]' ).val(),
                                            currency_id: $( dialog ).find( '[name="sheepla_new_shipping_price_currency"]' ).val(),
                                            payments: payments,
                                            save_shippment: 1
                                        },
                                        beforeSend: function() {

                                            $( dialog ).html( g_ajaxLoading );
                                        },
                                        success: function( data ) {

                                            try {

                                                data = $.trim( data );
                                                data = $.parseJSON( data );


                                                $( dialog ).html( data.html );
                                                
                                                
                                                if( 1 === data.status ) {
                                                    
                                                    $( '.dialog_button' ).remove();
                                                    sheepla_reloadShippments();
                                                }
                                                
                                            } catch( e ) { } 
                                        }
                                    });
                                }
                            });
                            
                            break;
                        case 'cancel':
                            
                            buttons.push({
                                text: '<?php echo $this->language->get( "shipping_method_cancel" ) ?>',
                                class: 'dialog_button',
                                click: function() {

                                    $( dialog ).dialog( 'destroy' );
                                }
                            });
                            
                            break;
                        case 'remove':
                            
                            buttons.push({
                                text: '<?php echo $this->language->get( "shipping_method_remove" ) ?>',
                                class: 'dialog_button',
                                click: function() {
                                    
                                    $.ajax({
                                        type: 'post',
                                        url: url,
                                        data: {
                                            confirmed: 1
                                        },
                                        beforeSend: function() {
                                            
                                            $( dialog ).html( g_ajaxLoading );
                                        },
                                        success: function( data ) {
                                            
                                            try {
                                                
                                                data = $.trim( data );
                                                data = $.parseJSON( data );

                                                $( dialog ).html( data.html );

                                                if( 1 === data.status ) {

                                                    $( '.dialog_button' ).remove();
                                                    sheepla_reloadShippments();
                                                }
                                                
                                            } catch( e ) { }
                                        }
                                    });
                                }
                            });
                            
                            break;
                    }
                }
            }
            
        
            $( dialog ).dialog({
                resizable: false,
                draggable: false,
                modal: true,
                title: options.title,
                minWidth: 400,
                maxWidth: 500,
                maxHeight: 500,
                position: {
                    at: 'center'
                },
                buttons: buttons,
                open: function() {
                    
                    if( false !== url ) { 
                    
                        $.ajax({
                            type: "post",
                            url: url,
                            beforeSend: function() {

                                $( dialog ).html( g_ajaxLoading );
                            },
                            success: function( data ) {

                                try {

                                    data = $.trim( data );
                                    data = $.parseJSON( data );

                                    $( dialog ).html( data.html );

                                } catch( e ) {} 

                                $( dialog ).dialog( "option" , "position" , { at: 'center' } );

                                $( document ).on( 'scroll' , function() {

                                    $( dialog ).dialog( "option" , "position" , { at: 'center' } );
                                });
                            }
                        });
                     }
                }
            });
            
            
            return dialog;
        }
        
        function sheepla_reloadShippments( data ) {
            
            var backup = '';
            
            $.ajax({
                type: 'post',
                url: $( '.shippments_url' ).attr( 'value' ),
                timeout: 10000,
                data : data,
                beforeSend: function() {
                    
                    backup = $( '#shippments_list' ).html();
                    $( '#shippments_list' ).html( g_ajaxLoading );
                },   
                success: function( data ) {
                    
                    data = $.trim( data );
                    data = $.parseJSON( data );
                    
                    if( 1 === data.status ) {
                        
                        $( '#shippments_list' ).html( data.html );
                    } else {
                        
                        $( '#shippments_list' ).html( backup );
                    }
                },
                error: function() {
                    
                    $( '#shippments_list' ).html( backup );
                }
            });
        }
    }
    
</script>

<?php 

}

?>