
define(function (require) {
    return {
		api: {
			headers: {'Content-Type': 'application/json'},
			userSelfPermission: "/api/user/self/permission",				
			logoutPlatform: "/api/user/logout",
			loginPlatform: "/api/user/login",
			userSelf: "/api/user/self",

			// TODO redefine			
			userGroupList: "/api/group/platformuser/list",
			platformUserGroup: "/api/group/platformuser",
			platformUser: "/api/user/platformuser",
			groupbuyingUser: "/api/user/groupbuyingmaster",
			productGroup: "/api/group/product",
            website:"api/website/",
            sungirl: "/api/sungirl",
			systemConfig:"/api/system/config",
		},
		path: {
			admin: "/admin.html",
			login: "/login.html",
			grouplist: "#!/group/list/1/100",
			userlist: "#!/user/list/1/100",
			image: "image/",
			report: "/reports/",
            upload : "/upload/"

		},		
		state: {
			deliveryAlready: 4,
		},
	};
});