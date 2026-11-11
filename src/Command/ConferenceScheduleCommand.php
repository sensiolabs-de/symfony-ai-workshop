<?php

namespace App\Command;

use App\Conference\ProgramLoader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:conference:schedule', description: 'Load conference schedule from live.symfony.com')]
final readonly class ConferenceScheduleCommand
{
    public function __construct(
        private ProgramLoader $loader,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Conference Schedule');

        foreach ($this->loader->load() as $event) {
            $io->writeln(\sprintf(
                ' * <info>%s</> <comment>(%s - %s)</>',
                $event->getTitle(),
                $event->getTimeSpan()->getStart()->format('H:i'),
                $event->getTimeSpan()->getEnd()->format('H:i'),
            ));
        }

        $io->success('Have a great conference!');

        return 0;
    }
}
