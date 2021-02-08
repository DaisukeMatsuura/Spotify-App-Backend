# Spotify Serach App's Backend API Server

### 概要

フロント側 [SpotifySearchApp](https://github.com/DaisukeMatsuura/SpotifySearchApp) のバックエンド

Laravel の マイクロフレームワーク [Lumen](https://lumen.laravel.com/) でAPIサーバーを構築した

### 技術要件
```
・Lumen v8.2.2
```

### エンドポイント

```
プレフィックス /api/
GET    favorites       Favorite一覧取得
POST   faovrites       Favorite登録
GET    favorites/{id}  id が {id} のFavorite取得
DELETE favorites/{id}  id が {id} のFavorite削除
```
