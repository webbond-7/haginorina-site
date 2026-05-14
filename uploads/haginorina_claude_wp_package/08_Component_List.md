# Component List：コンポーネント定義

## 1. Header

### 要素

- Logo
- Navigation
- Icon button

### 仕様

- PC：横並び
- SP：ハンバーガーまたは横スクロールnav
- 背景は透明または白系
- navはpill型

## 2. Logo

### 表記

- Haginorina
- Official Fan Site

### 方向性

- 丸み
- 軽やか
- 小さな花アイコン

## 3. Navigation Pill

### 項目

- 活動紹介
- Photo / Gallery
- News / Info
- Contact & SNS

### 状態

- default
- hover
- active

### 見た目

- 白背景
- 黄色active
- 角丸999px
- 軽い影

## 4. Hero Text Block

### 要素

- 肩書き
- メインタイトル
- リード文
- CTA

### 役割

- はぎのりな様の名前と印象を伝える中心要素

## 5. Hero Image Frame

### 要素

- メイン写真
- 装飾
- バッジ

### 仕様

- 大きな角丸
- object-fit: cover
- 顔位置を優先

## 6. CTA Button

### テキスト

- 最新情報をチェック

### 仕様

- 黄色背景
- 角丸pill
- 右矢印
- hoverで少し浮く

## 7. SNS Icon Group

### アイコン

- X
- Instagram
- YouTube

### 仕様

- 丸アイコン
- hoverで背景色変化
- SPでは中央寄せ

## 8. Decoration System

### 種類

- Circle
- Ring
- Dot grid
- Rounded line
- Soft blob

### 注意

- 装飾は `aria-hidden="true"`
- SPでは削減
- テキストの上に重ねない

## 9. Floating Badge

### 役割

- 公式感、ファンサイト感、感謝の温度感を足す

### 表記候補

- Thank you
- for always supporting me!
- Official Fan Site

## 10. Slider Indicator

### 仕様

- 写真が複数ある時のみ表示
- 現在位置は赤または黄色
- pauseボタンも検討
