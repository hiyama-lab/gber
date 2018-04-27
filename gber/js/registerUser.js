function registerData(){
    if( $("input[name='mail']").val() == "" ){
        sweetAlert("エラー","メールアドレスが未入力です。","error");
    } else if( !$("input[name='mail']").val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)){
        sweetAlert("エラー","メールアドレスの書式が無効です。","error");
    } else if( $("input[name='nickname']").val() == "" ){
        sweetAlert("エラー","ニックネームが未入力です。","error");
    } else if( $("input[name='pass']").val() == "" ){
        sweetAlert("エラー","パスワードが未入力です。","error");
    } else if( $("input[name='pass']").val() != $("input[name='pass_re']").val() ){
        sweetAlert("エラー","パスワードが再入力のものと一致しません。","error");
    } else {
    $.ajax({
        type: 'POST',
        data: $('#register-form').serialize(),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/registerUser.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "登録しました。クリックするとログインページに移動します。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "index.php";
                    }
                });
        },
        error: function(){
            sweetAlert("エラー", "新規登録できませんでした．同一のメールアドレスとパスワードが既に登録されているか，通信エラーが発生しています．", "error");
        }
    });
    }
}