/**
*   ControllerCreator is a AngularJs controller create tool.
*	It's hook some handler before or after controller allocated.
*
*	version 1.0
*/

define([], function () {

	var SbControllerCreator = ControllerCreator.Extend({	    

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
	    afterHooks: function($scope) {	        

	    },   
	});

    return SbControllerCreator;
});