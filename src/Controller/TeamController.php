<?php

namespace App\Controller;

use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends AbstractController
{
    #[Route('/team', methods: ['POST'])]
    public function createteam(Request $request, EntityManagerInterface $entityManager): JsonResponse {
        
        $data = json_decode(json: $request->getContent(), associative: true);
        
        $team = new Team();
        $team->setName(Name: $data['name'] ?? '');
        $team->setScore(Score: $data['score'] ?? '');

        $entityManager->persist($team);
        $entityManager->flush();

        return $this->json(['id'=> $team->getId() ,
        'name' => $team->getName(),
        'score'=> $team->getScore()  ], 201);
    }

    #[Route('/team/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse {
        $team = $entityManager->getRepository(team::class)->find($id);

        if(!$team) {
            throw $this->createNotFoundException(
                'No team found for id '.$id
            );
        }
        return $this->json(['id'=> $team->getId() ,
                            'name' => $team->getName(),
                            'score'=> $team->getScore()  ], 201) ;
    }

    #[Route('/teams', methods: ['GET'])]
    public function showAll(EntityManagerInterface $entityManager):JsonResponse {
        $teams = $entityManager->getRepository(team::class)->findAll();

        if(!$teams) {
            throw $this->createNotFoundException(
                'No teams found'
            );
        }
        return $this->json(['teams' => array_map(callback: function($team): array {
                return [
                    'id' => $team->getId(),
                    'name' => $team->getName(),
                    'score'=> $team->getScore() 
                ];
            }, array: $teams)]) ;
    }

    #[Route('/team/{id}', methods: ['PUT'])]
    public function update( Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $data = json_decode(json: $request->getContent(), associative: true);

        $team = $entityManager->getRepository(team::class)->find($id);

        if (!$team) {
            throw $this->createNotFoundException(
                'No team found for id '.$id
            );
        }

        $team->setName(Name: $data['name'] ?? '');
        $team->setScore(Score: $data['score'] ?? '');
        $entityManager->flush();

        return $this->json(['id'=> $team->getId() ,
                            'name' => $team->getName(),
                            'score'=> $team->getScore()  ], 201) ;
    }

    #[Route('/team/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse {
        $team = $entityManager->getRepository(team::class)->find($id);

        if(!$team) {
            throw $this->createNotFoundException(
                'No team found for id '.$id
            );
        }

        try {
            $entityManager->remove(object: $team);
            $entityManager->flush();

            return $this->json(['message' => 'Successfully eliminated team '.$team->getName()], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Couldnt eliminate team.'], 500);
        }
    }
}
