<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelGetController;

class Users extends ModelGetController
{
    protected $model = User::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('active', true);
        $c->with('role');

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('active', true);

        if ($query = $this->getProperty('query')) {
            $c->where(
                static function (Builder $c) use ($query) {
                    $c->where('username', 'LIKE', "%{$query}%");
                    $c->orWhereHas(
                        'role',
                        static function (Builder $c) use ($query) {
                            $c->where('title', 'LIKE', "%{$query}%");
                        }
                    );
                }
            );
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('role');

        return $c;
    }
}
