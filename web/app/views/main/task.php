<?$this->section('header');?>
<div class="container-fluid align-content-center">
    <div class="offset-sm-2 col-sm-8">
        <div class="loading-state fixed" id="loader"></div>
        <form id="addTaskForm">
            <input type="hidden" name="task_id" id="input-taskId" value="<?=$task_id;?>" />
            <div class="form-group">
                <label for="input-taskTitle" class="control-label">Название задачи</label>
                <input type="text" name="task_title" class="form-control" id="input-taskTitle" placeholder="Название задачи"
                    <?=!empty($title)?'value="' . $title . '"':'';?>/>
                <div class="invalid-feedback">Название задачи не может быть пустым и должно быть более 2-х символов</div>
            </div>
            <div class="form-group">
                <label for="input-taskBody" class="control-label">Задача</label>
                <textarea name="task_body" id="input-taskBody" style="min-height:200px;" class="form-control"><?=!empty($body)?$body:'';?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <label for="input-initialStatus" class="control-label">Статус задачи</label>
                    <select name="initial_status" id="input-initialStatus" class="form-control">
                        <?foreach($allowedStatuses as $statusKey => $statusVal){?>
                            <option value="<?=$statusKey;?>"<?=($statusKey===$status?' SELECTED':'');?>><?=$statusVal;?></option>
                        <?}?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-xs-12">
                    <button class="btn btn-success float-right"><?=$buttonText;?></button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/noty.js"></script>
<script type="text/javascript" src="/js/notify.js"></script>
<script type="text/javascript" src="/js/changeInputState.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var $addTaskForm = $('#addTaskForm'),
            $inputTaskId = $('#input-taskId'),
            $inputTaskTitle = $('#input-taskTitle'),
            $inputTaskTitleGroup = $inputTaskTitle.parent(),
            $inputTaskBody = $('#input-taskBody'),
            $inputTaskBodyGroup = $inputTaskBody.parent(),
            $inputTaskStatus = $('#input-initialStatus'),
            $inputTaskStatusGroup = $inputTaskStatus.parent(),
            $loadingState = $('#loader'),
            allowedStatuses = [<?=implode(',',array_map(function($v){return "'{$v}'";}, array_keys($allowedStatuses)));?>];

        var titleState = 'default',
            bodyState = 'default',
            statusState = 'default'; // default, valid, invalid

        function validateTitle(){
            var title = $inputTaskTitle.val(),
                state = '';

            if(title.length > 2){
                state = 'valid';
            }else{
                state = 'invalid';
            }

            changeInputState($inputTaskTitle, $inputTaskTitleGroup, titleState, state);
            titleState = state;

            return (state==='valid');
        }

        function validateStatus(){
            var status = $inputTaskStatus.val(),
                state = '';

            if(allowedStatuses.indexOf(status) !== -1){
                state = 'valid';
            }else{
                state = 'invalid';
            }

            changeInputState($inputTaskStatus, $inputTaskTitleGroup, statusState, state);
            statusState = state;

            return (state==='valid');
        }

        $addTaskForm.on('submit', function(e){
            e.preventDefault();
            if(!(validateTitle() && validateStatus()))
                return false;

            $loadingState.toggleClass('active');

            $.ajax({
                type: 'POST',
                url: '<?=$ajax_action;?>',
                data: {task_id: $inputTaskId.val(), title: $inputTaskTitle.val(), body: $inputTaskBody.val(), status: $inputTaskStatus.val()},
                dataType: 'json',
                cache: false,
                success: function(response){
                    console.log(response);
                    if(response.error !== undefined){
                        notify(response.error.message, 'error');
                        if(response.error.code == 1) {
                            setTimeout(function () {
                                location.href = '/auth/login';
                            }, 500);
                        }else{
                            $loadingState.toggleClass('active');
                        }
                    }else{
                        notify('Задача успешно сохранена!', 'success');
                        setTimeout(function(){
                            location.href = '/edit?task_id=' + response.response.task_id;
                        }, 500);
                    }
                },
                error: function(){
                    notify('Не удалось сохранить задачу!', 'error');
                    $loadingState.toggleClass('active');
                }
            })
        });

        $inputTaskTitle.on('keyup', function(){
            validateTitle();
        });

        $inputTaskStatus.on('change', function(){
            validateStatus();
        });

        <?if($validateOnInit){?>
        validateTitle();
        validateStatus();
        <?}?>
    });
</script>
<?$this->section('footer');?>