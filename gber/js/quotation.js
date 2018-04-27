/****** 地図を表示する ******/
var map;
var data = [];
var markers = [];
var marker_list;
var myLatLng;
var workLatLng;
var radius;

var hiduke = new Date();
var year = hiduke.getFullYear();
var month = ("0" + String(hiduke.getMonth()+1)).slice(-2);
var day = ("0" + hiduke.getDate()).slice(-2);
var thisday = year+"-"+month+"-"+day;

var nextmonth_num = hiduke.getMonth()+2;
var nextyear = year;
if(nextmonth_num>12){
    nextmonth_num = nextmonth_num - 12;
    nextyear = nextyear + 1;
}
var nextmonth = ("0" + String(nextmonth_num)).slice(-2);

var waiting_am;
var accepted_am;
var waiting_pm;
var accepted_pm;

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
    workLatLng = {lat: parseFloat(work_detail[0].lat), lng: parseFloat(work_detail[0].lng)};
    /* GPSを利用する */
    if(!navigator.geolocation){
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
    geocoder.geocode( { 'address': address}, function(results, status) {
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
function acceptOffer(groupno, workid, workday, workerno, am, pm, status){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workday: workday,
        workerno: workerno,
        am: am,
        pm: pm,
        status: status
    };
    var alerttext = "";
    if(status==1){alerttext="本当に承諾しますか？";}
    else{alerttext="本当に断りますか？";}
    swal({
        title: "確認",
        text: alerttext,
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
        url: baseurl+'model/apply4specialist.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "リクエストを正常に処理しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        //window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
                        $("span#"+workerno+"_"+workday+"_"+am+"_"+pm).empty();
                        if(status==1){$("span#"+workerno+"_"+workday+"_"+am+"_"+pm).prepend("　承諾済");}
                        else{$("span#"+workerno+"_"+workday+"_"+am+"_"+pm).prepend("　削除済");}
                        $("span#report_"+workerno+"_"+workday+"_"+am+"_"+pm).hide();
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのためリクエストを処理できませんでした", "error");
        }
    });
    });
}

/****** 評価を登録する ******/
function registerevalspecialistnew(workid, groupno, workerno){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workerno: workerno,
        evaluation: $("input[name='backing"+workerno+"']").val(),
        comment: $("[name='comment"+workerno+"']").val()
    };
    /*
    if($("[name='comment"+workerno+"']").val()==""){
        sweetAlert("エラー", "評価コメントを記入してください", "error");
        exit;
    }
    */
    swal({
        title: "確認",
        text: "評価を登録しますか？",
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
        url: baseurl+'model/registerEvalSpecialistNew.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "評価を登録しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため評価を登録できませんでした", "error");
        }
    });
    });
}

/****** クライアント情報を登録する ******/
function registerClientInfo(workid, groupno, clientid){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        clientid: clientid,
        comment: $("[name='clientinfo']").val()
    };
    swal({
        title: "確認",
        text: "情報を登録しますか？",
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
        url: baseurl+'model/registerClientInfo.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "情報を登録しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため情報を登録できませんでした", "error");
        }
    });
    });
}

/****** そもそも依頼が行われなかったとき，依頼全体を消去する ******/
function eraselogspecialist(groupno, workid){
    var JSONdata = {
        groupno: groupno,
        workid: workid
    };
    swal({
        title: "確認",
        text: "この操作は取り消せません。本当に削除しますか？",
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
        url: baseurl+'model/eraseLogSpecialist.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "データを削除しました。クリックするとトップページに移動します。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "index.php";
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのためデータを削除できませんでした", "error");
        }
    });
    });
}

/****** 全業務、全日報が終了したとき，依頼ステータスを3に変更する ******/
function workfinishreport(groupno, workid){
    var JSONdata = {
        groupno: groupno,
        workid: workid
    };
    swal({
        title: "確認",
        text: "この操作は取り消せません。本当に終了しますか？",
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
        url: baseurl+'model/workFinishReport.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "仕事を終了しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?groupno="+groupno+"&workid="+workid;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため仕事を終了できませんでした", "error");
        }
    });
    });
}

/****** 見積書がオフラインで完成したとき、仕事配分手続きに入るためにステータスを塗り替える ******/
function sendProposalAndAccept(workid, groupno, userno, worktitle){
    var JSONdata = {
        workid: workid,
        groupno: groupno,
        userno: userno,
        worktitle: worktitle,
        price: $("[name='price']").val(),
        content: $("[name='content']").val(),
        workdatetime: $("[name='workdatetime']").val()
    };
    swal({
        title: "確認",
        text: "見積書が完成するまでは押さないでください。本当に次に進みますか？",
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
        url: baseurl+'model/sendProposalAndAccept.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "見積書を締結しました。クリックすると仕事画面に戻ります。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため見積書を締結できませんでした", "error");
        }
    });
    });
}


