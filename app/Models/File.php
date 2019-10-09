<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 存储的文件
 *
 * @author linshunwei
 */
class File extends Model
{

	protected $table = 'files';

	protected $primaryKey = 'hash';

	public $incrementing = false;

}
