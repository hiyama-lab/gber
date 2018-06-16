# 概要

このドキュメントでは、Google Kubernetes Engine (GKE) 上にGBERをデプロイし、外部に公開するまでの手順を解説します。

## 事前準備

GBERを導入する前に、以下の作業を行ってください。

* [Google Platform Projectを作成](https://cloud.google.com/resource-manager/docs/creating-managing-projects)
* [Google Maps Javascript APIのAPIキーを取得](https://developers.google.com/maps/documentation/javascript/get-api-key#key)
* [SendGridのアカウント作成・APIキーを取得](https://sendgrid.kke.co.jp/plan/)

## 導入手順

### 1. Google Cloud Shellの準備

[Google Cloud Console](https://console.cloud.google.com)にアクセスし、使用するプロジェクトを選択します。  
その後、右上の「Google Cloud Shellを有効化」のアイコンをクリックしShellを表示します。  
以後は画面下部に出現したShell上で作業を行います。

### 2. Kubernetesクラスタの作成

`us-central1-a` に `gber` という名前のKubernetesクラスタを作成します。

```bash
$ gcloud config set compute/zone us-central1-a
$ gcloud container clusters create gber
$ gcloud container clusters get-credentials gber
```

### 3. Secretの登録

DBのパスワードやAPI Keyなどのcredential情報をGKEに登録します。

```bash
$ git clone https://github.com/hiyama-lab/gber.git
$ cd gber
$ git checkout 1.0.3
$ cp config/gber.env.example config/gber.env
$ vi config/gber.env
$ kubectl create secret generic gber-secrets --from-env-file config/gber.env
```

### 4. Persistent Diskの作成

GBERのデータを永続化するためのDiskを用意します。  
ユーザ情報や仕事の情報などGBERのデータはこのDiskに保管され、kubernetes clusterが削除されてもこれらのデータは残ります。

```bash
$ gcloud compute disks create --size 20GB gber-disk
```

### 5. static ipの予約

GBERを公開するためのグローバルIPアドレスを予約します。

```bash
$ gcloud compute addresses create gber-static-ip --global
```

以下のコマンドで予約されたIPアドレスを確認できます。

```bash
$ gcloud compute addresses list
NAME            REGION  ADDRESS        STATUS
gber-static-ip          35.190.37.204  RESERVED
```

### 6. ドメイン名の取得とDNSレコードの設定

この手順では、何らかのドメイン名登録事業者を通じてドメイン名 (例: `example.com`) を取得し、ネームサーバの設定で `example.com` が手順5で予約したIPアドレスに解決されるようにします。

ドメインのDNSレコードはネームサーバによって管理されます。ネームサーバはドメインが登録された場所（登録事業者）にすることも、[Google Cloud DNS](https://cloud.google.com/dns/)やその他のサードパーティプロバイダなどのDNSサービスにすることもできます。

* ネームサーバがGoogle Cloud DNSの場合: [クラウドDNSクイックスタートガイド](https://cloud.google.com/dns/quickstart#create_a_new_record)に従って、アプリケーションの予約済みのIPアドレスでドメイン名のDNS Aレコードを設定します。

* ネームサーバが他のプロバイダの場合: DNS Aレコードの設定に関するDNSサービスのドキュメントを参照して、ドメイン名を設定します。

### 7. SSL証明書の取得と設定（手動）

この手順では、外部のSSL証明書プロバイダにおいて手動で取得したSSL証明書を設定します。  
Let's Encryptを使ってSSL証明書を自動で発行・設定する場合はこの手順をskipし、手順8に進んでください。

まずSSL証明書を取得します。SSL証明書の取得方法はSSL証明書プロバイダのサイトを参考にしてください。  
SSL証明書が取得できたら、以下の手順で設定を行います。

```bash
# 取得したSSL証明書と鍵をserver.crt,server.keyという名前で保存
$ vi server.crt
$ vi server.key
# 以下の出力をそれぞれコピーし、certificate.yamlのtls.crt, tls.keyを上書き
$ cat server.crt | base64
$ cat server.key | base64
$ vi kubernetes/cert-custom/certificate.yaml
# 証明書をsecretとして登録
$ kubectl create -f kubernetes/cert-custom/certificate.yaml
# 登録されたsecretを確認
$ kubectl get secret gber-cert -o yaml
```
 
### 8. デプロイ

GKE上にGBERをデプロイします。

```bash
$ kubectl apply -f kubernetes/gber.yaml
```

以下のような出力が得られればデプロイ成功です。この状態になるまで数分かかることがあります。

```bash
$ kubectl get pod
NAME                     READY     STATUS    RESTARTS   AGE
mysql-3059556571-kcr4b   1/1       Running   0          1m
nginx-1309960431-h77zr   1/1       Running   0          2m
php-2471182301-khslg     1/1       Running   0          2m

$ kubectl get svc
NAME         TYPE        CLUSTER-IP      EXTERNAL-IP   PORT(S)        AGE
kubernetes   ClusterIP   10.43.240.1     <none>        443/TCP        24m
mysql        ClusterIP   10.43.249.122   <none>        3306/TCP       1m
nginx        NodePort    10.43.247.181   <none>        80:30775/TCP   2m
php          ClusterIP   10.43.250.49    <none>        9000/TCP       2m

$ kubectl get ingress
NAME           HOSTS     ADDRESS         PORTS     AGE
gber-ingress   *         35.190.37.204   80        2m
```

### 9. SSL証明書の取得と設定（自動）

この手順はLet's EncryptのSSL証明書を自動で設定する場合の手順です。  
手順7で既にSSL証明書の設定をしている場合はスキップしてください。

cert-managerを使って、Let's EncryptのSSL証明書を自動で取得してIngressに設定します。    
設定の前に、あらかじめ取得したドメイン名がグローバルIPに解決される状態であることを確認してください（手順6）

以下の手順でLet's EncryptのSSL証明書を設定します。

```bash
# cert-managerのinstallのためhelmをinstall
$ curl https://raw.githubusercontent.com/kubernetes/helm/master/scripts/get | bash

# helmの初期設定
$ kubectl create serviceaccount tiller --namespace kube-system
$ kubectl create clusterrolebinding tiller --clusterrole=cluster-admin --serviceaccount=kube-system:tiller
$ helm init --upgrade --service-account tiller

# cert managerをinstall
$ cd
$ git clone https://github.com/jetstack/cert-manager
$ cd cert-manager
$ git checkout -b v0.2.3
$ helm install --name cert-manager --namespace kube-system contrib/charts/cert-manager

# issuer.yamlのemailにLet's Encryptから通知を受け取るメールアドレスを設定
$ cd ~/gber
$ vi kubernetes/cert-letsencrypt/issuer.yaml
# issuerをdeploy
$ kubectl create -f kubernetes/cert-letsencrypt/issuer.yaml

# certificate.yamlに取得する証明書のドメインを設定
$ vi kubernetes/cert-letsencrypt/certificate.yaml
# certificateをdeploy（証明書を取得してsecretに登録）
$ kubectl create -f kubernetes/cert-letsencrypt/certificate.yaml

# デプロイに成功すると、gber-certシークレットに証明書と鍵が登録されます
$ kubectl get secret gber-cert -o yaml 
# certificateのログでも証明書が正常に取得できたことを確認できます
$ kubectl describe certificate gber-cert
```

### 10. 動作確認

ブラウザからIPアドレスまたはドメインにアクセスし、GBERのログイン画面が表示されることを確認します。  
GKEの設定反映に5~10分程度要するため、手順8を実行してから10分以上経過したあとにアクセスしてください。

### 11. adminアカウントのメールアドレス・パスワードの変更（必須）
GBERにはデフォルトで以下のadminアカウントが登録されています。  
初回ログイン時にadminアカウントでログインし、必ずメールアドレスとパスワードを変更してください。

|       | メールアドレス        | パスワード |
| :---- | :---------------- | :------- |
| adminアカウント | admin@example.com | gber_admin |