/****** ワーカーを選択する．管理人が使用 ******/
function sendoffernew(workday,am,pm,workerno,scheduleno,prefix){
    var workid = work_detail[0]['id'];
    var JSONdata = {
        groupno: sendoffergroupno,
        workid: work_detail[0]['id'],
        workday: workday,
        am: am,
        pm: pm,
        workerno: workerno
    };
    swal({
        title: "確認",
        text: "本当にこの人に依頼しますか？",
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
        url: baseurl+'model/sendOfferNew.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "オファーを送信しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        if(am == 1){
                            workerschedule[scheduleno][prefix+"_am"]=2;
                            $("#am_"+workerno).empty();
                            $("#am_"+workerno).append("<font color=\"red\">依頼済</font>");
                            waiting_am++;
                            rewritenumbers();
                        } else if(pm == 1){
                            workerschedule[scheduleno][prefix+"_pm"]=2;
                            $("#pm_"+workerno).empty();
                            $("#pm_"+workerno).append("<font color=\"red\">依頼済</font>");
                            waiting_pm++;
                            rewritenumbers();
                        }
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのためオファーを送信できませんでした", "error");
        }
    });
    });
}

/****** 日報を登録する ******/
function editreport(groupno,workid,workday,workerno,am,pm,workreport){
    if(workreport=="" || workreport=="クリックして日報を入力してください"){
        console.log("編集されていません");
    } else {
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workday: workday,
        workerno: workerno,
        am: am,
        pm: pm,
        workreport: workreport
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/registerWorkReport.php',
        timeout: 10000,
        success: function(data){
/*          swal({
                title: "成功",
                text: "日報を更新しました。",
                type: "success"});*/
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });}
}

/****** 日報を登録する ******/
function editcomment(groupno,workid,workerno,comment){
    if(comment==""){
        console.log("編集されていません");
    } else {
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workerno: workerno,
        comment: comment
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/registerComment.php',
        timeout: 10000,
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });}
}

/****** 確定済みワーカーにメール一斉送信する．管理人が使用 ******/
function sendEmail2workers(groupno){
    var JSONdata = {
        groupno: groupno,
        workid: work_detail[0]['id'],
        worktitle: work_detail[0]['worktitle'],
        mailsubject: $("input[name='mailsubject']").val(),
        mailcontent: $("[name='mailcontent']").val()
    };
    if($("input[name='mailsubject']").val()==""){
        sweetAlert("エラー", "メールタイトルが未記入です", "error");
        exit;
    } else if ($("[name='mailcontent']").val()==""){
        sweetAlert("エラー", "メール本文が未記入です", "error");
        exit;
    }
    swal({
        title: "確認",
        text: "本当にメールを送信しますか？",
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
        url: baseurl+'model/sendEmail2workers.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "メールを送信しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?groupno="+groupno+"&workid="+work_detail[0]['id'];
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのためメールを送信できませんでした", "error");
        }
    });
    });
}

// カレンダー用。日付を選択する
$.datepicker.setDefaults($.datepicker.regional['ja']);
$('#workdate').datepicker({
    dateFormat:'yy-mm-dd',
    onSelect:function(){loadAvailableWorker();}
});
$('#new_workdate').datepicker({
    dateFormat:'yy-mm-dd'
});

