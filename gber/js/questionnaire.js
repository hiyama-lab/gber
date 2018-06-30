function selectgyoushu(obj){
    var idx = obj.selectedIndex;
    var value = obj.options[idx].value;
    var sel = document.questionnaire_demographic.gyoushu_detail;
    var optionidx = sel.options.length;
    for(var i=1;i<optionidx+1;i++){sel.options[i] = null;}
    if(value=="0"){var gyoushulist = ["ソフトウェア・情報処理","インターネット関連","ゲーム関連","通信関連"];}
    else if(value=="1"){var gyoushulist = ["総合電機","コンピューター機器","家電・AV機器","ゲーム・アミューズメント製品","精密機器","通信機器","半導体・電子・電気機器","医療用機器・医療関連","輸送用機器（自動車含む）","重電・産業用電気機器","プラント・エンジニアリング","その他電気・電子関連"];}
    else if(value=="2"){var gyoushulist = ["鉱業・金属製品・鉄鋼","非鉄金属","ガラス・化学・石油","紙・パルプ","繊維","窯業・セラミック","ゴム","セメント"];}
    else if(value=="3"){var gyoushulist = ["住宅・建材・エクステリア","インテリア・住宅関連"];}
    else if(value=="4"){var gyoushulist = ["食品","化粧品・医薬品","日用品・雑貨","玩具","繊維・アパレル","スポーツ・レジャー用品（メーカー）","文具・事務機器関連","宝飾品・貴金属","その他メーカー"];}
    else if(value=="5"){var gyoushulist = ["総合商社","専門商社"];}
    else if(value=="6"){var gyoushulist = ["人材派遣・人材紹介","アウトソーシング","教育","医療・福祉・介護サービス","冠婚葬祭","セキュリティ","ビル管理・メンテナンス","エステティック・美容・理容","フィットネスクラブ","サービス（その他）"];}
    else if(value=="7"){var gyoushulist = ["レジャーサービス・アミューズメント","ホテル・旅館","旅行・観光"];}
    else if(value=="8"){var gyoushulist = ["百貨店","流通・チェーンストア","コンビニエンスストア","ドラッグストア・調剤薬局","ホームセンター","専門店（総合）","専門店（食品関連）","専門店（自動車関連）","専門店（カメラ・OA関連）","専門店（電気機器関連）","専門店（書籍・音楽関連）","専門店（メガネ・貴金属）","専門店（ファッション・服飾関連）","専門店（スポーツ用品）","専門店（インテリア関連）","専門店（その他販売）通信販売・ネット販売"];}
    else if(value=="9"){var gyoushulist = ["フードビジネス（総合）","フードビジネス（洋食）","フードビジネス（ファストフード）","フードビジネス（アジア系）","フードビジネス（和食）"];}
    else if(value=="10"){var gyoushulist = ["放送・映像・音響","新聞・出版・印刷","広告","ディスプレイ・空間デザイン・イベント","アート・芸能関連"];}
    else if(value=="11"){var gyoushulist = ["金融総合グループ","外資系金融","政府系・系統金融機関","銀行","外資系銀行","信用組合・信用金庫・労働金庫","信託銀行","投資信託委託・投資顧問","証券・投資銀行","商品取引","ベンチャーキャピタル","事業者金融・消費者金融","クレジット・信販","リース・レンタル","生命保険・損害保険","共済","その他金融"];}
    else if(value=="12"){var gyoushulist = ["シンクタンク・マーケティング・調査","専門コンサルタント","個人事務所（士業）"];}
    else if(value=="13"){var gyoushulist = ["建設コンサルタント","建設・土木","設計","設備工事","リフォーム・内装工事","不動産"];}
    else if(value=="14"){var gyoushulist = ["海運・鉄道・空輸・陸運","物流・倉庫"];}
    else if(value=="15"){var gyoushulist = ["環境・リサイクル","環境関連設備","電力・ガス・エネルギー"];}
    else if(value=="16"){var gyoushulist = ["警察・消防・自衛隊","官公庁","公益・特殊・独立行政法人"];}
    else if(value=="17"){var gyoushulist = ["生活協同組合","農業協同組合（JA金融機関含む）","農林・水産","その他"];}
    optionidx = 1;
    gyoushulist.forEach(function(item,index,array){
        sel.options[optionidx] = new Option(item,optionidx);
        optionidx++;
    });
    setTimeout(function(){$("#gyoushu_detail").trigger("change")},500);
}

