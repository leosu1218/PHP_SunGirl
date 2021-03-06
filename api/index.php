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

                //website
                array( "POST: 	/website/<position:\w+>/upload",								            "CarouselImageController", "upLoadHomePage(<position>)"),
                array( "DELETE: /website/<position:\w+>/<id:\d+>",											"CarouselImageController", "removeHomePage(<position>,<id>)"),
                array( "GET: 	/website/<position:\w+>/image/<pageNo:\d+>/<pageSize:\d+>",			   		"CarouselImageController", "getByBanner(<position>,<pageNo>,<pageSize>)"),
                array( "PUT:    /website/<position:\w+>/modify/<id:\d+>",                                   "CarouselImageController", "updateUrl(<position>,<id>)"),

                //sungirlbb
                array( "GET: 	/sungirl/<category:\w+>/list/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",			   		"SungirlbbListController", "getSungirlList(<category>,<pageNo>,<pageSize>)"),
                array( "GET: 	/sungirl/<category:\w+>/<id:\d+>",			   		                              "SungirlbbListController", "getSungirlById(<category>,<id>)"),
                array( "GET: 	/sungirl/<category:\w+>/<id:\d+>/client",			   		                       "SungirlbbListController", "getSungirlClientById(<category>,<id>)"),
                array( "GET: 	/sungirl/<category:\w+>/client/clickSum/<pageNo:\d+>/<pageSize:\d+>",		"SungirlbbListController", "getSungirlClientBySum(<category>,<pageNo>,<pageSize>)"),
                array( "GET: 	/sungirl/<category:\w+>/client/<pageNo:\d+>/<pageSize:\d+>",			   		"SungirlbbListController", "getSungirlClient(<category>,<pageNo>,<pageSize>)"),
                array( "POST: 	/sungirl/photo/upload",								                          "SungirlbbListController", "upLoadPhoto()"),
                array( "POST: 	/sungirl/<category:\w+>/create",								                  "SungirlbbListController", "create(<category>)"),
                array( "PUT: 	/sungirl/<category:\w+>/update/<id:\d+>",								                  "SungirlbbListController", "update(<category>,<id>)"),
                array( "DELETE: /sungirl/photo/delete/<filename:\w+>/<type:\w+>",											"SungirlbbListController", "removePhoto(<filename>,<type>)"),
                array( "DELETE: /sungirl/<category:\w+>/delete/<id:\d+>",											"SungirlbbListController", "removeSungirl(<category>,<id>)"),

                //sungirlDownload
                array( "GET: 	/sungirl/downloadList/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",			   		"SungirlDownloadController", "getSungirlDownload(<pageNo>,<pageSize>)"),
                array( "GET: 	/sungirl/download/getByid/<id:\d+>",			   		                                    "SungirlDownloadController", "getSungirlDownloadById(<id>)"),
                array( "GET: 	/sungirl/client/download/<pageNo:\d+>/<pageSize:\d+>",			   		"SungirlDownloadController", "getDownloadClient(<pageNo>,<pageSize>)"),
                array( "GET: 	/sungirl/client/download/clickSum/<pageNo:\d+>/<pageSize:\d+>",			   		"SungirlDownloadController", "getDownloadClientBysum(<pageNo>,<pageSize>)"),
                array( "GET: 	/sungirl/client/download/<id:\d+>",			   		              "SungirlDownloadController", "getDownloadClientByid(<id>)"),
                array( "POST: 	/sungirl/download/upload",								                          "SungirlDownloadController", "upLoadDownload()"),
                array( "POST: 	/sungirl/create/download",								                  "SungirlDownloadController", "create()"),
                array( "DELETE: /sungirl/delete/download/<id:\d+>",											"SungirlDownloadController", "removeDownload(<id>)"),
                array( "DELETE: /sungirl/download/deleteImg/<filename:\w+>/<type:\w+>",											"SungirlDownloadController", "removeDownloadImg(<filename>,<type>)"),
                array( "PUT: 	/sungirl/update/download/<id:\d+>",								                  "SungirlDownloadController", "update(<id>)"),
            ),
			'default' => array( "DefaultController", "getNotFound" )
		)
	));
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>