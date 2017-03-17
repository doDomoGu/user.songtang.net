<?php

namespace oa\models;

/*ALTER TABLE `apply` ADD `flow_user` VARCHAR(100) NOT NULL AFTER `flow_step`;*/

//oa 申请表
use ucenter\models\User;
use Yii;
class Apply extends \yii\db\ActiveRecord
{
    public static function getDb(){
        return Yii::$app->db_oa;
    }
    const STATUS_NORMAL     = 1; //正常流程中
    const STATUS_DELETE     = 0; //删除
    const STATUS_SUCCESS    = 2; //成功 完成
    const STATUS_FAILURE    = 3; //失败 完成
    const STATUS_BEATBACK   = 4; //打回 发生情况：1.审核不通过 2.执行失败

    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => '申请标题',
            'user_id' => '发起人ID',
            'task_id' => '对应任务表Id',
            'flow_step' => '流程执行到第几步',
            'message' => '申请人填写的备注/内容',
            'add_time' => '开始时间',
            'edit_time' => '编辑时间',
            'status' => '状态',

        ];
    }

    public function rules()
    {
        return [
            [['title','user_id','task_id','flow_step'], 'required'],
            [['user_id','task_id','flow_step','status'], 'integer'],
            [['add_time','edit_time','message','flow_user'],'safe']
        ];
    }

    public function getApplyUser(){
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }


    public function getFlow(){
        return $this->hasOne(Flow::className(), array('step' => 'flow_step','task_id'=>'task_id'));
    }

    public function getTask(){
        return $this->hasOne(Task::className(), array('id' => 'task_id'));
    }

    public static function getMyApplyList($getCount=false){
        $query = self::find()->where(['user_id'=>Yii::$app->user->id]);
        if($getCount)
            $return = $query->count();
        else
            $return = $query->orderBy('add_time desc')->all();
        return $return;
    }


    public static function getTodoList($getCount=false){
        $list = [];
        $user_id = Yii::$app->user->id;
        // 1.搜索执行中的申请
        $apply = Apply::find()->where(['status'=>self::STATUS_NORMAL])->all();

        foreach($apply as $a){
            // 2.根据申请对应的任务表  和  步骤 ，判断操作人是不是自己
            $flow = Flow::find()->where(['task_id'=>$a->task_id,'step'=>$a->flow_step])->one();
            if($flow){
                if($flow->user_id>0){
                    if($flow->user_id == $user_id){
                        $list[] = $a;
                    }
                }else{
                    //如果user_id = 0 则是由发起人选择的  在apply的flow_user字段中
                    $arr =  Apply::flowUserStr2Arr($a->flow_user);
                    if(isset($arr[$a->flow_step]) && $arr[$a->flow_step] == $user_id){
                        $list[] = $a;
                    }

                }
            }
        }

        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }

//        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->all();
//        if(!empty($flow)){
//            $query = Apply::find()->where(['status'=>self::STATUS_NORMAL]);
//            //使用 任务id和流程步骤数 搜索当前的申请表中匹配的
//            $or = [];
//            foreach($flow as $f){
//                $or[] = '(task_id = "'.$f->task_id.'" and flow_step = "'.$f->step.'")';
//            }
//            $condition = implode(' or ',$or);
//            $query = $query->andWhere($condition);
//
//            if($getCount){
//                $return = $query->count();
//            }else{
//                //按时间倒序
//                $return = $query->orderBy('add_time desc')->all();
//            }
//        }else{
//            if($getCount){
//                $return = 0;
//            }else{
//                $return = [];
//            }
//        }
        return $return;
    }

    public static function getDoneList($getCount=false){
        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->orderBy('step desc')->select(['task_id','step'])->all();
        $list = [];
        if(!empty($flow)){
            foreach($flow as $f){
                $applyList = Apply::find()->where(['task_id'=>$f->task_id,'status'=>self::STATUS_NORMAL])->andWhere(['>','flow_step',$f->step])->all();
                if(!empty($applyList))
                    $list = array_merge($list,$applyList);
            }
        }
        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }
        return $return;
    }

    public static function getFinishList($getCount=false){
        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->select(['task_id'])->all();
        $list = [];
        if(!empty($flow)){
            foreach($flow as $f){
                $applyList = Apply::find()->where(['task_id'=>$f->task_id,'status'=>self::STATUS_SUCCESS])->all();
                if(!empty($applyList))
                    $list = array_merge($list,$applyList);
            }
        }
        if($getCount){
            $return = count($list);
        }else{
            $return = $list;
        }
        return $return;
    }

    public static function getRelatedList($getCount=false){
        $flow = Flow::find()->where(['user_id'=>Yii::$app->user->id])->groupBy('task_id')->select('task_id')->all();
        if(!empty($flow)){
            $taskIds = [];
            foreach($flow as $f){
                $taskIds[] = $f->task_id;
            }

            $todoList = self::getTodoList();
            $doneList = self::getDoneList();
            $finishList = self::getFinishList();
            $notInIds = [];
            foreach($todoList as $l){
                $notInIds[] = $l->id;
            }
            foreach($doneList as $l){
                $notInIds[] = $l->id;
            }
            foreach($finishList as $l){
                $notInIds[] = $l->id;
            }

            $query = Apply::find()->where(['task_id'=>$taskIds])->andWhere(['not in','id',$notInIds])->orderBy('add_time desc');
            if($getCount)
                $return = $query->count();
            else
                $return = $query->all();
        }else{
            if($getCount)
                $return = 0;
            else
                $return = [];
        }
        return $return;
    }

    public static function flowUserStr2Arr($str){
        $arr = [];
        $temp = explode('|',$str);
        foreach($temp as $t){
            $temp2 = explode(':',$t);
            $arr[$temp2[0]] = $temp2[1];
        }
        return $arr;
    }

    public static function flowUserArr2Str($arr){
        $temp = [];
        foreach($arr as $k => $f){
            $temp[] = $k.':'.$f;
        }

        return implode('|',$temp);
    }
}
