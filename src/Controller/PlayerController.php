<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PlayerController extends AbstractController
{
    #[Route('/player', methods: ['POST'])]
    public function createPlayer(Request $request, EntityManagerInterface $entityManager): JsonResponse {
        
        $data = json_decode(json: $request->getContent(), associative: true);

        $team = $entityManager->getRepository(Team::class)->find($data['teamId'] ?? null);

        if(!$team) {
            throw $this->createNotFoundException(
                'No player found for id '.$id
            );
        }

        $player = new Player();
        $player->setFirstName(FirstName: $data['firstName'] ?? '');
        $player->setLastName(LastName: $data['lastName'] ?? '');
        $player->setTeam(Team: $team);

        $entityManager->persist($player);
        $entityManager->flush();

        return $this->json(['id'=> $player->getId() ,
        'name' => $player->getFirstName().' '.$player->getLastName(),
        'team' => $player->getTeam() ? $player->getTeam()->getName() : 'None' ], 201);
    }

    #[Route('/player/{id}', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse {

        $player = $entityManager->getRepository(Player::class)->find($id);

        if(!$player) {
            throw $this->createNotFoundException(
                'No player found for id '.$id
            );
        }
        return $this->json(['id'=> $player->getId() ,
                            'name' => $player->getFirstName().' '.$player->getLastName(),
                            'team' => $player->getTeam() ? $player->getTeam() : 'None' ], 201) ;
    }

    #[Route('/players', methods: ['GET'])]
    public function showAll(EntityManagerInterface $entityManager):JsonResponse {
        $players = $entityManager->getRepository(Player::class)->findAll();

        if(!$players) {
            throw $this->createNotFoundException(
                'No players found'
            );
        }
        return $this->json(['players' => array_map(callback: function($player): array {
                return [
                    'id' => $player->getId(),
                    'name' => $player->getFirstName().' '.$player->getLastName(),
                    'team' => $player->getTeam() ? $player->getTeam() : 'None',
                ];
            }, array: $players)]) ;
    }

    #[Route('/player/{id}', methods: ['PUT'])]
    public function update( Request $request, EntityManagerInterface $entityManager, int $id): Response
    {

        $data = json_decode(json: $request->getContent(), associative: true);
        
        $player = $entityManager->getRepository(Player::class)->find($id);
        if (!$player) {
            throw $this->createNotFoundException(
                'No player found for id '.$id
            );
        }

        $team = $entityManager->getRepository(Team::class)->find($data['teamId'] ?? null);

        if(!$team) {
            throw $this->createNotFoundException(
                'No player found for id '.$id
            );
        }

        $player->setFirstName(FirstName: $data['firstName'] ?? '');
        $player->setLastName(LastName: $data['lastName'] ?? '');
        $player->setTeam(Team: $team);
        $entityManager->flush();

        return $this->json(['id'=> $player->getId() ,
        'name' => $player->getFirstName().' '.$player->getLastName(),
        'team' => $player->getTeam() ? $player->getTeam()->getName() : 'None' ], 201);
    }

    #[Route('/player/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse {
        $player = $entityManager->getRepository(Player::class)->find($id);

        if(!$player) {
            throw $this->createNotFoundException(
                'No player found for id '.$id
            );
        }

        try {
            $entityManager->remove(object: $player);
            $entityManager->flush();

            return $this->json(['message' => 'Successfully eliminated player '.$player->getFirstName().' '.$player->getLastName()], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Couldnt eliminate player.'], 500);
        }
    }
}
