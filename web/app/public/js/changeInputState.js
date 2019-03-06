function changeInputState($input, $inputGroup, oldState, newState){
    if(oldState === newState)
        return true;

    switch(oldState){
        case 'valid': $inputGroup.removeClass('has-success'); $input.removeClass('is-valid'); break;
        case 'invalid': $inputGroup.removeClass('has-danger'); $input.removeClass('is-invalid'); break;
    }

    switch(newState){
        case 'valid': $inputGroup.addClass('has-success'); $input.addClass('is-valid'); break;
        case 'invalid': $inputGroup.addClass('has-danger'); $input.addClass('is-invalid'); break;
    }

    return true;
}