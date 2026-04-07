<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string|null $name
 * @property string|null $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $email
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string $tUpdated
 * @property string|null $eStatus
 * @property int $iRole
 * @property string|null $tLoginTime
 * @property string|null $tLockTime
 * @property string $eDeleted
 * @property string|null $vPassword
 * @property string|null $vUserIP
 * @property string|null $number_phone
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'auth_key', 'email', 'created_at', 'updated_at', 'number_phone', 'password_hash'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 10],
            [['username'], 'required'],
            [['status', 'created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'email'], 'string', 'max' => 255],
            [['name', 'auth_key'], 'string', 'max' => 100],
            [['number_phone'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Full Name',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'number_phone' => 'Phone'
        ];
    }

    public function getRole()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }
}
