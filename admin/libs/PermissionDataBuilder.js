define(function (require) {

	/**
	*	Permission columns data build tool.				
	*/
	var PermissionDataBuilder = function() {
		var columnNumber = 3;
		var self = this;

		function getPermissions() {
			return [
				{
					name: "團購商品權限",
					icon: "cube",
					show: false,
					items: [
						{id:42, name:"新增商品", icon:"plus", selected:false, show:false},
						{id:44, name:"刪除商品", icon:"minus", selected:false, show:false},
						{id:40, name:"瀏覽商品列表", icon:"list-alt", selected:false, show:false},
					]			
				},
				{
					name: "團購活動權限",
					icon: "asterisk",
					show: false,
					items: [
						{id:88, name:"編寫附註", icon:"pencil", selected:false, show:false},	
						{id:89, name:"發送對帳確認通知", icon:"send", selected:false, show:false},				
						{id:85, name:"匯出出貨單", icon:"truck", selected:false, show:false},					
						{id:86, name:"匯出退貨單", icon:"reply", selected:false, show:false},
						{id:87, name:"匯出匯款單", icon:"dollar", selected:false, show:false},
						{id:81, name:"瀏覽活動列表", icon:"list-alt", selected:false, show:false},
					]				
				},
				{
					name: "團購商品分類權限",
					icon: "cubes",
					show: false,
					items: [
						{id:22, name:"新增分類", icon:"plus", selected:false, show:false},
						{id:24, name:"刪除分類", icon:"minus", selected:false, show:false},
						{id:26, name:"修改分類", icon:"edit", selected:false, show:false},
						{id:20, name:"瀏覽分類列表", icon:"list-alt", selected:false, show:false},
					]				
				},
				{
					name: "團購主管理權限",
					icon: "male",
					show: false,
					items: [
						{id:60, name:"新增團購主", icon:"plus", selected:false, show:false},					
						{id:62, name:"修改團購主", icon:"edit", selected:false, show:false},
						{id:61, name:"瀏覽團購主列表", icon:"list-alt", selected:false, show:false},
					]				
				}
			];	
		}

		

		/**
		*	Get seleted id records.
		*
		*	@param permissionColumns array The permissions info.
		*	@param attribute string Attribute that want to get.
		*	@return Array Attribute = [11,12,45,77 ... ]
		*/
		this.getSeletedAttribute = function(permissionColumns, attribute) {
			var groupIds = [];
			var columnNumber, permissionColumn, permissionNumber, permission, itemNumber, item;

			for(columnNumber in permissionColumns) {
				permissionColumn = permissionColumns[columnNumber];

				for(permissionNumber in permissionColumn) {
					permission = permissionColumn[permissionNumber];

					for(itemNumber in permission.items) {
						item = permission.items[itemNumber];						
						if(item.selected) {
							groupIds.push(item[attribute]);
						}
					}					
				}
			}

			return groupIds;
		}

		/**
		*	Format permission list.
		*
		*	@param permissionList 	array 	Permission that has show flag.
		*	@param columnNumber 	int 	The data column number.
		*	@return Array 	$scope.permissionColumns[0] = [
		*						{
		*							name: "團購商品權限",
		*							icon: "cube",
		*							items: [
		*								{id:42, name:"新增商品", icon:"plus", selected:false},
		*								{id:44, name:"刪除商品", icon:"minus", selected:false},
		*								{id:40, name:"瀏覽商品列表", icon:"list-alt", selected:false},
		*							]			
		*						},
		*						{...}
		*					];
		*					$scope.permissionColumns[1] = [
		*						{
		*							name: "團購商品權限",
		*							icon: "cube",
		*							items: [
		*								{id:42, name:"新增商品", icon:"plus", selected:false},
		*								{id:44, name:"刪除商品", icon:"minus", selected:false},
		*								{id:40, name:"瀏覽商品列表", icon:"list-alt", selected:false},
		*							]			
		*						},
		*						{...}
		*					];
		*					$scope.permissionColumns[ N ]....
		*
		*
		*
		*/
		this.format = function(permissionList, columnNumber) {
			var permissionIndex, permission, itemIndex, item;
			var columns = [];
			var columnIndex = 0;

			for(permissionIndex in permissionList) {
				permission = permissionList [permissionIndex];

				// Has showing permission, replace items.
				if(permission.show) {								
					var items = [];
					for(itemIndex in permission.items) {
						item = permission.items[itemIndex];

						if(item.show) {										
							items.push(item);	
						}													
					}
					
					permission.items = items;								

					if(columnIndex < columnNumber) {
						columns[columnIndex] = columns[columnIndex] || [];
						columns[columnIndex].push(permission);
						columnIndex++;
					}
					else {
						columnIndex = 0;
					}
				}
			}

			return columns;
		}

		/**
		*	Build permission columns data by permission data from api.
		*
		*	@return Array [{
		*						name: "團購商品權限",
		*						icon: "cube",
		*						items: [
		*							{id:42, name:"新增商品", icon:"plus", selected:false},
		*							{id:44, name:"刪除商品", icon:"minus", selected:false},
		*							{id:40, name:"瀏覽商品列表", icon:"list-alt", selected:false},
		*						]			
		*					},
		*					{
		*						name: "團購商品權限",
		*						icon: "cube",
		*						items: [
		*							{id:42, name:"新增商品", icon:"plus", selected:false},
		*							{id:44, name:"刪除商品", icon:"minus", selected:false},
		*							{id:40, name:"瀏覽商品列表", icon:"list-alt", selected:false},
		*						]			
		*					},
		*					.....
		*				]
		*/
		this.export = function(records) {						
			var recordIndex, record, permissionIndex, permission, itemIndex, item;
			var permissions = getPermissions();

			for(recordIndex in records) {
				record = records[recordIndex];

				for(permissionIndex in permissions) {
					permission = permissions[permissionIndex];

					for(itemIndex in permission.items) {
						item = permission.items[itemIndex];

						if(item.id == record.permission_id) {
							item.show = true;
							permission.show = true;

							if(record.group_permission_id) {
								item.group_permission_id = record.group_permission_id;
							}
						}
					}
				}
			}

			return self.format(permissions, columnNumber);
		}
	}

    return PermissionDataBuilder;
});