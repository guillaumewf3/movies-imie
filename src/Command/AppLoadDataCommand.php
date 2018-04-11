<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppLoadDataCommand extends Command
{
    protected static $defaultName = 'app:load-data';

    protected $em;

    public function __construct(string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->em = $em;
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
            $this->em->getConnection()->exec("TRUNCATE review");
        }
        catch(\Exception $e){
            $io->error($e->getMessage());
        }

        $num = (int) $io->askQuestion(new Question("How many reviews?"));

        //récupère les 50 premiers films
        $movieRepo = $this->em->getRepository(Movie::class);
        $movies = $movieRepo->findBy([], ["rating" => "DESC"], 50);

        $io->progressStart($num);
        for($i=0; $i<$num; $i++) {
            //crée une instance
            $review = new Review();

            //hydrate l'instance
            $review->setTitle($faker->sentence);
            $review->setUsername($faker->userName);
            $review->setEmail($faker->email);
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
