<?php
require_once('config.php');

// ---- 解析参数 ---- //
$sid = 1;

// 判断是否存在get参数
if(is_array($_GET)&&count($_GET)>0) {
    // sid 指当前请求的物种id
    if (isset($_GET['sid'])) // 这里应该判断sid是否为整数
        $sid = $_GET['sid'];
}

// ---- 向后台请求左侧导航栏列表 ---- //
// $ch = curl_init($server_base . "get_project.php"); // fake data
$ch = curl_init($server_base_backend . "get_project"); // fetch data from server
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($ch);
$nav_list = json_decode($res);

// ---- 向后台请求项目进展信息 ---- //
// $ch = curl_init($server_base . "get_progress.php?sid=" . $sid);
$ch = curl_init($server_base_backend . "get_progress?sid=" . $sid); // fetch data from server
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($ch);
$pro_info = json_decode($res);

// 关闭cURL资源，并且释放系统资源
curl_close($ch);
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>首页</title>
    <link rel='icon' href='pics/logo.jpg' type='image/x-ico' />

		<!-- 新 Bootstrap 核心 CSS 文件 -->
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<!-- 可选的Bootstrap主题文件（一般不用引入） -->
		<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
		<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
		<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<link href="custom_css/index.css" rel="stylesheet">
		<link href="custom_css/common.css" rel="stylesheet">

  </head>

  <body>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">

      	<div class="col-md-2 logo">
          <img src="pics/logo.jpg" height="130">
        </div>

        <div class="col-md-10">
          <p class="chn-title"><b>国家重点研发计划——重要热带病传播相关的入侵媒介及其病原体的生物学特性研究</b></p>
          <div class="row">
            <div class="col-md-9 eng-title">
              <p><b>National Research and Development Plan: Research on Biological Characteristics of the Tropical Disease Related Invasion vectors and Pathogens</b><p>
            </div>
            <div class="col-md-3 search-box">
              <div id="custom-search-input">
                <div class="input-group col-md-12">
                  <input type="text" class="  search-query form-control" placeholder="搜索" />
                  <span class="input-group-btn">
                    <button class="btn btn-danger" type="button">
                      <span class=" glyphicon glyphicon-search"></span>
                    </button>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>

      <div class="container">
	      <div class="blog-masthead">
		      <div class="row">
		      	<div class="col-md-10 col-md-offset-1">
			        <nav class="blog-nav" >
			          <a class="blog-nav-item" href="index.html">首页</a>
                          <a class="blog-nav-item" href="project.php">关于项目</a>
                          <a class="blog-nav-item" href="team.php">研究团队</a>
                          <a class="blog-nav-item active" href="progress.php">项目进展</a>
                          <a class="blog-nav-item" href="paper.php">发表文章</a>
                          <a class="blog-nav-item" href="communication.php">学术交流</a>
                          <a class="blog-nav-item" href="tools.php">数据/工具</a>
			        </nav>
			    	</div>
			    </div>
		    </div>
      </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- 左侧导航栏 -->
            <div class="col-sm-2" style="font-size:20px">
                <div class="sidenav">
                <?php
                  if (!isset($nav_list) or count($nav_list) == 0) {
                ?>
                    <p>暂无项目信息</p>
                <?php
                  } else {
                    foreach ($nav_list as $line) {
                       $category_name = $line->name;
                       $splist = $line->specieslist;
                       echo '<p>' . $category_name . '</p>';
                       echo '<ul>';
                       foreach ($splist as $spline) {
                         $spname = $spline->name;
                         $tsid = $spline->sid;
                         echo '<li><a href="progress.php?sid='. $tsid . '">' .  $spname . '</a></li>';
                       }
                       echo '</ul>';
                    }
                  }
                ?>
                </div> 
            </div>

            <!-- 项目进展主体 -->
            <div class="col-sm-10">
                <div class="progress_body">
                <?php
                  if (!isset($pro_info) || !isset($pro_info->title)) {
                    echo '<p>暂无信息</p>';
                  } else {
                ?>
                    <h4><?php echo $pro_info->title ?></h4>
                    <h5 style="color:#666"><?php echo $pro_info->date;?></h5>
                    <div style="text-align:center"><img src="<?php echo $pro_info->img ?>"><div>
                    <p class="project-introduction-paragraph"><?php echo $pro_info->body ?></p>
                <?php
                   }
                ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>