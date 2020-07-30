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
