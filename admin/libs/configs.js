
define(function (require) {
    return {
        domain: "www.sungirlbb.com",
        registerGroupId: 1,
        registerPersonPermission: [26,27,28,29,30,31,32,33,34,35],
		api: {
			headers: {'Content-Type': 'application/json'},
			userSelfPermission: "/api/user/self/permission",				
			logoutPlatform: "/api/user/logout",
			loginPlatform: "/api/user/platform/login",
			userSelf: "/api/user/self",
			exporter:"/api/export",
			user: "/api/user",
			groupPlatformuser:"/api/group/platformuser",
            website:"api/website/",
            sungirl:"api/sungirl",
            photoUpload:"api/sungirl/photo/upload",
            downloadUpload:"api/sungirl/download/upload"
		},
		path: {
			appRoot:"/admin",
			login: "/adLogin.html",
            carousel:"upload/carousel",
            upload:"upload/"
		},
	};
});