function selectshokushu(obj){
    var idx = obj.selectedIndex;
    var value = obj.options[idx].value;
    var sel = document.questionnaire_demographic.shokushu_detail;
    var optionidx = sel.options.length;
    for(var i=1;i<optionidx+1;i++){sel.options[i] = null;}
    if(value=="0"){var shokushulist = ["営業・代理店営業・ルートセールス・MR","人材コーディネーター・カウンセラー","コールセンター・カスタマーサポート"]}
    else if(value=="1"){var shokushulist = ["マーケティング・企画・宣伝","MD・バイヤー・店舗開発","事業企画・経営企画・エグゼクティブ","FCオーナー・代理店研修生"]}
    else if(value=="2"){var shokushulist = ["購買・資材・貿易・物流","経理・財務・会計","人事・総務・法務・広報・IR・内部監査","一般事務・受付・秘書"]}
    else if(value=="3"){var shokushulist = ["スーパーバイザー・教育担当","小売・流通","フード・アミューズメント"]}
    else if(value=="4"){var shokushulist = ["エステ・理美容・リラクゼーション","ブライダル・葬祭","旅行・ホテル","交通・運輸"]}
    else if(value=="5"){var shokushulist = ["医療サービス","福祉・介護サービス・栄養"]}
    else if(value=="6"){var shokushulist = ["保育","教師・講師・インストラクター","通訳・翻訳"]}
    else if(value=="7"){var shokushulist = ["広告・グラフィック","編集・制作","印刷","ファッション","工業デザイン・インテリア・空間デザイン","放送・映像・音響・イベント・芸能"]}
    else if(value=="8"){var shokushulist = ["WEBサイト・インターネットサービス","ゲーム・アミューズメント","WEBショップ・ECサイト運営"]}
    else if(value=="9"){var shokushulist = ["コンサルタント","士業（公認会計士・税理士・弁護士・その他）","金融系専門職（営業・投資銀行・運用・分析）","金融系専門職（ミドル・バック・その他）","不動産系専門職"]}
    else if(value=="10"){var shokushulist = ["公務員・警察・消防・自衛隊","団体・学校法人職員"]}
    else if(value=="11"){var shokushulist = ["コンサルタント・アナリスト・プリセールス","システム開発（WEB・オープン・モバイル系）","システム開発（汎用機系）","システム開発（組み込み・ファームウェア・制御系）","パッケージソフト・ミドルウェア開発","ネットワーク・通信インフラ・サーバー設計・構築","テクニカルサポート・監視・運用・保守","社内システム","研究開発・特許・品質管理・その他"]}
    else if(value=="12"){var shokushulist = ["研究・開発・特許","回路・システム・半導体設計・光学関連","制御設計","機械・機構設計・金型設計・解析","生産・製造・プロセス技術","品質保証・品質管理・生産管理・製造管理","セールスエンジニア・FAE","サービスエンジニア・サポートエンジニア","CAD・CAM（電気・電子・半導体・機械系）","評価・検査"]}
    else if(value=="13"){var shokushulist = ["設計・積算・測量・構造解析","施行管理・設備・環境保全","研究開発・品質管理・特許その他"]}
    else if(value=="14"){var shokushulist = ["化学・素材・バイオ","食品・化粧品","医薬品・医療機器"]}
    else if(value=="15"){var shokushulist = ["技能工（整備・生産・製造・工事）","施設・設備管理・警備・清掃","配送・運送・倉庫","農林水産関連"]}
    else if(value=="16"){var shokushulist = ["その他"]}
    optionidx = 1;
    shokushulist.forEach(function(item,index,array){
        sel.options[optionidx] = new Option(item,optionidx);
        optionidx++;
    });
    setTimeout(function(){$("#shokushu_detail").trigger("change")},500);
}

