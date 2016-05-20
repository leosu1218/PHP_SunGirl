var EventHero = ((new function() {
    var self = this;

    /**
     * Handlers instance collection.
     * @type {{}}
     */
    self.handlers = {};

    /**
     * Define class HandlerParam
     * @constructor
     */
    var HandlerParam = function() {
        var hp = this;
        hp.isSet = false;

        /**
         * Setter.
         * @param value
         */
        hp.setValue = function(value) {
            hp.currentValue = value;
            hp.isSet = true;
        };

        /**
         * Getter
         * @returns {*}
         */
        hp.getValue = function() {
            return hp.currentValue;
        };

        /**
         * Reset the param state and value;
         */
        hp.reset = function() {
            delete hp.currentValue;
            hp.isSet = false;
        };
    };

    /**
     * Emitter class creator.
     * @param handler {Handler}
     * @param handlerParams {[HandlerParams1, HandlerParams2, HandlerParams3 ...]}
     * @constructor
     */
    self.createEmitter = function() {
        var Emitter = function(handler, handlerParams) {
            this.handlerParams  = handlerParams || [];
            this.handler        = handler;
        };

        Emitter.prototype.emitReceived = function() {
            this.handler.emitReceivedParams(this.handlerParams, this);
        };

        /**
         * Define how to emit listener was received new params.
         * @returns {Function}
         */
        Emitter.prototype.createListener = function() {
            var emitter = this;
            return function() {
                if(emitter.onListenerReceived(emitter.handlerParams, arguments)) {
                    emitter.emitReceived();
                }
            }
        };

        /**
         * Hook for listener received params.
         * @param params
         * @param args
         * @returns {boolean} Emit handler event listener completed.
         */
        Emitter.prototype.onListenerReceived = function(params, args) {
            return true;
        };

        return Emitter;
    };

    /**
     * Define class GeneralEmitter.
     */
    var GeneralEmitter = self.createEmitter();

    /**
     * Hook for listener received params.
     * @param params
     * @param args
     * @returns {boolean} Emit handler event listener completed.
     */
    GeneralEmitter.prototype.onListenerReceived = function(params, args) {
        for(var i in args) {
            if(args[i] && params[i]) {
                params[i].setValue(args[i]);
            }
        }
        return true;
    };

    /**
     * Defined class Handler.
     * @param define {function}
     * @param Emitter {Emitter}
     */
    var Handler = function(define, Emitter) {
        var h = this;
        h.params = {};
        h.originHandler = define;

        /**
         * Initialize params.
         */
        (function initParams(def) {
            var args = self.getParamNames(def);
            for(var i in args) {
                h.params[args[i]] = new HandlerParam(args[i]);
            }
        }(define));

        /**
         * Initialize Emitter.
         */
        (function initEmitter() {
            Emitter = Emitter || self.emitter("general");
        }());

        /**
         * Create a event listener for params.
         * @param {[, params1, params2, params3...]} Handler params.
         */
        h.listen = function() {
            var emitter = new Emitter(h, arguments);
            return emitter.createListener()
        };

        /**
         * params getter.
         * @returns {{}}
         */
        h.getParams = function() {
            return h.params;
        };

        /**
         * Apply params to original handler.
         */
        h.applyOrigin = function() {
            var args = [];
            var params = h.getParams();

            for(var i in params) {
                args.push(params[i].getValue());
            }

            h.originHandler.apply(h, args);

            if(h.onApplyOrigin(params)) {
                h.clearParams();
            }
        };

        /**
         * Emitting received new params from emitter event.
         * @param newParams
         * @param srcEmitter
         */
        h.emitReceivedParams = function(newParams, srcEmitter) {
            if(h.onReceivedParams(h.getParams(), newParams, srcEmitter)) {
                h.applyOrigin();
            }
        };


        /**
         * Hook for received new param value by listener.
         * @param allParams All of the handler's params.
         * @param newParams The new params of received.
         * @param srcEmitter Source emitter of params.
         * @returns {boolean}
         */
        h.onReceivedParams = function(allParams, newParams, srcEmitter) {
            var isShouldEmit = true;

            for(var name in allParams) {
                isShouldEmit = isShouldEmit && allParams[name].isSet;
            }

            return isShouldEmit;
        };

        /**
         * Hook for original handler called.
         * @param allParams All of the handler's params.
         * @returns {boolean} Return true to clear all params.
         */
        h.onApplyOrigin = function(allParams) {
            return true;
        };

        /**
         * Clear all of the handler's params.
         */
        h.clearParams = function() {
            var params = h.getParams();
            for(var i in params) {
                params[i].reset();
            }
        };
    };

    /**
     * Get params list from function object.
     * @param func {function}
     * @returns {Array|{index: number, input: string}}
     */
    self.getParamNames = function(func) {
        var STRIP_COMMENTS = /((\/\/.*$)|(\/\*[\s\S]*?\*\/))/mg;
        var ARGUMENT_NAMES = /([^\s,]+)/g;

        var fnStr = func.toString().replace(STRIP_COMMENTS, '');
        var result = fnStr.slice(fnStr.indexOf('(')+1, fnStr.indexOf(')')).match(ARGUMENT_NAMES);
        if(result === null)
            result = [];
        return result;
    };

    /**
     * Factory method for event handler object.
     * @param name {string} name of handler.
     * @param define {function} Original handler function define.
     */
    self.create = function(define, customerSettings) {
        customerSettings = customerSettings || {};
        var handler;
        var emitter;

        if(customerSettings.emitter) {
            emitter = self.emitter(customerSettings.emitter);
        }

        handler = new Handler(define, emitter);

        if(customerSettings.name) {
            self.handlers[customerSettings.name] = handler;
        }

        if(customerSettings.onApplyOrigin) {
            handler.onApplyOrigin = customerSettings.onApplyOrigin;
        }

        if(customerSettings.onReceivedParams) {
            handler.onReceivedParams = customerSettings.onReceivedParams;
        }

        return handler;
    };

    /**
     * Emitter class factory.
     * @param name {string} Name of emitter class.
     */
    self.emitter = function(name) {
        return GeneralEmitter;
    };

}()));

if(typeof(module) != 'undefined') {
    module.exports = EventHero;
}
