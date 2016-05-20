
define(['angular'], function (angular) {

    var ORM = {};

    /**
     * Create a ORM prototype class.
     * @param api
     * @param config
     * @param constructor
     * @returns {*|Function}
     */
    ORM.createPrototype = function (api, config, constructor) {
        config = config || {};

        /**
         * Construct Class Prototype.
         * @param $http
         * @constructor
         */
        var Prototype = constructor || function($http) {
                this.$services.$http = $http;
            };

        /**
         * Angular services Prototype.
         */
        Prototype.prototype.$services = {};

        /**
         * API result cache data pool.
         */
        Prototype.prototype.$caches = {};
        Prototype.prototype.$lastCache = {};

        /**
         * API event handler pool.
         * @type {Array}
         */
        Prototype.prototype.$successCallbacks = [];
        Prototype.prototype.$errorCallbacks = [];

        /**
         * Common evnet handler pool.
         * @type {Array}
         */
        Prototype.prototype.$eventCallbacks = {};

        /**
         * API configurations.
         * @type {Array}
         */
        Prototype.prototype.$config = {
            find: config.find || api,
            list: config.list || api,
            get: config.get || api,
            create: config.create || api,
            update: config.update || api,
            remove: config.remove || api,
            refresh: config.refresh || 'reload',
        };

        /**
         * Setter for outer customer add api configuration.
         * @param apiUrl
         */
        Prototype.prototype.$findApi = function(apiUrl) {
            this.$config.find = apiUrl;
        };

        /**
         * Setter for outer customer add api configuration.
         * @param apiUrl
         */
        Prototype.prototype.$listApi = function(apiUrl) {
            this.$config.list = apiUrl;
        };

        /**
         * Setter for outer customer add api configuration.
         * @param apiUrl
         */
        Prototype.prototype.$getApi = function(apiUrl) {
            this.$config.get = apiUrl;
        };

        /**
         * Setter for outer customer add api configuration.
         * @param apiUrl
         */
        Prototype.prototype.$createApi = function(apiUrl) {
            this.$config.create = apiUrl;
        };

        /**
         * Setter for outer customer add api configuration.
         * @param apiUrl
         */
        Prototype.prototype.$removeApi = function(apiUrl) {
            this.$config.remove = apiUrl;
        };

        /**
         * Setter for outer customer add api configuration.
         * @param apiUrl
         */
        Prototype.prototype.$updateApi = function(apiUrl) {
            this.$config.update = apiUrl;
        };

        /**
         * Emit common event.
         * @param event {String}
         * @param args {Array}
         */
        Prototype.prototype.$emit = function(event, args) {
            var self = this;
            this.$eventCallbacks[event] = this.$eventCallbacks[event] || [];

            for(var item in this.$eventCallbacks[event]) {
                try {
                    self.$eventCallbacks[event][item].apply(self, args);
                }
                catch(e) {
                    // Nothing to do.
                }
            }
        };

        /**
         * Set common event callback.
         * @param event {String}
         * @param callback {Function}
         */
        Prototype.prototype.$on = function(event, callback) {
            this.$eventCallbacks[event] = this.$eventCallbacks[event] || [];
            this.$eventCallbacks[event].push(callback);
        };

        /**
         * Emit error event
         * @param request   http request
         * @param response  http response.
         */
        Prototype.prototype.$emitError = function(request, response, orm) {
            for(var index in this.$errorCallbacks) {
                this.$errorCallbacks[index](request, response, orm);
            }
        };

        /**
         * Emit success event
         * @param request   http request
         * @param response  http response.
         */
        Prototype.prototype.$emitSuccess = function(request, response, orm) {
            for(var index in this.$successCallbacks) {
                this.$successCallbacks[index](request, response, orm);
            }
        };


        /**
         * Set error handler.
         * @param handler
         */
        Prototype.prototype.$onError = function(handler) {
            this.$errorCallbacks.push(handler);
            return this;
        };

        /**
         * Set success handler.
         * @param handler
         */
        Prototype.prototype.$onSuccess = function(handler) {
            this.$successCallbacks.push(handler);
            return this;
        };

        /**
         * Get API result from cache pool.
         * @param key
         */
        Prototype.prototype.$getCache = function(key) {
            this.$caches[key] = this.$caches[key] || {};
            return this.$caches[key];
        };

        /**
         * Save API result into cache pool.
         * @param key
         * @param response
         * @param request
         * @param condition
         */
        Prototype.prototype.$saveCache = function(key, response, request, condition) {
            this.$caches[key] = this.$caches[key] || {};
            this.$caches[key].response = response;
            this.$caches[key].request = request;
            this.$caches[key].condition = condition;
            this.$lastCache = this.$caches[key];
        };

        /**
         * Clear all API result cache.
         */
        Prototype.prototype.$clearCache = function() {
            this.$caches = this.$caches || {};
            for(var index in this.$caches) {
                delete this.$caches[index];
            }
        };

        // Define property.
        if(Object.defineProperty) {

            Object.defineProperty(Prototype.prototype, '$services', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$services
            });

            Object.defineProperty(Prototype.prototype, '$caches', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$caches
            });

            var $lastCache = {};
            Object.defineProperty(Prototype.prototype, '$lastCache', {
                enumerable : false,
                get: function() {
                    return $lastCache;
                },
                set: function(value) {
                    $lastCache = value;
                }
            });

            Object.defineProperty(Prototype.prototype, '$eventCallbacks', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$eventCallbacks
            });

            var $successCallbacks = [];
            Object.defineProperty(Prototype.prototype, '$successCallbacks', {
                enumerable : false,
                get: function() {
                    return $successCallbacks;
                },
                set: function(value) {
                    $successCallbacks = value;
                }
            });

            var $errorCallbacks = [];
            Object.defineProperty(Prototype.prototype, '$errorCallbacks', {
                enumerable : false,
                get: function() {
                    return $errorCallbacks;
                },
                set: function(value) {
                    $errorCallbacks = value;
                }
            });

            var $config = {
                find: config.find || api,
                list: config.list || api,
                get: config.get || api,
                create: config.create || api,
                update: config.update || api,
                remove: config.remove || api,
                refresh: config.refresh || 'reload'
            };
            Object.defineProperty(Prototype.prototype, '$config', {
                enumerable : false,
                get: function() {
                    return $config;
                },
                set: function(value) {
                    $config = value;
                }
            });

            Object.defineProperty(Prototype.prototype, '$findApi', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$findApi
            });

            Object.defineProperty(Prototype.prototype, '$listApi', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$listApi
            });

            Object.defineProperty(Prototype.prototype, '$getApi', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$getApi
            });

            Object.defineProperty(Prototype.prototype, '$removeApi', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$removeApi
            });

            Object.defineProperty(Prototype.prototype, '$updateApi', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$updateApi
            });

            Object.defineProperty(Prototype.prototype, '$createApi', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$createApi
            });

            Object.defineProperty(Prototype.prototype, '$on', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$on
            });

            Object.defineProperty(Prototype.prototype, '$emit', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$emit
            });

            Object.defineProperty(Prototype.prototype, '$emitError', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$emitError
            });

            Object.defineProperty(Prototype.prototype, '$emitSuccess', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$emitSuccess
            });

            Object.defineProperty(Prototype.prototype, '$onError', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$onError
            });

            Object.defineProperty(Prototype.prototype, '$onSuccess', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$onSuccess
            });

            Object.defineProperty(Prototype.prototype, '$getCache', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$getCache
            });

            Object.defineProperty(Prototype.prototype, '$saveCache', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$saveCache
            });

            Object.defineProperty(Prototype.prototype, '$clearCache', {
                enumerable : false,
                writable: false,
                value : Prototype.prototype.$clearCache
            });
        }

        return Prototype;
    };

    /**
     * Create model class.
     * @param api
     * @param config
     * @returns {*|Function}
     */
    ORM.model = function(api, config) {

        var Model = ORM.createPrototype(api, config, function($http, context, object) {
            this.$services.$http = $http;
            this._refresh(object);
            this.$lastCache = object;
            this.$context = context
        });

        Model.prototype.$context = null;

        /**
         * Refresh model's elements.
         * @param object
         * @private
         */
        Model.prototype._refresh = function(object) {
            angular.merge(this, object);
        };

        /**
         * Clear model's elements.
         * @private
         */
        Model.prototype._clear = function() {
            for(var index in this) {
                delete this[index];
            }
        };

        /**
         * Remove the object with server db row.
         */
        Model.prototype.$remove = function(condition) {
            var model = this;

            condition = condition || {};
            condition.success = condition.success || function() {};
            condition.error = condition.error || function() {};
            condition.refresh = condition.refresh || this.$config.refresh; // sync, reload, none

            var request = {
                method: 'DELETE',
                url: this.$config.remove + "/" + this["id"],
                headers: {'Content-Type': 'application/json'}
            };

            model.$services.$http(request).success(function(data, status, headers, config) {
                var event = "model-removed-" + condition.refresh;
                var response = {
                    data: data,
                    status: status,
                    headers: headers,
                    config: config
                };

                model._clear();
                model.$context.$emit(event , [model]);
                model.$emitSuccess(request, response, model);
                condition.success(request, response, model);
            }).error(function(data, status, headers, config) {
                var response = {
                    data: data,
                    status: status,
                    headers: headers,
                    config: config
                };

                model.$emitError(request, response, collection);
                condition.error(request, response, collection);
            });
        };


        // Define property.
        if(Object.defineProperty) {

            var context = null;
            Object.defineProperty(Model.prototype, '$context', {
                enumerable : false,
                get : function() {
                    return context;
                },
                set : function(value) {
                    context = value;
                }
            });

            Object.defineProperty(Model.prototype, '_clear', {
                enumerable : false,
                writable: false,
                value : Model.prototype._clear
            });

            Object.defineProperty(Model.prototype, '_refresh', {
                enumerable : false,
                writable: false,
                value : Model.prototype._refresh
            });

            Object.defineProperty(Model.prototype, '$remove', {
                enumerable : false,
                writable: false,
                value : Model.prototype.$remove
            });
        }

        return Model;
    };

    /**
     * Create collection class.
     * @param api
     * @param config
     * @returns {*|Function}
     */
    ORM.collection = function(api, config) {

        var Model       = ORM.model(api, config);

        var Collection  = ORM.createPrototype(api, config, function($http) {
            this.$services.$http    = $http;
            var self                = this;

            this.$on("model-removed-sync", function(model) {
                self._remove(model);
            });

            this.$on("model-removed-reload", function(model) {
                self.$reload();
            });

            this.$on("model-created-sync", function(request, response, condition) {
                self.push(self.$createModel(response.data));
            });

            this.$on("model-created-reload", function(request, response, condition) {
                self.$reload();
            });
        });


        // Extend Native Class Array.
        var methods = Object.getOwnPropertyNames(Array.prototype);
        for(var index in methods) {
            var name = methods[index];

            if(Object.defineProperty) {
                if(name == 'length') {
                    var length = 0;
                    Object.defineProperty(Collection.prototype, name, {
                        enumerable : false,
                        get: function() {
                            return length;
                        },
                        set: function(value) {
                            length = value;
                        }
                    });
                }
                else {
                    Object.defineProperty(Collection.prototype, name, {
                        enumerable : false,
                        writable: false,
                        value : Array.prototype[name]
                    });
                }
            }
            else {
                Collection.prototype[name] = Array.prototype[name];
            }
        }

        /**
         * Create a request json object.
         * @param verb
         */
        Collection.prototype.$createRequest = function(method, condition) {

            condition = condition || {};
            var request = {
                headers: {'Content-Type': 'application/json'}
            };

            if(method == '$find') {
                request.method = "GET";
                request.url = this.$config.find;

                var suffix 	= (condition.params) ? '/' : '';

                if(condition.pageNo) {
                    request.url += '/' + condition.pageNo;
                }
                if(condition.pageSize) {
                    request.url += '/' + condition.pageSize;
                }
                if(suffix) {
                    request.url += suffix;
                }
                if(condition.params) {
                    request.params = condition.params;
                }
            }
            else if(method == '$list') {
                request.method = "GET";
                request.url = this.$config.list;
                var suffix = '/';

                if(condition.pageNo) {
                    request.url += '/' + condition.pageNo;
                }
                else {
                    request.url += '/1';
                }

                if(condition.pageSize) {
                    request.url += '/' + condition.pageSize;
                }
                else {
                    request.url += '/9999'
                }
                if(suffix) {
                    request.url += suffix;
                }
            }
            else if(method == '$create') {
                request.method = "POST";
                request.url = this.$config.create;
                request.data = condition.value;
            }
            else {
                throw {message: "Undefined request type " + method + "."};
            }

            return request;
        };

        /**
         * Remove a model that in the collection.
         * @param model
         * @private
         */
        Collection.prototype._remove = function(model) {
            this.splice(this.indexOf(model), 1);
        };

        /**
         * Refresh collection's elements.
         * @param data
         */
        Collection.prototype._refresh = function(response) {

            var records = [];
            var object, model;

            for(var index in response.data.records) {
                object = response.data.records[index];
                model = this.$createModel(object);
                records.push(model);
            }

            if(this.length > 0) {
                this.splice(0, this.length);
            }

            var args = [0, 0].concat(records);
            this.splice.apply(this, args);
        };

        /**
         * Reload records from api by last request, condition
         */
        Collection.prototype.$reload = function() {
            var lastRequest = angular.merge({}, this.$lastCache.request);
            var lastCondition = angular.merge({}, this.$lastCache.condition);
            lastCondition.useCache = false;

            this.$read(lastRequest, lastCondition);
        };

        /**
         * Read records as collection by request
         * @param request
         * @param condition
         * @returns {Collection}
         */
        Collection.prototype.$read = function(request ,condition) {
            var collection  = this;
            var cacheKey    = request.url + JSON.stringify(request.params);

            condition = condition || {};
            condition.success = condition.success || function() {};
            condition.error = condition.error || function() {};

            // has cache.
            if(collection.$caches[cacheKey] && condition.useCache) {
                var response = collection.$getCache(cacheKey).response;
                response.status = 304;

                collection._refresh(response);
                collection.$saveCache(cacheKey, response, request, condition);
                setTimeout(function() {
                    collection.$emitSuccess(request, response, collection);
                    condition.success(request, response, collection);
                }, 1);
            }
            else {
                collection.$services.$http(request).success(function(data, status, headers, config) {
                    var response = {
                        data: data,
                        status: status,
                        headers: headers,
                        config: config
                    };

                    collection._refresh(response);
                    collection.$saveCache(cacheKey, response, request, condition);
                    setTimeout(function() {
                        collection.$emitSuccess(request, response, collection);
                        condition.success(request, response, collection);
                    }, 1);
                }).error(function(data, status, headers, config) {
                    var response = {
                        data: data,
                        status: status,
                        headers: headers,
                        config: config
                    };

                    collection.$emitError(request, response, collection);
                    condition.error(request, response, collection);
                });
            }

            return collection;
        };

        /**
         * Find records as collection
         * @returns {Collection}
         */
        Collection.prototype.$find = function(condition) {
            var request = this.$createRequest('$find', condition);
            return this.$read(request, condition);
        };

        /**
         * List records as collection
         * @returns {Collection}
         */
        Collection.prototype.$list = function(condition) {
            var request = this.$createRequest('$list', condition);
            return this.$read(request, condition);
        };

        /**
         * Create a model into the collection by object.
         * @param object
         * @param condition
         * @returns {Collection}
         */
        Collection.prototype.$create = function(object, condition) {
            var collection  = this;
            condition = condition || {};
            condition.success = condition.success || function() {};
            condition.error = condition.error || function() {};
            condition.value = object || {};
            condition.refresh = condition.refresh || this.$config.refresh; // sync, reload, none

            var request = this.$createRequest('$create', condition);

            collection.$services.$http(request).success(function(data, status, headers, config) {
                var response = {
                    data: data,
                    status: status,
                    headers: headers,
                    config: config
                };

                var event = "model-created-" + condition.refresh;

                collection.$emit(event, [request, response, condition]);
                collection.$emitSuccess(request, response, collection);
                condition.success(request, response, collection);
            }).error(function(data, status, headers, config) {
                var response = {
                    data: data,
                    status: status,
                    headers: headers,
                    config: config
                };

                collection.$emitError(request, response, collection);
                condition.error(request, response, collection);
            });

            return collection;
        };

        /**
         * Create new model instance.
         * @param object {json} Default model attributes.
         */
        Collection.prototype.$createModel = function(object) {
            return new Model(this.$services.$http, this, object);
        };

        /**
         * Get a json object that mapped Prototype attributes.
         */
        Collection.prototype.$toJson = function() {
            if(this.$lastCache) {
                return angular.merge({}, this.$lastCache.response.data);
            }
            else {
                return {};
            }
        };

        // Define property.
        if(Object.defineProperty) {

            Object.defineProperty(Collection.prototype, '$createRequest', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$createRequest
            });

            Object.defineProperty(Collection.prototype, '_refresh', {
                enumerable : false,
                writable: false,
                value : Collection.prototype._refresh
            });

            Object.defineProperty(Collection.prototype, '$reload', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$reload
            });

            Object.defineProperty(Collection.prototype, '$read', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$read
            });

            Object.defineProperty(Collection.prototype, '$find', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$find
            });

            Object.defineProperty(Collection.prototype, '$list', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$list
            });

            Object.defineProperty(Collection.prototype, '$create', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$create
            });

            Object.defineProperty(Collection.prototype, '$createModel', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$createModel
            });

            Object.defineProperty(Collection.prototype, '$toJson', {
                enumerable : false,
                writable: false,
                value : Collection.prototype.$toJson
            });
        }

        return Collection;
    };



    return ORM;
});