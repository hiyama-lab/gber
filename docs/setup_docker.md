# 概要

このドキュメントでは、Docker ComposeでGBERを起動する手順を解説します。

## 事前準備

GBERを導入する前に、以下の作業を行ってください。

* [Google Maps Javascript APIのAPIキーを取得](https://developers.google.com/maps/documentation/javascript/get-api-key#key)
* [SendGridのアカウント作成・APIキーを取得](https://sendgrid.kke.co.jp/plan/)

## 必要環境

* Docker 1.13+
* Docker Compose 1.13+

## 導入手順

### 1. gberリポジトリをclone

```bash
$ git clone https://github.com/hiyama-lab/gber.git
```

### 2. 設定

MYSQLのユーザ・パスワード、Google MAPのAPI Key、SendGridのAPI Keyを設定します。

```bash
$ cd gber
$ cp config/gber.env.example config/gber.env
$ edit config/gber.env
```

### 3. GBERを起動

Docker ComposeでGBERを起動します。

```bash
$ docker-compose up
```

### 4. 動作確認

ブラウザからhttpまたはhttpsでアクセスするとログイン画面が表示されます。

* <http://localhost:8080/>
* <https://localhost:8443/>