// 日付を選択した時、その日のワーカー状況を表示する
function loadAvailableWorker(){
    $(".clear").empty();//まずはリセット
    var sendofferworkdate = $("[name='workdate']").val();
/*
    if( sendofferworkdate < thisday) {
        sweetAlert("エラー","日付が過去の日付です。","error");
    } else {
*/
        var workdatearray = sendofferworkdate.split("-");
        var prefix = "";
        var pastflag = 0;
        if(workdatearray[0]==year && workdatearray[1]==month){
            prefix = "d";
        } else if (workdatearray[0]==nextyear && workdatearray[1]==nextmonth) {
            prefix = "next_d";
        } else if (workdatearray[0] > nextyear || (workdatearray[0]==nextyear && workdatearray[1]>nextmonth)) {
            sweetAlert("エラー","日付が再来月以降です。","error");
            return;
        } else {
            pastflag = 1;
        }
        // 日付が1~9だと、d08_amのようになってしまいエラーになるので、フォーマットを例外的に変えている。
        var d_str = workdatearray[2];
        if(d_str.charAt(0)=="0"){ d_str=d_str.substr(1); }
        waiting_am = 0;
        accepted_am = 0;
        waiting_pm = 0;
        accepted_pm = 0;
        var marubatsu = ["×","<font color=\"red\">◯</font>","<font color=\"red\">依頼済</font>"];
        $.each(workerschedule, function(index,val){
        if(!pastflag){
            var amstr = marubatsu[this[prefix+d_str+"_am"]];
            if(this[prefix+d_str+"_am"]==0){
                amstr = "× <input type=\"button\" value=\"依頼\" onclick=\"sendoffernew('"+sendofferworkdate+"','1','0','"+val.userno+"','"+index+"','"+prefix+d_str+"');\"/>";
            } else if(this[prefix+d_str+"_am"]==1){
                amstr = "<font color=\"red\">◯</font> <input type=\"button\" value=\"依頼\" onclick=\"sendoffernew('"+sendofferworkdate+"','1','0','"+val.userno+"','"+index+"','"+prefix+d_str+"');\"/>";
            } else if(this[prefix+d_str+"_am"]==2){
                waiting_am++;
            }
            var pmstr = marubatsu[this[prefix+d_str+"_pm"]];
            if(this[prefix+d_str+"_pm"]==0){
                pmstr = "× <input type=\"button\" value=\"依頼\" onclick=\"sendoffernew('"+sendofferworkdate+"','0','1','"+val.userno+"','"+index+"','"+prefix+d_str+"');\"/>";
            } else if(this[prefix+d_str+"_pm"]==1){
                pmstr = "<font color=\"red\">◯</font> <input type=\"button\" value=\"依頼\" onclick=\"sendoffernew('"+sendofferworkdate+"','0','1','"+val.userno+"','"+index+"','"+prefix+d_str+"');\"/>";
            } else if(this[prefix+d_str+"_pm"]==2){
                waiting_pm++;
            }
        } else {
            amstr = "<input type=\"button\" value=\"依頼\" onclick=\"sendoffernew('"+sendofferworkdate+"','1','0','"+val.userno+"','"+index+"','');\"/>";
            pmstr = "<input type=\"button\" value=\"依頼\" onclick=\"sendoffernew('"+sendofferworkdate+"','0','1','"+val.userno+"','"+index+"','');\"/>";
        }
            $("#am_"+val.userno).append(amstr);
            $("#pm_"+val.userno).append(pmstr);
        }); // end of foreach
        //依頼済の日程を上書きする
        $.each(worker_candidate,function(index,val){
            if(val.workday == sendofferworkdate){
                if(val.am == 1){
                    $("#am_"+val.workerno).empty();
                    if(val.status == 0){waiting_am++; $("#am_"+val.workerno).append("<font color=\"red\">依頼済</font>");}
                    else if(val.status == 1){accepted_am++; $("#am_"+val.workerno).append("<font color=\"blue\">承認済</font>");}
                } else if(val.pm == 1){
                    $("#pm_"+val.workerno).empty();
                    if(val.status == 0){waiting_pm++; $("#pm_"+val.workerno).append("<font color=\"red\">依頼済</font>");}
                    else if(val.status == 1){accepted_pm++; $("#pm_"+val.workerno).append("<font color=\"blue\">承認済</font>");}
                }
            }
        });
    //} //先月以前の日付を選択可にしたためコメントアウト
    //ソート機能をつける
    $("#sorttable th").unbind();
    $("#sorttable").tablesorter();
    rewritenumbers();

}

//距離計算を行う。地球を円で近似しているため精度は若干悪いが、市内くらいの距離なら問題なし
/*
function calcDistance(x1,y1,x2,y2){
    var radius = 6367000;
    var dx = (x2-x1)*Math.PI/180;
    var dy = (y2-y1)*Math.PI/180;
    var distance = radius*Math.sqrt(Math.pow(dx,2)+Math.pow(dy,2));
    return Math.floor(distance);
}
function showDistance(){
    $.each(workerschedule,function(index,val){
        if(val["mylat"]!=null){
            $("#distance_"+val["userno"]).append(calcDistance(val["mylat"],val["mylng"],work_detail[0]["lat"],work_detail[0]["lng"]));
        }
    });
}
*/

