var map;
var markers = [];
var marker_list;
var myLatLng;
var radius;
var oms;
var iw;

initMap();

// 地図は最初にこれが呼ばれる．マップ領域を確保した後，位置情報を取得してmakeMap()に飛ぶ
function initMap(){
    marker_list = new google.maps.MVCArray();
    var bodyHeight = $(window).height()-$('body').find('[data-role="header"]').outerHeight()-$("#workdate").outerHeight();
    $("#search-map").css('height',bodyHeight*0.92);
    map = new google.maps.Map(document.getElementById('search-map'),{
        center: new google.maps.LatLng(35.8621349, 139.9715586),
        zoom: 13,
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
    if(!navigator.geolocation){
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
    swal(latlng.length+"件の住所登録があります。");
    setTimeout(function(){drawMarkers(val);},1000);
}

// 日付を元にPHPを叩き仕事データを持ってくる
function loadInitialData(){
    refreshMap(false);
}

// 住所データのピンを立てる
function drawMarkers(val){
    if(val){var markerBounds = new google.maps.LatLngBounds();}
    for (i = 0; i < latlng.length; i++) {
        var pos = new google.maps.LatLng(latlng[i].mylat, latlng[i].mylng);
        markers[i] = new google.maps.Marker({
            position: new google.maps.LatLng(latlng[i].mylat, latlng[i].mylng),
            map: map
        });
        if(val){markerBounds.extend(pos);}
        dispInfo(markers[i]);
    }
    var pos = new google.maps.LatLng(myLatLng.lat, myLatLng.lng);
    if(val){markerBounds.extend(pos);}
    if(val){map.fitBounds(markerBounds);}
}

function dispInfo(marker) {
    oms.addMarker(marker);
    marker_list.push(marker);
}

