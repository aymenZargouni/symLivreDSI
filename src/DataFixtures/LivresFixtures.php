<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Livres;
use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LivresFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($j=1;$j<=3;$j++)
        {
        $cat = new Categories();
        $cat->setLibelle($faker->name())
            ->setDescription($faker->text());
        $manager->persist($cat);

        
        for ($i = 1 ; $i <= random_int(10,15) ; $i++){
            $livre =new Livres();
            $livre->setTitre($faker->name())
                ->setPrix(random_int(10,300))
                ->setResume($faker->text())
                ->setDateEdition(new \DateTime($faker->date()))
                ->setImage('https://picsum.photos/300/?random='.$i)
                ->setEditeur($faker->company())
                ->setCategories($cat);
            $em=$manager->persist($livre);
        }
        
    }
    $manager->flush();
}
}