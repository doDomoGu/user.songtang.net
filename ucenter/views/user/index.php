<?php
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use common\components\CommonFunc;
use ucenter\models\Department;
    $this->title = '职员管理';
    //app\assets\AppAsset::addJsFile($this,'js/main/structure/index.js');
?>
<section>
    <div style="margin-bottom: 10px;">
        <?/*=Html::a('新增职员','/user/add-and-edit',['class'=>'btn btn-success'])*/?>
        <?/*=Html::a('清除user缓存','/user/clear-cache',['class'=>'btn btn-danger'])*/?>
    </div>
    <table class="table table-bordered" style="background: #fafafa;">
        <tr>
            <th>#</th>
            <th>用户名</th>
            <th>姓名</th>
            <th>地区 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
            <th>行业 <?/*=Html::dropDownList('area-select',$aid,$aArr,['prompt'=>'----','id'=>'area-select'])*/?></th>
            <th>公司 <?/*=$aid>0?Html::dropDownList('business-select',$bid,$bArr2,['prompt'=>'----','id'=>'business-select']):''*/?></th>
            <th>部门</th>
            <th>职位</th>
            <th>性别</th>
            <th>生日</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <tbody>
        <?php foreach($list as $l):?>
            <tr>
                <td><?=$l->id?></td>
                <td><?=$l->username?></td>
                <td><?=$l->name?></td>
                <td><?=$districtArr[$l->district_id]?></td>
                <td><?=$industryArr[$l->industry_id]?></td>
                <td><?=$companyArr[$l->company_id]?></td>
                <td><?=CommonFunc::getByCache(Department::className(),'getFullRoute',[$l->department_id],'ucenter:department/full-route')?></td>
                <td><?=$positionArr[$l->position_id]?></td>
                <td><?=CommonFunc::getGenderCn($l->gender)?></td>
                <td><?=$l->birthday?></td>
                <td><?=CommonFunc::getStatusCn($l->status)?></td>
                <td>
<!--                    --><?//=Html::a('添加业态','script:void(0)',['data-pid'=>$l->aid,'data-pname'=>$aArr[$l->aid],'data-type'=>'area','data-toggle'=>"modal",'data-target'=>"#addModal",'class'=>'btn btn-xs btn-success'])?>
                </td>
            </tr>

        <?php endforeach;?>
        </tbody>
    </table>
</section>




<?php
Modal::begin([
    'header' => '<span class="modal-title"></span>',
    'id'=>'addModal',
    'options'=>['style'=>'margin-top:120px;'],
]);
?>
    <div id="addContent">
        <form class="form-horizontal" role="form">
            <input class="aid-value" type="hidden" />
            <input class="bid-value" type="hidden" />
            <input class="p_id-value" type="hidden" />
            <div class="form-group">
                <label class="col-sm-4 control-label label1">地区</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="aname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">业态</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="bname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label1">部门路径</label>
                <div class="col-sm-6" style="padding-top: 7px;">
                    <span class="pname-text"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label label2">部门选择</label>
                <div class="col-sm-6">
                    <select class="form-control new-did-select">
                    </select>
                    <div class="errormsg-text" style="display:none;color:red;padding-top:10px;"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-6">
                    <button type="button" class="btn btn-success" id="add-btn">提交</button>
                </div>
            </div>
        </form>
    </div>
<?php
Modal::end();
?>