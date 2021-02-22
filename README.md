# Spotify Serach App's Backend API Server

### 概要

フロント側 [SpotifySearchApp](https://github.com/DaisukeMatsuura/SpotifySearchApp) のバックエンド

Laravel の マイクロフレームワーク [Lumen](https://lumen.laravel.com/) でAPIサーバーを構築した

### 技術要件
```
・Lumen v8.2.2
・jwt-auth v1.0.2
```

### エンドポイント

```
プレフィックス /api/general/
GET    favorites       Favorite一覧取得
POST   faovrites       Favorite登録
DELETE favorites/{id}  id が {id} のFavorite削除

プレフィックス /api/
POST   register        User登録
POST   login           User認証

プレフィックス /api/ , ミドルウェア auth(jwt認証)
GET    users/{user_id}/favorites       Userに紐付くFavorite一覧取得
POST   users/{user_id}/favorites       Userに紐付くFavorite登録
DELETE users/{user_id}/favorites/{favorite_id}  Userに紐付く favorite_id の Favorite削除
```
