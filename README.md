# LFJBParser
ラ・フォル・ジュルネ びわ湖 2015のオープンデータのパーサー  
[「ラ・フォル・ジュルネ びわ湖 2015」に関するオープンデータについて](http://opendata.shiga.jp/lfjb2015/)

## 機能(Feature)
* 日時情報をISO8601形式に変更
* schema:Personを1人1データに変更
* 複数のschema:performerがいる場合は、配列で格納するように変更

## 使い方(Usage)
```php
//$jsonld is from http://lfjb.biwako-hall.or.jp/events/[id]/jsonld/
$parser = new LFJBParser();
$newEventData = $parser->parse($jsonld); //Parse All data
$newPerformer = $parser->parsePerformer($jsonld['schema:performer']); //Parse Only Performer
$newStartDate = $parser->parseDate($jsonld['schema:startDate']); //Parse Only Date
```