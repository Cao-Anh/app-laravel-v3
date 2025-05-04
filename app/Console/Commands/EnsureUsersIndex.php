<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elastic\Elasticsearch\Client;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'es:ensure-users-index',
    description: 'Create the users index in Elasticsearch if it does not exist'
)]
class EnsureUsersIndex extends Command
{
    public function handle(Client $es): int
    {
        $index = 'users';

        $exists = $es->indices()->exists(['index' => $index]);

        if ($exists->asBool()) {
            $this->info("Index '{$index}' already exists.");
            return self::SUCCESS;
        }

        $es->indices()->create([
            'index' => $index,
            'body'  => [
                'settings' => [
                    'number_of_shards'   => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    'properties' => [
                        'username'   => ['type' => 'text'],
                        'email'      => ['type' => 'text'],
                        'created_at' => [
                            'type'   => 'date',
                            'format' => 'strict_date_optional_time||epoch_millis',
                        ],
                    ],
                ],
            ],
        ]);

        $this->info("Index '{$index}' has been created.");
        return self::SUCCESS;
    }
}
