## 基于ThinkPHP 5.0的后台管理系统

### 使用方法

``` bash
git clone git@github.com:jirengu-inc/animating-resume.git
cd animating-resume
```

### 部署方法

1. 编辑 application/database.php，修改数据库信息为本地数据库信息
2. 编译、上传
   ``` bash
   php think migrate:run
   php think seed:run
   ```