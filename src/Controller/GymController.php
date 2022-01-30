<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Programme;
use App\Repository\AppointmentRepository;
use App\Repository\CustomerRepository;
use App\Repository\ProgrammeRepository;
use App\Repository\RoomRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class GymController extends AbstractController
{
    private const TOKEN = 'thisisasecrettoken';

    /**
     * @Route("/programme",  methods={"POST"})
     */
    public function addProgramme(Request $request, EntityManagerInterface $entityManager, RoomRepository $roomRepository, ProgrammeRepository $programmeRepository)
    {
        $requestInfo = json_decode($request->getContent(), true);

        if (!isset($requestInfo['token']) || $requestInfo['token'] !== self::TOKEN) {
            return new JsonResponse(['result' => 'Forbidden'], 403);
        }

        $startTime = new DateTime($requestInfo['start_time']);
        if ($startTime->format('H') < 8 || $startTime->format('H') > 20) {
            return new JsonResponse(['result' => 'start hour must be after 8 or before 20'], 400);
        }

        $endTime = new DateTime($requestInfo['end_time']);
        if ($endTime->format('H') < 8 || $endTime->format('H') > 20) {
            return new JsonResponse(['result' => 'end hour must be after 8 or before 20'], 400);
        }

        if ($startTime > $endTime) {
            return new JsonResponse(['result' => 'start date can not be after end date']);
        }

        $room = $roomRepository->find($requestInfo["room"]);

        if($room === null){
            return new JsonResponse(['result' => 'room doesn\'t exit'],400);
        }

        $overlappingProgrammes = $programmeRepository->findOverlappingProgramme($startTime, $endTime, $room);
        if (count($overlappingProgrammes) > 0) {
            return new JsonResponse(['result' => 'can not add programme, there already is a programme for this time slot'], 400);
        }

        $programme = new Programme();
        $programme->setName($requestInfo['name']);
        $programme->setMaxParticipants($requestInfo['max_participants']);
        $programme->setStartTime($startTime);
        $programme->setEndTime($endTime);
        $programme->setRoom($room);

        $entityManager->persist($programme);
        $entityManager->flush();

        return new JsonResponse(['result' => 'ok']);
    }

    /**
     * @Route("/programme", methods={"DELETE"})
     */
    public function deleteProgramme(Request $request, ProgrammeRepository $programmeRepository, EntityManagerInterface $entityManager)
    {
        $requestInfo = json_decode($request->getContent(), true);

        if (!isset($requestInfo['token']) || $requestInfo['token'] !== self::TOKEN) {
            return new JsonResponse(['result' => 'Forbidden'], 403);
        }

        $programme = $programmeRepository->find($requestInfo["programme"]);
        if ($programme === null){
            return  new JsonResponse(['result'=> 'programm doesn\'t exist'], 400);
        }

        $entityManager->remove($programme);
        $entityManager->flush();

        return new JsonResponse(['result' => 'ok']);
    }

    /**
     * @Route("/appointment", methods={"POST"})
     */
    public function addAppointment(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, ProgrammeRepository $programmeRepository, AppointmentRepository $appointmentRepository)
    {
        $requestInfo = json_decode($request->getContent(), true);

        if (!isset($requestInfo['token']) || $requestInfo['token'] !== self::TOKEN) {
            return new JsonResponse(['result' => 'Forbidden'], 403);
        }

        // Daca nu exista customer cu id-ul dat -> eroare
        $customer = $customerRepository->find($requestInfo["customer"]);
        if($customer==null){
            return new JsonResponse(['result'=> 'customer doesn\'t exist'],400);
        }

        $programme = $programmeRepository -> find($requestInfo["programme"]);
        if($programme == null){
            return new JsonResponse(['result'=> 'programme doesn\'t exist'], 400);
        }

        $appointments = $appointmentRepository->findBy(['programme' => $programme]);
        if (count($appointments) >= $programme->getMaxParticipants()) {
            return new JsonResponse(['result' => 'programme is full']);
        }

        $appointment= new Appointment();
        $appointment->setCustomer($customer);
        $appointment ->setProgramme($programme);
        $entityManager->persist($appointment);
        $entityManager->flush();


        return new JsonResponse(['result' => 'ok']);
    }
}