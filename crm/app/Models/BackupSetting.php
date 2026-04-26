<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BackupSetting extends Model
{
    protected $fillable = ['enabled','schedule_time','backup_type','file_preset','formats','upload_yandex'];
    protected $casts = ['enabled'=>'boolean','upload_yandex'=>'boolean','formats'=>'array'];

    public static function instance(): self {
        return static::firstOrCreate([], [
            'enabled' => true,
            'schedule_time' => '03:00',
            'backup_type' => 'full',
            'file_preset' => 'project',
            'formats' => ['zip','tar_gz'],
            'upload_yandex' => true,
        ]);
    }
}
