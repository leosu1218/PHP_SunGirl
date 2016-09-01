
'use strict';

define(['SbControllerCreator'], function (SbControllerCreator) {

    var createController = function( handler ){    
    	var creator = SbControllerCreator.New();
    	return creator.create(handler);
    };

    return createController;
});