
## 生成 jwt 密钥
```
 php artisan key:generate
 php artisan jwt:secret
```
## .env 配置
```
//用户域名
DOMAIN_API=api.xxx.com  
//管理后台
DOMAIN_ADMIN_API=adminapi.xxx.com

```
## 运行迁移
```
php artisan migrate

```

## 填充数据
```
php artisan  db:seed --class=AdminTableSeeder
```

