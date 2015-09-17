# WarmerPHP
An open source web application development framework for PHP.

获取
	下载网址 https://github.com/DombPHP/WarmerPHP
	GIT clone https://github.com/DombPHP/WarmerPHP.git
安装
	将源码置于网站根目录或其他任意目录，然后在网站入口文件中引用框架根目录中的warmer.php文件。
	index.php 内容，
	defined('PROJECT', 'home'); // 指定项目名称为home
	include "../WarmerPHP/warmer.php";
	
	新建站点目录和项目目录，目录结构应为如下结构（以demo站点为例，框架和站点在同一目录），
	WarmerPHP (框架目录)
	demo （站点根目录）
	  --conf （站点配置文件存放目录，可以省略）
	  --func （站点函数文件存放目录，可以省略）
	  --index.php （站点入口文件）
	新建项目目录，默认项目所在目录为站点根目录，可以在配置文件中指定项目所在目录，新建home项目为例，项目结构如下，
	home
	  --conf （项目配置文件存放目录，可以省略）
	  --func （项目函数文件存放目录，可以省略）
	  --controller （控制器类存放目录）
	  --model （数据模型类存放目录）
	  --view （模板文件存放目录）
	  --cache （模板解析缓存目录，可以省略，但需要在配置文件中指定缓目录）
	  --logs （日志文件存放目录）
	建好项目后站点结构如下，
	WarmerPHP (框架目录)
	demo （站点根目录）
	  --conf （站点配置文件存放目录，可以省略）
	  --func （站点函数文件存放目录，可以省略）
	  --home （项目目录，加入新建了一个项目home，默认项目所在目录为根目录）
		--conf
		--func
		--controller
		--model
		--view
		--cache
		--logs
	  --index.php （站点入口文件）
	项目目录建好后，在项目控制器目录（controller）中新建IndexController.class.php控制器类，并实现index方法，
	class IndexController extends Controoler {
	    public function index() {
		    echo 'Hello world!';
		}
	}
	在浏览器地址栏输入http://网址/index.php访问站点，可以如果成功访问index方法，说明站点创建成功。
配置

模型

数据库

模板