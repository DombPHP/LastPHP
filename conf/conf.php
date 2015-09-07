<?php exit;?>
;ini configure file
charset = utf-8

;[database]
db_host = localhost
db_port = 3306
db_name = test
db_user = root
db_pwd = 123456
db_charset = utf8

;[url]
suffix = html
seperator = /

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

;[project map]
project_path = ./
map_project_name = on
map_index = home
map_admin = admin2

;[domain map]
map_domain_name = on
first.com = admin:User
second.com = home
third.com = admin

;[error]
error_page = CORE_PATH/error/error.php
error_message = system error