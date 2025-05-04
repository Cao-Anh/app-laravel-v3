<?php

namespace App\Console\Commands;

use App\Models\User;
use Elastic\Elasticsearch\Client;
use Illuminate\Console\Command;

class IndexUsersToElasticsearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-users-to-elasticsearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(Client $es): int
    {
        $users = User::all();

        foreach ($users as $user) {
            $es->index([
                'index' => 'users',
                'id'    => $user->id,
                'body'  => [
                    'username'   => $user->username,
                    'email'      => $user->email,
                    'created_at' => $user->created_at->toISOString(),
                ],
            ]);
        }

        $this->info("Indexed {$users->count()} users.");
        return self::SUCCESS;
    }
}
