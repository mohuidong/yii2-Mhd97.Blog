
    function searchAction(){
        $('#user-search-form').submit();
    }

    function viewAction(id){
        alert(1);
        initModel(id, 'view', 'fun');
    }
//
    function initEditSystemModule(data, type){

        if(type == "view"){
            $("#id").attr({readonly:true,disabled:true});
            $("#id").val(data.id);
            $("#pid").attr({readonly:true,disabled:true});
            $("#pid").val(data.pid);
            $("#name").attr({readonly:true,disabled:true});
            $("#name").val(data.name);
            $("#password_hash").attr({readonly:true,disabled:true});
            $("#password_hash").parent().parent().hide();
            $("#payment_password_hash").attr({readonly:true,disabled:true});
            $("#payment_password_hash").parent().parent().hide();
            $("#auth_key").attr({readonly:true,disabled:true});
            $("#auth_key").parent().parent().hide();
            $("#email").attr({readonly:true,disabled:true});
            $("#email").val(data.email);
            $("#id_card").attr({readonly:true,disabled:true});
            $("#id_card").val(data.id_card);
            $("#status").attr({readonly:true,disabled:true});
            $("#status").val(getStatus(data.status));
            $("#type").attr({readonly:true,disabled:true});
            $("#phone").attr({readonly:true,disabled:true});
            $("#commission").attr({readonly:true,disabled:true});
            $("#available_money").attr({readonly:true,disabled:true});
            $("#balance").attr({readonly:true,disabled:true});
            $("#score").attr({readonly:true,disabled:true});
            $("#available_score").attr({readonly:true,disabled:true});
            $("#authentication").attr({readonly:true,disabled:true});
            $("#updated_at").attr({readonly:true,disabled:true});
            $("#created_at").attr({readonly:true,disabled:true});
            $("#phone").val(data.phone);
            $("#type").val(getTypes(data.type));
            $("#commission").val(data.commission);
            $("#available_money").val(data.available_money);
            $("#balance").val(data.balance);
            $("#score").val(data.score);
            $("#available_score").val(data.available_score);
            $("#authentication").val(getAuthentication(data.authentication));
            $("#updated_at").val(getMyDate(data.updated_at));
            $("#created_at").val(getMyDate(data.created_at));
            $('#edit_dialog_ok').addClass('hidden');
        }
        else{
            $("#id").attr({readonly:false,disabled:false});
            $("#uname").attr({readonly:true,disabled:true});

            $("#auth_key").attr({readonly:true,disabled:true});
            $("#auth_key").parent().parent().hide();
            $("#last_ip").attr({readonly:true,disabled:true});
            $("#last_ip").parent().parent().hide();
            $("#is_online").attr({readonly:true,disabled:true});
            $("#is_online").parent().parent().hide();
            $("#domain_account").attr({readonly:false,disabled:false});
            $("#domain_account").parent().parent().hide();
            $("#status").attr({readonly:false,disabled:false});
            $("#create_user").attr({readonly:true,disabled:true});
            $("#create_user").parent().parent().hide();
            $("#create_date").attr({readonly:true,disabled:true});
            $("#create_date").parent().parent().hide();
            $("#update_user").attr({readonly:true,disabled:true});
            $("#update_user").parent().parent().hide();
            $("#update_date").attr({readonly:true,disabled:true});
            $("#update_date").parent().parent().hide();
            $('#edit_dialog_ok').removeClass('hidden');
        }
        $('#edit_dialog').modal('show');
    }

    function initModel(id, type, fun){

        $.ajax({
            type: "GET",
            url: "<?=Url::toRoute('user/view')?>",
            data: {"id":id},
            cache: false,
            dataType:"json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function(data){
                initEditSystemModule(data, type);
            }
        });
    }
    function getStatus(str) {
        if (str==1){
            return "正常";
        } else {
            return "封号";
        }
    }
    function getTypes(str) {
        if (str==1){
            return "融资客";
        } else if (str == 2) {
            return "经纪人";
        }else{
            return "信贷员"
        }
    }
    function getAuthentication(str){
        if (str==1){
            return "实名认证";
        } else {
            return "未实名认证";
        }
    }
    function getMyDate(str){
        str=str*1000;
        var oDate = new Date(str),
            oYear = oDate.getFullYear(),
            oMonth = oDate.getMonth()+1,
            oDay = oDate.getDate(),
            oHour = oDate.getHours(),
            oMin = oDate.getMinutes(),
            oSen = oDate.getSeconds(),
            oTime = oYear +'-'+ getzf(oMonth) +'-'+ getzf(oDay) +' '+ getzf(oHour) +':'+ getzf(oMin) +':'+getzf(oSen);//最后拼接时间
        return oTime;
    };
    //补0操作
    function getzf(num){
        if(parseInt(num) < 10){
            num = '0'+num;
        }
        return num;
    }

    function deleteAction(id){
        var ids = [];
        if(!!id == true){
            ids[0] = id;
        }
        else{
            var checkboxs = $('#data_table :checked');
            if(checkboxs.size() > 0){
                var c = 0;
                for(i = 0; i < checkboxs.size(); i++){
                    var id = checkboxs.eq(i).val();
                    if(id != ""){
                        ids[c++] = id;
                    }
                }
            }
        }
        if(ids.length > 0){
            admin_tool.confirm('请确认是否删除', function(){
                $.ajax({
                    type: "GET",
                    url: "<?=Url::toRoute('user/delete')?>",
                    data: {"ids":ids},
                    cache: false,
                    dataType:"json",
                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                        alert("出错了，" + textStatus);
                    },
                    success: function(data){
                        for(i = 0; i < ids.length; i++){
                            $('#rowid_' + ids[i]).remove();
                        }
                        admin_tool.alert('msg_info', '删除成功', 'success');
                        window.location.reload();
                    }
                });
            });
        }
        else{
            admin_tool.alert('msg_info', '请先选择要删除的数据', 'warning');
        }
    }