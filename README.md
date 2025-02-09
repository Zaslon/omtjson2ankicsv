# omtjson2ankicsv
このリポジトリは、omt-json形式の辞書をanki用のcsvにするものである。

## 想定仕様
- 出力：'word', 'title', 'translation', 'title2', 'translation2', 'title3', 'translation3', 'definition', 'ralation'
- 訳語が複数ない場合は空欄で埋める
- 定義が無い場合は空欄で埋める
- relationは同義語を想定しているため、表示側で = を付けて表示される。
