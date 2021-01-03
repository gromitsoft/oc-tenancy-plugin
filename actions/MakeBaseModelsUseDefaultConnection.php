<?php

namespace GromIT\Tenancy\Actions;

use Backend\Models\AccessLog;
use Backend\Models\Preference;
use Backend\Models\User;
use Backend\Models\UserGroup;
use Backend\Models\UserPreference;
use Backend\Models\UserRole;
use Backend\Models\UserThrottle;
use GromIT\Tenancy\Concerns\UsesTenancyConfig;
use October\Rain\Database\Model;

class MakeBaseModelsUseDefaultConnection
{
    use UsesTenancyConfig;

    public static function make(): MakeBaseModelsUseDefaultConnection
    {
        return new static();
    }

    public function execute(): void
    {
        // Force base models to use default database connection.
        // It must be done, because if connection is not specified,
        // related models will use connection from caller model.
        //
        // Example: $post is RinaLab.Blog post.
        // $post->author will try to find user in backend_users of tenant's database.
        // And obviously will fail.

        $models = [
            AccessLog::class,
            Preference::class,
            User::class,
            UserGroup::class,
            UserPreference::class,
            UserRole::class,
            UserThrottle::class,
        ];

        foreach ($models as $model) {
            /** @noinspection PhpUndefinedMethodInspection */
            $model::extend(function (Model $model) {
                $model->setConnection($this->getDefaultConnectionName());
            });
        }
    }
}
