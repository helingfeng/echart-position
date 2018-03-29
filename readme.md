# PrettyJob


<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About PrettyJob

#### 项目开发思路思维导出

- http://naotu.baidu.com/file/7cd9be9956b94ff8bc8c08579e2e7aae?token=9f6f5ca600cfc220

...


## Install

1. 环境配置

- php7.0>=
- nginx

2. 安装依赖

```php
composer install
```

3.  nginx 配置

```shell
server {
    listen       80;
    server_name  pretty.job.com;
    charset      utf-8;
    
    root /project/PrettyJob/public;
    index index.php index.html;

    location ~ .*\.(css|js|gif|jpg|jpeg|png|bmp|zip|exe|txt|ico|rar|eot|woff|woff2|svg|ttf|swf|mp3|wmv|wma|mp4|mpg|flv)$ {
        add_header Access-Control-Allow-Origin *;
        expires 30d;
    }
    location ~ ^/.*$ {
        include fastcgi_params;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
        fastcgi_pass 127.0.0.1:9000;
    }   
}
```


## Usage




## License

The `PrettyJob` is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
