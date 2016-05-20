
function Class( attributes ) {
  
    var instancer = function() {
        
        var originalAttributes = attributes;
        var self = this;

        this.New = function(arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9, arg10, arg11, arg12) {            
            var _class = function(){};
            _class.prototype = originalAttributes;            
            if(typeof(_class.prototype.construct) != 'function') {
            	_class.prototype.construct = function(){};
            }            
            var instance = new _class();  
            instance.construct(arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9, arg10, arg11, arg12);              
            return instance;       
        }
        
        this.Extend = function( attributes ) {          
            var extendAttributes = {};            
            for(name in originalAttributes) {
                extendAttributes[name] = originalAttributes[name]
            }            
            for(name in attributes) {                
                extendAttributes[name] = attributes[name]
            }            
            return Class(extendAttributes);
        }         
    }
    
    return new instancer();
}

define(function (require) {
    return Class;
});