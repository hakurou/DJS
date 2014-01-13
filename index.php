<?php

function __autoload($name)
{
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $name);
    $classPath = dirname(__FILE__).DIRECTORY_SEPARATOR.$classPath.'.class.php';
    
    require_once($classPath);
}

// TESTS

$i = new \djs\DJS();
$js = $i->parseFile(dirname(__FILE__).'/demo/main.djs');


?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			li
			{
				cursor: pointer;
			}
			
			blockquote
			{
				background-color: #EEEEFF;
				padding: 10px;
				border-radius: 10px;
				font-family: "lucida console";
				font-size: 12px;
				box-shadow: 0px 0px 5px #777;
			}
		</style>
	</head>
	<body>
		<h1>Todo list</h1>
		<input type="text" id="todo_input" />
		<ul id="todo_list"></ul>
		<div><span id="todo_nb_items">0 item(s)</span></div>		
		
		<script><?php echo $js; ?></script>
		
		<p><h2>Generated code:</h2></p>
		<blockquote><?php echo $js; ?></blockquote>
	</body>
</html>