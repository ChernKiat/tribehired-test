(function() {
    var _OBJECT_ROOT_ = window;

    if (typeof _OBJECT_ROOT_.console == "undefined" || !_OBJECT_ROOT_.console)
    {
        // !console.firebug
        
        //_OBJECT_ROOT_.console = {log: function() {}};
        
        var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

        _OBJECT_ROOT_.console = {};

        for (var i = 0; i < names.length; ++i)
        {
            _OBJECT_ROOT_.console[names[i]] = function(){};
        }
    }

    function type_of(obj)
    {
        if (typeof(obj) == 'object')
            if (typeof obj.length == "undefined" || !obj.length)
                return 'object';
            else
                return 'array';
        else
            return typeof(obj);
    }

    //=================================================
    // BEGIN Namespace util ------------------
    //var HTMLScrabble = (typeof HTMLScrabble == "undefined" || !HTMLScrabble ) ? {} : HTMLScrabble;
    //var HTMLScrabble.UI = (typeof HTMLScrabble.UI == "undefined" || !(HTMLScrabble.UI) ) ? {} : HTMLScrabble.UI;
    /*
    console.log("Checking namespace: " + "HTMLScrabble.UI");
    EnsureNamespace("HTMLScrabble.UI");

    console.log("Checking namespace: " + "HTMLScrabble.Core");
    EnsureNamespace("HTMLScrabble.Core");
    */
    function EnsureNamespace(nsString)
    //_OBJECT_ROOT_.EnsureNamespace = function(nsString)
    {
        //console.log("Ensuring namespace: " + nsString);
        
        var nsStrings = nsString.split(".");
        var root = _OBJECT_ROOT_;
        for (var i = 0; i < nsStrings.length; i++)
        {
            var nsName = nsStrings[i];
            var val = root[nsName];
            if (typeof val == "undefined" || !val)
            {
                //console.log("Creating namespace object: " + nsName);
                root[nsName] = new Object(); // {} ?
            }
            else
            {
                //console.log("Namespace object already exists: " + nsName);
            }
            root = root[nsName];
        }
    }
    // END Namespace util ------------------
    //=================================================
