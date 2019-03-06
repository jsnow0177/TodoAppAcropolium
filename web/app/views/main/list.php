<?$this->section('header');?>
<div class="container-fluid">
    <div class="card-columns">
        <?foreach($tasks as $task){
            $cardClass = 'primary';

            switch($task['status']){
                case 'new': $cardClass = 'primary'; break;
                case 'active': $cardClass = 'warning'; break;
                case 'done': $cardClass = 'success'; break;
            }
        ?>
        <div class="card text-white bg-<?=$cardClass;?>" data-task-id="<?=$task['task_id'];?>">
            <div class="card-header">
                <h5 class="card-title">
                    <?=$task['title'];?>&nbsp;<small>#<?=$task['task_id'];?></small>
                    <button type="button" class="close remove-task"><span aria-hidden="true">&times;</span></button>
                </h5>
                <small class="badge badge-info"><?=$statuses[$task['status']];?></small>
            </div>
            <div class="card-body">
                <p class="card-text"><?=$task['body'];?></p>
            </div>
            <div class="card-body">
                <a href="/edit?task_id=<?=$task['task_id'];?>" class="btn btn-info">Редактировать</a>
                <a href="#" class="btn btn-danger remove-task">Удалить</a>
            </div>
            <div class="card-footer">
                <small>Создано: <?= $task['created']; ?></small>
            </div>
        </div>
        <?}?>
    </div>
</div>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/noty.js"></script>
<script type="text/javascript" src="/js/notify.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var Loader = (function(){
            var lastLoaderId = 1;

            return {
                show: function($card){
                    $card.prepend('<div class="loading-state active" id="card-loading-element-' + lastLoaderId + '"></div>');
                    return lastLoaderId;
                },
                hide: function($card){
                    $card.find('.loading-state').remove();
                },
                hideById: function(id){
                    $('card-loading-element-' + id).remove();
                }
            };

        }());

        $('body').on('click', '.remove-task', function(e){
            e.preventDefault();
            var $card = $(this).parents('.card'),
                taskId = +$card.data('taskId');

            if(isNaN(taskId) || !isFinite(taskId)) {
                notify('Не удалось удалить задачу', 'error');
                return false;
            }

            Loader.show($card);

            $.ajax({
                type: 'POST',
                url: '/task.delete',
                data: {task_id: taskId},
                dataType: 'json',
                cache: false,
                success: function(response){
                    if(response.error !== undefined){
                        notify(response.error.message, 'error');
                        Loader.hide($card);
                    }else if(response.response !== undefined && response.response.deleted){
                        notify('Задача удалена', 'success');
                        $card.remove();
                    }
                },
                error: function(){
                    notify('Не удалось удалить задачу', 'error');
                    Loader.hide($card);
                }
            });
        });
    });
</script>
<?$this->section('footer');?>