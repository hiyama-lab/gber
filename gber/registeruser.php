<!DOCTYPE html>
<html>
<head>
    <?php include("./common/commonhead.php"); ?>
</head>
<body>
<div data-role="page">

    <!-- HEADER -->
    <?php include("./common/header.php"); ?>

    <!-- CONTENT -->
    <div data-role="content">
        <form id="register-form">
            <label for="mail">メールアドレス</label>
            <input type="text" id="mail" size="30" name="mail"
                   autocapitalize="none" required/></br>
            <label for="nickname">ニックネーム</label>
            <input type="text" id="nickname" size="20" name="nickname"
                   autocapitalize="none" required/></br>
            <label for="pass">パスワード</label>
            <input type="password" id="pass" name="pass" required/></br>
            <label for="pass_re">パスワード(再入力)</label>
            <input type="password" id="pass_re" name="pass_re" required/></br>
            <input type="button" value="登録する" data-theme="b" name="go"
                   onClick="registerData();"/>
        </form>
    </div>

    <?php include("./common/commonFooter.php"); ?>
    <script type="text/javascript" src="js/registerUser.js"></script>
</div>
</body>
</html>