function answerdemographic(){
    var doukyo = "";
    var firstdoukyo = true;
    $("#doukyocheckbox :checkbox:checked").each(function(index,currentcheck){
        if(firstdoukyo){
            doukyo += $(currentcheck).val();
            firstdoukyo = false;
        } else {
            doukyo += ","+$(currentcheck).val();
        }
    });
    if( $("input[name='userno']").val() == "" ){
        sweetAlert("エラー","ユーザIDが未入力です。","error");
    } else if( $("[name='gakureki']").val() == "choose-one" ){
        sweetAlert("エラー","最終学歴が選択されていません。","error");
    } else if( $("[name='gyoushu']").val() == "choose-one" ){
        sweetAlert("エラー","業種(大分類)が選択されていません。","error");
    } else if( $("[name='gyoushu_detail']").val() == "choose-one" ){
        sweetAlert("エラー","業種(中分類)が選択されていません。","error");
    } else if( $("[name='shokushu']").val() == "choose-one" ){
        sweetAlert("エラー","職種(大分類)が選択されていません。","error");
    } else if( $("[name='shokushu_detail']").val() == "choose-one" ){
        sweetAlert("エラー","職種(中分類)が選択されていません。","error");
    } else if($("#doukyocheckbox :checkbox:checked").length==0){
        sweetAlert("エラー","同居人が選択されていません。","error");
    } else if( $("[name='undou_light']").val() == "choose-one" ){
        sweetAlert("エラー","運動習慣が選択されていません。","error");
    } else if( $("[name='undou_medium']").val() == "choose-one" ){
        sweetAlert("エラー","運動習慣が選択されていません。","error");
    } else if( $("[name='undou_heavy']").val() == "choose-one" ){
        sweetAlert("エラー","運動習慣が選択されていません。","error");
    } else {
        var userno = $("input[name='userno']").val();
        var JSONdata = {
            userno: userno,
            gakureki: $("[name='gakureki'] option:selected").text(),
            gyoushu: $("[name='gyoushu'] option:selected").text(),
            gyoushudetail: $("[name='gyoushu_detail'] option:selected").text(),
            shokushu: $("[name='shokushu'] option:selected").text(),
            shokushudetail: $("[name='shokushu_detail'] option:selected").text(),
            doukyo: doukyo,
            undou_light: $("[name='undou_light'] option:selected").text(),
            undou_medium: $("[name='undou_medium'] option:selected").text(),
            undou_heavy: $("[name='undou_heavy'] option:selected").text(),
            shikaku: $("[name='certificate']").val()
        };
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl+'model/answerDemographic.php',
            timeout: 10000,
            success: function(data){
                swal({
                    title: "成功",
                    text: "登録しました。クリックするとマイページに移動します。",
                    type: "success"},
                    function(isConfirm){
                        if(isConfirm){
                            window.location.href = "mypage.php?userno="+userno;
                        }
                    });
            },
            error: function(){
                sweetAlert("エラー", "登録できませんでした", "error");
            }
        });
    }
}

function answerworkstyle(){
    if( $("input[name='userno']").val() == "" ){
        sweetAlert("エラー","ユーザIDが未入力です。","error");
    } else if( $("[name='workdayperweek']").val() == "choose-one" ){
        sweetAlert("エラー","1週間の希望就業日数が選択されていません。","error");
    } else if( $("[name='worktimeperday']").val() == "choose-one" ){
        sweetAlert("エラー","1日の希望就業時間が選択されていません。","error");
    } else if( $("[name='commutetime']").val() == "choose-one" ){
        sweetAlert("エラー","希望通勤時間が選択されていません。","error");
    } else {
        var userno = $("input[name='userno']").val();
        var JSONdata = {
            userno: userno,
            workdayperweek: $("[name='workdayperweek'] option:selected").text(),
            worktimeperday: $("[name='worktimeperday'] option:selected").text(),
            commutetime: $("[name='commutetime'] option:selected").text(),
            transit_car: Number($("[name='checkbox-car']").prop("checked")),
            transit_train: Number($("[name='checkbox-train']").prop("checked")),
            transit_bus: Number($("[name='checkbox-bus']").prop("checked")),
            transit_bicycle: Number($("[name='checkbox-bicycle']").prop("checked")),
            transit_onfoot: Number($("[name='checkbox-onfoot']").prop("checked")),
            transit_other: Number($("[name='checkbox-other']").prop("checked")),
            workobject_money_1: Number($("[name='checkbox-workobject-1']").prop("checked")),
            workobject_money_2: Number($("[name='checkbox-workobject-2']").prop("checked")),
            workobject_purposeoflife: Number($("[name='checkbox-workobject-3']").prop("checked")),
            workobject_health: Number($("[name='checkbox-workobject-4']").prop("checked")),
            workobject_contribution: Number($("[name='checkbox-workobject-5']").prop("checked")),
            workobject_asked: Number($("[name='checkbox-workobject-6']").prop("checked")),
            workobject_sparetime: Number($("[name='checkbox-workobject-7']").prop("checked")),
            workobject_skill: Number($("[name='checkbox-workobject-8']").prop("checked")),
            workobject_other: Number($("[name='checkbox-workobject-9']").prop("checked")),
        };
        //alert(JSON.stringify(JSONdata));
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl+'model/answerWorkstyle.php',
            timeout: 10000,
            success: function(data){
                swal({
                    title: "成功",
                    text: "登録しました。クリックするとマイページに移動します。",
                    type: "success"},
                    function(isConfirm){
                        if(isConfirm){
                            window.location.href = "mypage.php?userno="+userno;
                        }
                    });
            },
            error: function(){
                sweetAlert("エラー", "登録できませんでした", "error");
            }
        });
    }
}

