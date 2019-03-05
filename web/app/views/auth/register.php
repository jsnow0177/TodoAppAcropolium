<?$this->section('header');?>
<div class="container-fluid">
    <div class="offset-sm-4 col-sm-4">
        <div class="card">
            <div class="loading-state"></div>
            <h3 class="card-header">Регистрация</h3>
            <div class="card-body">
                <form id="loginForm">
                    <fieldset>
                        <div class="form-group row">
                            <label for="input-email">Email:</label>
                            <input type="email" name="login" class="form-control" id="input-email" placeholder="mail@example.com" />
                            <div class="invalid-feedback">Должен быть указан валидный адрес электронной почты</div>
                        </div>
                        <div class="form-group row">
                            <label for="input-pass">Пароль:</label>
                            <input type="password" name="pass" class="form-control" id="input-pass" placeholder="Пароль" />
                            <div class="invalid-feedback">Пароль должен быть не короче пяти символов</div>
                        </div>
                        <div class="form-group row">
                            <label for="input-pass">Повторить пароль:</label>
                            <input type="password" name="repass" class="form-control" id="input-repass" placeholder="Пароль" />
                            <div class="invalid-feedback">Повторный пароль должен совпадать с паролем</div>
                        </div>
                        <div class="form-group row float-right">
                            <button class="btn btn-primary btn-lg" type="submit">Зарегистрироваться</button>
                        </div>
                    </fieldset>
                </form>
                Уже есть аккаунт? <a href="/auth/login" class="card-link">Войти</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/noty.js"></script>
<script type="text/javascript" src="/js/delay.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var $form = $('#loginForm'),
            $inputEmail = $('#input-email'),
            $inputEmailGroup = $inputEmail.parent(),
            $inputPass = $('#input-pass'),
            $inputPassGroup = $inputPass.parent(),
            $inputRePass = $('#input-repass'),
            $inputRePassGroup = $inputRePass.parent(),
            $loading = $form.parent().parent().find('div.loading-state'),
            emailRegEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w+)+$/;

        var loginState = 'default',
            passState = 'default',
            rePassState = 'default'; // default, valid, invalid

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

        function validateEmail(){
            var email = $inputEmail.val(),
                state = '';

            if(email === ''){
                state = 'default';
            }else if(emailRegEx.test(email)){
                state = 'valid';
            }else{
                state = 'invalid';
            }

            changeInputState($inputEmail, $inputEmailGroup, loginState, state);
            loginState = state;

            return (state==='valid');
        }

        function validatePass(){
            var pass = $inputPass.val(),
                state = '';

            if(pass === ''){
                state = 'default';
            }else if(pass.length >= 5){
                state = 'valid';
            }else{
                state = 'invalid';
            }

            changeInputState($inputPass, $inputPassGroup, passState, state);
            passState = state;

            return (state==='valid');
        }

        function validateRePass(){
            var rePass = $inputRePass.val(),
                pass = $inputPass.val(),
                state = '';

            if(rePass === ''){
                state = 'default';
            }else if(pass === rePass){
                state = 'valid';
            }else{
                state = 'invalid';
            }

            changeInputState($inputRePass, $inputRePassGroup, rePassState, state);
            rePassState = state;

            return (state==='valid');
        }

        $form.on('submit', function(e){
            e.preventDefault();
            delay.clear(['validateEmail', 'validatePass', 'validateRePass']);

            if(!(validateEmail() && validatePass() && validateRePass()))
                return false;

            $loading.toggleClass('active');

            $.ajax({
                type: 'POST',
                url: '/auth/ajax_register',
                data: {login: $inputEmail.val(), pass: $inputPass.val()},
                dataType: 'json',
                cache: false,
                success: function(response){
                    if(response.error !== undefined){
                        new Noty({
                            type: 'error',
                            layout: 'topCenter',
                            text: response.error.message
                        }).show();

                        $loading.toggleClass('active');
                    }else{
                        new Noty({
                            type: 'success',
                            layout: 'topCenter',
                            text: 'Регистрация прошла успешно. Теперь можно авторизоваться'
                        }).show();

                        setTimeout(function(){
                            location.href = '/auth/login';
                        }, 500);
                    }
                },
                error: function(){
                    new Noty({
                        type: 'error',
                        layout: 'topCenter',
                        text: 'Не удалось зарегистрироваться'
                    }).show();

                    $loading.toggleClass('active');
                }
            });
        });

        $inputEmail.on('keyup', function(e){
            delay('validateEmail', validateEmail, 250);
        });

        $inputPass.on('keyup', function(e){
            delay('validatePass', validatePass, 250);
        });

        $inputRePass.on('keyup', function(e){
            delay('validateRePass', validateRePass, 250);
        });
    });
</script>
<?$this->section('footer');?>
