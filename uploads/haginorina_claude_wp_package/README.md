# はぎのりな様 公式サイト実装引き継ぎ一式

この一式は、Claude / Claude Code 等に渡して、最終的に WordPress 実装へ進めるための設計資料です。

## 含まれる資料

1. `01_Design.md`
   - サイト全体の世界観・コンセプト・デザイン方針

2. `02_FV_Spec.md`
   - ファーストビュー専用の実装仕様

3. `03_Design_Tokens.json`
   - 色・フォント・余白・角丸・影などの設計変数

4. `04_Responsive_Rules.md`
   - PC / Tablet / SP のレスポンシブルール

5. `05_WordPress_Implementation.md`
   - WordPress実装時の方針・構造・注意点

6. `06_Claude_Implementation_Prompt.md`
   - Claudeにそのまま渡せる実装依頼プロンプト

7. `07_Assets_List.md`
   - 必要素材・画像・ロゴ・アイコンの整理

8. `08_Component_List.md`
   - ヘッダー、FV、CTA、装飾などのコンポーネント定義

## 使用前の注意

- メインビジュアル画像、ロゴ画像、SNSリンク、実際のNews情報は仮情報を含みます。
- WordPress化する前に、まず静的HTML/CSS/JSで再現し、その後テーマファイルへ分解する想定です。
- 装飾は世界観を作るための重要要素ですが、スマホでは量を減らして可読性を優先してください。
