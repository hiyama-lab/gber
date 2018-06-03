<?php
require_once __DIR__ . '/lib/sessionUtil.php';
require_unlogined_session();
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
</head>
<body>
<div data-role="page">
    <?php
    include __DIR__ . '/lib/mysql_credentials.php';
    ?>

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <div id="toplogo" margin="0 auto">
            <img src="./img/logo1.svg"/></br>
        </div>
        <?php
        if($_ENV["IS_DEMO"] === 'true'){
            ?>
            <div id="demo-account">
                <h2>デモ用アカウント</h2>
                <dl>
                    <dt>メールアドレス:</dt><dd>user1@example.com</dd>
                    <dt>パスワード:</dt><dd>password</dd>
                </dl>
                <dl>
                    <dt>メールアドレス:</dt><dd>user2@example.com</dd>
                    <dt>パスワード:</dt><dd>password</dd>
                </dl>
            </div>
            <?php
        }
        ?>
        <form id="login-form" autocomplete="on" data-ajax="false" method="post"
              action="model/login.php">
            <label for="mail">メールアドレス</label>
            <input type="text" id="mail" size="30" name="mail"
                   autocapitalize="none" required/></br>
            <label for="pass">パスワード</label>
            <input type="password" id="pass" name="pass" required/></br>
            <input type="hidden" name="token" value="<?=h(generate_token())?>">
            <input type="submit" value="ログインする" name="go"/>
        </form>
        </br>
        <button class="ui-btn ui-btn-c"
                onclick="location.href='help.php?register=true'">GBERとは
        </button>
        <button class="ui-btn" onclick="location.href='registeruser.php'">新規登録
        </button>
    </div>

    <?php include("./common/commonFooter.php"); ?>
</div>
</body>
</html>
