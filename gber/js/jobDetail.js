/****** 地図を表示する ******/
var map;
var data = [];
var markers = [];
var marker_list;
var myLatLng;
var workLatLng;
var radius;

function initMap(){
    marker_list = new google.maps.MVCArray();
    var bodyHeight = $('body').height();
    $("#detail-map").css('height',bodyHeight/2);
    map = new google.maps.Map(document.getElementById('detail-map'),{
        center: new google.maps.LatLng(35.8621349, 139.9715586),
        zoom: 16,
        mapTypeControl: false,
        scrollwheel: false,
        scaleControl: false,
        streetViewControl: false,
        disableDoubleClickZoom: true
    });
    map.setOptions({styles: mapstyles});
    workLatLng = {lat: parseFloat(work_detail.lat), lng: parseFloat(work_detail.lng)};
    /* GPSを利用する */
    if(!navigator.geolocation || isDemo){
        console.log("GPS非対応ブラウザなので「柏駅」の座標を使用します");
        myLatLng = {lat: 35.8621349, lng: 139.9715586};
        radius = 0;
        makeMap();
    }
    else{
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    }
    /* 住所を文字で出力 */
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        latLng: workLatLng
    }, function(results, status) {
        $("#place").append(results[0].formatted_address.replace(/^日本, /, ''));
    });
};

function drawMarkers(){
    var markerBounds = new google.maps.LatLngBounds();
    //現在地
    var pos0 = new google.maps.LatLng(myLatLng.lat, myLatLng.lng);
    markers[0] = new google.maps.Marker({
        position: new google.maps.LatLng(myLatLng.lat, myLatLng.lng),
        map: map,
        icon: {
            path: 'M -18,0 0,-18 18,0 0,18 z',
            strokeColor: '#0000FF',
            fillColor: '#0000FF',
            fillOpacity: 1.0,
            scale: 0.6
        }
    });
    dispMarker(markers[0],pos0);
    markerBounds.extend(pos0);
    
    //勤務地
    var pos1 = new google.maps.LatLng(workLatLng.lat, workLatLng.lng);
    markers[1] = new google.maps.Marker({
        position: new google.maps.LatLng(workLatLng.lat, workLatLng.lng),
        map: map
    });
    dispMarker(markers[1],pos1);
    markerBounds.extend(pos1);
    setTimeout(function(){
        map.fitBounds(markerBounds);
    },100);
}
function dispMarker(marker,pos){
    marker_list.push(marker);
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
    drawMarkers();
}

function editPin(){
    $("#changeaddressbutton").hide();
    $("#visiblelatlng").show();
    markers[1].setDraggable(true);
    google.maps.event.addListener(map, 'center_changed', function(){
        var pos = map.getCenter();
        markers[1].setPosition(pos);
        document.getElementById('changelat').value = markers[1].getPosition().lat();
        document.getElementById('changelng').value = markers[1].getPosition().lng();
        $("#visiblelat").text(markers[1].getPosition().lat());
        $("#visiblelng").text(markers[1].getPosition().lng());
    });
    google.maps.event.addListener( markers[1], 'dragend', function(ev){
        document.getElementById('changelat').value = ev.latLng.lat();
        document.getElementById('changelng').value = ev.latLng.lng();
        $("#visiblelat").text(ev.latLng.lat());
        $("#visiblelng").text(ev.latLng.lng());
        map.panTo(markers[1].position);
    });
    $("#mapsearch").change(function(){
        var changeaddress = $("input[name='changeaddress']").val();
        geosearch(changeaddress);
    });
}

function geosearch(address){
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( {'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            addressLatLng = results[0].geometry.location;
            document.getElementById('changelat').value = addressLatLng.lat();
            document.getElementById('changelng').value = addressLatLng.lng();
            $("#visiblelat").text(addressLatLng.lat());
            $("#visiblelng").text(addressLatLng.lng());
            markers[1].setPosition(addressLatLng);
            map.panTo(markers[1].position);
        } else {
            alert("Error");
        }
    });
}


/****** 仕事に応募する ******/
function answerinterest(workerno,interest){
    var JSONdata = {
        workid: work_detail.id,
        workerno: workerno,
        interest: interest
    };
    var successtext;
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/answerInterest.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "登録しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "jobdetail.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため登録できませんでした", "error");
        }
    });
}

/****** 掲載を非表示にする ******/
function hideJobDetail(){
    var JSONdata = {
        workid: work_detail.id,
    };
    swal({
        title: "確認",
        text: "本当に非公開にしますか？",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "はい",
        cancelButtonText: "いいえ",
        closeOnConfirm: false
    },function(){
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/hideJobDetail.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "非公開にしました。クリックするとトップページに移動します。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "index.php";
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため非公開にできませんでした", "error");
        }
    });
    });
}