/*
function answersocialactivity(){
    if( $("input[name='userno']").val() == "" ){
        sweetAlert("エラー","ユーザIDが未入力です。","error");
    } else {
        var userno = $("input[name='userno']").val();
        var JSONdata = {
            userno: userno,
            worktype_prune: Number($("[name='checkbox-worktype-1']").prop("checked")),
            worktype_agriculture: Number($("[name='checkbox-worktype-2']").prop("checked")),
            worktype_cleaning: Number($("[name='checkbox-worktype-3']").prop("checked")),
            worktype_housework: Number($("[name='checkbox-worktype-4']").prop("checked")),
            worktype_shopping: Number($("[name='checkbox-worktype-5']").prop("checked")),
            worktype_repair: Number($("[name='checkbox-worktype-6']").prop("checked")),
            worktype_caretaking: Number($("[name='checkbox-worktype-7']").prop("checked")),
            worktype_teaching: Number($("[name='checkbox-worktype-8']").prop("checked")),
            worktype_consulting: Number($("[name='checkbox-worktype-9']").prop("checked")),
            study_english: Number($("[name='checkbox-study-english']").prop("checked")),
            study_foreignlanguage: Number($("[name='checkbox-study-foreignlanguage']").prop("checked")),
            study_it: Number($("[name='checkbox-study-it']").prop("checked")),
            study_business: Number($("[name='checkbox-study-business']").prop("checked")),
            study_caretaking: Number($("[name='checkbox-study-caretaking']").prop("checked")),
            study_housework: Number($("[name='checkbox-study-housework']").prop("checked")),
            study_liberalarts: Number($("[name='checkbox-study-liberalarts']").prop("checked")),
            study_art: Number($("[name='checkbox-study-art']").prop("checked")),
            volunteer_health: Number($("[name='checkbox-volunteer-health']").prop("checked")),
            volunteer_elderly: Number($("[name='checkbox-volunteer-elderly']").prop("checked")),
            volunteer_disable: Number($("[name='checkbox-volunteer-disable']").prop("checked")),
            volunteer_children: Number($("[name='checkbox-volunteer-children']").prop("checked")),
            volunteer_sport: Number($("[name='checkbox-volunteer-sport']").prop("checked")),
            volunteer_town: Number($("[name='checkbox-volunteer-town']").prop("checked")),
            volunteer_safety: Number($("[name='checkbox-volunteer-safety']").prop("checked")),
            volunteer_nature: Number($("[name='checkbox-volunteer-nature']").prop("checked")),
            volunteer_disaster: Number($("[name='checkbox-volunteer-disaster']").prop("checked")),
            volunteer_international: Number($("[name='checkbox-volunteer-international']").prop("checked")),
            hobby_musicalinstrument: Number($("[name='checkbox-hobby-musicalinstrument']").prop("checked")),
            hobby_chorus: Number($("[name='checkbox-hobby-chorus']").prop("checked")),
            hobby_dance: Number($("[name='checkbox-hobby-dance']").prop("checked")),
            hobby_shodo: Number($("[name='checkbox-hobby-shodo']").prop("checked")),
            hobby_kado: Number($("[name='checkbox-hobby-kado']").prop("checked")),
            hobby_sado: Number($("[name='checkbox-hobby-sado']").prop("checked")),
            hobby_wasai: Number($("[name='checkbox-hobby-wasai']").prop("checked")),
            hobby_knit: Number($("[name='checkbox-hobby-knit']").prop("checked")),
            hobby_cooking: Number($("[name='checkbox-hobby-cooking']").prop("checked")),
            hobby_gardening: Number($("[name='checkbox-hobby-gardening']").prop("checked")),
            hobby_diy: Number($("[name='checkbox-hobby-diy']").prop("checked")),
            hobby_painting: Number($("[name='checkbox-hobby-painting']").prop("checked")),
            hobby_pottery: Number($("[name='checkbox-hobby-pottery']").prop("checked")),
            hobby_photo: Number($("[name='checkbox-hobby-photo']").prop("checked")),
            hobby_writing: Number($("[name='checkbox-hobby-writing']").prop("checked")),
            hobby_go: Number($("[name='checkbox-hobby-go']").prop("checked")),
            hobby_camp: Number($("[name='checkbox-hobby-camp']").prop("checked"))
        };
        //alert(JSON.stringify(JSONdata));
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl+'model/answerSocialActivity.php',
            timeout: 10000,
            success: function(data){
                swal({
                    title: "成功",
                    text: "登録しました。クリックするとマイページに移動します。",
                    type: "success"},
                    function(isConfirm){
                        if(isConfirm){
                            window.location.href = "mypage.php?userno="+userno;
                        }
                    });
            },
            error: function(){
                sweetAlert("エラー", "登録できませんでした", "error");
            }
        });
    }
}
*/

