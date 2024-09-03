<?php

namespace App\Command;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name       : 'app:init:locations',
    description: 'Add a short description for your command',
)]
class InitLocationsCommand extends Command
{
    private const SYSTEMS  = 'systems';
    private const TITLE    = 'title';
    private const PLANETES = 'planetes';
    private const STATIONS = 'stations';
    private const MOONS    = 'moons';
    private SymfonyStyle $io;

    /**
     * @var array|null[]|string[]
     */
    private array $locationsTitles;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LocationRepository     $locationRepository,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->locationsTitles = [];
        foreach ($this->locationRepository->findAll() as $item) {
            $this->locationsTitles[$item->getTitle()] = $item;
        }


        foreach ($this->getData() as $system) {
            $this->initSystem($system);
        }

        $this->em->flush();

        $this->io->success('Ok');

        return Command::SUCCESS;
    }

    private function getData(): array
    {
        return [
            [
                self::TITLE    => 'Stanton',
                self::PLANETES => [
                    [
                        self::TITLE    => 'Hurston',
                        self::STATIONS => [
                            'Everus Harbor',
                            'HUR-L1 Green Glade Station',
                            'HUR-L2 Faithful Dream Station',
                            'HUR-L3 Thundering Express Station',
                            'HUR-L4 Melodic Fields Station',
                            'HUR-L5 High Course Station',
                        ],
                        self::MOONS    => [
                            'Arial',
                            'Aberdeen',
                            'Magda',
                            'Ita',
                        ],
                    ],
                    [
                        self::TITLE    => 'Crusader',
                        self::STATIONS => [
                            'Seraphim Station',
                            'CRU-L1 Ambitious Dream Station',
                            'CRU-L4 Shallow Fields Station',
                            'CRU-L5 Beautiful Glen Station',
                        ],
                        self::MOONS    => [
                            'Cellin',
                            'Daymar',
                            'Yela',
                        ],
                    ],
                    [
                        self::TITLE    => 'ArcCorp',
                        self::STATIONS => [
                            'Baijini Point',
                            'ARC-L1 Wide Forest Station',
                            'ARC-L2 Lively Pathway Station',
                            'ARC-L3 Modern Express Station',
                            'ARC-L4 Faint Glen Station',
                            'ARC-L5 Yellow Core Station',
                        ],
                        self::MOONS    => [
                            'Lyria',
                            'Wala',
                        ],
                    ],
                    [
                        self::TITLE    => 'MicroTech',
                        self::STATIONS => [
                            'Port Tressler',
                            'MIC-L1 Shallow Frontier Station',
                            'MIC-L2 Long Forest Station',
                            'MIC-L3 Endless Odyssey Station',
                            'MIC-L4 Red Crossroads Station',
                            'MIC-L5 Modern Icarus Station',
                        ],
                        self::MOONS    => [
                            'Calliope',
                            'Clio',
                            'Euterpe',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function initSystem(array $system): void
    {
        $this->io->info(sprintf('Adding system %s', $system[self::TITLE]));

        if (!($systemEntity = $this->locationsTitles[$system[self::TITLE]] ?? null)) {
            $systemEntity = (new Location())
                ->setType('system')
                ->setTitle($system[self::TITLE])
            ;
        }

        foreach ($system[self::PLANETES] ?? [] as $planete) {
            $this->initPlanete($systemEntity, $planete);
        }

        $this->em->persist($systemEntity);
    }

    private function initPlanete(Location $systemEntity, array $planete): void
    {
        $this->io->info(sprintf('Adding planete %s', $planete[self::TITLE]));

        if (!($planeteEntity = $this->locationsTitles[$planete[self::TITLE]] ?? null)) {
            $planeteEntity = (new Location())
                ->setType('planete')
                ->setTitle($planete[self::TITLE])
                ->setParent($systemEntity)
            ;
        }

        foreach ($planete[self::STATIONS] ?? [] as $station) {
            $this->initStation($planeteEntity, $station);
        }

        foreach ($planete[self::MOONS] ?? [] as $moon) {
            $this->initMoon($planeteEntity, $moon);
        }


        $this->em->persist($planeteEntity);
    }

    private function initStation(Location $planeteEntity, string $station): void
    {
        $this->io->info(sprintf('Adding station %s', $station));

        if (!($stationEntity = $this->locationsTitles[$station] ?? null)) {
            $stationEntity = (new Location())
                ->setType('station')
                ->setTitle($station)
                ->setParent($planeteEntity)
            ;
        }

        $this->em->persist($stationEntity);
    }

    private function initMoon(Location $planeteEntity, string $moon): void
    {
        $this->io->info(sprintf('Adding moon %s', $moon));

        if (!($moonEntity = $this->locationsTitles[$moon] ?? null)) {
            $moonEntity = (new Location())
                ->setType('moon')
                ->setTitle($moon)
                ->setParent($planeteEntity)
            ;
        }

        $this->em->persist($moonEntity);
    }
}
