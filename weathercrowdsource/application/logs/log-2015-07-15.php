<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

DEBUG - 2015-07-15 07:33:29 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 07:33:30 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 07:33:30 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:09:18 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:09:18 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:09:19 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:09:19 --> {"msg":"Failed to response to task [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]. Bad request [error=The Responsedate field is required.\n]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/Worker.php","method":"Worker::task_response_post","line":418}
DEBUG - 2015-07-15 11:21:38 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:21:38 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:21:38 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:21:39 --> Severity: Notice --> Undefined property: Worker::$task_model /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/controllers/api/Worker.php 433
ERROR - 2015-07-15 11:21:39 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
ERROR - 2015-07-15 11:21:39 --> Severity: Error --> Call to a member function update_status() on null /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/controllers/api/Worker.php 433
DEBUG - 2015-07-15 11:22:00 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:22:00 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:22:00 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:22:26 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:22:26 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:22:27 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:22:27 --> Severity: Notice --> Undefined index: userid /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/core/MY_Controller.php 195
DEBUG - 2015-07-15 11:22:27 --> hien10
DEBUG - 2015-07-15 11:22:27 --> {"msg":"user logged in successfully [username=hien10], [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/User.php","method":"User::login_post","line":321}
ERROR - 2015-07-15 11:22:27 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 504
ERROR - 2015-07-15 11:22:27 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
ERROR - 2015-07-15 11:22:27 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 527
DEBUG - 2015-07-15 11:22:55 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:22:55 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:22:55 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:22:55 --> hien10
DEBUG - 2015-07-15 11:22:55 --> {"msg":"user logged in successfully [username=hien10], [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/User.php","method":"User::login_post","line":321}
DEBUG - 2015-07-15 11:23:10 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:23:10 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:23:11 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:23:11 --> Severity: Warning --> pg_query(): Query failed: ERROR:  invalid input syntax for integer: &quot;\x32613337656166612d646135332d343234392d393533312d343266376161623564633863&quot;
LINE 2: WHERE &quot;taskid&quot; = E'\\x32613337656166612d646135332d343234392d...
                         ^ /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2015-07-15 11:23:11 --> Query error: ERROR:  invalid input syntax for integer: "\x32613337656166612d646135332d343234392d393533312d343266376161623564633863"
LINE 2: WHERE "taskid" = E'\\x32613337656166612d646135332d343234392d...
                         ^ - Invalid query: UPDATE "tasks" SET "status" = 2
WHERE "taskid" = E'\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863'
DEBUG - 2015-07-15 11:23:11 --> DB Transaction Failure
ERROR - 2015-07-15 11:23:11 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
DEBUG - 2015-07-15 11:24:03 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:24:03 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:24:03 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:24:04 --> Severity: Warning --> pg_query(): Query failed: ERROR:  invalid input syntax for integer: &quot;\x32613337656166612d646135332d343234392d393533312d343266376161623564633863&quot;
LINE 2: WHERE &quot;taskid&quot; = E'\\x32613337656166612d646135332d343234392d...
                         ^ /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2015-07-15 11:24:04 --> Query error: ERROR:  invalid input syntax for integer: "\x32613337656166612d646135332d343234392d393533312d343266376161623564633863"
LINE 2: WHERE "taskid" = E'\\x32613337656166612d646135332d343234392d...
                         ^ - Invalid query: UPDATE "tasks" SET "status" = 2
WHERE "taskid" = E'\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863'
DEBUG - 2015-07-15 11:24:04 --> DB Transaction Failure
ERROR - 2015-07-15 11:24:04 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
DEBUG - 2015-07-15 11:24:14 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:24:14 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:24:14 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:24:14 --> Severity: Warning --> pg_query(): Query failed: ERROR:  invalid input syntax for integer: &quot;\x32613337656166612d646135332d343234392d393533312d343266376161623564633863&quot;
LINE 2: WHERE &quot;taskid&quot; = E'\\x32613337656166612d646135332d343234392d...
                         ^ /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2015-07-15 11:24:14 --> Query error: ERROR:  invalid input syntax for integer: "\x32613337656166612d646135332d343234392d393533312d343266376161623564633863"
