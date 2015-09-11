<?php exit;?>
;ini configure file
charset = utf-8
suffix = html
seperator = /

;[database]
ddb = on
proxy = off
servers = db_host_1,db_host_2
db_host = localhost
db_port = 3306
db_name = test
db_user = root
db_pwd = 123456
db_charset = utf8
db_prefix = zx_

db_host_1 = localhost
db_port_1 = 3306
db_name_1 = test
db_user_1 = root
db_pwd_1 = 123456
db_charset_1 = utf8
db_prefix_1 = zx_

db_host_2 = localhost
db_port_2 = 3306
db_name_2 = test
db_user_2 = rootsssssssssssss
db_pwd_2 = 123456
db_charset_2 = utf8
db_prefix_2 = zx_

;[template]
template_suffix = tpl
cache_dir = APP_PATH/cache
engine = SmartyExt
index_mode = on
theme = default
tips_success = CORE_PATH/error/success.php
tips_error = CORE_PATH/errror/error.php
tips_success_title = 保存成功
tips_error_title = 参数错误

;[domain map]
map_domain = on
first.com = admin:User
one.first.com = home
second.com = home
third.com = admin

;[error]
error_page = CORE_PATH/debug.php
error_message = system error