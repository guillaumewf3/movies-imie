<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppLoadDataCommand extends Command
{
    protected static $defaultName = 'app:load-data';

    protected $em;
    protected $encoder;

    public function __construct(string $name = null,
                                EntityManagerInterface $em,
                                UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Load dummy data in database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        //instancie Faker
        $faker = \Faker\Factory::create('fr_CA');

        try {
            //exécute une bonne vieille requête SQL
            //pour vider la table
            $this->em->getConnection()->exec("
            SET FOREIGN_KEY_CHECKS=0;
            TRUNCATE user; 
            TRUNCATE review;");
        }
        catch(\Exception $e){
            $io->error($e->getMessage());
        }

        $num = (int) $io->askQuestion(new Question("How many reviews?"));

        //récupère les films
        $movieRepo = $this->em->getRepository(Movie::class);
        $movies = $movieRepo->findAll();

        //crée un administrateur
        $user = new User();
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setUsername("admin");
        $user->setEmail("admin@admin.com");
        $encoded = $this->encoder->encodePassword($user, "admin");
        $user->setPassword($encoded);
        $user->setDateCreated(new \DateTime());
        $this->em->persist($user);

        //crée un user simple
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $user->setUsername("yo");
        $user->setEmail("yo@yo.com");
        $encoded = $this->encoder->encodePassword($user, "yo");
        $user->setPassword($encoded);
        $user->setDateCreated(new \DateTime());
        $this->em->persist($user);

        //crée 50 users
        $users = [];
        for($i=0; $i<50; $i++){
            $user = new User();
            $user->setUsername($faker->username);
            $user->setEmail($faker->email);

            //j'utilise le username comme mdp
            $encoded = $this->encoder->encodePassword($user, $user->getUsername());
            $user->setPassword($encoded);
            $user->setRoles(["ROLE_USER"]);
            $user->setDateCreated(new \DateTime());

            $this->em->persist($user);
            $users[] = $user;
        }

        $this->em->flush();

        $io->progressStart($num);
        for($i=0; $i<$num; $i++) {
            //crée une instance
            $review = new Review();

            //un user au hasard pour l'auteur
            $author = $faker->randomElement($users);
            $review->setAuthor($author);

            //hydrate l'instance
            $review->setTitle($faker->sentence);
            $review->setContent($faker->realText(1000));
            $review->setDateCreated($faker->dateTimeBetween("- 2 years"));

            //associe la review à un film au hasard
            shuffle($movies);
            $movie = $movies[0];
            $review->setMovie($movie);

            //on demande à Doctrine de sauvegarder notre instance
            $this->em->persist($review);
            $io->progressAdvance();
        }
        $io->progressFinish();
        //exécute la requête SQL
        $this->em->flush();
        $io->success("Data inserted, yeah!");

        /*
        $response = $io->askQuestion(new Question('coucou?'));
        $io->caution($response);
        $io->writeln('coucou!');
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        */
    }
}
