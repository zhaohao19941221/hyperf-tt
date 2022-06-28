## hyperf-tt
基于hyperf框架v2.2.0+改造，支持多应用，Repository，swagger 文档生成,刷新model属性,多文件多目录多前缀。需要的可以下载看看

## 额外支持功能
1. 刷新model属性
```shell
php bin/hyperf.php model:refresh --help
```
2. swagger 文档生成使用```hyperf/swagger``
```shell
php bin/hyperf.php swagger:format --help
```
3. 多文件多目录多前缀(router)
4. 多应用bundle区分
5. 完整的格式化代码规则
```shell
composer check
```

# 注
- 改造过程
https://www.zhaohaoblog.com/?s=hyperf
- 配合hyperf_docker_compose_autoconf
https://github.com/zhaohao19941221/hyperf_docker_compose_autoconf.git

# 项目结构
本项目结构将从下面几个结构来介绍，如有错误希望更正

官方建议将所有代码都放到```app```目录下面：
- app 目录包含应用程序的核心代码。你应用中几乎所有的类都应该放在这里。
- app 目录包含额外的各种目录，比如：Command, Constants, 和 Listener
  按照官方的建议将所有的应用程序代码都放到 ```app``` 目录里面，随着项目的增大，代码增多，需求逐渐复杂，后期将会导致代码过于复杂，维护成本急剧增高。

## 目录结构调整：
除基本配置目录外，自带的app目录基本已经废弃，引入了```Bundle```，每个Bundle包含了一个独立的业务。
一个```Bundle```就是一个模块，其包含模块所有的资源。所有的 Bundle 都位于 src 目录下， Bundle 的命名规则为业务名称+Bundle
# 整体结构
```
├── app
│   ├── Command // 通用脚本
│   ├── Constants // 定义枚举相关
│   ├── Controller // 访问控制器
│   ├── Exception // 异常处理相关
│   ├── Listener // 监听器相关
│   ├── Middleware // 相关中间件
│   ├── Model // 相关数据库 model
│   ├── Repository //仓库类
│   ├── Service // 服务类
│   ├── Task // 异步task
│   ├── Traits // 异步task
│   └── helpers.php // 常用方法
├── bin // 启动 Hyperf 服务文件
├── config // 配置文件 请看下面 `配置文件结构` 文档
├── doc // 文档
├── migrations // 生成的迁移文件
├── routes // 路由文件 请看下面 `路由文件结构` 文档
│   ├── front // 前台路由
├── runtime // 运行程序相关文件
│   ├── container // 缓存代理类
│   ├── logs // 项目运行日志
│   └── hyperf.pid // master 进程的 PID
├── storage // 存储文件夹
│   ├── download // 下载文件存储
│   ├── swagger // swagger文档
├── test // 测试用例相关
├── vendor // composer 加载的 vendor 包
├── .env // 配置文件，`cp .env.example .env` ，复制 env 文件
├── .env.example // 配置例子文件，记得相关配置增加注释
├── .env.develop // 测试环境配置
├── .env.prd // 生产环境配置
├── .gitignore
├── .php-cs-fixer.php // 格式化代码风格配置文件，注意不要动，`composer cs-fix` 格式化代码
├── composer.json // 包管理文件
└── phpunit.xml // 测试用例配置文件
```

# 配置文件结构
```
config
├── autoload // 此文件夹内的配置文件会被配置组件自己加载，并以文件夹内的文件名作为第一个键值
│   ├── annotations.php // 用于管理注解
│   ├── aspects.php // 用于管理 AOP 切面
│   ├── cache.php // 用于管理缓存组件
│   ├── commands.php // 用于管理自定义命令
│   ├── crontab.php // 定时任务
│   ├── consul.php // 用于管理 Consul 客户端
│   ├── databases.php // 用于管理数据库客户端
│   ├── dependencies.php // 用于管理 DI 的依赖关系和类对应关系
│   ├── devtool.php // 用于管理开发者工具
│   ├── exceptions.php // 用于管理异常处理器
│   ├── file.php // 文件系统管理文件
│   ├── listeners.php // 用于管理事件监听者
│   ├── logger.php // 用于管理日志
│   ├── middlewares.php // 用于管理中间件
│   ├── opentracing.php // 用于管理调用链追踪
│   ├── processes.php // 用于管理自定义进程
│   └── translation.php // 多语言版本
├── dev // 测试环境配置(如有新的模块配置请另建配置文件)
│   └── common.php // 公共配置
│   └── server.php // 用于管理 Server 服务
├── indev // 开发环境配置(如有新的模块配置请另建配置文件)
│   └── common.php // 公共配置
│   └── server.php // 用于管理 Server 服务
├── production // 生产环境配置(如有新的模块配置请另建配置文件)
│   └── common.php // 公共配置
│   └── server.php // 用于管理 Server 服务
├── config.php // 用于管理用户或框架的配置，如配置相对独立亦可放于 autoload 文件夹内
├── container.php // 负责容器的初始化，作为一个配置文件运行并最终返回一个 Psr\Container\ContainerInterface 对象
└── routes.php // 用于管理路由
```

# 路由文件结构
```
routes
├── admin // 后台路由
│   ├── user.php // 用户接口路由
├── front // 前台
│   ├── home.php // 前台对外公共路由
│   ├── user.php // 用户路由
```

# 代码目录结构
```
src
├── WechatBundle // 微信相关
├── HomeBundle // 前台对外公共相关
```


# Bundle目录结构
```
WechatBundle
├── Constants // 定义枚举相关
├── Model // 模型
├── Http // 控制器相关
│   ├── Controllers // 控制器相关 
│   │   ├── Admin // 后台接口控制器相关
│   │   ├── Front // 小程序接口控制器相关
├── Interfaces // 接口相关
├── Middleware // 中间件相关
├── Process // 异步消费进程
├── Services // 服务相关，逻辑处理
├── Tests // 测试相关
│   ├── Admin // 后台测试用例
│   ├── Front // 前台测试用例
│   └── Services // 服务测试用例
└── Traits // 特性相关
```

# 编码规范
* 遵循psr规范 https://learnku.com/docs/psr

# 命名规范
## 命名规范
基本都为```业务名称+作用模块```
- Bundle 名 必须 为 业务名称+Bundle 整体命名遵循大驼峰规范。
- Repository 类 必须 为 业务名称+Repository
- Service 类 必须 为 业务名称+Service

## 控制器
控制器方法```应该```只包含以下三个职责：
- 验证输入参数有效性
- 组织数据，调用Service
- 对调用 Service 返回的数据根据需求调整数据格式返回
  控制器方法代码行数```应该```不超过```80```行，超过```80```行很可能需要将处理逻辑写到 Service 中。
  控制器的方法 应该只调用 ```Service``` ，不能```直接```调用```Repository```。
```
一旦控制器的方法中直接调用了Repository。 后续参与的开发人员就会延续之前的思路继续在控制器中写代码，破窗效应 一旦形成，后续的代码质量将无法控制。
```


# 开发分支
1. 所有开发分支都以`develop`分支作为基础分支
2. 分支命名为`feature/`开头 + 基础分支 + 姓名 + 功能 + 日期。例如: `feature/develop-test-zhaohao-1013`

## 路由命名
* 全部以小写英文编写,单词与单词之间使用下划线隔离

## 数据库迁移(migration)
1. 生成迁移文件
```bash
## --path=(可选。可以是migrations文件夹下的一个目录)
php bin/hyperf.php gen:migration create_users_table --table=表名                                                       
```
2. 命名(不允许执行删除表)
* 创建表结构: ```create_表名_table```
* 添加DDL: ```add_column_字段_to_表名```
* 修改DDL: ```update_column_字段_to_表名```
* 删除DDL: ```delete_column_字段_to_表名```
* 添加索引: ```add_index_索引_to_表名```
* 删除索引: ```delete_index_索引_to_表名```
* 修改索引: 请示领导

# 编码时注意一下几点
* `servcie方法` 记得增加参数的类型以及返回值的类型，接收外部参数记得转换类型
* 提交代码前要在根目录下执行 `composer check`。其中: `composer cs-fix` 格式化代码，`composer analyse` 静态检测
* 每个对应的 `外部接口` 都要编写自动化测试
* 所有 `队列` 必须可以重复执行
* 所有缓存的`cache key` 必须在对应配置文件中配置

## 参数的类型以及返回值的类型例子
```php
<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
use App\Constants;
/**
 * 测试
 * Class DevelopTest
 */
