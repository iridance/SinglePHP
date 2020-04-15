# Single PHP framework

### 簡介

此版本為 SinglePHP 的composer package版本

### 文件

暫缺：

### Demo

暫缺：

### 目錄結構

    ├── app                                 #主程式
    │   ├── controllers                     #controller文件
    │   │   └── IndexController.php
    │   ├── libs                            #外部函式庫
    │   ├── views                           #樣版文件目錄
    │   │   ├── site                        #對應Site Controller
    │   │   │   └── index.php
    │   │   └── layout
    │   │       ├── footer.php
    │   │       └── header.php
    │   ├── widgets                         #widget目錄
    │   │   ├── MenuWidget.php
    │   │   └── tpl                         #widget樣版
    │   │       └── MenuWidget.php
    │   └── common.php                      #其他共用函数
    ├── runtime/logs                        #log目錄，需要寫入權限
    ├── vendor/irice/single-php/Core.php    #framework核心
    └── public/index.php                    #入口程式
    
### Hello World

入口程式：public/index.php

    <?php
    require __DIR__ . '/../vendor/autoload.php';
    $config = ['APP_PATH' => 'app'];
    \single\Core::getInstance($config)->run();
    
預設控制器：app/controllers/SiteController.php

    <?php
    class IndexController extends Controller
    {
        public function indexAction()
        {
            $this->assign('content', 'Hello World');
            $this->display('site/index');
        }
    }
    
樣版文件：app/views/site/index.php

    <?php echo $content;
    
進入index.php時會輸出

    Hello World
