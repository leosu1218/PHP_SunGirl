
define(function (require) {
    return {
		api: {
			headers: {'Content-Type': 'application/json'},
			userSelfPermission: "/api/user/self/permission",				
			logoutPlatform: "/api/user/logout",
			loginPlatform: "/api/user/login",
			userSelf: "/api/user/self",

			order: "/api/order",
			returned: "/api/return",

			// TODO redefine			
			userGroupList: "/api/group/platformuser/list",
			platformUserGroup: "/api/group/platformuser",
			platformUser: "/api/user/platformuser",
			groupbuyingUser: "/api/user/groupbuyingmaster",
			productGroup: "/api/group/product",

            groupbuyingActivity: "/api/activity/groupbuying",
            generalActivity: "/api/activity/general",
			
			materialUpload: "/api/materials/upload",
			material:"/api/materials",
			product:"/api/product",
			productUpload:"/api/product/materials/upload",
			exportFile:"/api/export/excel/",

			systemConfig:"/api/system/config",
		},
		path: {
			admin: "/admin.html",
			login: "/login.html",
			grouplist: "#!/group/list/1/100",
			userlist: "#!/user/list/1/100",
			gbActivityList: '#!/activity/list/1/100',	
			gbActivity: '#!/activity',			
			gblogin: '/gblogin.html',
			gbadmin: '/gbadmin.html',
			material: "/upload/",
			image: "image/",
			report: "/reports/",
			groupbuyingActivity: '#!/groupbuying/activity',
            generalActivity: '#!/general/activity',

		},		
		state: {
			deliveryAlready: 4,
		},
	};
});