function answerworktag(){
    if( $("input[name='groupno']").val() == "" ){
        sweetAlert("エラー","グループ番号が未入力です。","error");
    } else if( $("input[name='workid']").val() == "" ){
        sweetAlert("エラー","仕事IDが未入力です。","error");
    } else if( $("input[name='userno']").val() == "" ){
        sweetAlert("エラー","ユーザIDが未入力です。","error");
    } else {
        var groupno = $("input[name='groupno']").val();
        var workid = $("input[name='workid']").val();
        var userno = $("input[name='userno']").val();
        var JSONdata = {
            groupno: groupno,
            workid: workid,
            userno: userno,
            worktype_prune: Number($("[name='checkbox-worktype-1']").prop("checked")),
            worktype_agriculture: Number($("[name='checkbox-worktype-2']").prop("checked")),
            worktype_cleaning: Number($("[name='checkbox-worktype-3']").prop("checked")),
            worktype_housework: Number($("[name='checkbox-worktype-4']").prop("checked")),
            worktype_shopping: Number($("[name='checkbox-worktype-5']").prop("checked")),
            worktype_repair: Number($("[name='checkbox-worktype-6']").prop("checked")),
            worktype_caretaking: Number($("[name='checkbox-worktype-7']").prop("checked")),
            worktype_teaching: Number($("[name='checkbox-worktype-8']").prop("checked")),
            worktype_consulting: Number($("[name='checkbox-worktype-9']").prop("checked")),
            study_english: Number($("[name='checkbox-study-english']").prop("checked")),
            study_foreignlanguage: Number($("[name='checkbox-study-foreignlanguage']").prop("checked")),
            study_it: Number($("[name='checkbox-study-it']").prop("checked")),
            study_business: Number($("[name='checkbox-study-business']").prop("checked")),
            study_caretaking: Number($("[name='checkbox-study-caretaking']").prop("checked")),
            study_housework: Number($("[name='checkbox-study-housework']").prop("checked")),
            study_liberalarts: Number($("[name='checkbox-study-liberalarts']").prop("checked")),
            study_art: Number($("[name='checkbox-study-art']").prop("checked")),
            volunteer_health: Number($("[name='checkbox-volunteer-health']").prop("checked")),
            volunteer_elderly: Number($("[name='checkbox-volunteer-elderly']").prop("checked")),
            volunteer_disable: Number($("[name='checkbox-volunteer-disable']").prop("checked")),
            volunteer_children: Number($("[name='checkbox-volunteer-children']").prop("checked")),
            volunteer_sport: Number($("[name='checkbox-volunteer-sport']").prop("checked")),
            volunteer_town: Number($("[name='checkbox-volunteer-town']").prop("checked")),
            volunteer_safety: Number($("[name='checkbox-volunteer-safety']").prop("checked")),
            volunteer_nature: Number($("[name='checkbox-volunteer-nature']").prop("checked")),
            volunteer_disaster: Number($("[name='checkbox-volunteer-disaster']").prop("checked")),
            volunteer_international: Number($("[name='checkbox-volunteer-international']").prop("checked")),
            hobby_musicalinstrument: Number($("[name='checkbox-hobby-musicalinstrument']").prop("checked")),
            hobby_chorus: Number($("[name='checkbox-hobby-chorus']").prop("checked")),
            hobby_dance: Number($("[name='checkbox-hobby-dance']").prop("checked")),
            hobby_shodo: Number($("[name='checkbox-hobby-shodo']").prop("checked")),
            hobby_kado: Number($("[name='checkbox-hobby-kado']").prop("checked")),
            hobby_sado: Number($("[name='checkbox-hobby-sado']").prop("checked")),
            hobby_wasai: Number($("[name='checkbox-hobby-wasai']").prop("checked")),
            hobby_knit: Number($("[name='checkbox-hobby-knit']").prop("checked")),
            hobby_cooking: Number($("[name='checkbox-hobby-cooking']").prop("checked")),
            hobby_gardening: Number($("[name='checkbox-hobby-gardening']").prop("checked")),
            hobby_diy: Number($("[name='checkbox-hobby-diy']").prop("checked")),
            hobby_painting: Number($("[name='checkbox-hobby-painting']").prop("checked")),
            hobby_pottery: Number($("[name='checkbox-hobby-pottery']").prop("checked")),
            hobby_photo: Number($("[name='checkbox-hobby-photo']").prop("checked")),
            hobby_writing: Number($("[name='checkbox-hobby-writing']").prop("checked")),
            hobby_go: Number($("[name='checkbox-hobby-go']").prop("checked")),
            hobby_camp: Number($("[name='checkbox-hobby-camp']").prop("checked")),
            hobby_watchsport: Number($("[name='checkbox-hobby-watchsport']").prop("checked")),
            hobby_watchperformance: Number($("[name='checkbox-hobby-watchperformance']").prop("checked")),
            hobby_watchmovie: Number($("[name='checkbox-hobby-watchmovie']").prop("checked")),
            hobby_listenmusic: Number($("[name='checkbox-hobby-listenmusic']").prop("checked")),
            hobby_reading: Number($("[name='checkbox-hobby-reading']").prop("checked")),
            hobby_pachinko: Number($("[name='checkbox-hobby-pachinko']").prop("checked")),
            hobby_karaoke: Number($("[name='checkbox-hobby-karaoke']").prop("checked")),
            hobby_game: Number($("[name='checkbox-hobby-game']").prop("checked")),
            hobby_attraction: Number($("[name='checkbox-hobby-attraction']").prop("checked")),
            hobby_train: Number($("[name='checkbox-hobby-train']").prop("checked")),
            hobby_car: Number($("[name='checkbox-hobby-car']").prop("checked")),
            trip_daytrip: Number($("[name='checkbox-trip-daytrip']").prop("checked")),
            trip_domestic: Number($("[name='checkbox-trip-domestic']").prop("checked")),
            trip_international: Number($("[name='checkbox-trip-international']").prop("checked")),
            sport_baseball: Number($("[name='checkbox-sport-baseball']").prop("checked")),
            sport_tabletennis: Number($("[name='checkbox-sport-tabletennis']").prop("checked")),
            sport_tennis: Number($("[name='checkbox-sport-tennis']").prop("checked")),
            sport_badminton: Number($("[name='checkbox-sport-badminton']").prop("checked")),
            sport_golf: Number($("[name='checkbox-sport-golf']").prop("checked")),
            sport_gateball: Number($("[name='checkbox-sport-gateball']").prop("checked")),
            sport_bowling: Number($("[name='checkbox-sport-bowling']").prop("checked")),
            sport_fishing: Number($("[name='checkbox-sport-fishing']").prop("checked")),
            sport_swimming: Number($("[name='checkbox-sport-swimming']").prop("checked")),
            sport_skiing: Number($("[name='checkbox-sport-skiing']").prop("checked")),
            sport_climbing: Number($("[name='checkbox-sport-climbing']").prop("checked")),
            sport_cycling: Number($("[name='checkbox-sport-cycling']").prop("checked")),
            sport_jogging: Number($("[name='checkbox-sport-jogging']").prop("checked")),
            sport_walking: Number($("[name='checkbox-sport-walking']").prop("checked"))
        };
        //alert(JSON.stringify(JSONdata));
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl+'model/answerWorktag.php',
            //timeout: 10000,
            success: function(data){
                swal({
                    title: "成功",
                    text: "登録しました",
                    type: "success"},
                    function(isConfirm){
                        if(isConfirm){
                            window.location.href = "worktaglist.php";
                        }
                    });
            },
            error: function(){
                sweetAlert("エラー", "登録できませんでした", "error");
            }
        });
    }
}

