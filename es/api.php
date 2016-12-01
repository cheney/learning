<?php
/*
*Elasticsearch-php full demo
*document URL http://es.xiaoleilu.com/
*@author cheney(phpfun@126.com)
*/

require'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
$hosts=array('127.0.0.1:9200');
$client=ClientBuilder::create() -> setHosts($hosts) -> build();
$id = $_REQUEST['id'];
echo "<pre>";
/**
*插入数据
*/
// $content = array(
// 	"first_name"=>"John","last_name"=> "Smith","age"=>25,
//     "about"=>"I love to go rock climbing","interests"=>array("sports", "music")
// );
// $params = ['index' => 'megacorp','type' => 'employee','id' => 1,'body' => $content];
// $response = $client->index($params);
// var_dump($response);
// $content = array(
// 	"first_name"=> "Jane","last_name"=>  "Smith","age"=>32,
//     "about"=>"I like to collect rock albums","interests"=>array("music")
// );
// $params = ['index' => 'megacorp','type' => 'employee','id' => 2,'body' => $content];
// $response = $client->index($params);
// var_dump($response);
// $content = array(
// 	"first_name"=> "Douglas","last_name"=>  "Fir","age"=>35,
//     "about"=>"I like to build cabinets","interests"=>array("forestry")
// );
// $params = ['index' => 'megacorp','type' => 'employee','id' => 3,'body' => $content];
// $response = $client->index($params);
// var_dump($response);
/*
*读取数据
*/
// $params = ['index' => 'megacorp','type' => 'employee','id' => $id];
// $response = $client->get($params);
// print_r($response);
/*
*查询所有数据
*/
// $params = array('index' => 'megacorp','type' => 'employee','body' => '');
// $response = $client->search($params);
// print_r($response);
/*
*查询所有数据(分页+超时)
*/
// $params = array('from'=>5,'size'=>10,'timeout'=>'10ms','body' => '');
// $response = $client->search($params);
// print_r($response);
/*
查询某一字段
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'query'=>array('match'=>array('last_name'=>'Smith'))
//     )
// );
// $response = $client->search($params);
// print_r($response);
/*
加过滤器查某一条件
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'query'=>array(
// 	    	'filtered'=>array(
// 	    		'filter'=>array('range'=>array('age'=>array('gt'=>'30'))),
// 	    		'query'=>array('match'=>array('last_name'=>'smith'))
// 		    )//end filtered
//     	)//end query
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
全文搜索
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'query'=>array(
// 	    	'match'=>array(
// 		    	'about'=>'rock climbing'
// 		    )//end match
//     	)//end query
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
短语搜索(精确)
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'query'=>array(
// 	    	'match_phrase'=>array('about'=>'rock climbing')
//     	)//end query
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
搜索高亮(注意空对象{})
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'query'=>array(
// 	    	'match_phrase'=>array(
// 		    	'about'=>'rock climbing'
// 		    )//end match
//     	),//end query
//     	'highlight'=>array(
//     		'fields'=>array(
//     			'about'=>new \stdClass()
//     		)//end fields
//     	)//end hl
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
分析统计（兴趣爱好最大共同点）
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'aggs'=>array(
// 	    	'all_interests'=>array(
// 		    	'terms'=>array(
// 		    		'field'=>'interests'
// 		    	)//end terms
// 		    )//end all
//     	)//end aggs
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
分析统计（加条条件）
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'query'=>array(
//     		'match'=>array(
//     			'last_name'=>'smith'
//     		)
//     	),//end query
//     	'aggs'=>array(
// 	    	'all_interests'=>array(
// 		    	'terms'=>array(
// 		    		'field'=>'interests'
// 		    	)//end terms
// 		    )//end all
//     	)//end aggs
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
聚合分级汇总
*/
// $params = array('index' => 'megacorp','type' => 'employee',
//     'body' => array(
//     	'aggs'=>array(
// 	    	'all_interests'=>array(
// 		    	'terms'=>array(
// 		    		'field'=>'interests'
// 		    	),
// 		    	'aggs'=>array(
// 		    		'avg_age'=>array(
// 		    			'avg'=>array('field'=>'age')
// 		    		)
// 		    	)
// 		    )//end all
//     	)//end aggs
//     )//end body
//  );
// $response = $client->search($params);
// print_r($response);
/*
集群健康
*/
//$response = $client->cluster()->health();
//print_r($response);
/*
分片分配
*/
// $params = array('index' => 'blogs','type'=>'',
//     'body' => array(
//     	'settings'=>array('number_of_shards'=>3,'number_of_replicas'=>1)
//     )
//  );
// $response = $client->create($params);
// print_r($response);
/*
返回指定字段
*/
// $params = [
//     'index' => 'megacorp',
//     'type' => 'employee',
//     'id' => $id,
//     '_source'=>array('first_name','last_name')
// ];
// $response = $client->getSource($params);
// print_r($response);
/*
仅仅判断文档是否存在
*/
// $params = [
//     'index' => 'megacorp',
//     'type' => 'employee',
//     'id' => $id,
// ];
// try {
// 	$response = $client->exists($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
/*
更新整个文档（_version会变）
*/
// $content = array("first_name"=> "Douglas","last_name"=>  "Fir","age"=>35,
//     "about"=>"I like to build cabinets","interests"=>array("forestry")
// 	);
// $params = [
//     'index' => 'megacorp',
//     'type' => 'employee',
//     'id' => 1,
//     'body' => $content
// ];
// $response = $client->index($params);
// var_dump($response);
/*
删除文档
*/
// $content = array("first_name"=> "Douglas","last_name"=>  "Fir","age"=>35,
//     "about"=>"I like to build cabinets","interests"=>array("forestry")
// 	);
// $params = [
//     'index' => 'megacorp',
//     'type' => 'employee',
//     'id' => 4,
//     'body' => $content
// ];
// $response = $client->index($params);
// var_dump($response);
// $params = [
//     'index' => 'megacorp',
//     'type' => 'employee',
//     'id' => 4,
// ];
// try {
// 	$response = $client->delete($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
/*
乐观并发控制
*/
// $content = array("title"=> "My first blog entry",
// 				 "text"=>"Just trying this out.."
// 			);
// $params = ['index' => 'website','type' => 'blog','id' => 1,'body' => $content];
// $response = $client->index($params);
// var_dump($response);
// $params = ['index' => 'website','type' => 'blog','id' => 1];
// try {
// 	$response = $client->get($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
// $content = array("title"=> "My first blog entry",
// 				 "text"=>"Starting to get the hang of this..."
// 			);
// $params = ['index'=>'website','type'=>'blog','id'=>1,'version'=>1,'body'=>$content];
// try {
// 	$response = $client->index($params);
// 	echo "when version is 1 update";
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "version is error !";
// }
// $params = ['index'=>'website','type'=>'blog','id'=>1,'version'=>1,'body'=>$content];
// try {
// 	$response = $client->index($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "version is error !";
// }
/*
外部版本控制
*/
// $content = array("title"=> "My first external blog entry",
// 				 "text"=>"Starting to get the hang of this..."
// 			);
// $params = ['index' => 'website','type' => 'blog','id' => 2,'version'=>5,
// 			'version_type'=>'external','body' => $content];
// $response = $client->index($params);
// echo "create !";
// var_dump($response);
// $params = ['index' => 'website','type' => 'blog','id' => 2];
// try {
// 	$response = $client->get($params);
// 	echo "get";
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
// $content = array("title"=> "My first external blog entry",
// 				 "text"=>"This is a piece of cake..."
// 			);
// $params = ['index'=>'website','type'=>'blog','id'=>2,'version'=>10,
// 			'version_type'=>'external','body'=>$content];
// try {
// 	$response = $client->index($params);
// 	echo "update";
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "version is error !";
// }
/*
文档局部更新(依赖上面创建的文档)
*/
// $content = array('doc'=>array("tags"=> array("testing"),"view"=>0));
// $params = ['index'=>'website','type'=>'blog','id'=>1,'body'=>$content];
// try {
// 	$response = $client->update($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "update error!";
// }
// $params = ['index' => 'website','type' => 'blog','id' => 1];
// try {
// 	$response = $client->get($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
/*
脚本局部更新
修改 elasticsearch.yml
添加：(注意script前加空格)
script.engine.groovy.inline.search: on
script.engine.groovy.inline.update: on
*/
// $content = array('script'=>'ctx._source.view+=1');
// $params = ['index'=>'website','type'=>'blog','id'=>1,'body'=>$content];
// try {
// 	$response = $client->update($params);
// } catch (Exception $e) {
// 	echo "update error!";
// }
// $content = array('script'=>'ctx._source.tags+=new_tag',
// 			'params'=>array('new_tag'=>'search')
// 	);
// $params = ['index'=>'website','type'=>'blog','id'=>1,'body'=>$content];
// try {
// 	$response = $client->update($params);
// } catch (Exception $e) {
// 	echo "update error!";
// }
// //delete doc
// $content = array('script'=>"ctx.op = ctx._source.view == count ? 'delete' : 'none'",
// 			'params'=>array('count'=>200)
// 	);
// $params = ['index'=>'website','type'=>'blog','id'=>1,'body'=>$content];
// try {
// 	$response = $client->update($params);
// } catch (Exception $e) {
// 	echo "update error!";
// }
// //get 
// $params = ['index' => 'website','type' => 'blog','id' => 1];
// try {
// 	$response = $client->getSource($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
/*
更新可能不存在的文档(更新和冲突retry_on_conflict)
*/
// $content = array('script'=>'ctx._source.money+=1',
// 				'upsert'=>array('money'=>0));
// $params = ['index'=>'website','type'=>'pageviews','id'=>1,'retry_on_conflict'=>5,'body'=>$content];
// try {
// 	$response = $client->update($params);
// } catch (Exception $e) {
// 	echo "update error!";
// }
// //get 
// $params = ['index' => 'website','type' => 'pageviews','id' => 1];
// try {
// 	$response = $client->get($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
/*
检索多个文档
*/
// $params = array(
// 	'body'=>array(
// 		'docs'=>array(
// 			array('_index'=>'website','_type'=>'blog','_id'=>2),
// 			array('_index'=>'website','_type'=>'pageviews','_id'=>1,'_source'=>'money')
// 		)
// 	)
// );
// try {
// 	$response = $client->mget($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
//同一个文档
// $params = array('index'=>'website','type'=>'blog',
// 	'body'=>array(
// 		'docs'=>array(
// 			array('_id'=>2),
// 			array('_type'=>'pageviews','_id'=>3)
// 		)
// 	)
// );
// try {
// 	$response = $client->mget($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
// $params = array('index'=>'website','type'=>'blog',
// 	'body'=>array(
// 		'docs'=>array(
// 			array('_id'=>2),
// 			array('_id'=>1)
// 		)
// 	)
// );
// try {
// 	$response = $client->mget($params);
// 	var_dump($response);
// } catch (Exception $e) {
// 	echo "no found !";
// }
/*
批量操作
*/
// $params = array(
// 	'body'=>array(
// 		array('delete'=>array("_index" => "website", "_type" => "blog", "_id" => "123")),
// 		array('create'=>array("_index" => "website", "_type" => "blog", "_id" => "123")),
// 		array('title' => "My first blog post"),
// 		array("index" => array("_index" => "website", "_type" => "blog")),
// 		array("title" => "My second blog post"),
// 		array('update'=>array("_index" => "website", "_type" => "blog", "_id" => "123", "_retry_on_conflict" => 3)),
// 		array('doc' => array("title" => "My updated blog post"))
// 	)
// );
// $response = $client->bulk($params);
// var_dump($response);
/*
查询mapping信息(表结构)
*/
// $params = array('index'=>'website');
// $response = $client->indices()->getMapping($params);
// print_r($response);
/*
测试分析器
*/
// $params = array('analyzer'=>'standard','text'=>'Text to analyze test');
// $response = $client->indices()->analyze($params);
// print_r($response);
/*
添加更新映射
*/
// $content = array(
// 	'mappings'=>array(
// 		'tweet'=>array(
// 			'properties'=>array(
// 				'tweet'=>array("type" => "string","analyzer" => "english"),
// 				'date'=>array("type" => "date"),
// 				'name'=>array("type" => "string"),
// 				'user_id'=>array("type" => "long"),
// 			)
// 		)
// 	)
// );
// $params = [
//     'index' => 'gb',
//     'body' => $content
// ];
// $response = $client->indices()->create($params);
// var_dump($response);
// $content = array(
// 	'properties'=>array(
// 		'tag'=>array(
// 			'type' => 'string',
// 			'index' => 'not_analyzed'
// 		)
// 	)
// );
// $params = [
//     'index' => 'gb',
//     'type' => 'tweet',
//     'body' => $content
// ];
// $response = $client->indices()->putMapping($params);
// var_dump($response);
/*
测试映射
*/
// $params = array('index'=>'gb','field'=>'tweet','text'=>'Black-cats');
// $response = $client->indices()->analyze($params);
// print_r($response);
// $params = array('index'=>'gb','field'=>'tag','text'=>'Black-cats');
// $response = $client->indices()->analyze($params);
// print_r($response);
/*
验证查询
*/
// $params = array(
// 	'body'=>array(
// 		'query'=>array(
// 			'match'=>array('tweet'=>'really powerful')
// 		)
// 	),
// 	'explain'=>true
// );
// $response = $client->indices()->validateQuery($params);
// print_r($response);
