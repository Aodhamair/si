<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(5,'category',function ($i) {
            $category = new Category();
            $category->setName($this->faker->word);

            return $category;
        });

        $manager->flush();
    }
}
