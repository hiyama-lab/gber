<p align="center"><img src="gber.png" alt="GBER" width=230></p>

<p align="center">
<a href="https://github.com/hiyama-lab/gber/releases/tag/1.1.0"><img src="https://img.shields.io/badge/Version-v1.1.0-orange.svg" alt="Version"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/License-Apache%202.0-blue.svg" alt="License"></a>
</p>

<p align="center">
元気高齢者の地域活動をサポートするウェブプラットフォーム
</p>

## GBERとは

GBER (ジーバー) とは元気高齢者の地域活動をサポートするウェブプラットフォームです。英訳の “Gathering Brisk Elderly in the Region” の頭文字を取り名付けました。

少子高齢化の諸問題解決と、元気高齢者の生きがいのため、高齢者就労が求められています。しかし従来のフルタイム制は、時間、体力などの問題から非現実的でした。そこで高齢者に適した働き方として、『モザイク型就労』を提唱しています。

モザイク型就労とは、就労条件をスキル、時間、場所の3つに分け、それらを組み合わせることで、仮想的に一人分の労働力として提供する仕組みです。GBERでは、それらの情報を抽出し、ジョブマッチングを実現しています。

セカンドライフにおいて、趣味仲間やコミュニティへの所属は大きな課題です。しかしコミュニティや地域のイベント情報は検索できる形で情報化されていることは少なく、自分にあった活動や組織を見つけることが難しいという問題がありました。そこで、GBERではそれらの情報を電子化し簡単に検索できるように整理することで、オープンで活発な地域コミュニティの創成を目指します。

詳しくは <http://gber.jp/> をご覧ください。

## 使い方

### デモを試す

Dockerを使ってすばやくデモ版GBERを立ち上げます。 → [デモの試し方](docs/setup_demo.md)

### クラウドで動かす

GBERを運用する際は、クラウド（Google Cloud Platform） 上にデプロイします。 → [デプロイのやり方](docs/setup_gke.md)

### 開発者の方へ

開発時は、Docker Composeを使ってローカルマシン上で動かします。 → [Docker Composeを使ったセットアップ](docs/setup_docker.md)

Docker imageの作り方はこちら → [Docker imageの作り方](docs/development.md)

## ライセンス

Copyright 2018 [Senior Cloud Project](http://sc.cyber.t.u-tokyo.ac.jp/), The University of Tokyo

GBERは[Apache License, Version 2.0](LICENSE)に基づいてライセンスされています。

本研究の一部は科学技術振興機構（JST）の研究成果展開事業【戦略的イノベーション創出推進プログラム】（S-イノベ）の支援によって行われたものです。
