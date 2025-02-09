<?php
	function json_to_csv()
	{
		$url = "idyer.json"; //url指定なら他ユーザーに対する権限が必要。相対指定ならオーナーとしてアクセスする模様。
		$json = file_get_contents($url);
		$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		$arr = json_decode($json,true);

		$returns = array();

		// $returns[] = ['word', 'title', 'translation', 'title2', 'translation2', 'title3', 'translation3', 'definition', 'abbreviation'];

		foreach($arr["words"] as $entryId => $word){
			$translations = $word["translations"];
			$return = &$returns[$entryId];//参照渡しとすることで、returnを編集するとreturnsの各要素も変更される
			$return[] = $word["entry"]["form"];
			// 訳語を繋げる
			foreach($translations as $i => $translation){
				$text = '';
				$return[] = $translation["title"];
				foreach($translation["forms"] as $m => $translationForm){
					$text = $text . $translationForm;
					if($m !== array_key_last($translation["forms"])){
						$text =$text . "、";
					}
				}
				//最大3つとする
				$return[] = $text;
				if($i === 3){
					break;
				}
			}

			// 訳語が少ないものとのバランスをとる
			$return = array_pad($return, 7, "");

			// 語法の場合のみ追加
			foreach($word["contents"] as $content){
				if($content["title"] === "語法"){
					$return[] = $content["text"];
				}
			}
			$return = array_pad($return, 8, "");

			//省略語の場合最後に追加
			foreach($word["relations"] as $relation){
				if($relation["title"] === "省略"){
					$return[] = "= " . $relation["entry"]["form"];
				}
			}
			$return = array_pad($return, 9, "");

		}
		return $returns;
	}

	$out = json_to_csv();
	$fp = fopen('out.csv', 'w');
	foreach($out as $row){
		fputcsv($fp, $row);
	}
	fclose($fp);

	echo 'done ';
	echo time();