<?php

namespace Smousss\Laravel\Novalize\Commands;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;

class NovalizeCommand extends Command
{
    protected $signature = 'smousss:novalize';

    protected $description = 'Create a Nova resource for a given model';

    public function handle() : int
    {
        if (empty(config('novalize.secret_key'))) {
            $this->error('Please generate a secret key on smousss.com and add it to your .env file as SMOUSSS_SECRET_KEY.');

            return self::FAILURE;
        }

        $model = $this->ask("Name of your model (e.g. App\Models\Post)", 'App\Models\Post');

        $modelInstance = (new $model);

        $model_code = $this->getSourceCodeForModel($modelInstance);

        $model_schema = implode('; ', $this->getSchemaForModel($modelInstance));

        $this->line("GPT-4 is generating tokens for your Nova resource based on {$model}…");

        $response = Http::withToken(config('novalize.secret_key'))
            ->timeout(300)
            ->post(config('novalize.debug', false)
                ? 'https://smousss.test/api/novalize'
                : 'https://smousss.com/api/novalize', compact('model_code', 'model_schema'))
            ->throw()
            ->json();

        $baseModelName = Str::after($model, 'App\\Models\\');

        File::put(base_path($path = "app/Nova/{$baseModelName}.php"), trim(trim($response['data'], '`ph')) . PHP_EOL);

        $this->info("Your new Nova resource has been created at $path! 🎉 (Tokens: {$response['meta']['consumed_tokens']})");

        return self::SUCCESS;
    }

    protected function getSourceCodeForModel(Model $model) : string
    {
        $sourceCode = File::get((new ReflectionClass($model::class))->getFileName());

        $sourceCode = str_replace("\t", '', $sourceCode);
        $sourceCode = str_replace("\n", ' ', $sourceCode);

        return preg_replace('/ {2,}/', ' ', $sourceCode);
    }

    protected function getSchemaForModel(Model $model)
    {
        return DB::getDoctrineConnection()->getDatabasePlatform()->getCreateTableSQL(
            DB::getDoctrineSchemaManager()->introspectTable($model->getTable())
        );
    }
}
