var delay = (function(){

    var _tags = {};

    function delay(tag, cb, ms){
        if(_tags[tag] !== undefined)
            clearTimeout(_tags[tag]);

        _tags[tag] = setTimeout(cb, ms);
    }

    delay.clear = function(tag){
        if(Array.isArray(tag)){
            for(var i = 0; i < tag.length; i++){
                if(_tags[tag[i]] !== undefined){
                    clearTimeout(_tags[tag[i]]);
                    delete _tags[tag[i]];
                }
            }
        }else{
            if(_tags[tag] !== undefined)
                clearTimeout(undefined);

            delete _tags[tag];
        }
    };

    delay.tags = _tags;

    return delay;
}());