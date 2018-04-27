var map;
var data = [];
var markers = [];
var marker;
var myLatLng;
var addressLatLng;
var radius;

function initMap(){
    var bodyHeight = $('body').height();
    $("#upload-map").css('height',bodyHeight/2);
    map = new google.maps.Map(document.getElementById('upload-map'),{
        center: new google.maps.LatLng(35.8621349, 139.9715586),
        zoom: 16,
        mapTypeControl: false,
        scrollwheel: false,
        scaleControl: false,
        disableDoubleClickZoom: true
    });
    map.setOptions({styles: mapstyles});
    if(!navigator.geolocation || isDemo){
        console.log("GPS非対応ブラウザなので「柏駅」の座標を使用します");
        myLatLng = {lat: 35.8621349, lng: 139.9715586};
        radius = 0;
        makeMap();
    }
    else{
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }
}
function successCallback(position){
    myLatLng = {lat: position.coords.latitude, lng: position.coords.longitude};
    radius = position.coords.accuracy;
    makeMap();
}
function errorCallback(error) {
    var err_msg = "";
    switch(error.code)
    {
        case 1:
            err_msg = "位置情報の利用が許可されていません。";
            break;
        case 2:
            err_msg = "デバイスの位置が判定できません。";
            break;
        case 3:
            err_msg = "タイムアウトしました。";
            break;
    }
    console.log(err_msg+"「柏駅」の座標を使用します。");
    myLatLng = {lat: 35.8621349, lng: 139.9715586};
    radius = 0;
    makeMap();
}

function makeMap(){
    document.getElementById('lat').value = myLatLng.lat;
    document.getElementById('lng').value = myLatLng.lng;
    $("#visiblelat").text(myLatLng.lat);
    $("#visiblelng").text(myLatLng.lng);
    marker = new google.maps.Marker({
        position: myLatLng,
        title: "助けを求める場所",
        draggable: true
    });
    marker.setMap(map);
    var currentPos = new google.maps.LatLng(myLatLng.lat, myLatLng.lng);
    new google.maps.Circle({
        map: map,
        center: currentPos,
        radius: radius,
        strokeColor: '#0088ff',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#0088ff',
        fillOpacity: 0.2
    });
    map.panTo(currentPos);
    google.maps.event.addListener(map, 'center_changed', function(){
        var pos = map.getCenter();
        marker.setPosition(pos);
        document.getElementById('lat').value = marker.getPosition().lat();
        document.getElementById('lng').value = marker.getPosition().lng();
        $("#visiblelat").text(marker.getPosition().lat());
        $("#visiblelng").text(marker.getPosition().lng());
    });
    google.maps.event.addListener( marker, 'dragend', function(ev){
        document.getElementById('lat').value = ev.latLng.lat();
        document.getElementById('lng').value = ev.latLng.lng();
        $("#visiblelat").text(marker.getPosition().lat());
        $("#visiblelng").text(marker.getPosition().lng());
        map.panTo(marker.position);
    });
}

function geosearch(address){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            addressLatLng = results[0].geometry.location;
            document.getElementById('lat').value = addressLatLng.lat();
            document.getElementById('lng').value = addressLatLng.lng();
            $("#visiblelat").text(addressLatLng.lat());
            $("#visiblelng").text(addressLatLng.lng());
            marker.setPosition(addressLatLng);
            map.panTo(marker.position);
        } else {
            alert("Error");
        }
    });
}

function uploadData(){
    if( $("input[name='lat']").val() == "" ){
        sweetAlert("エラー","緯度が未入力です。","error");
    } else if( $("input[name='lng']").val() == "" ){
        sweetAlert("エラー","経度が未入力です。","error");
    } else if( $("input[name='lat']").val() == 0 ){
        sweetAlert("エラー","赤いピンが刺さっていません。","error");
    } else if( $("input[name='lng']").val() == 0 ){
        sweetAlert("エラー","赤いピンが刺さっていません。","error");
    } else if( $("input[name='userno']").val() == "" ){
        sweetAlert("エラー","ユーザナンバーが未入力です。","error");
    } else if( $("input[name='worktitle']").val() == "" ){
        sweetAlert("エラー","仕事タイトルが未入力です。","error");
    } else if( $("[name='content']").val() == "" ){
        sweetAlert("エラー","依頼内容が未入力です。","error");
    } else if( $("[name='workgenre']").val() == "choose-one" ){
        sweetAlert("エラー","募集内容の種類が未選択です。","error");
    } else if( $("[name='groupgenre']").val() == "choose-one" ){
        sweetAlert("エラー","募集団体/募集者の種類が未選択です。","error");
    } else if( $("[name='workernum']").val() == "" ){
        sweetAlert("エラー","募集対象者、人数が未入力です。","error");
    } else if( $("[name='price']").val() == "" ){
        sweetAlert("エラー","費用/日給が未入力です。","error");
    } else if( $("[name='contact']").val() == "" ){
        sweetAlert("エラー","申込方法、連絡先が未入力です。","error");
    } else {
    var postdata = $('#upload-form').serialize().replace(/%5B%5D/g, '[]');
    $.ajax({
        type: 'POST',
        data: postdata,
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/uploadJob.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "追加しました。クリックするとトップページに移動します。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "index.php";
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため追加できませんでした", "error");
        }
    })
    }
}

/* 住所変更を確定する */
function deleteeachdate(workdate){
    $("#spandatelist_"+workdate).remove();
    $("#inputdatelist_"+workdate).remove();
}