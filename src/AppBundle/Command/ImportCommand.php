<?php
/**
 * This file is part of PremiereLu.
 *
 * Copyright (c) 2017 Benoît FRISCH
 *
 * PremiereLu is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PremiereLu is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PremiereLu If not, see <http://www.gnu.org/licenses/>.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Course;
use AppBundle\Entity\Event;
use AppBundle\Entity\Group;
use AppBundle\Entity\Local;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;


    protected function configure()
    {
        $this
            ->setName('import:json')
            ->setDescription('Import Events from a JSON file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get("doctrine.orm.default_entity_manager");

        $output->writeln("##################################");
        $output->writeln("Starting import...");


        $events = json_decode(file_get_contents('files/calendar.json'), true);

        $progress = new ProgressBar($output, count($events));
        // start and displays the progress bar
        $progress->start();
        $progress->setFormat("normal");
        $progress->setMessage("Importing all data for each section...");

        for ($i = 0; $i < count($events); $i++) {
            $courseName = $events[$i]['Title'];
            $localName = $events[$i]['Local'];
            $groupName = $events[$i]['ICPE'];
            $startDate = $events[$i]['DateDebut'];
            $endDate = $events[$i]['DateFin'];

            echo "  " . $courseName . "\n";

            $course = $this->em->getRepository('AppBundle:Course')->findOneBy(['name' => $courseName]);
            if (empty($course)) {
                $course = new Course();
                $course->setName($courseName);
                $this->em->persist($course);
                $this->em->flush();
            }

            echo "  " . $course . "\n";

            $local = $this->em->getRepository('AppBundle:Local')->findOneBy(['name' => $localName]);
            if (empty($local)) {
                $local = new Local();
                $local->setName($localName);
                $this->em->persist($local);
                $this->em->flush();
            }


            echo "  " . $local . "\n";


            $groupPattern = '/^(Groupe)(\s)([0-9]{1})(\s)(\((.*?)\))/';
            preg_match($groupPattern, $groupName, $groupMatches);
            print_r($groupMatches);

            if (count($groupMatches) > 0) {
                $group = $this->em->getRepository('AppBundle:Group')->findOneBy(['name' => $groupMatches[0], 'number' => $groupMatches[3], 'extra' => $groupMatches[6]]);
                if (empty($group)) {
                    $group = new Group();
                    $group->setName($groupMatches[0]);
                    $group->setNumber($groupMatches[3]);
                    $group->setExtra($groupMatches[6]);
                    $this->em->persist($group);
                    $this->em->flush();
                }
            }


            echo "  " . $group . "\n";

            $event = new Event();
            $event->setCourse($course);
            if (count($groupMatches) > 0) {
                $event->setGroup($group);
            }
            $event->setLocal($local);
            $event->setStartDate(new DateTime($startDate));
            $event->setEndDate(new DateTime($endDate));
            $this->em->persist($event);
            $this->em->flush();

            $progress->advance();
        }


        $progress->finish();
        $output->writeln("Finished import.");
        $output->writeln("##################################");
    }
}