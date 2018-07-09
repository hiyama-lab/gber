# 概要

このドキュメントでは、GBER開発者向けの情報をまとめます。

## gberイメージのビルド

gberは `gber-nginx`, `gber-php`, `gber-mysql` の3つのイメージで構成されています。
イメージのビルド方法は以下の通りです。

```bash
# 各イメージを個別にビルド
$ docker build -f docker/nginx/Dockerfile.dev -t gber-nginx .
$ docker build -f docker/php/Dockerfile.dev -t gber-php .
$ docker build -f docker/mysql/Dockerfile.dev -t gber-mysql .

# 3つのイメージを全てビルド
$ docker-compose build
```

## gberイメージの起動

開発環境ではローカルの`gber`配下にあるファイル群をgber-nginx, gber-phpコンテナにマウントする形で起動します。
そのため、初回起動時のみcomposerのinstallが必要です。正常にinstallが終了すると、ローカルに`gber/vendor`ディレクトリが生成されます。

```bash
# 起動
$ docker-compose up
# 初回起動時のみ必要
$ docker exec -it gber_php_1 composer install
```

## gber-demoイメージのビルド

gber-demoは `gber-demo` という1つのイメージで構成されています。
イメージのビルド方法は以下の通りです。

```bash
# イメージをビルド
$ docker build -f docker/demo/Dockerfile -t gber-demo .
```
