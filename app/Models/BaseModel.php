<?php
/**
 * Created by PhpStorm.
 * User: dingzhipeng
 * Date: 2020/4/21
 * Time: 3:22 PM
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class BaseModel extends Model{
    //格式化时间
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