function registerWorktype(){
    if( $("input[name='name']").val() == "" ){
        sweetAlert("エラー","仕事タイプが未入力です。","error");
    } else {
        var worktypeid = $("input[name='worktypeid']").val();
        var groupno = $("input[name='groupno']").val();
        var name = $("input[name='name']").val();
        var JSONdata = {
            id: worktypeid,
            groupno: groupno,
            name: name,
            worktype_prune: Number($("[name='checkbox-worktype-1']").prop("checked")),
            worktype_agriculture: Number($("[name='checkbox-worktype-2']").prop("checked")),
            worktype_cleaning: Number($("[name='checkbox-worktype-3']").prop("checked")),
            worktype_housework: Number($("[name='checkbox-worktype-4']").prop("checked")),
            worktype_shopping: Number($("[name='checkbox-worktype-5']").prop("checked")),
            worktype_repair: Number($("[name='checkbox-worktype-6']").prop("checked")),
            worktype_caretaking: Number($("[name='checkbox-worktype-7']").prop("checked")),
            worktype_teaching: Number($("[name='checkbox-worktype-8']").prop("checked")),
            worktype_consulting: Number($("[name='checkbox-worktype-9']").prop("checked")),
            study_english: Number($("[name='checkbox-study-english']").prop("checked")),
            study_foreignlanguage: Number($("[name='checkbox-study-foreignlanguage']").prop("checked")),
            study_it: Number($("[name='checkbox-study-it']").prop("checked")),
            study_business: Number($("[name='checkbox-study-business']").prop("checked")),
            study_caretaking: Number($("[name='checkbox-study-caretaking']").prop("checked")),
            study_housework: Number($("[name='checkbox-study-housework']").prop("checked")),
            study_liberalarts: Number($("[name='checkbox-study-liberalarts']").prop("checked")),
            study_art: Number($("[name='checkbox-study-art']").prop("checked")),
            volunteer_health: Number($("[name='checkbox-volunteer-health']").prop("checked")),
            volunteer_elderly: Number($("[name='checkbox-volunteer-elderly']").prop("checked")),
            volunteer_disable: Number($("[name='checkbox-volunteer-disable']").prop("checked")),
            volunteer_children: Number($("[name='checkbox-volunteer-children']").prop("checked")),
            volunteer_sport: Number($("[name='checkbox-volunteer-sport']").prop("checked")),
            volunteer_town: Number($("[name='checkbox-volunteer-town']").prop("checked")),
            volunteer_safety: Number($("[name='checkbox-volunteer-safety']").prop("checked")),
            volunteer_nature: Number($("[name='checkbox-volunteer-nature']").prop("checked")),
            volunteer_disaster: Number($("[name='checkbox-volunteer-disaster']").prop("checked")),
            volunteer_international: Number($("[name='checkbox-volunteer-international']").prop("checked")),
            hobby_musicalinstrument: Number($("[name='checkbox-hobby-musicalinstrument']").prop("checked")),
            hobby_chorus: Number($("[name='checkbox-hobby-chorus']").prop("checked")),
            hobby_dance: Number($("[name='checkbox-hobby-dance']").prop("checked")),
            hobby_shodo: Number($("[name='checkbox-hobby-shodo']").prop("checked")),
            hobby_kado: Number($("[name='checkbox-hobby-kado']").prop("checked")),
            hobby_sado: Number($("[name='checkbox-hobby-sado']").prop("checked")),
            hobby_wasai: Number($("[name='checkbox-hobby-wasai']").prop("checked")),
            hobby_knit: Number($("[name='checkbox-hobby-knit']").prop("checked")),
            hobby_cooking: Number($("[name='checkbox-hobby-cooking']").prop("checked")),
            hobby_gardening: Number($("[name='checkbox-hobby-gardening']").prop("checked")),
            hobby_diy: Number($("[name='checkbox-hobby-diy']").prop("checked")),
            hobby_painting: Number($("[name='checkbox-hobby-painting']").prop("checked")),
            hobby_pottery: Number($("[name='checkbox-hobby-pottery']").prop("checked")),
            hobby_photo: Number($("[name='checkbox-hobby-photo']").prop("checked")),
            hobby_writing: Number($("[name='checkbox-hobby-writing']").prop("checked")),
            hobby_go: Number($("[name='checkbox-hobby-go']").prop("checked")),
            hobby_camp: Number($("[name='checkbox-hobby-camp']").prop("checked")),
            hobby_watchsport: Number($("[name='checkbox-hobby-watchsport']").prop("checked")),
            hobby_watchperformance: Number($("[name='checkbox-hobby-watchperformance']").prop("checked")),
            hobby_watchmovie: Number($("[name='checkbox-hobby-watchmovie']").prop("checked")),
            hobby_listenmusic: Number($("[name='checkbox-hobby-listenmusic']").prop("checked")),
            hobby_reading: Number($("[name='checkbox-hobby-reading']").prop("checked")),
            hobby_pachinko: Number($("[name='checkbox-hobby-pachinko']").prop("checked")),
            hobby_karaoke: Number($("[name='checkbox-hobby-karaoke']").prop("checked")),
            hobby_game: Number($("[name='checkbox-hobby-game']").prop("checked")),
            hobby_attraction: Number($("[name='checkbox-hobby-attraction']").prop("checked")),
            hobby_train: Number($("[name='checkbox-hobby-train']").prop("checked")),
            hobby_car: Number($("[name='checkbox-hobby-car']").prop("checked")),
            trip_daytrip: Number($("[name='checkbox-trip-daytrip']").prop("checked")),
            trip_domestic: Number($("[name='checkbox-trip-domestic']").prop("checked")),
            trip_international: Number($("[name='checkbox-trip-international']").prop("checked")),
            sport_baseball: Number($("[name='checkbox-sport-baseball']").prop("checked")),
            sport_tabletennis: Number($("[name='checkbox-sport-tabletennis']").prop("checked")),
            sport_tennis: Number($("[name='checkbox-sport-tennis']").prop("checked")),
            sport_badminton: Number($("[name='checkbox-sport-badminton']").prop("checked")),
            sport_golf: Number($("[name='checkbox-sport-golf']").prop("checked")),
            sport_gateball: Number($("[name='checkbox-sport-gateball']").prop("checked")),
            sport_bowling: Number($("[name='checkbox-sport-bowling']").prop("checked")),
            sport_fishing: Number($("[name='checkbox-sport-fishing']").prop("checked")),
            sport_swimming: Number($("[name='checkbox-sport-swimming']").prop("checked")),
            sport_skiing: Number($("[name='checkbox-sport-skiing']").prop("checked")),
            sport_climbing: Number($("[name='checkbox-sport-climbing']").prop("checked")),
            sport_cycling: Number($("[name='checkbox-sport-cycling']").prop("checked")),
            sport_jogging: Number($("[name='checkbox-sport-jogging']").prop("checked")),
            sport_walking: Number($("[name='checkbox-sport-walking']").prop("checked"))
        };
        //alert(JSON.stringify(JSONdata));
        $.ajax({
            type: 'POST',
            data: JSON.stringify(JSONdata),
            dataType: "jsonp",
            jsonp: 'jsoncallback',
            url: baseurl+'model/registerWorktype.php',
            //timeout: 10000,
            success: function(data){
                swal({
                        title: "成功",
                        text: "登録しました",
                        type: "success"},
                    function(isConfirm){
                        if(isConfirm){
                            window.location.href = "worktypelist.php?groupno=" + groupno;
                        }
                    });
            },
            error: function(){
                sweetAlert("エラー", "登録できませんでした", "error");
            }
        });
    }
}