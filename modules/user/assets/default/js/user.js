imsUser = {
	withdrawWcoin:function(form_id){
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $("#"+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
				loading('show');
	            $.ajax({
	                type: "POST",
	                url: ROOT + "ajax.php",
	                data: {"m": "user", "f": "withdrawWcoin", "data": fData, "lang_cur": lang}
	            }).done(function (string) {
	                var data = JSON.parse(string);
	                loading('hide');
	                if(data.ok == 1) {
						Swal.fire({
						  	icon: 'success',
						  	title: lang_js['aleft_title'],
						  	text: data.mess
						})
					} else {
						Swal.fire({
						  	icon: 'error',
						  	title: lang_js['aleft_title'],
						  	html: data.mess,
						})
					}
	            });
				return false;
			},
			rules: {
				num_wcoin: {
                    required: true,
                },
				bankcode: {
                    required: true,
                },
                bankname: {
                    required: true,
                },
                bankbranch: {
                    required: true,
                },
                full_name: {
                    required: true,
                }
			},
			messages: {
				num_wcoin: lang_js['err_valid_input'],
				bankcode: lang_js['err_valid_input'],
				bankname: lang_js['err_valid_input'],
				bankbranch: lang_js['err_valid_input'],
				full_name: lang_js['err_valid_input']
			}			
		});
	},
    swap_commission:function(form_id){
        $("#"+form_id).validate({
            submitHandler: function() {
                var form_mess = $("#"+form_id).find('.form_mess');
                form_mess.stop(true,true).slideUp(200).html('');
                var fData = $("#"+form_id).serializeArray();
                // loading('show');
                $.ajax({
                    type: "POST",
                    url: ROOT + "ajax.php",
                    data: {"m": "user", "f": "swap_commission", "data": fData, "lang_cur": lang}
                }).done(function (string) {
                    var data = JSON.parse(string);
                    loading('hide');
                    if(data.ok == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: lang_js['aleft_title'],
                            text: data.mess
                        }).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: lang_js['aleft_title'],
                            html: data.mess,
                        })
                    }
                });
                return false;
            },
            rules: {
                num_commission: {
                    required: true,
                }
            },
            messages: {
                num_commission: lang_js['err_valid_input'],
            }
        });
    },


	addAddessBook:function(form_id){
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $("#"+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
				loading('show');
	            $.ajax({
	                type: "POST",
	                url: ROOT + "ajax.php",
	                data: {"m": "user", "f": "addAddressBook", "data": fData, "lang_cur": lang}
	            }).done(function (string) {
	                var data = JSON.parse(string);
	                loading('hide');
	                if (data.ok == 1) {
	                    if (data.default>0) {
	                        $("#address_book").val(data.default);
	                    }
	                    $.fancybox.close();
	                    location.reload();
	                }else{
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
	                }
	            });
				return false;
			},
			rules: {
				full_name: {
                    required: true,
                },
				email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true,
                    phone: true,
                }
			},
			messages: {
				
			}			
		});
	},
	popupAddessBook:function(){
		$(document).on("click", ".popupAddessBook", function(e){
			e.preventDefault();
			var element = $(this);
			var id = $(this).data('value');
			if(!id) return false;
			loading('show');
			$.ajax({
				type: "POST",
				url: ROOT+"ajax.php",
				data: { "m" : "user", "f" : "popupAddessBook", "id" : id}
			}).done(function( string ) {
				loading('hide');				
				var data = JSON.parse(string);
				var status_succes = 'success';
				if(data.ok == 1) {
					$.fancybox.open(data.html);
					imsUser.addAddessBook(data.form_id);
				} else {
					Swal.fire({
	                    icon: 'error',
	                    title: lang_js['aleft_title'],
	                    text: data.mess,
	                })
				}
			});
			return false;
		});
	},
	add_favorite : function(){
		$(".add_favorite").on("click",function(){
			var element = $(this);
			var id = $(this).attr('data-id');
			var path = window.location.pathname;
			if(!id) return false;
			loading('show');
			$.ajax({
				type: "POST",
				url: ROOT+"ajax.php",
				data: { "m" : "user", "f" : "check_favorite", "id" : id, "path" : path}
			}).done(function( string ) {
				loading('hide');				
				var data = JSON.parse(string);
				var status_succes = 'success';
				if(data.ok == 1) {
					if(data.is_favorite == 1) {
						element.addClass("added");
						element.find("i").removeClass('fal');
						element.find("i").addClass('fas');
					}else if(data.is_favorite == 0){
						element.removeClass("added");
						element.find("i").removeClass('fas');
						element.find("i").addClass('fal');
						status_succes = 'warning';
					}
					// Swal.fire({
					//   	icon: status_succes,
					//   	title: lang_js['aleft_title'],
					//   	text: data.mess
					// })
				} else {
					Swal.fire({
					  	icon: 'error',
					  	title: lang_js['aleft_title'],
					  	html: data.mess,
					})
				}
			});
			return false;
		});
	},
	post_advisory:function(form_id) {
		$("."+form_id).validate({
			submitHandler: function() {
				var form_mess = $('.'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("."+form_id).serializeArray();
                link_act = $("."+form_id).attr('link-go');
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "post_advisory", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');
					var data = JSON.parse(string);					
					var html = '';
					$('.captcha_refresh').click();
					if(data.ok == 1) {
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				return false;
			},
			rules: {
				txtaComment: {
					required: true
				},
				txtName: {
					required: true,
				},
				txtEmail: {
					required: true,
					email: true,
				},
				captcha: {
					required: true,
				}
			},
			messages: {
				txtaComment: '',
				txtName: lang_js['err_valid_input'],
				txtEmail: {
					required: lang_js['err_valid_input'],
					email: lang_js['err_email_input']
				},
				captcha: ''
			}			
		});
	},
	load_comment:function(form_id){
		$(document).on('click', ".btn_loadmore", function (e) {
			// var type_id = $("#"+form_id + " button[type='submit'] ").data('type_id');
			var type_id = $(this).attr('data-type_id');
	    	var start = $(this).attr('data-start');
	    	var type = $(this).attr('data-type');
			loading('show');
	    	if(!start) { return false; }
			$.ajax({
	            type: "POST",
	            url: ROOT+"ajax.php",
	            data: { "m" : "user", "f" : "load_comment", "lang_cur" : lang, "type_id" : type_id,'start' : start, 'type' : type }
	        }).done(function( string ) {
				loading('hide');
				var html = '';
				console.log(string);
	            var data = JSON.parse(string);
	       		html = data.html
				$('.div_more').before(data.html);
				if(data.start == data.max || data.start > data.max){
					$('.btn_loadmore').text('');
					$('.btn_loadmore').removeAttr('data-start');
					$('.btn_loadmore').removeAttr('data-max');
					$('.btn_loadmore').removeClass('btn_loadmore');
				}
				else{
					$('.btn_loadmore').attr("data-start", data.start);
				}
				$('.div_more .count_comment span').text(data.start);
		    });
	    });
	},
	post_comment:function(form_id) {
		$("#"+form_id).validate({
			submitHandler: function(e) {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
                var type = $("#"+form_id + " button[type='submit'] ").data('type');
                var type_id = $("#"+form_id + " button[type='submit'] ").data('type_id');
                var type_reply = $("#"+form_id + " button[type='submit'] ").data('type_reply');
                if ( !type ) { return false; }
                if ( !type_id ) { return false; }
                fData.push({
	                name: "type",
	                value: type
	            });
	            fData.push({
	                name: "type_id",
	                value: type_id
	            });  
	            fData.push({
	                name: "type_reply",
	                value: type_reply
	            });  
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "post_comment", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');
					var data = JSON.parse(string);
					var html = '';
					$('html, body').stop().animate({
				        scrollTop: $("#"+form_id).offset().top - 50
				    }, 500);
					if(data.ok == 1) {
						$('.captcha_refresh').click();
						location.reload(true);
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				return false;
			},
			rules: {
				txtaComment: {
					required: true
				},
				txtName: {
					required: true,
				},
				txtEmail: {
					required: true,
					email: true,
				},
				captcha: {
					required: true,
				}
			},
			messages: {
				txtaComment: '',
				txtName: lang_js['err_valid_input'],
				txtEmail: {
					required: lang_js['err_valid_input'],
					email: lang_js['err_email_input']
				},
				captcha: ''
			}			
		});
	},
	post_rating:function(form_id){
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();      
				var type = $("#"+form_id + " button[type='submit'] ").data('type');
	            var type_id = $("#"+form_id + " button[type='submit'] ").data('type_id');
				if ( !type ) { return false; }
	            if ( !type_id ) { return false; }
	            fData.push({
	                name: "type",
	                value: type
	            });
	            fData.push({
	                name: "type_id",
	                value: type_id
	            });        
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "post_rating", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');					
					var data = JSON.parse(string);
					var html = '';
					$('.captcha_refresh').click();
					if(data.ok == 1) {
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				return false;
			}
		})
	},
	check_order:function (form_id) {
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
                link_act = $("#"+form_id).attr('link-go');
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "get_order", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');
					var data = JSON.parse(string);
					if(data.ok == 1) {
						var code = $('#'+form_id + " [name='order_code']").val();
						go_link(link_act + '' + code + '.html');
					}
					else if(data.ok == 2) {
						var code = $('#'+form_id + " [name='order_code']").val();
						go_link(link_act + '?by_phone=' + code);
					} else {
						Swal.fire({
		                    icon: 'error',
		                    title: lang_js['aleft_title'],
		                    text: data.mess,
		                })
					}
				});
				return false;
			},
			rules: {
				order_code: {
					required: true
				},
				o_email: {
					required: true,
				},
				
			},
			messages: {
				order_code: '',
				o_email: '',
			}			
		});
	},
	form_notification:function (form_id) {
		// notification_product 
		var ajax_send = false;

	    $( ".notification_product .button_no" ).click(function( event ) {
	    	if (ajax_send == true){
               return false;
	        }
	        ajax_send = true;
	        var item_id = $("input[name=item_id]").val();
	        var title = $("input[name=title]").val();
	        var image = $("input[name=picture]").val();
	        $.jAlert({ 
	            'title': '',
	            'content' : '<form id="form_notification" name="form_report" method="post" action="" novalidate="novalidate" >' +
	                        '<div class="form_mess"></div>' +
	                        '<div class="view_no_product">' +
	                        '<div class="image"><img src="' + image + '"/></div>' +
	                        '<div class="title">' + title + '</div>' +
	                        '</div>' +
	                        '<div class="send_no_product">' +
	                            '<input name="name" type= "text" placeholder="' + lang_js['full_name'] + '">' +
	                            '<input name="email" type= "text" placeholder="' + lang_js['enter_email'] + '">' +
	                            '<textarea name="content" type= "text"></textarea>' +
	                            '<input style="display: none" type="text" name="item_id" value="' + item_id + '"></textarea>' +
	                            '<div class="bottom">' +
	                                '<button type="submit" name="send" class="btn btn-primary btn-lg fr" style="width: 100%;">' + lang_js['register_now'] + '</button>' +
	                                '<div class="clear"></div>' +
	                            '</div>'+
	                            '<div class="clear"></div>' +
	                        '</div>' +
	                        '<script language="javascript"> ajax_send = false; imsUser.form_notification("form_notification");</script>' + 
	                        '</form>',
	            'size': 'md'
	        });
	    });
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "get_notification", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');
					var data = JSON.parse(string);
					if(data.ok == 1) {
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				return false;
			},
			rules: {
				name: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				content: {
					required: true
				},
			},
			messages: {
				name: lang_js['err_valid_input'],
				email: {
					required: lang_js['err_valid_input'],
					email: lang_js['err_email_input']
				},
				content: lang_js['err_valid_input'],
			}			
		});
	},
	show_signup:function (show, hide) {
		$('#'+show).slideDown(200);
		$('#'+hide).slideUp(200);
	},
	
	signup:function (form_id, link_go) {
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
				
				loading('show');
				
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "signup", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {					
					loading('hide');
					console.log(string);
					var data = JSON.parse(string);
					console.log(data);
					if(data.ok == 1) {
						Swal.fire({
                            icon: 'success',
                            title: lang_js['aleft_title'],
                            text: data.mess,
                            timer: 2000
                            }).then(function() {
                            go_link(link_go);
                        });
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				//e.preventDefault(); //STOP default action
				//e.unbind(); //unbind. to stop multiple form submit.
				return false;
				
			},
			rules: {
				full_name: {
					required: true
				},
				username: {
					required: true,
					email: true
				},
				password: {
					required: true
				},
				re_password: {
					 equalTo: '#'+form_id+' input[name*="password"]'
				},
				/*re_password: {
					equalTo: '#'+form_id+' #header_password'
				},*/
				phone: {
					required: true
				},
//				address: {
//					required: true
//				},
				captcha: {
					required: true
				}
			},
			messages: {
				nickname: lang_js['err_valid_input'],
				username: lang_js['err_valid_input'],
				password: lang_js['err_valid_input'],
				re_password: lang_js['err_valid_input'],
				phone: lang_js['err_valid_input'],
				//address: lang_js['err_valid_input'],
				captcha: lang_js['err_valid_input']
			}			
		});
	},
	
	signin:function (form_id, link_go) {
		var form_mess = $('#'+form_id).find('.form_mess');
		var acctive = getUrlParameter('acctive');
		if(acctive == 1){
			form_mess.html(imsTemp.html_alert(lang_js['acctive_user'],'success')).stop(true,true).slideDown(200);
		}
		$("#"+form_id).validate({
			submitHandler: function() {
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $('#'+form_id).serializeArray();
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "signin", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');
					var data = JSON.parse(string);
					if(data.ok == 1) {
						go_link(link_go);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				//e.preventDefault(); //STOP default action
				//e.unbind(); //unbind. to stop multiple form submit.
				return false;
			},
			rules: {
				username: {
					required: true,
					email: true
				},
				password: {
					required: true
				}
			},
			messages: {
				username: lang_js['err_valid_input'],
				password: lang_js['err_valid_input']
			}
		});
		
	},
	
	signout:function (link_go) {
		
		loading('show');
		
		$.ajax({
			type: "POST",
			url: ROOT+"ajax.php",
			data: { "m" : "user", "f" : "signout" }
		}).done(function( string ) {
			
			loading('hide');
			
			var data = JSON.parse(string);
			if(data.ok == 1) {
                loading('show');
				go_link(link_go);
			}
		});
		return false;
	},
	
	// account ============================================================
	account:function (form_id, request=0) {
		$("#"+form_id).validate({
            submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
                formData = new FormData($("#"+form_id)[0]);
                formData.append("f", "account");
                formData.append("m", "user");
                formData.append("lang_cur", lang);
                // loading('show');
                $.ajax({
                    type: 'POST',
                    url: ROOT+"ajax.php",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
				}).done(function( string ) {					
					loading('hide');					
					var data = JSON.parse(string);
					if(data.ok == 1) {
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
						if(request != 0){
							Swal.fire({
	                            icon: 'success',
	                            title: lang_js['aleft_title'],
	                            text: lang_js_mod['user']['wait_accept'],
	                        }).then((result) => {
                                location.reload();
                            });
						}
					} else {
                        Swal.fire({
                            icon: 'error',
                            title: lang_js['aleft_title'],
                            html: data.mess,
                        })
					}
				});
				return false;
			},
			rules: {
				full_name: {
					required: true
				},
				birthday: {
					required: true
				},
//				gender: {
//					required: true
//				},
//				description: {
//					required: true
//				}
			},
			messages: {
				//picture: lang_js['err_empty'].replace("[name]", lang_js['picture']),
                //nickname: lang_js['err_email'].replace("[name]", get_lang('nickname', 'user')),
				nickname: get_lang('err_email', 'user', {'[name]':get_lang('nickname', 'user')}),
				birthday: lang_js['err_empty'].replace("[name]", lang_js['birthday']),
//				gender: lang_js['err_empty'].replace("[name]", lang_js['gender']),
//				description: lang_js['err_empty'].replace("[name]", lang_js['description'])
			}
		});
	},
	
	// change_pass ============================================================
	change_pass:function (form_id) {
		
		$("#"+form_id).validate({
            submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $('#'+form_id).serializeArray();
				
				loading('show');
				
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "change_pass", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					
					loading('hide');
					
					var data = JSON.parse(string);
					if(data.ok == 1) {
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				//e.preventDefault(); //STOP default action
				//e.unbind(); //unbind. to stop multiple form submit.
				return false;
			},
			rules: {
				password_cur: {
					required: true
				},
				password: {
					required: true
				},
				re_password: {
					equalTo: '#'+form_id+' #password'
				},
			},
			messages: {
				password_cur: get_lang('err_invalid', 'user', {'[name]':get_lang('password_cur', 'user')}),
				password: get_lang('err_invalid', 'user', {'[name]':get_lang('password', 'user')}),
				re_password: get_lang('err_invalid', 'user', {'[name]':get_lang('re_password', 'user')}),
			}
		});
	},
	
	// forget_pass ============================================================
	forget_pass:function (form_id) {
        
		$("#"+form_id).validate({
            submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $('#'+form_id).serializeArray();
				
				loading('show');
				
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "forget_pass", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					
					loading('hide');
					
					var data = JSON.parse(string);
					if(data.ok == 1) {
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				//e.preventDefault(); //STOP default action
				//e.unbind(); //unbind. to stop multiple form submit.
				return false;
			},
			rules: {
				username: {
					required: true,
					email: true
				}
			},
			messages: {
				username: {
					required: lang_js['err_valid_input'],
					email: lang_js['err_email_input']
				}
			}
		});
	},
	save_link:function(form_id){
		$("#"+form_id).validate({
			submitHandler: function() {
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $('#'+form_id+' .link_shorten').val();
				// var fData = $('#'+form_id).serializeArray();
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "save_link", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					// console.log(string);
					loading('hide');				
					var data = JSON.parse(string);
					if(data.ok == 1) {
						$('#'+form_id+' .link').val(data.link_shorten);
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
						// $("#"+form_id).load(window.location.href + " #"+form_id );
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				//e.preventDefault(); //STOP default action
				//e.unbind(); //unbind. to stop multiple form submit.
				return false;
			}
		})
	},
	form_send_inv:function(form_id){
		$("#"+form_id).validate({
			submitHandler: function(e) {
				e.preventDefault();
				var form_mess = $('#'+form_id).find('.form_mess');
				form_mess.stop(true,true).slideUp(200).html('');
				var fData = $("#"+form_id).serializeArray();
				loading('show');
				$.ajax({
					type: "POST",
					url: ROOT+"ajax.php",
					data: { "m" : "user", "f" : "send_inv", "lang_cur" : lang, "data" : fData }
				}).done(function( string ) {
					loading('hide');
					var data = JSON.parse(string);
					// console.log(data);
					if(data.ok == 1) {
						$("#"+form_id)[0].reset();
						form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
					} else {
						form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
					}
				});
				return false;
			},
			rules: {
				content: {
					required: true
				},
			},
			messages: {
				content: '',
			}			
		});
	},
	share_mail:function(id_selector){
		var ajax_send = false;
	    $("#"+id_selector).click(function( event ) {
	        if (ajax_send == true){
	           return false;
	        }
	        ajax_send = true;
	        var link = $(this).attr('data-link-mail');
	        var link_singin = $(this).attr('data-link-singin');
	        var content_more = $('#content_more').text();
	        if(link == '') {
	            go_link(link_singin);
	        }
	        if(!link) { return false; }
	        Swal.fire({
                icon: 'info',
                title: lang_js_mod['user']['share_link_mail'],
                html: '<div>'+lang_js_mod['user']['share_link']+'</div>'+
            			'<form id="form_send_inv" name="form_report" method="post" action="" novalidate="novalidate" >' +
                        '<div class="form_mess"></div>' +
                        '<div class="send_no_product">' +                            
                            '<input onClick="this.setSelectionRange(0, this.value.length)" name="name" type= "hidden" value="'+ link +'">' +
                            '<div class="clear"></div>' +
                            '<textarea style="margin-bottom: 10px;" name="content" type= "text" placeholder="' +  lang_js_mod['user']['placeholder_share_link'] + '"></textarea>' +
                            // '<textarea name="content_more" type= "text" placeholder="' +  lang_js['placeholder_contentshare_link'] + '">'+ content_more +'</textarea>' +
                            '<div class="bottom">' +
                                // '<button type="submit" name="send" class="btn btn-primary btn-md fr" style="width: 100%;">' + lang_js_mod['user']['send_inv'] + '</button>' +
                                '<div class="clear"></div>' +
                            '</div>'+
                            //     '<div class="clear"></div>' +
                        '</div>' +
                        // '<script language="javascript"> ajax_send = false; imsUser.form_send_inv("form_send_inv");</script>' + 
                        '</form>',
                confirmButtonText: lang_js_mod['user']['send_inv'],
            	showCancelButton: false,
				preConfirm: () => {
					var form_mess = $('#form_send_inv').find('.form_mess');
					form_mess.stop(true,true).slideUp(200).html('');
					var fData = $("#form_send_inv").serializeArray();
					loading('show');
					$.ajax({
						type: "POST",
						url: ROOT+"ajax.php",
						data: { "m" : "user", "f" : "send_inv", "lang_cur" : lang, "data" : fData }
					}).done(function( string ) {
						loading('hide');
						var data = JSON.parse(string);
						// console.log(data);
						if(data.ok == 1) {
							$("#form_send_inv")[0].reset();
							form_mess.html(imsTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
						} else {
							form_mess.html(imsTemp.html_alert(data.mess,'error')).stop(true,true).slideDown(200);
						}
					});
					return false;
				}
            }).then(function (result) {
			    // $("#form_send_inv").submit();
			})
	        // $.jAlert({ 
	        //     'title': lang_js['share_link'],
	        //     'content' : '<form id="form_send_inv" name="form_report" method="post" action="" novalidate="novalidate" >' +
	        //                 '<div class="form_mess"></div>' +
	        //                 '<div class="send_no_product">' +
	        //                     lang_js['share_link_mail'] +
	        //                     '<input onClick="this.setSelectionRange(0, this.value.length)" name="name" type= "hidden" value="'+ link +'">' +
	        //                     '<div class="clear"></div>' +
	        //                     '<textarea style="margin-bottom: 10px;" name="content" type= "text" placeholder="' +  lang_js['placeholder_share_link'] + '"></textarea>' +
	        //                     // '<textarea name="content_more" type= "text" placeholder="' +  lang_js['placeholder_contentshare_link'] + '">'+ content_more +'</textarea>' +
	        //                     '<div class="bottom">' +
	        //                             '<button type="submit" name="send" class="btn btn-primary btn-md fr" style="width: 100%;">' + lang_js['send_inv'] + '</button>' +
	        //                             '<div class="clear"></div>' +
	        //                         '</div>'+
	        //                         '<div class="clear"></div>' +
	        //                 '</div>' +
	        //                 '<script language="javascript"> ajax_send = false; imsUser.form_send_inv("form_send_inv");</script>' + 
	        //                 '</form>',
	        //     'size': 'md',
	        //     'theme' : 'blue'
	        // });
	        ajax_send = false;
	    });
	},
	add_deeplink:function (form_id) {
        $("#"+form_id).validate({
            submitHandler: function() {
                var form_mess = $('#'+form_id).find('.form_mess');
                form_mess.stop(true,true).slideUp(200).html('');
                var fData = $('#'+form_id).serializeArray();

                loading('show');
                $.ajax({
                    type: "POST",
                    url: ROOT+"ajax.php",
                    data: { "m" : "user", "f" : "add_deeplink", "lang_cur" : lang, 'data': fData}
                }).done(function( string ) {
                    loading('hide');
                    var data = JSON.parse(string);
                    if(data.ok == 1) {
                        location.reload();
                    } else {
                    	Swal.fire({
		                    icon: 'error',
		                    title: lang_js['aleft_title'],
		                    html: data.mess,
		                })
                        // $.jAlert({
                        //     'title': 'Thông báo',
                        //     //'size': 'xsm',
                        //     'content':data.mess,
                        //     'animationTimeout': '10',
                        //     'theme': 'red',
                        //     'closeOnClick': true,
                        //     'blurBackground': true,
                        // })
                    }
                });
                return false;
            },
            rules: {
                link_source: {
                    required: true,
                },

            },
            messages: {
                link_source: {
                    required: '',
                },
            }
        });
    },
    delete_deeplink:function (item_id) {
        $.jAlert({
            'type': 'confirm',
            'confirmQuestion': 'Bạn có thực sự muốn xóa link này?',
            'confirmBtnText': 'Xóa',
            'denyBtnText': 'Hủy bỏ',
            'onConfirm': function(e, btn){
                $.ajax({
                    type: "POST",
                    url: ROOT + "ajax.php",
                    data: {"m": "user", "f": "delete_deeplink", "item_id": item_id}
                }).done(function (string) {
                    loading('hide');
                    var data = JSON.parse(string);
                    if(data.ok == 1) {
                        location.reload();
                    } else {
                    	Swal.fire({
		                    icon: 'error',
		                    title: lang_js['aleft_title'],
		                    html: data.mess,
		                })
                        // $.jAlert({
                        //     'title': 'Thông báo',
                        //     'size': 'sm',
                        //     'content':data.mess,
                        //     'animationTimeout': '10',
                        //     'theme': 'error',
                        //     'closeOnClick': true,
                        //     'blurBackground': true,
                        // })
                    }
                });
            }
        })
    },
    create_embed:function(url){
    	var iframe = '<iframe width="250" height="400" src="'+url+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';    	
		$("#embed_box textarea").html(iframe)
		$.fancybox.open({ src: $("#embed_box"), type : 'inline', clickSlide : false, });
    },
    cancelOrder: function(order_code){
		var form_mess = $('.form_mess');
			form_mess.stop(true,true).slideUp(200).html('');
		fancyConfirm({
		    title     : lang_js_mod['user']['cancelOrder_message_title'],
		    message   : lang_js_mod['user']['cancelOrder_message'],
		    okButton  : lang_js_mod['user']['cancelOrder_message_ok'],
		    noButton  : lang_js_mod['user']['cancelOrder_message_no'],
		    callback  : function (value) {
				if (value) {
					// loading('show');
					$.ajax({
						type: "POST",
						url: ROOT+"ajax.php",
						data: { "m" : "user", "f" : "cancel_order", "lang_cur" : lang, "data" : order_code }
					}).done(function( string ) {
						console.log(string);
						loading('hide');			
						var data = JSON.parse(string);
						if(data.ok == 1) {
							Swal.fire({
			                    icon: 'success',
			                    title: lang_js['aleft_title'],
			                    html: data.mess,
			                });
			                $("#ims-content").load(window.location.href + " #ims-content>*",function(){});
							// form_mess.html(ttHTemp.html_alert(data.mess,'success')).stop(true,true).slideDown(200);
						} else {
							Swal.fire({
			                    icon: 'error',
			                    title: lang_js['aleft_title'],
			                    html: data.mess,
			                })							
						}
					});
				}
			}
		})
	}
};
function fancyConfirm( opts ){
  opts  = $.extend( true, {
    title     : 'Are you sure?',
    message   : '',
    okButton  : 'OK',
    noButton  : 'Cancel',
    callback  : $.noop
  }, opts || {} );
  $.fancybox.open({
    type : 'html',
    src  :
    '<div class="fc-content">' +
    '<h4>' + opts.title   + '</h4>' +
    '<p>'  + opts.message + '</p>' +
    '<p class="tright">' +
    '<button data-value="0" data-fancybox-close class="btn btn-back mr-2">' + opts.noButton + '</button>' +
    '<button data-value="1" data-fancybox-close class="btn btn-ok">' + opts.okButton + '</button>' +
    '</p>' +
    '</div>',
    opts : {
      animationDuration : 350,
      animationEffect   : 'material',
      modal : true,
      baseTpl :
      '<div class="fancybox-container fc-container" role="dialog" tabindex="-1">' +
      '<div class="fancybox-bg"></div>' +
      '<div class="fancybox-inner">' +
      '<div class="fancybox-stage"></div>' +
      '</div>' +
      '</div>',
      afterClose : function( instance, current, e ) {
        var button = e ? e.target || e.currentTarget : null;
        var value  = button ? $(button).data('value') : 0;
        opts.callback( value );
      }
    }
  });
}
$(document).ready(function() {
    $("#form_ordering_address").on("click","#addNewAddress,.edit-address",function(){
    	var data = $(this).attr("data-id");
        loading("show");
        $.ajax({
            type: "POST",
            url: ROOT + "ajax.php",
            data: {"m": "user", "f": "load_form_address", "data": data}
        }).done(function (string) {        	
            var data = JSON.parse(string);
            loading("hide");
            $(".ordering_address").find(".address-form").html(data);
            $("html, body").animate({
                scrollTop: $(".address-form").offset().top-50,
            }, 700);
		    imsLocation.locationChange("province", ".select_location_province_d");
		    imsLocation.locationChange("district", ".select_location_district_d");
        });
    })
    $("#form_ordering_address").on("click",".delete-address",function(){
    	var data = $(this).attr("data-id");
    	fancyConfirm({
		    title     : lang_js_mod['user']['delete_message_title'],
		    message   : '',
		    okButton  : lang_js_mod['user']['delete_message_ok'],
		    noButton  : lang_js_mod['user']['delete_message_no'],
		    boxClass  : 'confirm_delete',
		    callback  : function (value) {
				if (value) {
					loading('show');
					$.ajax({
			            type: "POST",
			            url: ROOT + "ajax.php",
			            data: {"m": "user", "f": "delete_address", "data": data}
			        }).done(function (string) {
			        	console.log(string);
			        	$( "#form_ordering_address" ).load(window.location.href + " #form_ordering_address" );
			            loading("hide");
			        });
				}
	    	}
	 	});
    })
    $("#checkall").on("click", function () {
	    $(".select input[type=\"checkbox\"]").prop("checked", this.checked);
	    var focus = $(this).parents("form").find(".row_notification");	    
	    if($("#checkall").is(":checked")){	    	
	    	focus.addClass("focus");
	    }else{
	    	focus.removeClass("focus");
	    }
	})
	$(".select:not(.select-all) input[type=\"checkbox\"]").each(function(){
		var focus = $(this).parents(".row_notification");
		$(this).on("click",function(){			
			if(focus.hasClass("focus")){
		    	focus.removeClass("focus");
		    }else{
		    	focus.addClass("focus");
		    }
		})
	})
	$(document).on("click", ".manager_save_later .delete_item", function(){
		var id = $(this).data("id");
		loading('show');
		$.ajax({
            type: "POST",
            url: ROOT + "ajax.php",
            data: {"m": "user", "f": "removeSaveLater", "id": id}
        }).done(function (string) {     
        	var data = JSON.parse(string);
            loading('hide');
            if (data.ok == 1) {
                location.reload();
            }else{
            	Swal.fire({
				  	icon: 'error',
				  	title: lang_js['aleft_title'],
				  	html: data.mess,
				});
	        }   	        	
        });
	});
	$(".affiliate").on("click",".copy_link",function(){	
		$(this).CopyToClipboard();
		$(this).focus().select();	
	})
	$("#embed_box").on("click",".btn-copy",function(){
		var code = $(this).parents("#embed_box").find("textarea");		
		code.CopyToClipboard();
		code.focus().select();
		
	})
})