LINE 2: WHERE "taskid" = E'\\x32613337656166612d646135332d343234392d...
                         ^ - Invalid query: UPDATE "tasks" SET "status" = 2
WHERE "taskid" = E'\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863'
DEBUG - 2015-07-15 11:24:14 --> DB Transaction Failure
ERROR - 2015-07-15 11:24:14 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
DEBUG - 2015-07-15 11:27:35 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:27:35 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:27:35 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:27:35 --> Severity: Notice --> Undefined property: Worker::$requester_model /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/controllers/api/Worker.php 438
ERROR - 2015-07-15 11:27:36 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
ERROR - 2015-07-15 11:27:36 --> Severity: Error --> Call to a member function requesterid_from_taskid() on null /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/controllers/api/Worker.php 438
DEBUG - 2015-07-15 11:28:04 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:04 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:04 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:28:26 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:26 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:26 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:28:26 --> Severity: Notice --> Undefined index: userid /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/core/MY_Controller.php 195
DEBUG - 2015-07-15 11:28:26 --> hien10
DEBUG - 2015-07-15 11:28:26 --> {"msg":"user logged in successfully [username=hien10], [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/User.php","method":"User::login_post","line":321}
ERROR - 2015-07-15 11:28:26 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 504
ERROR - 2015-07-15 11:28:26 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
ERROR - 2015-07-15 11:28:26 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 527
DEBUG - 2015-07-15 11:28:32 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:32 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:32 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:28:32 --> hien10
DEBUG - 2015-07-15 11:28:32 --> {"msg":"user logged in successfully [username=hien10], [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/User.php","method":"User::login_post","line":321}
DEBUG - 2015-07-15 11:28:33 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:33 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:33 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:28:33 --> hien10
DEBUG - 2015-07-15 11:28:33 --> {"msg":"user logged in successfully [username=hien10], [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/User.php","method":"User::login_post","line":321}
DEBUG - 2015-07-15 11:28:34 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:34 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:34 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
DEBUG - 2015-07-15 11:28:34 --> hien10
DEBUG - 2015-07-15 11:28:34 --> {"msg":"user logged in successfully [username=hien10], [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863]","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/User.php","method":"User::login_post","line":321}
DEBUG - 2015-07-15 11:28:41 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:41 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:41 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:28:41 --> Severity: Notice --> Undefined variable: trans_status /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/controllers/api/Worker.php 485
DEBUG - 2015-07-15 11:28:41 --> {"msg":"Fail to update status in other tables [userid=\\x32613337656166612d646135332d343234392d393533312d343266376161623564633863].","file":"\/Users\/ubriela\/git\/weather-crowdsource\/weathercrowdsource\/application\/controllers\/api\/Worker.php","method":"Worker::task_response_post","line":488}
ERROR - 2015-07-15 11:28:41 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 504
ERROR - 2015-07-15 11:28:41 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
ERROR - 2015-07-15 11:28:41 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 527
DEBUG - 2015-07-15 11:28:56 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:28:56 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:28:56 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
ERROR - 2015-07-15 11:28:57 --> Severity: Notice --> Undefined variable: userid /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/controllers/api/Worker.php 497
ERROR - 2015-07-15 11:28:57 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 504
ERROR - 2015-07-15 11:28:57 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Common.php 573
ERROR - 2015-07-15 11:28:57 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /Users/ubriela/git/weather-crowdsource/weathercrowdsource/system/core/Exceptions.php:272) /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/libraries/REST_Controller.php 527
DEBUG - 2015-07-15 11:29:32 --> UTF-8 Support Enabled
DEBUG - 2015-07-15 11:29:32 --> Global POST, GET and COOKIE data sanitized
DEBUG - 2015-07-15 11:29:32 --> Config file loaded: /Users/ubriela/git/weather-crowdsource/weathercrowdsource/application/config/rest.php
