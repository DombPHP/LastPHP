# WarmerPHP
An open source web application development framework for PHP.

##获取
+ 项目网址 https://github.com/DombPHP/WarmerPHP
+ GIT克隆  https://github.com/DombPHP/WarmerPHP.git

## 安装
    将WarmerPHP源码置于网站根目录或站点外任意目录，
    然后在网站入口文件(index.php)中引用框架根目录中的warmer.php文件。
    index.php：
```php
    defined('PROJECT', 'home'); // 指定项目名称为home
    include "../WarmerPHP/warmer.php";
```

## 站点结构（demo）
	WarmerPHP (框架目录)
	demo （站点根目录）
	  --conf （站点配置文件（conf.php）存放目录，可以省略）
	  --func （站点函数文件（func.php）存放目录，可以省略）
	  --ext （扩展目录）
	  --pack （包目录）
	  --index.php （站点入口文件）

## 项目结构
    默认项目所在目录为站点根目录，可以在配置文件中指定项目所在目录，新建home项目为例，项目结构如下，
	home
	  --conf （项目配置文件（conf.php）存放目录，可以省略）
	  --func （项目函数文件（func.php）存放目录，可以省略）
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
	  --home （项目目录，新建了一个项目home，默认项目所在目录为根目录）
		--conf
		--func
		--controller
		--model
		--view
		--cache
		--logs
	  --index.php （站点入口文件）

## Hello world
    项目目录建好后，在项目控制器目录（controller）中新建IndexController.class.php控制器类，
	并实现index方法，
	IndexController.class.php：
```php
	class IndexController extends Controoler {
	    public function index() {
		    echo 'Hello world';
		}
	}
```
	在浏览器地址栏输入http://domain/index.php访问站点，若输出“Hello world”，则说明站点创建成功。
## 控制器
Controller类框架提供的底层控制器类，所有模块控制器需要继承该类，开发人员可以扩展自己的控制器类。
系统默认模块为Index，默认方法为index。

控制器名称分为模块名称+后缀名（Controller），如：IndexController
### 新建控制器
    以UserController为例，
```php
    class UserController extends Controller {
	    
	}
```
### 控制器输出
    输出用户信息页面，模板名称为info.html
```php
    class UserController extends Controller {
	    
		public function info() {
		    // 模板赋值
		    $this->assign('id', '1024');
			$this->assign('name', 'my name');
			
			// 输出模板内容
			// 默认模板名称为方法名称，这里为info
			$this->display();
			
			// 如果模板名称不等于方法名称，可以指定模板文件名称，
			// $this->display('userinfo');
		}
	}
```
## 数据模型
本框架中的数据模型是以扩展的形式存在，也就是说框架本身并没有定义固定的模型结构，开发者可以扩展第三方的模型类库使其适用于本框架。

发布的框架内已经扩展了一个第三方模型类库WarmerModel，其扩展后的名称为WarmerModelExt。该扩展使用方法单独说明。