class DevelopTest
{
    /**
     * Test constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param int $userId
     * @return int
     */
    public function test(int $userId): int
    {
        return $userId;
    }
}
```

# 格式化代码
> 执行命令 `composer cs-fix` 格式化代码

```
> composer cs-fix && composer analyse
> php-cs-fixer fix $1
Loaded config default from "/hyperf-skeleton/api.mobilelegends.com_events_test/.php-cs-fixer.php".
   1) hyperf-tt/app/Command/ModelRefreshCommand.php
   2) hyperf-tt/app/Command/SwaggerCommand.php
   3) hyperf-tt/app/Constants/ContextConstant.php
   4) hyperf-tt/app/Constants/ErrorCode.php
```
格式化代码的风格在项目根目录.php_cs 中定义，目前按照以下方式来格式化代码

```php
<?php

$header = <<<'EOF'
This file is part of hyperf-tt.

@link     https://github.com/zhaohao19941221/hyperf-tt
@document https://github.com/zhaohao19941221/hyperf-tt.git
EOF;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $header,
            'separate' => 'none',
            'location' => 'after_declare_strict',
        ],
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'list_syntax' => [
            'syntax' => 'short'
        ],
        'concat_space' => [
            'spacing' => 'one'
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'declare',
            ],
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author'
            ],
        ],
        'ordered_imports' => [
            'imports_order' => [
                'class', 'function', 'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'single_line_comment_style' => [
            'comment_types' => [
            ],
        ],
        'yoda_style' => [
            'always_move_variable' => false,
            'equal' => false,
            'identical' => false,
        ],
        'phpdoc_align' => [
            'align' => 'left',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'constant_case' => [
            'case' => 'lower',
        ],
        'class_attributes_separation' => true,
        'combine_consecutive_unsets' => true,
        'declare_strict_types' => true,
        'linebreak_after_opening_tag' => true,
        'lowercase_static_reference' => true,
        'no_useless_else' => true,
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'not_operator_with_space' => false,
        'ordered_class_elements' => true,
        'php_unit_strict' => false,
        'phpdoc_separation' => false,
        'single_quote' => true,
        'standardize_not_equals' => true,
        'multiline_comment_opening_closing' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('public')
            ->exclude('runtime')
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
```

## 静态检测
> 执行脚本 composer analyse，对项目进行静态检测，便可以找到出现问题的代码段。

```
$ hyperf-tt(develop*) » composer analyse
> phpstan analyse --memory-limit 300M -l 0 -c phpstan.neon ./app ./src ./config
 181/181 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%
                                                                                                                        
 [OK] No errors                        
```

# 数据库命名规范
1. 所有的数据库对象名称必须使用小写字母并用下划线表示，因为默认情况下，mysql对大小写敏感，mysql数据库本质上是linux系统下的一个文件，而linux系统是大小写敏感的
2. 所有数据库对象名称禁止使用mysql保留关键字
3. 数据库对象的命名要能做到见名知意，并且最好不要超过32个字符。太长不方便使用，并且会在传输时增加网络开销
4. b临时表必须以tmp_为前缀并以日期为后缀
5. 备份表必须以bak_为前缀并以日期为后缀
6. 所有存储相同数据的列名和列类型必须一致，比如user表中的id和car表中的user_id

# 数据库基本设计规范
```
所有表必须使用Innodb存储引擎
Innodb引擎是5.6之后的默认存储引擎；mysql5.5之前使用Myisam(默认存储引擎)
Innodb优点：支持事务，行级锁，更好的恢复性，高并发下性能更好
数据库和表的字符集统一使用UTF-8-mb4
如果要存储一些如表情符号的，还需使用UTF-8的拓展字符集
数据库，表，字段字符集一定要统一，统一字符集可以避免由于字符集转换产生的乱码
在mysql中UTF-8字符集，汉字占3字节，ASCII码占1字节
所有表和字段都需要添加注释
从一开始就进行数据字典的维护，即数据库说明文档
尽量控制单表数据量大小，
建议控制在500万以内，虽然500万并不是mysql的数据库限制，但是会给修改表结构，备份，恢复带来很大困难。
单表可存储数据量大小取决于存储设置和文件系统
想减少单表数据量：历史数据归档(常见于日志表)，分库分表(常见于业务表)，分区表
建议不要使用mysql分区表，因为分区表在物理上表现为多个文件，在逻辑上表现为一个表。如果一定要分区，请谨慎选择分区键，跨分区查询效率比查询大数据量的单表查询效率更低
建议采物理分表的方式管理大数据，但是对应用程序的开发要求和复杂度更高
尽量做到冷热数据分离，减少表的宽度(字段数)
减少磁盘IO，保证热数据的内存缓存命中率，更有效的利用缓存，避免读入无用的冷数据
这样的话，就要对表的列进行拆分，将经常使用的列放到一个表中，可以避免过多的关联操作，也可以提高查询性能
禁止在表中建立预留字段
预留字段很难做到见名知义，预留字段无法确定存储的数据类型，后期如果修改字段类型，会对全表锁定，严重影响数据库的并发性
对目前mysql来说，修改一个字段的成本要远远大于增加一个字段的成本
禁止在数据库中存储图片，文件等二级制数据
这类数据如果要存，就得使用blog或者text这样的大字段加以存储，会影响数据库的性能
文件这种通常所占数据容量很大，会在短时间内造成数据库文件的快速增长，而数据库在读取数据时，会进行大量的随机IO操作，如果数据文件过大，IO操作会非常耗时，从而影响数据库性能
正确做法是将这类数据存储在文件服务器中，而数据库只村存储地址信息
```
# 索引设计规范(Innodb中主键实质上是一个索引)
```
限制每张表上索引数量，建议单表不超过5个索引。索引并不是越多越好，可以提高查询效率，但是会降低插入和更新的效率。甚至在一些情况下，还会降低查询效率，因为mysql优化器在选择如何优化查询时，会根据统计信息，对每一个可用索引来进行评估，以生成一个最好的执行计划，如果同时有很多索引都可以用于查询，就会增加mysql查询优化器生成查询计划的时间。
每个Innodb表都必须有一个主键。Innodb是一种索引索引组织表，是指数据存储的逻辑顺序和索引的顺序是相同，Innodb是按照主键索引的顺序来组织表的，因此，每个Innodb表都必须要有一个主键，如果我们没有指定主键，那么Innodb会优先选择表中第一个非空唯一索引来作为主键，如果没有这个索引，那么Innodb会自动生成一个占6字节的主键，而这个主键的性能并不是最好。
不使用更新频繁的列作为主键，不使用多列联合主键。因为Innodb是一种索引索引组织表，如果主键上的值频繁更新，就意味着数据存储的逻辑顺序频繁变动，必然会带来大量的IO操作，降低数据库性能。
不要使用uuid，md5，hash，字符串列作为主键。因为这种主键不能保证主键的值是顺序增长的，如果后来的主键值在已有主键值的中间段，那么这个主键插入的时候，会将所有主键值大于它的列都向后移。
最好选择能保证值的顺序为顺序增长的列为主键。并且数据不能重复，建议用mysql自增id建立主键
在select，delete，update的where从句中的列
包含在order by，group by，distinct字段中的列
多表join的关联列：mysql对关联操作的处理方式只有一种，那就是嵌套循环的关联方式，所以这种操作的性能对关联列上的索引的依赖性很大
复合索引：从左到右的顺序来使用
区分度(列中group by的数目和此列总行数的比值趋近于1)最高的列放在联合索引的最左侧
在区分度差不多的情况下，尽量吧字段长度小的放在联合索引的最左侧，因为同样的行数，字段小的文件也小，读取时IO性能更优
使用最频繁的列放在联合索引的左侧，这样的话，可以较少地建立索引就能满足需求
避免建立冗余索引和重复索引
对于频繁的查询优先使用覆盖索引
就是包含了所有查询字段的索引，这样可以避免Innodb表进行索引的二次查找，并可以把随机IO变为顺序IO提高查询效率
尽量避免使用外键
mysql和别的数据库不同，会自动在外键上建立索引，会降低数据库的写性能
建议不使用外键约束，但是一定要在表与表之间的关联键上建立索引，虽然外键是为了保证数据的完整性，但是最好在代码中去保证。
```
# 字段设计规范
```
优先选择符合存储需要的最小的数据类型
尽量将字符串转化为数字类型存储：如将ip存储为数字：inet_aton(‘255.255.255.255’) = 4294967295 ,反之， inet_ntoa(4294967295) = ‘255.255.255.255’
对于非负整型数据，优先使用无符号整型来存储，如：id,age,无符号相对于有符号，可以多出一倍的存储空间
mysql中，varchar(n)中n表示字符数而不是字节数
避免使用text，blog来存储字段，这种类型只能使用前缀索引，如果非要使用，建议将这种数据分离到单独的拓展表中
避免使用enum类型。枚举本身是一个字符串类型，但是内部确是用正数类型来存储的，所以最多可存储65535种不同的值，修改的话必须使用alter语句，直接修改元数据，有操作风险；order by效率低，必须转换并无法使用索引，禁止使用数值作为enum值，因为enum本身是索引顺序存储的，会造成逻辑混淆
尽可能把所有列定义为not null。
索引null列需要额外的空间来保存，占更多空间
进行比较和计算时，对null值作特别的处理，可能造成索引失效
禁止使用字符串来存储日期型数据。
无法使用日期函数计算比较
字符串存储要占更多的内存空间，datetime(8字节)和timestamp(本身是以int存储，占4字节,范围:1970-01-01 00:00:01到2038-01-19 03:14:07)
财务相关数据，使用decimal类型 (精准浮点类型，在计算时不丢失精度)。
```
五. SQL开发规范
```
尽量使用框架模型关联,简单的语句允许使用极简DB。性能差别不大
建议使用预编译语句(prepareStatment)进行数据库操作
可以同步执行预编译计划，减少预编译时间
可以有效避免动态sql带来的SQL注入的问题
只传参数，一次解析，多次使用，比传递sql语句更高效
避免数据类型的隐式转换
一般出现在where从句中，会导致索引失效，如：select id,name from user where id = ‘12’;
充分利用已存在的索引
避免使用双%的查询条件，不走索引
一个SQL只能利用到复合索引中的一列进行范围查询
使用left join或not exists来优化not in操作
程序连接不同的数据库使用不同的账号，禁止跨库查询
禁止使用select * 来查询，必须用字段名
可能会消耗更多的cpu和IO以及网络资源
无法使用覆盖索引
可以减少表结构变更对已有程序的影响
禁止使用不含字段列表的insert语句。
可以减少表结构变更对已有程序的影响
禁止使用子查询
虽然可使sql可读性好，但是缺点远远大于优点
子查询返回的结果集无法使用索引，结果集会被存储到一个临时表中，结果集越大性能越低
把子查询优化为join操作，但是并不是所有的都可以优化为join，一般情况下，只有当子查询是在in字句中，并且子查询是一个简单的sql(不包含union，group by，order by，limit)才能转换为关联查询
避免join过多的表
每join一个表会占一部分内存(join_buffer_size)
会产生临时表操作，影响查询效率
mysql最多允许关联61个表，建议不超过5个
减少同数据库的交互次数
数据库更适合处理批量操作
合并多个相同的操作到一起，提高处理效率
使用in代替or
in的值不要超过500个
in 操作可以有效利用索引
禁止使用order by rand()进行随机排序
会把表中所有符合条件的数据装载到内存中进行排序
会消耗大量的cpu和io及内存资源
推荐在程序中获取随机值
禁止在where从句中对列进行函数转换和计算
导致无法使用相关列上的索引
where date(create_time)=’20170901’ 写成 where create_time >= ‘20170901’ and create_time < ‘20170902’
在明显不会有重复值时使用union all而不是union
union 会把所有数据放在临时表中后再进行去重操作，会多消耗内存，IO，网络资源
union all 不会再对结果集进行去重操作
拆分复杂的大sql为多个小sql
目前mysql中一个sql只能使用一个cpu计算，不支持多cpu并行计算
sql拆分后可以通过并行执行来提高处理效率
```
