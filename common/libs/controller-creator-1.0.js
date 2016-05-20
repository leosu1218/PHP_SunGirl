/**
*   ControllerCreator is a AngularJs controller create tool.
*	It's hook some handler before or after controller allocated.
*
*	version 1.0
*/

var ControllerCreator = Class({
    /**
    *   Hook some handlers before controller allocated.
    *
    */
    beforeHooks: function() {
        // Default do nothing.
    },

    /**
    *	Hook some handlers after controller allocated.
    *    
    */
    afterHooks: function() {
        // Default do nothing.
    },

    /**
    *	Get the parameters from a function object.
    *
    *	@param func function Function object that is get parameters.
    *	@return string Function paramter string e.g. "$scope, $routeParams"
    */
    getFunctionParams: function(func) {

    	if(typeof(func) != 'function') {
    		throw "Can't get parameters from the object.";
    	}
    	else {
    		var functionString = String(func);
    		var functionSplit = functionString.split(/function[ ]*\((.*)\)[ \n\r]*\{/);	
    		return functionSplit[1];	
    	}    
    },

    /**
    *	Create the controller function with hooks.
    *
    *	@param controller function The angularJs controller.
    */
    create: function(controller) {

    	var self = this;
    	var newController = function() {};
    	var parameters = self.getFunctionParams(controller);

    	var evalCode = "";
    	evalCode += "newController = function (" + parameters + ") {"
		evalCode += "		self.beforeHooks(" + parameters + ");";
		evalCode += "		controller(" + parameters + ");";
		evalCode += "		self.afterHooks(" + parameters + ");";
		evalCode += "}";

		eval(evalCode);

		return newController;
    },


});

define(function (require) {
    return ControllerCreator;
});