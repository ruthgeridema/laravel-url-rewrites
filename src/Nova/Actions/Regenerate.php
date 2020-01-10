<?php

declare(strict_types=1);

namespace RuthgerIdema\UrlRewrite\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use RuthgerIdema\UrlRewrite\Facades\UrlRewrite;

class Regenerate extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ActionFields $fields, Collection $models)
    {
        $i = 0;
        foreach ($models as $model) {
            try {
                UrlRewrite::regenerateRoute($model);
                $i++;
            } catch (\Exception $exception) {
                return Action::danger("$i done, $model->id failed: $exception->getMessage()");
            }
        }

        return Action::message($i.' '.trans('urlrewrites::translations.regenerated'));
    }
}