function rewritenumbers(){
    $("#waiting_am").empty();
    $("#waiting_am").append(waiting_am);
    $("#waiting_pm").empty();
    $("#waiting_pm").append(waiting_pm);
    $("#accepted_am").empty();
    $("#accepted_am").append(accepted_am);
    $("#accepted_pm").empty();
    $("#accepted_pm").append(accepted_pm);
}

//仕事内容を選択した時、仕事内容を日報欄に追加し、データベースを更新する。
function insertjob(obj,spanid,groupno,workid,workday,workerno,am,pm){
    if($("#"+spanid).text()=="クリックして日報を入力してください"){$("#"+spanid).empty();}
    $("#"+spanid).prepend(obj.options[obj.selectedIndex].text+"。");
    editreport(groupno,workid,workday,workerno,am,pm,$("#"+spanid).text());
}

//勤務時間を登録する
function insertworktime(obj,groupno,workid,workday,workerno,am,pm){
    var worktime = obj.options[obj.selectedIndex].value;
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workday: workday,
        workerno: workerno,
        am: am,
        pm: pm,
        worktime: worktime
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/registerWorkTime.php',
        timeout: 10000,
        success: function(data){
/*          swal({
                title: "成功",
                text: "勤務時間を更新しました。",
                type: "success"});*/
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });
}


/****** 見積もりに行った人の勤務記録を登録 ******/
function registerNewEstimator(workid, groupno){
    if($("[name='new_workdate']").val()==""){
        sweetAlert("エラー","日付が未入力です。","error");
    } else if($("[name='new_workerid']").val()=="ユーザID") {
        sweetAlert("エラー","ユーザIDが未入力です。","error");
    } else if($("[name='new_worktime']").val()=="勤務時間") {
        sweetAlert("エラー","勤務時間が未入力です。","error");
    } else {
    var JSONdata = {
        workid: workid,
        groupno: groupno,
        workday: $("[name='new_workdate']").val(),
        workerno: $("[name='new_workerid']").val(),
        worktime: $("[name='new_worktime']").val()
    };
    swal({
        title: "確認",
        text: "見積もりの勤務時間を登録しますか？",
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
        url: baseurl+'model/registerNewEstimator.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "登録しました。クリックすると仕事画面に戻ります。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため登録できませんでした", "error");
        }
    });
    });
    }
}

/**** 仕事のタイトルを管理者が編集できるように ****/
function edittitle(groupno,workid,newtitle){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        newtitle: newtitle
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/editSpecialistTitle.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });
}

/**** 仕事の連絡先を管理者が編集できるように ****/
function editcontact(groupno,workid,newcontact){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        newcontact: newcontact
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/editSpecialistContact.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });
}

/**** 仕事の詳細を管理者が編集できるように ****/
function editcontent(groupno,workid,newcontent){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        newcontent: newcontent
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/editSpecialistContent.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });
}

/**** 仕事の勤務予定日時を管理者が編集できるように ****/
function editworkdatetime(groupno,workid,newworkdatetime){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        newworkdatetime: newworkdatetime
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/editSpecialistWorkdatetime.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    });
}

/* 住所変更を確定する */
function confirmAddressChange(groupno,workid){
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
    } else if( $("input[name='groupno']").val() == "" ){
        sweetAlert("エラー","グループ番号が未入力です。","error");
    } else {
    $.ajax({
        type: 'POST',
        data: $('#changelatlng').serialize(),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/changelatlngspecialist.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "更新しました。",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため更新できませんでした", "error");
        }
    })
    }
}

/****** 仕事に立候補する ******/
function answerspecialistinterest(groupno,workid,workerno,interest){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workerno: workerno,
        interest: interest
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/answerSpecialistInterest.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "登録しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため登録できませんでした", "error");
        }
    });
}

/****** 仕事に立候補する ******/
function changeinterest(groupno,workid,workerno,interest){
    var JSONdata = {
        groupno: groupno,
        workid: workid,
        workerno: workerno,
        interest: interest
    };
    $.ajax({
        type: 'POST',
        data: JSON.stringify(JSONdata),
        dataType: "jsonp",
        jsonp: 'jsoncallback',
        url: baseurl+'model/changeSpecialistInterest.php',
        timeout: 10000,
        success: function(data){
            swal({
                title: "成功",
                text: "変更しました",
                type: "success"},
                function(isConfirm){
                    if(isConfirm){
                        window.location.href = "quotation.php?workid="+workid+"&groupno="+groupno;
            }});
        },
        error: function(){
            sweetAlert("エラー", "エラーのため変更できませんでした", "error");
        }
    });
}