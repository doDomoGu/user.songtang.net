<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use oa\models\Flow;
use yii\helpers\Url;
    $this->title = '【'.$task->title.'】的流程设置';
    oa\modules\admin\assets\AdminAsset::addJsFile($this,'js/main/task/flow.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?php if($task->set_complete==0):?>
        <?=Html::a('新增流程','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#createModal",'class'=>'btn btn-success'])?>
        <?php endif;?>
        <?=Html::a('返回','/admin/task',['class'=>'btn btn-default'])?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>标题</th>
            <th>操作类型</th>
            <th>指定操作人</th>
            <!--<th>转发</th>-->
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->step?></td>
                <td><?=$l->title?></td>
                <td><?=Flow::getTypeCn($l->type)?></td>
                <td><?=$l->user_id>0?$l->user->name: '<span style="color:#f44b00;">[由发起人选择]</span>' ?></td>
                <!--<td><?/*=$l->enable_transfer==0?'禁止':'允许'*/?></td>-->
                <td><?=\common\components\CommonFunc::getStatusCn($l->status)?></td>
                <td>
                    <?=Html::a('编辑','script:void(0)',['data-toggle'=>"modal",'data-target'=>"#editModal",'class'=>'btn btn-primary btn-xs','data-title'=>$l->title,'data-type'=>$l->type,'data-user-id'=>$l->user_id,'data-id'=>$l->id])?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '新增流程',
    'id'=>'createModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="createContent">
        <form class="form-horizontal" role="form">
            <input class="task-id" type="hidden" value="<?=$task->id?>" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">标题</label>
                <div class="col-sm-6">
                    <input class="form-control create-title">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">类型</label>
                <div class="col-sm-6">
                    <select class="form-control create-type-select">
                        <?=Flow::getOptions()?>
                    </select>
                </div>
            </div>
            <div class="form-group" style="display:none;">
                <label class="col-sm-4 control-label label1">是否允许转发</label>
                <div class="col-sm-6">
                    <select class="form-control create-enable-transfer-select">
                        <option value="0">禁止</option>
                        <option value="1">允许</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">职员</label>
                <div class="col-sm-6">
                    <?=\yii\bootstrap\BaseHtml::dropDownList('user-select','',\ucenter\models\User::getItems(),['class'=>"form-control create-user-select",'prompt'=>'---'])?>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="create-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>


<?php
Modal::begin([
    'header' => '编辑流程',
    'id'=>'editModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
<div id="editContent">
    <form class="form-horizontal" role="form">
        <input class="task-id" type="hidden" value="<?=$task->id?>" />
        <input class="edit-flow-id" type="hidden"  />
        <div class="form-group">
            <label class="col-sm-4 control-label label1">标题</label>
            <div class="col-sm-6">
                <input class="form-control create-title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">类型</label>
            <div class="col-sm-6">
                <select class="form-control create-type-select">
                    <?=Flow::getOptions()?>
                </select>
            </div>
        </div>
        <div class="form-group" style="display:none;">
            <label class="col-sm-4 control-label label1">是否允许转发</label>
            <div class="col-sm-6">
                <select class="form-control create-enable-transfer-select">
                    <option value="0">禁止</option>
                    <option value="1">允许</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-4 control-label label1">职员</label>
            <div class="col-sm-6">
                <?=\yii\bootstrap\BaseHtml::dropDownList('user-select','',\ucenter\models\User::getItems(),['class'=>"form-control create-user-select",'prompt'=>'---'])?>
                <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <button type="button" class="btn btn-success" id="edit-btn">提交</button>
            </div>
        </div>
    </form>
</div>
<?php
Modal::end();
?>