/****** 掲載を再表示する ******/
function showJobDetail(){
    var JSONdata = {
        workid: work_detail.id,
    };
    swal({
        title: "確認",
        text: "本当に再公開しますか？",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "はい",
        cancelButtonText: "いいえ",
        closeOnConfirm: false
    },function(){
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/showJobDetail.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "再公開しました。クリックすると募集ページに移動します。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "jobdetail.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため再公開できませんでした", "error");
        }
    });
    });
}

/****** 掲載を締め切る ******/
function shutJobDetail(){
    var JSONdata = {
        workid: work_detail.id,
    };
    swal({
        title: "確認",
        text: "本当に締め切りますか？",
        type: "warning",
        showCancelButton: true,
        cancelButtonColor: "#DD6B55",
        confirmButtonText: "はい",
        cancelButtonText: "いいえ",
        closeOnConfirm: false
    },function(){
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/shutJobDetail.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "締め切りました。クリックするとトップページに移動します。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "index.php";
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため締め切りにできませんでした", "error");
        }
    });
    });
}

/**** 仕事の詳細を掲示責任者やマスターアカウントが編集できるように ****/
function editcontent(workid,newcontent,field){
    var JSONdata = {
        workid: workid,
        newcontent: newcontent,
        field: field
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/editContent.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "jobdetail.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });
}

/* 住所変更を確定する */
function confirmAddressChange(){
    if( $("input[name='changelat']").val() == "" ){
        sweetAlert("エラー","緯度が未入力です。","error");
    } else if( $("input[name='changelng']").val() == "" ){
        sweetAlert("エラー","経度が未入力です。","error");
    } else if( $("input[name='changelat']").val() == 0 ){
        sweetAlert("エラー","赤いピンが刺さっていません。","error");
    } else if( $("input[name='changelng']").val() == 0 ){
        sweetAlert("エラー","赤いピンが刺さっていません。","error");
    } else if( $("input[name='workid']").val() == "" ){
        sweetAlert("エラー","募集IDが未入力です。","error");
    } else {
    $.ajax({
        type: 'POST',
        data: $('#changelatlng').serialize(),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/changelatlng.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "jobdetail.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    })
    }
}

/* 日程を追加する */
function addworkdate(redirect){
    var adddate = $("[name='addworkdate']").val();
    var JSONdata = {
        workid: work_detail.id,
        workdate: adddate
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/addWorkdate.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "日程追加しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        if(redirect == 0){
                            window.location.href = "jobdetail.php?workid="+work_detail.id;
                        } else if (redirect == 1){
                            window.location.href = "chouseikun.php?workid="+work_detail.id;
                        }
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため日程追加できませんでした", "error");
        }
    })
}

/* 募集日を削除する */
function deleteeachdate(helpdateid){
    var JSONdata = {
        helpdateid: helpdateid
    };
    swal({
        title: "確認",
        text: "募集日を削除しますか？",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "はい",
        cancelButtonText: "いいえ",
        closeOnConfirm: false
    },function(){
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/deleteEachDate.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "削除しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "jobdetail.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため削除できませんでした", "error");
        }
    });
    });
}

/****** 仕事に立候補する ******/
function changeippaninterest(workid,workerno,interest){
    var JSONdata = {
        workid: workid,
        workerno: workerno,
        interest: interest
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/changeIppanInterest.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "変更しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "jobdetail.php?workid="+workid;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため変更できませんでした", "error");
        }
    });
}

/****** 仕事の時間帯コメントを編集 ******/
function editcomment(helpdateid,comment){
    if(comment!="" && comment!="時間を入力する場合ダブルクリック"){
    var JSONdata = {
        helpdateid: helpdateid,
        comment: comment
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/editComment4eachHelpdate.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "変更しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "chouseikun.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため変更できませんでした", "error");
        }
    });}
}

/****** 仕事に応募する ******/
function answerattendance(helpdateid,workerno,attendance){
    var JSONdata = {
        helpdateid: helpdateid,
        workerno: workerno,
        attendance: attendance
    };
    var successtext;
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/answerAttendance.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "登録しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "chouseikun.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため登録できませんでした", "error");
        }
    });
}

/****** 仕事に応募する ******/
function changeattendance(helpdateid,workerno,attendance){
    var JSONdata = {
        helpdateid: helpdateid,
        workerno: workerno,
        attendance: attendance
    };
    var successtext;
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/changeAttendance.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "登録しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "chouseikun.php?workid="+work_detail.id;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため登録できませんでした", "error");
        }
    });
}
