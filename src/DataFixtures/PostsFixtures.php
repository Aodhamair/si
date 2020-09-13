<?php
/**
 * Posts fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Posts;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostsFixtures.
 */
class PostsFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(15, 'posts', function ($i) {
            $post = new Posts();
            $post->setTitle($this->faker->sentence);
            $post->setContent($this->faker->sentence);
            $post->setCategory($this->getRandomReference('category'));
            $post->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));

            return $post;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}
