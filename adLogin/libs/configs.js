
define(function (require) {
    return {
    	domain: "www.aems.com",
		api: {
			headers: {'Content-Type': 'application/json'},
			userSelfPermission: "/api/user/self/permission",				
			logoutPlatform: "/api/user/logout",
			loginPlatform: "/api/user/login",
			userSelf: "/api/user/self",

		},
		path: {
			appRoot:"/adLogin",
			admin:"admin.html",
			domain:"www.xdashboard.com",
		},
	};
});
