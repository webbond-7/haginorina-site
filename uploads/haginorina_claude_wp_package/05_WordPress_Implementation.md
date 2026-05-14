# WordPress Implementation：WordPress実装方針

## 1. 実装方針

最終的にはWordPressで実装する。  
ただし、最初からWordPress化せず、まずは静的HTML/CSS/JSでFVを再現し、その後テーマファイルへ組み込む。

## 2. 推奨ステップ

### Step 1：静的プロトタイプ

- `index.html`
- `style.css`
- `script.js`
- `/assets/images/`
- `/assets/icons/`

まずこの形でFVを高精度に再現する。

### Step 2：WordPressテーマ化

- `front-page.php`
- `header.php`
- `footer.php`
- `functions.php`
- `assets/css/style.css`
- `assets/js/main.js`
- `template-parts/hero.php`
- `template-parts/section-news.php`
- `template-parts/section-gallery.php`

### Step 3：管理画面対応

可能であれば Advanced Custom Fields を使用。

## 3. 投稿タイプ設計

### News / Info

WordPress標準投稿またはカスタム投稿タイプ `news`

想定フィールド：
- タイトル
- 日付
- カテゴリ
- 本文
- 外部リンク
- 表示/非表示

### Photo / Gallery

カスタム投稿タイプ `gallery`

想定フィールド：
- 写真
- キャプション
- 撮影日
- カテゴリ
- 表示順

### 活動紹介

固定ページまたはカスタム投稿タイプ `works`

想定カテゴリ：
- 舞台
- 映像
- モデル
- 執筆・詩・コラム

## 4. FV管理項目

ACF等で管理できると良い項目：

- FV肩書き
- FVメインタイトル
- FVリード文
- FV CTAテキスト
- FV CTAリンク
- FV画像 1〜5枚
- 季節テーマカラー
- 装飾ON/OFF
- SNSリンク

## 5. CSS設計

### 推奨

- CSS変数でDesign Tokensを管理
- BEMまたはコンポーネント単位の命名
- `hero`, `header`, `nav`, `button`, `decoration` など役割ごとに分割

### CSS変数例

```css
:root {
  --color-red: #E63946;
  --color-yellow: #F4D35E;
  --color-white: #FFFFFF;
  --color-bg: #F7F6F2;
  --color-text: #222222;
  --color-text-sub: #666666;

  --radius-pill: 999px;
  --radius-lg: 40px;
  --shadow-soft: 0 12px 32px rgba(0, 0, 0, 0.06);
}
```

## 6. JavaScript方針

### FVスライダー

- 写真が1枚の場合も崩れない
- 複数枚ある場合のみインジケーター表示
- 自動再生はゆっくり
- pause制御があると望ましい

### アニメーション

- CSSアニメーション中心
- 装飾円は軽く浮く
- CTA hover
- スクロール誘導

## 7. パフォーマンス

- FV画像はWebP推奨
- `srcset` でレスポンシブ画像対応
- lazyloadはFV以外に使用
- FV画像は必要に応じてpreload

## 8. SEO / アクセシビリティ

- h1は「はぎのりな」
- meta title / descriptionを設定
- alt属性を必ず設定
- navはsemanticに
- ボタンではなくリンクの場合は `a` タグを使う
- 装飾画像には `aria-hidden="true"`

## 9. WordPressでの注意点

- 管理画面で差し替える画像サイズを統一する
- 画像の顔位置が変わる可能性があるため、`object-position` を調整できる余地を残す
- ニュースやギャラリーは後から更新しやすい構造にする
- 装飾を画像化しすぎず、CSSで再現できるものはCSSで実装する
