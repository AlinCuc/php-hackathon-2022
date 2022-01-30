<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    /**
     * @Route("/create-rooms")
     */
    public function createRooms(EntityManagerInterface $entityManager)
    {
        $rooms = [
            'Blue Room', 'Red Room', 'Green Room'
        ];

        foreach ($rooms as $roomName) {
            $room = new Room();
            $room->setName($roomName);
            $entityManager->persist($room);
            $entityManager->flush();
        }

        return new Response('Created rooms!');
    }

    /**
     * @Route("/create-customers")
     */
    public function createCustomers(EntityManagerInterface $entityManager)
    {
        $customers = [
            ['1111111111111', 'John'],
            ['2222222222222', 'Marry']
        ];

        foreach ($customers as $customerInfo) {
            $customer = new Customer();
            $customer->setName($customerInfo[1]);
            $customer->setCnp($customerInfo[0]);
            $entityManager->persist($customer);
            $entityManager->flush();
        }

        return new Response('Created Customers');
    }
}