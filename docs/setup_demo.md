# 概要

このドキュメントでは、デモ用のイメージgber-demoを起動する方法を解説します。

## 事前準備

gber-demoを導入する前に、以下の作業を行ってください。

* [Google Maps Javascript APIのAPIキーを取得](https://developers.google.com/maps/documentation/javascript/get-api-key#key)

## 必要環境

* Docker 1.13+

## 導入手順

### 1. 起動

```bash
$ docker pull hiyamalab/gber-demo
$ docker run -it -e "GOOGLE_MAP_APIKEY=<YOUR API KEY>" -p 8080:80 -p 8443:443 hiyamalab/gber-demo
```

### 2. ブラウザで開く

ブラウザからhttpまたはhttpsでアクセスするとログイン画面が表示されます。

* <http://localhost:8080/>
* <https://localhost:8443/>

### 3. ログイン

初期状態で以下の2ユーザが登録されているので、お好きなほうでログインしてください。

|       | メールアドレス        | パスワード |
| :---- | :---------------- | :------- |
| ユーザ1 | user1@example.com | password |
| ユーザ2 | user2@example.com | password |
