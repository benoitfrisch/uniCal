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

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Course;
use AppBundle\Entity\Event;
use AppBundle\Entity\Group;
use AppBundle\Entity\Local;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadExamData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $allGroup = new Group();
        $allGroup->setName("All");
        $allGroup->setNumber(0);
        $manager->persist($allGroup);
        $manager->flush();

        $events = json_decode(file_get_contents('files/roger.json'), true);

        for ($i = 0; $i < count($events); $i++) {
            $courseName = $events[$i]['Title'];
            $localName = $events[$i]['Local'];
            $groupName = $events[$i]['ICPE'];
            $startDate = $events[$i]['DateDebut'];
            $endDate = $events[$i]['DateFin'];

            echo "  " . $courseName . "\n";

            $course = $manager->getRepository('AppBundle:Course')->findOneBy(['name' => $courseName]);
            if (empty($course)) {
                echo "creating new course";
                $course = new Course();
                $course->setName($courseName);
                $manager->persist($course);
                $manager->flush();
            }

            $local = $manager->getRepository('AppBundle:Local')->findOneBy(['name' => $localName]);
            if (empty($local)) {
                echo "creating new local";
                $local = new Local();
                $local->setName($localName);
                $manager->persist($local);
                $manager->flush();
            }

            $groupPattern = '/^(Groupe)(\s)([0-9]{1})(\s)(\((.*?)\))/';
            preg_match($groupPattern, $groupName, $groupMatches);
            print_r($groupMatches);

            if (count($groupMatches) > 0) {
                $group = $manager->getRepository('AppBundle:Group')->findOneBy(['name' => $groupMatches[0], 'number' => $groupMatches[3], 'extra' => $groupMatches[6]]);
                if (empty($group)) {
                    $group = new Group();
                    $group->setName($groupMatches[0]);
                    $group->setNumber($groupMatches[3]);
                    $group->setExtra($groupMatches[6]);
                    $group->setCourse($course);
                    $manager->persist($group);
                    $manager->flush();
                }
            } else {
                $group = $allGroup;
            }

            $event = new Event();
            $event->setCourse($course);
            $event->setGroup($group);
            $event->setLocal($local);
            $event->setStartDate(new DateTime($startDate));
            $event->setEndDate(new DateTime($endDate));
            $manager->persist($event);
            $manager->flush();
        }
    }
}