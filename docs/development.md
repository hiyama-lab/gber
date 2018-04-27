# 概要

このドキュメントでは、GBER開発者向けの情報をまとめます。

## gberイメージのビルド

gberは `gber-nginx`, `gber-php`, `gber-mysql` の3つのイメージで構成されています。  
イメージのビルド方法は以下の通りです。

```bash
# 各イメージを個別にビルド
$ docker build -f docker/nginx/Dockerfile -t gber-nginx .
$ docker build -f docker/php/Dockerfile -t gber-php .
$ docker build -f docker/mysql/Dockerfile -t gber-mysql .

# 3つのイメージを全てビルド
$ docker-compose build
```

## gber-demoイメージのビルド

gber-demoは `gber-demo` という1つのイメージで構成されています。  
イメージのビルド方法は以下の通りです。

```bash
# イメージをビルド
$ docker build -f docker/demo/Dockerfile -t gber-demo .
```
