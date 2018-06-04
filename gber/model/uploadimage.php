<?php
header('Content-type: text/plain; charset=UTF-8');

include __DIR__ . '/../lib/mysql_credentials.php';
require_once __DIR__ . '/../lib/auth.php';

$userno = mysql_real_escape_string($_GET['userno']);

require_logined_session();
if (!authorize($_SESSION['userno'], ROLE['USER'], ['userno' => $userno])){
    http_response_code(403);
    exit;
}

$img = addslashes(file_get_contents($_FILES['file']['tmp_name']));

$dat = pathinfo($_FILES['file']['name']);
$extension = $dat['extension'];
$mime = "";

$new_image;
$original_image;
$new_width;
$new_height;
$original_width;
$original_height;

if ($extension == "jpg" || $extension == "jpeg") {
    $mime = "image/jpeg";
    /*
        $original_image = ImageCreateFromJPEG($_FILES['file']['tmp_name']); //JPEGファイルを読み込む
        $new_width = 80;
        $original_width = ImageSx($original_image);
        $original_height = ImageSy($original_image);
        //元画像の比率を計算し、高さを設定
        $proportion = $original_width / $original_height;
        $new_height = $new_width / $proportion;
        //高さが幅より大きい場合は、高さを幅に合わせ、横幅を縮小
        if($proportion < 1){
            $new_height = $new_width;
            $new_width = $new_width * $proportion;
        }
        $new_image = ImageCreateTrueColor($new_width, $new_height); // 画像作成
    */
} elseif ($extension == "gif") {
    $mime = "image/gif";
    /*
        $original_image = ImageCreateFromGIF($_FILES['file']['tmp_name']); //GIFファイルを読み込む
        $new_width = 80;
        $original_width = ImageSx($original_image);
        $original_height = ImageSy($original_image);
        //元画像の比率を計算し、高さを設定
        $proportion = $original_width / $original_height;
        $new_height = $new_width / $proportion;
        //高さが幅より大きい場合は、高さを幅に合わせ、横幅を縮小
        if($proportion < 1){
            $new_height = $new_width;
            $new_width = $new_width * $proportion;
        }
        $new_image = ImageCreateTrueColor($new_width, $new_height); // 画像作成
        $alpha = imagecolortransparent($original_image);  // 元画像から透過色を取得する
        imagefill($new_image, 0, 0, $alpha);       // その色でキャンバスを塗りつぶす
        imagecolortransparent($new_image, $alpha); // 塗りつぶした色を透過色として指定する
    */
} elseif ($extension == "png") {
    $mime = "image/png";
    /*
        $original_image = ImageCreateFromPNG($_FILES['file']['tmp_name']); //PNGファイルを読み込む
        $new_width = 80;
        $original_width = ImageSx($original_image);
        $original_height = ImageSy($original_image);
        //元画像の比率を計算し、高さを設定
        $proportion = $original_width / $original_height;
        $new_height = $new_width / $proportion;
        //高さが幅より大きい場合は、高さを幅に合わせ、横幅を縮小
        if($proportion < 1){
            $new_height = $new_width;
            $new_width = $new_width * $proportion;
        }
        $new_image = ImageCreateTrueColor($new_width, $new_height); // 画像作成
        imagealphablending($new_image, false);  // アルファブレンディングをoffにする
        imagesavealpha($new_image, true);       // 完全なアルファチャネル情報を保存するフラグをonにする
    */
} else {
    // 何も当てはまらなかった場合の処理は書いてませんので注意！
    return;
}

/*
// 元画像から再サンプリング
ImageCopyResampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);
// 画像をバッファに保存
ob_start();
if ($extension == "jpg" || $extension == "jpeg") {
    ImageJPEG($new_image);
} elseif ($extension == "gif") {
    ImageGIF($new_image);
} elseif ($extension == "png") {
    ImagePNG($original_image);
}
$insert_img = ob_get_contents();
ob_end_clean();
// メモリを開放する
//imagedestroy($new_image);
imagedestroy($original_image);
*/

mysql_query("UPDATE photodata SET photodata='" . $img . "', mime='" . $mime
    . "' WHERE userno='" . $userno . "'") or die ('Error: ' . mysql_error());

mysql_close($con);

?>