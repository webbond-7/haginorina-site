# はぎのりな Official Fan Site — Static Prototype

WordPress化前の静的プロトタイプ（HTML/CSS/JS）。

## ページ構成

- `index.html` — トップ（FV / コンセプト / Works / Profile / News / Gallery / Contact プレビュー）
- `works.html` — 活動紹介（舞台・映像・モデル・けん玉）
- `gallery.html` — Photo / Gallery（ライトボックス対応）
- `news.html` — News 一覧（カテゴリタブ + ページネーション）
- `news-single.html` — News 詳細記事（テンプレート）
- `news-category.html` — カテゴリ別 News 一覧（テンプレート）
- `contact.html` — Contact フォーム + SNS
- `sitemap.html` — 全ページのコンテンツ一覧（社内資料）
- `research-notes.html` — リサーチノート（社内資料）

## ファイル構成

```
haginorina-site/
├─ *.html
├─ assets/
│  ├─ css/style.css
│  ├─ js/main.js
│  ├─ images/  (FV + Gallery 写真)
│  └─ icons/   (tulip ロゴ)
└─ README.md
```

## ローカルでの確認

`index.html` をブラウザで開くだけで動作します（Font Awesome / Google Fonts は CDN）。

## WordPress化に向けた構造

CSS はトークン化済み（`:root` 内の CSS 変数）。各セクションは `template-parts/` 単位に切り出せる構成です。
