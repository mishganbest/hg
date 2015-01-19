function recall_close(){
	$('#recall_form').hide();
		return false;
		    }
		    
			function recall_show(){
			        margin_top = -$('#recall_form').height()/2;
			                margin_left= -$('#recall_form').width()/2;
			                        $('#recall_form').css({'margin-left': margin_left, 'margin-top': margin_top });
			                    	    $('#recall_form').show();
			                    	    
			                    		    $('#recall_ajax_form').show();
			                    			    $('#recall_success').hide();
			                    			    
			                    				    $('#user_name').val('');
			                    					    $('#user_phone').val('');
			                    						    $('#recommend_to_call').val('');
			                    							    $('#user_comment').val('');
			                    								    $('#recall_code').val('');
			                    									    return false;
			                    										}
			                    										
			                    										    function show_message_recall(id_message, message){
			                    											    $('#'+id_message+'_error').html(message).show();
			                    												    $("#"+id_message).focus();
			                    													    $("#"+id_message).addClass('input_error');
			                    														    return false;
			                    															}
			                    															
			                    															    function recall_ajax(){
			                    																    var vars = $('#recall_ajax_form').serialize();
			                    																	    $('#load_recall').show();
			                    																		    $('#submit_recall').hide();
			                    																			    $.ajax({
			                    																					type: "POST",
			                    																						    data: 'recall=yes&'+vars,
			                    																								url:'index.php?route=module/recall/ajax',
			                    																									    dataType:'json',
			                    																											success: function(json){
			                    																													$('#load_recall').hide();
			                    																															$('#submit_recall').show();
			                    																																	$('.recall_input').removeClass('input_error');
			                    																																			$('.error_message').html('').hide();
			                    																																					switch (json['result']) {
			                    																																							    case 'success':
			                    																																										    $('#recall_message2').hide();
			                    																																													    $('#recall_ajax_form').hide();
			                    																																																    $('#recall_success').show();
			                    																																																			break;
			                    																																																					    case 'error':
			                    																																																								    $.each(json['errors'], 
			                    																																																											    function(index, value){
			                    																																																															show_message_recall(index, value);
			                    																																																																		});
			                    																																																																		
			                    																																																																				    break;
			                    																																																																						    }
			                    																																																																								}
			                    																																																																									    });
			                    																																																																										    return false;
			                    																																																																											}