<?php


require_once('../configs/sys.config.inc.php');

try
{
	$PushSynature = new Synature(array(
		'SysRoot' => ROOT,
		'frameworkRoot' => FRAMEWORK_PATH,
        'logPath' => LOG_PATH,
		'router' => array(
			'ctrlRoot' => FRAMEWORK_PATH . 'controllers',
			'patterns' => array(

				//User
				array( "POST: 	/user/logout", 																"UserController", "logout()"),
				array( "POST: 	/user/login", 																"UserController", "login()"),
				array( "POST: 	/user/<userType:\w+>/<id:\d+>/group/permission",							"UserController", "appendGroupPermission(<id>)"),
				array( "POST: 	/user/<userType:\w+>/<id:\d+>/person/permission",							"UserController", "appendPersonPermission(<id>)"),
				
				array( "POST: 	/user/register",															"UserController", "register()"),
				array( "GET: 	/user/self", 																"UserController", "getSelf()"),
				array( "GET: 	/user/self/permission", 													"UserController", "getSelfPermission()"),
				array( "GET: 	/user/list/<pageNo:\d+>/<pageSize:\d+>", 					                "UserController", "getList(<pageNo>,<pageSize>)"),
				array( "PUT: 	/user/self", 																"UserController", "updateSelf()"),

				// UserGroup Section
				array( "POST: 	/group/platformuser/<id:\d+>/permission", 									"UserGroupController", "appendPermission(<id>)"),
				array( "POST: 	/group/platformuser", 														"UserGroupController", "create()"),
				array( "GET: 	/group/platformuser/list/<pageNo:\d+>/<pageSize:\d+>",						"UserGroupController", "getList(<pageNo>,<pageSize>)"),
				array( "GET: 	/group/platformuser/<id:\d+>/permission/list/<pageNo:\d+>/<pageSize:\d+>",	"UserGroupController", "getPermissionList(<id>,<pageNo>,<pageSize>)"),
				array( "GET: 	/group/platformuser/<id:\d+>/user/list/<pageNo:\d+>/<pageSize:\d+>",		"UserGroupController", "getUserList(<id>,<pageNo>,<pageSize>)"),
				array( "PUT: 	/group/platformuser/<groupId:\d+>/user/<userId:\d+>", 						"UserGroupController", "updateUser(<groupId>,<userId>)"),
				array( "DELETE: /group/platformuser/<id:\d+>", 												"UserGroupController", "remove(<id>)"),
				array( "PUT: 	/group/platformuser/password/<groupId:\d+>/user/<userId:\d+>", 				"UserGroupController", "updateUserPassword(<groupId>,<userId>)"),
                array( "GET: 	/export/<category:\w+>/<entityType:[\w\-]+>/<querystring:\w+>",					"ExportController", "export(<category>,<entityType>,<querystring>)"),

                // IMedia event raw data.
                array( "GET: 	/imediaevent/self-all/box/list/<pageNo:\d+>/<pageSize:\d+>",					    "IMediaEventRawController", "getBoxListSelf(<pageNo>,<pageSize>)"),
                array( "GET: 	/imediaevent/self-all/box/<boxId:[\w\-\%]+>/cam/list/<pageNo:\d+>/<pageSize:\d+>",		"IMediaEventRawController", "getCamListSelfByBoxId(<boxId>, <pageNo>,<pageSize>)"),

                // IMedia event raw data.
                array( "GET: 	/imediaevent/translate-rule/list/<pageNo:\d+>/<pageSize:\d+>",					        "IMediaEventTranslateRuleController", "getList(<pageNo>, <pageSize>)"),
                array( "GET: 	/imediaevent/translate-rule/<id:\d+>",					                                "IMediaEventTranslateRuleController", "getById(<id>)"),
                array( "DELETE: /imediaevent/translate-rule/<id:\d+>",					                                "IMediaEventTranslateRuleController", "deleteById(<id>)"),
                array( "PUT: 	/imediaevent/translate-rule/<id:\d+>",					                                "IMediaEventTranslateRuleController", "updateById(<id>)"),
                array( "POST: 	/imediaevent/translate-rule",					                                        "IMediaEventTranslateRuleController", "create()"),

                //  Raw file
                array( "POST: 	/rawfile/imediaevent",					                                    "RawFileController", "receiveFile()"),
                array( "GET: 	/rawfile/imediaevent/list/<pageNo:\d+>/<pageSize:\d+>",					    "RawFileController", "getList(<pageNo>,<pageSize>)"),

                array( "DELETE: /rawfile/imediaevent/self-all", 											"RawFileController", "removeSelfAll()"),
                array( "DELETE: /rawfile/imediaevent/<id:\d+>", 											    "RawFileController", "remove(<id>)"),

                // Test
                array( "GET: 	/test/piechart",						                                    "TestController", "getPieChart()"),
                array( "GET: 	/test/barchart",						                                    "TestController", "getBarChart()"),
                array( "GET: 	/test/curvechart",						                                    "TestController", "getCurveChart()"),

                // standard case page
                array( "GET: 	/standard/case/get/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",		"StandardCasePageController", "searchByClient(<pageNo>,<pageSize>,<querystring>)"),
			),
			'default' => array( "DefaultController", "getNotFound" )
		)
	));
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>