<?php

declare(strict_types=1);

use App\Bootstrap;
use Faker\Factory;
use Nette\Database\Explorer;

require '/var/www/vendor/autoload.php';

$bootstrap = new App\Bootstrap;
$container = $bootstrap->bootWebApplication();


/** @var Explorer $database */
$database = $container->getByType(Explorer::class);

$faker = Factory::create();

// $database->query('SET FOREIGN_KEY_CHECKS = 0');

// $database->table('activity_comments')->delete();
// $database->table('customer_activities')->delete();
// $database->table('customers')->delete();

// $database->query('SET FOREIGN_KEY_CHECKS = 1');

for ($i = 0; $i < 100; $i++) {

    $customer = $database->table('customers')->insert([
        'public_id' => uniqid('c'),
        'name' => $faker->name(),
        'email' => $faker->unique()->safeEmail(),
        'is_active' => $faker->boolean(),
    ]);

    $activitiesCount = random_int(5, 50);

    for ($j = 0; $j < $activitiesCount; $j++) {

        $activity = $database->table('customer_activities')->insert([
            'customer_id' => $customer->id,
            'type' => $faker->randomElement(\Mtr\MiniCRM\Repository\Customers\Activity\ActivityType::toArray()),
            'details' => $faker->paragraph(),
            'created_at' => $faker->dateTimeBetween('-1 year'),
        ]);

        $commentsCount = random_int(0, 30);

        for ($k = 0; $k < $commentsCount; $k++) {

            $database->table('activity_comments')->insert([
                'customer_activity_id' => $activity->id,
                'comment' => $faker->sentence(),
                'created_at' => $faker->dateTimeBetween('-1 year'),
            ]);
        }
    }
}

echo "Seed completed.\n";