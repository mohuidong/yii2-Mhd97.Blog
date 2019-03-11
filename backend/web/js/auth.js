/*!
 * auth
 * xiewulong <xiewulong@vip.qq.com>
 * create: 2015/7/27
 * version: 1.0.0
 */

//user add && edit && remove && role remove
(function($, window, document, undefined){
	
	$(document).on('click', '[data-auth-user=add]', function(){
		var $modal, $phone, 
			$this	= $(this),
			$users	= $('[data-auth=users]');
		$.ajax({
			url: '/auth/user-role',
			dataType: 'json',
			method: 'get',
			async: false,
			success: function(d){
				if(+ d.status){
					$modal = $.modal(_createHtml(d.data));
				}else{
                    swal({
                        icon:'error',
                        text:d.message
                    });
				}
			}
		});
		$phone = $modal.find('input[name=phone]');
		$modal.on('click', '[data-search=user]', function(){
			var error,
				phone = $phone.val();
			if(phone == ''){
                swal({
                    icon:'warning',
                    text:'请输入要查询的用户名'
                });
				$phone.focus();
				return false;
			}
			$.ajax({
				url: '/auth/user-search',
				data: {phone: phone},
				dataType: 'json',
				method: 'get',
				success: function(d){
					if(+ d.status){
						$phone.parents('.form-group').siblings('.user').html(_createUser(d.data)).siblings('.roles').show();
					}else{
                        swal({
                            icon:'warning',
                            text:d.message
                        });
						$phone.focus();
					}
				}
			});
			return false;
		}).on('submit', '[data-form-auth=user]', function(){
			return _assign.call(this, function(d){
				$modal.modal('hide');
				$users.append(	'<tr>' +
									'<td>' + d.username + '</td>' +
									'<td>' + d.phone + '</td>' +
									'<td>' + d.email + '</td>' +
									'<td>' + d.description + '</td>' +
									'<td><a href="/auth/user-role?uid=' + d.uid + '" data-auth-user="edit" data-revoke="' + d.name + '">编辑</a> | <a href="/auth/revoke?uid=' + d.uid + '" data-delete="assign" data-id="' + d.name + '" data-tag="tr" data-prompt="确定要撤销此用户的管理权限吗?">删除</a></td>' + 
								'</tr>').find('tr.none').remove();
			});
		});
		return false;
	}).on('click', '[data-auth-user=edit]', function(){
		var $modal, $phone, 
			$this	= $(this),
			$users	= $('[data-auth=users]');
		$.ajax({
			url: $this.attr('href'),
			dataType: 'json',
			method: 'get',
			async: false,
			success: function(d){
				if(+ d.status){
					d.data.revoke = $this.attr('data-revoke');
					$modal = $.modal(_createHtml(d.data));
				}else{
                    swal({
                        icon:'error',
                        text:d.message
                    });
				}
			}
		});
		$modal.on('submit', '[data-form-auth=user]', function(){
			return _assign.call(this, function(d){
				$modal.modal('hide');
				$this.attr('data-revoke', d.name).parent('td').prev('td').text(d.description);
			});
		});
		return false;
	}).on('removed.x.delete', '[data-auth=users] tr', function(){
		var $this = $(this);
		!$this.siblings('tr').length && $this.after('<tr class="none"><td colspan="5" class="text-center"><strong>没有匹配的数据</strong></td></tr>');
	}).on('removed.x.delete', '[data-auth=roles] tr', function(){
		var $this = $(this);
		!$this.siblings('tr').length && $this.after('<tr class="none"><td colspan="3" class="text-center"><strong>没有匹配的数据</strong></td></tr>');
	}).on('click', '.J-x-auth-list .title', function(){
		$(this).nextAll('.content').slideToggle();
	}).on('click', '.J-x-auth-list .title .checkbox-inline', function(e){
		e.stopPropagation();
	});

	function _assign(fn){
		var error,
			data	= {};
		if(!this.uid || this.uid.value == ''){
			error = '请选择用户';
		}
		if(error){
            swal({
                icon:'error',
                text:error
            });
			return false;
		}
		data.uid = this.uid.value;
		data.role = this.role.value;
		if(this.revoke){
			data.revoke = this.revoke.value;
		}
		//data._csrf = _csrf;
		$.ajax({
			url: '/auth/user-assign',
			data: data,
			dataType: 'json',
			method: 'post',
			success: function(d){
				if(+ d.status){
					fn(d.data);
                    swal({
                        icon:'success',
                        text: d.message,
                    }).then(function () {
                        window.location.reload();
                    });
				}else{
                    swal({
                        icon:'error',
                        text:d.message
                    });
				}
			}
		});
		return false;
	}

	function _createUser(user){
		return	'<div class="form-group">' +
					'<label class="col-sm-3 control-label text-right">用户名：</label>' +
					'<div class="col-sm-3"><p class="form-control-static"><input type="hidden" name="uid" value="' + user.id + '" />' + user.username + '</p></div>' +
				'</div>' +
				'<div class="form-group">' +
					'<label class="col-sm-3 control-label text-right">手机：</label>' +
					'<div class="col-sm-3"><p class="form-control-static">' + user.phone + '</p></div>' +
				'</div>' +
				'<div class="form-group">' +
					'<label class="col-sm-3 control-label text-right">邮箱：</label>' +
					'<div class="col-sm-3"><p class="form-control-static">' + user.email + '</p></div>' +
				'</div>';
	}

	function _createHtml(d){
		var role, i, len,
			d		= d,
			html	=	'<div class="modal fade">' +
							'<div class="modal-dialog">' +
								'<form method="post" class="modal-content form-horizontal" data-form-auth="user">' +
									'<fieldset>'+
										'<div class="modal-header">' +
											'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
											'<h4 class="modal-title">' + (d.user ? '编辑' : '添加') + '管理员</h4>' +
										'</div>' +
										'<div class="modal-body">' +
											(d.user ? '<div class="user">' + _createUser(d.user) + '<input type="hidden" name="revoke" value="' + d.revoke + '" /></div>' :
											'<div class="form-group">' +
												'<label for="x-auth-user-role" class="col-sm-3 control-label text-right">搜索用户：</label>' +
												'<div class="col-sm-4"><input type="text" name="phone" id="x-auth-user-role" class="form-control" placeholder="请输入要查询的用户名" /></div>' +
												'<div class="col-sm-3"><button type="button" class="btn btn-info" data-search="user">搜索</button></div>' +
											'</div>'+
											'<div class="user"></div>') +
											'<div class="form-group roles"' + (d.user ? '' : ' style="display:none;"') + '>' +
												'<label for="x-auth-user-role" class="col-sm-3 control-label text-right">设定角色：</label>' +
												'<div class="col-sm-6">' +
													'<select id="x-auth-user-role" name="role" class="form-control">${roles}</select>' +
												'</div>' +
											'</div>' +
										'</div>' +
										'<div class="modal-footer">' +
											'<button type="submit" class="btn btn-primary" autofocus="autofocus">确定</button>' +
											'<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>' +
										'</div>' +
									'</fieldset>'+
								'</form>' +
							'</div>' +
						'</div>',
			roles	= [];
		for(i = 0, len = d.roles.length; i < len; i++){
			role = d.roles[i];
			roles.push('<option value="' + role.name + '"' + (d.revoke && d.revoke == role.name ? ' selected="selected"' : '') + '>' + role.description + '</option>');
		}
		return html.replace('${roles}', roles.join(''));
	}

})(jQuery, window, document);
