<?php include('controller/database.php');?>
<?php
    compSum();
    $tasks = taskAll();
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/cal-heatmap/3.6.2/cal-heatmap.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.6.2/cal-heatmap.css" />
    <meta charset="UTF-8">
    <title>mission6|プログラミング学習記録管理</title>
</head>

<body>
    <div>
        <?php include('header.html');?>
        
    	<h2>今日のタスク</h2>
    	<table>
    	<?php if(count($tasks) == 0): ?>
            <tr>
                <td>タスクがありません</td>
            </tr>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo $task['task_name'];?></td>
                    <td><a href="edit.php?id=<?php echo $task['id'];?>">編集</a></td>
                    <td><a href="delete.php?id=<?php echo $task['id'];?>">削除</a></td>
                </tr>
            <?php endforeach; ?>
            </table>
        <?php endif; ?>
	</div>
	<div id="cal-heatmap"></div>
    <script type="text/javascript" src="js/heatmap.js"></script>

</body>

</html>