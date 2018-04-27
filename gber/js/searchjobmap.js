var map;
var data = [];
var markers = [];
var marker_list;
var myLatLng;
var radius;
var oms;
var iw;

var hiduke = new Date();
var year = hiduke.getFullYear();
var month = ("0" + String(hiduke.getMonth()+1)).slice(-2);
var day = ("0" + hiduke.getDate()).slice(-2);
var thisday = year+"-"+month+"-"+day;

//initMap();

// 地図は最初にこれが呼ばれる．マップ領域を確保した後，位置情報を取得してmakeMap()に飛ぶ
function initMap(){
    marker_list = new google.maps.MVCArray();
    var bodyHeight = $(window).height()-$('body').find('[data-role="header"]').outerHeight()-$("#workdate").outerHeight();
    $("#search-map").css('height',bodyHeight*0.92);
    map = new google.maps.Map(document.getElementById('search-map'),{
        center: new google.maps.LatLng(35.8621349, 139.9715586),
        zoom: 16,
        mapTypeControl: false,
        scrollwheel: false,
        scaleControl: false,
        streetViewControl: false,
        disableDoubleClickZoom: true
    });
    map.setOptions({styles: mapstyles});
    var usualColor = 'eebb22';
    var spiderfiedColor = 'ffee22';
    iw = new google.maps.InfoWindow();
    oms = new OverlappingMarkerSpiderfier(map,{
        markersWontMove: true,
        markersWontHide: true,
        keepSpiderfied: true
    });
    oms.addListener('click', function(marker) {
        iw.setContent(marker.desc);
        iw.open(map, marker);
    });
    oms.addListener('spiderfy', function(markers) {
        iw.close();
    });
    oms.addListener('unspiderfy', function(markers) {
    });
    if(!navigator.geolocation || isDemo){
        console.log("GPS非対応ブラウザなので「柏駅」の座標を使用します");
        myLatLng = {lat: 35.8621349, lng: 139.9715586};
        radius = 0;
        makeMap();
    }
    else{
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }
};
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

// 現在位置にピンを立て，想定誤差の円を描く
function makeMap(){
    var marker = new google.maps.Marker({
        position: myLatLng,
        title: "現在地",
        map: map,
        icon: {
            path: 'M -18,0 0,-18 18,0 0,18 z',
            strokeColor: '#0000FF',
            fillColor: '#0000FF',
            fillOpacity: 1.0,
            scale: 0.6
        }
    });
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
    loadInitialData();
}

// 仕事データを取得したら，drawMarkers()し直す．
function refreshMap(val){
    swal(data.length+"件の募集中案件が見つかりました。");
    setTimeout(function(){drawMarkers(val);},1000);
}

// 日付を元にPHPを叩き仕事データを持ってくる．
function loadData(){
    if( $("[name='workdate']").val() == "" ){
        loadInitialData();
    } else if( !isDemo && $("[name='workdate']").val() < thisday) {
        sweetAlert("エラー","日付が過去の日付です。","error");
    } else {
    $.ajax({
        type: 'POST',
        data: $('#search-form').serialize(),
        url: baseurl+'model/downloadFromHelplistOnDate.php',
        dataType: 'jsonp',
        jsonp: 'jsoncallback',
        timeout: 5000,
        success: function(receivedData, status){
            //既にあるマーカーを取り除く
            data.length = 0;
            marker_list.forEach(function(marker, idx) {
                marker.setMap(null);
            });
            //新しい日付のデータを追加する
            $.each(receivedData, function(i,item){
                data.push({worktitle:item.worktitle, workid:item.id, workdate:item.workdate, status:item.status, genre:item.genre, lat:item.lat, lng:item.lng, userno:item.userno, price:item.price, content:item.content});
            });
            refreshMap(true);
        },
        error: function(){
            console.log('There was an error loading the data.');
        }
    });
    }
}

// 日付を元にPHPを叩き仕事データを持ってくる．
function loadInitialData(){
    $.ajax({
        type: 'POST',
        data: $('#search-form').serialize(),
        url: baseurl+'model/downloadFromHelplistAll.php',
        dataType: 'jsonp',
        jsonp: 'jsoncallback',
        timeout: 5000,
        success: function(receivedData, status){
            //新しい日付のデータを追加する
            $.each(receivedData, function(i,item){
                data.push({worktitle:item.worktitle, workid:item.id, lat:item.lat, lng:item.lng});
            });
            refreshMap(true);
        },
        error: function(){
            console.log('There was an error loading the data.');
        }
    });
}


// 仕事データのピンを立てる
function drawMarkers(val){
    if(val){var markerBounds = new google.maps.LatLngBounds();}
    for (i = 0; i < data.length; i++) {
        var pos = new google.maps.LatLng(data[i].lat, data[i].lng);
        markers[i] = new google.maps.Marker({
            position: new google.maps.LatLng(data[i].lat, data[i].lng),
            map: map
        });
        if(val){markerBounds.extend(pos);}
        //markers[i].setIcon(baseurl+"img/map/question.png");
        var helpDescription = ""+
            "<div class='infoWindow'>"+
                "<p>"+data[i].worktitle+"</p>"+
                "<input type=\"button\" onclick=\"location.href='jobdetail.php?workid="+data[i].workid+"'\" value=\"詳細を見る\">"+
            "</div>";
        dispInfo(markers[i],helpDescription);
    }
    var pos = new google.maps.LatLng(myLatLng.lat, myLatLng.lng);
    if(val){markerBounds.extend(pos);}
    if(val){map.fitBounds(markerBounds);}

}

function dispInfo(marker,description) {
    oms.addMarker(marker);
    marker.desc = description;
    marker_list.push(marker);
}
