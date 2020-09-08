# CDN URL地址鉴权

腾讯CDN鉴权链接生成、阿里云CDN鉴权链接生成。

## 安装
```
composer require big-dream/cdn-url-auth:0.0.2
```

## 使用示例

### 腾讯云CDN鉴权链接地址生成使用示例

初始化类：
```php
$auth = new \bigDream\CdnUrlAuth\Tencent(
    'r1u1sxtmgf8u5u9eazh2wpj',  // [必传]鉴权秘钥
    'sign',                     // [选传]鉴权参数名（鉴权方式TypeA和TypeD会用到）
    't',                        // [选传]时间参数名（鉴权方式TypeD会用到）
    false                       // [选传]将时间戳十六进制（鉴权方式TypeD会用到）
);
```

#### TypeA鉴权方式

```php
$auth->typeA(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time(),                                     // [选传]当前时间的时间戳
    md5(microtime()),                           // [选传]随机数
    '0'                                         // [选传]用户ID 
);
```

#### TypeB鉴权方式

```php
$auth->typeB(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time()                                      // [选传]当前时间的时间戳
);
```

#### TypeC鉴权方式

```php
$auth->typeC(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time()                                      // [选传]当前时间的时间戳
);
```

#### TypeD鉴权方式

```php
$auth->typeD(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time()                                      // [选传]当前时间的时间戳
);
```

### 阿里云CDN鉴权链接地址生成使用示例

初始化类：
```php
$auth = new \bigDream\CdnUrlAuth\Aliyun(
    'r1u1sxtmgf8u5u9eazh2wpj',  // [必传]鉴权秘钥
    'auth_key',                 // [选传]鉴权参数名（鉴权方式TypeA和TypeD会用到）
    3600                        // [选传]链接有效期（秒）
);
```

#### TypeA鉴权方式

```php
$auth->typeA(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time(),                                     // [选传]链接失效时间的时间戳
    md5(microtime()),                           // [选传]随机数
    '0'                                         // [选传]用户ID 
);
```

#### TypeB鉴权方式

```php
$auth->typeB(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time()                                      // [选传]链接失效时间的时间戳
);
```

#### TypeC鉴权方式

```php
$auth->typeC(
    '/files/install/wampserver3.2.3_x64.exe',   // [必传]需要鉴权的链接
    time()                                      // [选传]链接失效时间的时间戳
);
```