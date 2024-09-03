<?php

namespace App\Command;

use App\Entity\Commodity;
use App\Entity\Location;
use App\Repository\CommodityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name       : 'app:init:commodities',
    description: 'Add a short description for your command',
)]
class InitCommoditiesCommand extends Command
{
    private const SYSTEMS  = 'systems';
    private const TITLE    = 'title';
    private const PLANETES = 'planetes';
    private const STATIONS = 'stations';
    private const MOONS    = 'moons';
    private SymfonyStyle $io;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CommodityRepository    $commodityRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $commoditiesTitles = array_map(static fn(Commodity $commodity) => $commodity->getTitle(), $this->commodityRepository->findAll());

        foreach ($this->getData() as $title) {
            if (in_array($title, $commoditiesTitles)) {
                continue;
            }
            $this->em->persist((new Commodity())->setTitle($title));
        }

        $this->em->flush();

        $this->io->success('Ok');

        return Command::SUCCESS;
    }

    private function getData(): array
    {
        return [
            'AcryliPlex Composite',
            'Agricium',
            'Agricium (Ore)',
            'Agricultural Supplies',
            'Altruciatoxin',
            'Aluminum',
            'Aluminum (Ore)',
            'Amioshi Plague',
            'Aphorite',
            'Astatine',
            'Atlasium',
            'Beryl',
            'Beryl (Raw)',
            'Bexalite',
            'Bexalite (Raw)',
            'Borase',
            'Borase (Ore)',
            'Carbon',
            'Chlorine',
            'Compboard',
            'Construction Materials',
            'Copper',
            'Copper (Ore)',
            'Corundum',
            'Corundum (Raw)',
            'Degnous Root',
            'Diamond',
            'Diamond (Raw)',
            'Diluthermex',
            'Distilled Spirits',
            'Dolivine',
            'Dymantium',
            'E\'tam',
            'Fluorine',
            'Gasping Weevil Eggs',
            'Gold',
            'Gold (Ore)',
            'Golden Medmon',
            'Hadanite',
            'Heart of the Woods',
            'Helium',
            'Hephaestanite',
            'Hephaestanite (Raw)',
            'Hydrogen',
            'Inert Materials',
            'Iodine',
            'Iron',
            'Iron (Ore)',
            'Janalite',
            'Kopion Horn',
            'Laranite',
            'Laranite (Raw)',
            'Maze',
            'Medical Supplies',
            'Neon',
            'Nitrogen',
        ];
    }
}
