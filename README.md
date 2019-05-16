# swoole yii2
运行在swoole扩展下的yii2框架，通过自定义组件，无需更改一行yii框架代码和业务代码。

*本版本应用暂未实现Yii底层的数据库&&缓存异步化，服务端依旧运行在阻塞模式下，不过依然比PHP-FPM性能高出不少。*
## 快速开始

1. 安装composer依赖
2. 修改**config/server.php**相关配置
3. `./yii server/start`

## 服务器控制脚本

```bash
./yii server/start # 启动服务器
./yii server/shutdown # 安全停止服务器
./yii server/reload # reload工作进程
```

## 版本

+ PHP 7.0.0+
+ Swoole 4.0+

## TODO

+ 异步化Yii常用组件(db, cache, etc.)